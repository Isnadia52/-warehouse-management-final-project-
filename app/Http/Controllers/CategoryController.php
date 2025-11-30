<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Otorisasi: Hanya Admin dan Manager
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::withCount('products')->orderBy('name')->paginate(10);
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            // 'image' => ['nullable', 'image', 'max:2048'],
        ]);
        
        Category::create($validated);
        
        return redirect()->route(auth()->user()->role . '.categories.index')
            ->with('success', 'Category "' . $validated['name'] . '" added successfully.');
    }

    // Fungsi show dan edit/update diabaikan karena fokus pada list, create, dan delete
    public function show(Category $category)
    {
        abort(404);
    }
    public function edit(Category $category)
    {
        abort(404);
    }
    public function update(Request $request, Category $category)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action.');
        }
        
        // Validasi: Tidak bisa menghapus kategori yang masih memiliki produk
        if ($category->products()->count() > 0) {
             return back()->with('error', 'Cannot delete category "' . $category->name . '" because it still contains ' . $category->products()->count() . ' products.');
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route(auth()->user()->role . '.categories.index')
            ->with('success', 'Category "' . $categoryName . '" successfully deleted.');
    }
}
