<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Admin: Show categories list
     */
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Admin: Show create category form
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Admin: Store new category
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191|unique:categories,name',
        ]);

        $data['slug'] = Str::slug($data['name']);
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    /**
     * Admin: Show edit category form
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Admin: Update category
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191|unique:categories,name,' . $category->id,
        ]);

        $data['slug'] = Str::slug($data['name']);
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    /**
     * Admin: Delete category
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}