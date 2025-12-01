<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
            'image' => ['nullable', 'image', 'max:2048'],
            
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('category_images', 'public'); // Folder baru
            $validated['image'] = $path;
        }
        
        Category::create($validated);
        
        return redirect()->route(auth()->user()->role . '.categories.index')
            ->with('success', 'Category "' . $validated['name'] . '" added successfully.');
    }

    public function show(Category $category)
    {

        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Unauthorized action. You do not have permission to view this category detail.');
        }
 
        $category->loadCount('products'); 

        $products = $category->products()->paginate(10);
        
        return view('categories.show', compact('category', 'products'));    }


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


        $category->loadCount('products'); 

        if ($category->products_count > 0) { 
            return back()->with('error', '🚫 Category "' . $category->name . '" cannot be deleted because it still contains ' . $category->products_count . ' products. Please move or delete the associated products first.');
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route(auth()->user()->role . '.categories.index')
            ->with('success', 'Category "' . $categoryName . '" successfully deleted.');
    }
}
