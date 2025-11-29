<?php

// File: app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Otorisasi Policy
        Gate::authorize('viewAny', Product::class);

        // Ambil data produk
        $products = Product::with('category')
                            ->orderBy('current_stock', 'asc')
                            ->paginate(10);
                            
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Otorisasi Policy
        Gate::authorize('create', Product::class);
        
        $categories = Category::all(); 
        
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Otorisasi Policy
        Gate::authorize('create', Product::class);

        // 1. Validasi Data
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:1'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'rack_location' => ['nullable', 'string', 'max:100'],
            // 'image' => ['nullable', 'image', 'max:2048'],
        ]);
        
        // 2. Simpan Data
        Product::create($validated);
        
        // 3. Redirect dan Notifikasi
        $redirectRoute = auth()->user()->role . '.products.index'; 

        return redirect()->route($redirectRoute)
            ->with('success', 'Product ' . $validated['name'] . ' added successfully to Quantum Stockroom.');
    }
        

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Otorisasi: Admin, Manager, Staff
        Gate::authorize('view', $product);
        
        // Kita tidak akan membuat view khusus untuk show.
        // Untuk saat ini, kita bisa redirect ke edit view untuk melihat detail,
        // atau menampilkan view sederhana. Kita buat view sederhana.
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Otorisasi: Hanya Admin dan Manager
        Gate::authorize('update', $product);

        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Otorisasi: Hanya Admin dan Manager
        Gate::authorize('update', $product);

        // 1. Validasi Data
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            // SKU harus unik, TAPI harus mengabaikan SKU produk yang sedang diedit
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)], 
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:1'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'rack_location' => ['nullable', 'string', 'max:100'],
            // 'image' => ['nullable', 'image', 'max:2048'], 
        ]);

        // 2. Update Data
        $product->update($validated);

        // 3. Redirect dan Notifikasi
        $redirectRoute = auth()->user()->role . '.products.index'; 

        return redirect()->route($redirectRoute)
            ->with('success', 'Product ' . $validated['name'] . ' data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Otorisasi: Hanya Admin dan Manager
        Gate::authorize('delete', $product);

        $productName = $product->name;
        $product->delete();

        $redirectRoute = auth()->user()->role . '.products.index'; 

        return redirect()->route($redirectRoute)
            ->with('success', 'Product ' . $productName . ' successfully eliminated from Quantum Stock.');
    }
}
