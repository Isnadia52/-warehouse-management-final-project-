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
        // Otorisasi: Admin, Manager, Staff
        Gate::authorize('viewAny', Transaction::class);

        // Filter data berdasarkan role:
        if (auth()->user()->role === 'staff') {
            // Staff hanya melihat transaksi yang dia buat
            $transactions = Transaction::where('staff_id', auth()->id())
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(15);
        } elseif (auth()->user()->role === 'manager') {
            // Manager melihat semua transaksi yang statusnya Pending (perlu approval)
            $transactions = Transaction::where('status', 'Pending')
                                       ->orderBy('created_at', 'asc')
                                       ->paginate(15);
        } else {
            // Admin melihat semua transaksi
            $transactions = Transaction::orderBy('created_at', 'desc')->paginate(15);
        }
        
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource. (Hanya untuk Staff)
     */
    public function create()
    {
        // Otorisasi: Hanya Staff
        Gate::authorize('create', Transaction::class);

        // Data yang dibutuhkan: Daftar Produk dan Daftar Supplier
        $products = Product::where('current_stock', '>', 0)->get(['id', 'sku', 'name', 'current_stock']);
        $suppliers = User::where('role', 'supplier')->where('is_approved', true)->get(['id', 'name']);
        
        return view('transactions.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage. (Aksi Utama oleh Staff)
     */
    public function store(Request $request)
    {
        // Otorisasi: Hanya Staff
        Gate::authorize('create', Transaction::class);

        // dd($request->all());

        // 1. Validasi Transaksi Utama (Header)
        $validatedHeader = $request->validate([
            'type' => ['required', 'in:incoming,outgoing'],
            'transaction_date' => ['required', 'date'],
            'supplier_id' => [Rule::requiredIf($request->type === 'incoming'), 'exists:users,id'],
            'related_party_name' => [Rule::requiredIf($request->type === 'outgoing'), 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);
        
        // 2. Validasi Items (Detail)
        $request->validate([
            'product_id.*' => ['required', 'exists:products,id'],
            'quantity.*' => ['required', 'integer', 'min:1'],
        ]);
        
        // Logika Transaction Number (auto-generated)
        $typePrefix = $validatedHeader['type'] === 'incoming' ? 'INC-' : 'OUT-';
        $transactionNumber = $typePrefix . Str::upper(Str::random(8)) . '-' . now()->format('Ymd');

        // 3. Simpan Transaction (Status awal selalu 'Pending')
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

        // 4. Simpan Transaction Items (Detail)
        $items = [];
        foreach ($request->product_id as $index => $productId) {
            $product = Product::find($productId);

            // Tambahkan cek darurat untuk produk yang tidak ditemukan
            if (!$product) {
                return back()->withInput()->withErrors(['item_error' => 'Product not found for ID: ' . $productId]);
            }

            // Tentukan harga yang digunakan (Buy Price untuk Incoming, Sell Price untuk Outgoing)
            $price = $validatedHeader['type'] === 'incoming' ? $product->buy_price : $product->sell_price;
            
            // Pengecekan stok untuk Outgoing (Harus dilakukan di sini jika validasi kompleks)
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
        
        // 5. Redirect
        return redirect()->route('staff.transactions.index')
            ->with('success', 'Transaction ' . $transactionNumber . ' created. Waiting for Manager approval.');
    }
    
    // ... fungsi show(), update(), destroy() akan kita implementasikan di langkah berikutnya ...

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
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
        // Otorisasi Policy: Hanya Admin dan Manager yang bisa meng-approve
        Gate::authorize('approve', $transaction); 

        $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        $action = $request->input('action');
        $redirectRoute = auth()->user()->role . '.transactions.index';

        if ($action === 'approve') {
            // Logika Persetujuan
            
            // 1. Cek stok kritis (hanya untuk Outgoing yang di-approve)
            if ($transaction->type === 'outgoing') {
                foreach ($transaction->items as $item) {
                    if ($item->quantity > $item->product->current_stock) {
                        return back()->with('error', 'Approval failed: Product ' . $item->product->name . ' has insufficient stock.');
                    }
                }
            }
            
            // 2. Update Stok (CORE LOGIC)
            foreach ($transaction->items as $item) {
                $product = $item->product;
                
                if ($transaction->type === 'incoming') {
                    // Barang Masuk: Tambah Stok
                    $product->increment('current_stock', $item->quantity);
                } elseif ($transaction->type === 'outgoing') {
                    // Barang Keluar: Kurangi Stok
                    $product->decrement('current_stock', $item->quantity);
                }
            }
            
            // 3. Update Status Transaksi
            $transaction->update([
                'status' => 'Approved',
                'manager_id' => auth()->id(), // Manager/Admin yang melakukan approval
                'approved_at' => now(),
            ]);

            return redirect()->route($redirectRoute)
                ->with('success', 'Transaction ' . $transaction->transaction_number . ' successfully approved. Stock updated.');
            
        } elseif ($action === 'reject') {
            // Logika Penolakan (Tidak ada perubahan stok)
            
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
