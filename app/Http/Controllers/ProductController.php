<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Product::class);

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
        Gate::authorize('create', Product::class);
        
        $categories = Category::all(); 
        
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Product::class);

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
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product_images', 'public');
            $validated['image'] = $path;
        }
        
        Product::create($validated);
        
        $redirectRoute = auth()->user()->role . '.products.index'; 

        return redirect()->route($redirectRoute)
            ->with('success', 'Product ' . $validated['name'] . ' added successfully to Quantum Stockroom.');
    }
        

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category'); 
        
        Gate::authorize('view', $product);
        
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', $product);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)], 
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:1'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'rack_location' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'], 
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('product_images', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        $redirectRoute = auth()->user()->role . '.products.index'; 

        return redirect()->route($redirectRoute)
            ->with('success', 'Product ' . $validated['name'] . ' data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $productName = $product->name;
        $product->delete();

        $redirectRoute = auth()->user()->role . '.products.index'; 

        return redirect()->route($redirectRoute)
            ->with('success', 'Product ' . $productName . ' successfully eliminated from Quantum Stock.');
    }
}