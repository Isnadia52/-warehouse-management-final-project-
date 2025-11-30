<?php


namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Transaction::class);

        if (auth()->user()->role === 'staff') {
            $transactions = Transaction::where('staff_id', auth()->id())
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(15);
        } elseif (auth()->user()->role === 'manager') {
            $transactions = Transaction::where('status', 'Pending')
                                       ->orderBy('created_at', 'asc')
                                       ->paginate(15);
        } else {
            $transactions = Transaction::orderBy('created_at', 'desc')->paginate(15);
        }
        
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource. (Hanya untuk Staff)
     */
    public function create()
    {
        Gate::authorize('create', Transaction::class);

        $products = Product::where('current_stock', '>', 0)->get(['id', 'sku', 'name', 'current_stock']);
        $suppliers = User::where('role', 'supplier')->where('is_approved', true)->get(['id', 'name']);
        
        return view('transactions.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage. (Aksi Utama oleh Staff)
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Transaction::class);


        $validatedHeader = $request->validate([
            'type' => ['required', 'in:incoming,outgoing'],
            'transaction_date' => ['required', 'date'],
            'supplier_id' => [Rule::requiredIf($request->type === 'incoming'), 'exists:users,id'],
            'related_party_name' => [Rule::requiredIf($request->type === 'outgoing'), 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
        
        $request->validate([
            'product_id.*' => ['required', 'exists:products,id'],
            'quantity.*' => ['required', 'integer', 'min:1'],
        ]);
        
        $typePrefix = $validatedHeader['type'] === 'incoming' ? 'INC-' : 'OUT-';
        $transactionNumber = $typePrefix . Str::upper(Str::random(8)) . '-' . now()->format('Ymd');

        $transaction = Transaction::create([
            'transaction_number' => $transactionNumber,
            'staff_id' => auth()->id(),
            'type' => $validatedHeader['type'],
            'transaction_date' => $validatedHeader['transaction_date'],
            'supplier_id' => $validatedHeader['supplier_id'] ?? null,
            'related_party_name' => $validatedHeader['related_party_name'] ?? null,
            'notes' => $validatedHeader['notes'],
            'status' => 'Pending', 
        ]);

        $items = [];
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);

            if (!$product) {
                return back()->withInput()->withErrors(['item_error' => 'Product not found for ID: ' . $productId]);
            }

            $price = $validatedHeader['type'] === 'incoming' ? $product->buy_price : $product->sell_price;
            
            if ($validatedHeader['type'] === 'outgoing' && $request->quantity[$index] > $product->current_stock) {
                 return back()->withInput()->withErrors(['stok' => 'Outgoing quantity for ' . $product->name . ' exceeds current stock.']);
            }
            
            $items[] = new \App\Models\TransactionItem([
                'product_id' => $productId,
                'quantity' => $request->quantity[$index],
                'unit_price' => $price,
            ]);
        }
        $transaction->items()->saveMany($items);
        
        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaction ' . $transactionNumber . ' created. Waiting for Manager approval.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        Gate::authorize('viewAny', Transaction::class);

        $transaction->load('items.product', 'staff', 'manager', 'supplier');
        
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        Gate::authorize('approve', $transaction); 

        $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        $action = $request->input('action');
        $redirectRoute = auth()->user()->role . '.transactions.index';

        if ($action === 'approve') {
            
            if ($transaction->type === 'outgoing') {
                foreach ($transaction->items as $item) {
                    if ($item->quantity > $item->product->current_stock) {
                        return back()->with('error', 'Approval failed: Product ' . $item->product->name . ' has insufficient stock.');
                    }
                }
            }
            
            foreach ($transaction->items as $item) {
                $product = $item->product;
                
                if ($transaction->type === 'incoming') {
                    $product->increment('current_stock', $item->quantity);
                } elseif ($transaction->type === 'outgoing') {
                    $product->decrement('current_stock', $item->quantity);
                }
            }
            
            $transaction->update([
                'status' => 'Approved',
                'manager_id' => auth()->id(),
                'approved_at' => now(),
            ]);

            return redirect()->route($redirectRoute)
                ->with('success', 'Transaction ' . $transaction->transaction_number . ' successfully approved. Stock updated.');
            
        } elseif ($action === 'reject') {
            
            $transaction->update([
                'status' => 'Rejected',
                'manager_id' => auth()->id(), 
            ]);

            return redirect()->route($redirectRoute)
                ->with('success', 'Transaction ' . $transaction->transaction_number . ' has been rejected.');
        }

        return redirect()->route($redirectRoute);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
