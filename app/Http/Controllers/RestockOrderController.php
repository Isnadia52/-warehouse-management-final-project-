<?php


namespace App\Http\Controllers;

use App\Models\RestockOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RestockOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Akses Penuh: Admin, Manager. Supplier hanya melihat order untuk dirinya.
        $role = auth()->user()->role;

        if ($role === 'supplier') {
            // Supplier hanya melihat order yang ditujukan untuknya
            $orders = RestockOrder::where('supplier_id', auth()->id())
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(15);
        } else {
            // Admin/Manager melihat semua order
            $orders = RestockOrder::orderBy('created_at', 'desc')->paginate(15);
        }
        
        return view('restock_orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource. (Hanya untuk Manager)
     */
    public function create()
    {
        // Otorisasi: Hanya Admin dan Manager
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $products = Product::orderBy('name')->get(['id', 'sku', 'name', 'unit']);
        $suppliers = User::where('role', 'supplier')->where('is_approved', true)->get(['id', 'name']);
        
        return view('restock_orders.create', compact('products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Otorisasi: Hanya Admin dan Manager
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $validatedHeader = $request->validate([
            'supplier_id' => ['required', 'exists:users,id'],
            'expected_delivery_date' => ['nullable', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string'],
        ]);
        
        $request->validate([
            'product_id.*' => ['required', 'exists:products,id'],
            'quantity.*' => ['required', 'integer', 'min:1'],
        ]);
        
        // Logika PO Number (auto-generated)
        $poNumber = 'PO-' . Str::upper(Str::random(6)) . '-' . now()->format('Ym');

        // 1. Simpan Restock Order (Status awal selalu 'Pending')
        $order = RestockOrder::create([
            'po_number' => $poNumber,
            'manager_id' => auth()->id(),
            'supplier_id' => $validatedHeader['supplier_id'],
            'order_date' => now(),
            'expected_delivery_date' => $validatedHeader['expected_delivery_date'],
            'notes' => $validatedHeader['notes'],
            'status' => 'Pending', 
        ]);

        // 2. Simpan Order Items (Detail)
        $items = [];
        foreach ($request->product_id as $index => $productId) {
            $items[] = new \App\Models\RestockOrderItem([
                'product_id' => $productId,
                'quantity' => $request->quantity[$index],
            ]);
        }
        $order->items()->saveMany($items);
        
        return redirect()->route(auth()->user()->role . '.restock_orders.index')
            ->with('success', 'Purchase Order ' . $poNumber . ' created and sent to supplier.');
    }
    
    // ... (fungsi show, update, destroy akan ditambahkan nanti)

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RestockOrder $restock_order)
    {
        $role = auth()->user()->role;
        $action = $request->input('action');
        $redirectRoute = $role . '.restock_orders.index';

        // 1. Otorisasi dan Validasi Aksi
        
        // Hanya Supplier yang bisa Confirm Pending Order
        if ($action === 'confirm' && $role === 'supplier' && $restock_order->status === 'Pending') {
            
            // Validasi sederhana
            $request->validate(['action' => 'required']); 
            
            $restock_order->update(['status' => 'Confirmed by Supplier']);
            return redirect()->route($redirectRoute)
                ->with('success', 'Purchase Order ' . $restock_order->po_number . ' confirmed. Awaiting shipment.');

        // Hanya Manager/Admin yang bisa set In Transit atau Received
        } elseif (in_array($role, ['manager', 'admin'])) {
            
            if ($action === 'in_transit' && $restock_order->status === 'Confirmed by Supplier') {
                $restock_order->update(['status' => 'In Transit']);
                return redirect()->route($redirectRoute)
                    ->with('success', 'PO ' . $restock_order->po_number . ' updated to In Transit.');
                    
            } elseif ($action === 'received' && $restock_order->status === 'In Transit') {
                
                // PENTING: Order Received tidak langsung mengubah stok.
                // Order Received hanya memberitahu Staff Gudang bahwa Barang Masuk harus dicatat.
                
                $restock_order->update(['status' => 'Received']);
                
                return redirect()->route($redirectRoute)
                    ->with('success', 'PO ' . $restock_order->po_number . ' marked as Received. Please inform Staff Gudang to create the Incoming Transaction.');
                    
            }
        }

        // Jika tidak ada aksi atau status tidak sesuai
        return back()->with('error', 'Update failed due to invalid action or current order status.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
