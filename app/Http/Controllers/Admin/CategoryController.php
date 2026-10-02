<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::orderBy('type')->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $categories = $query->paginate(20)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'type' => 'required|in:destination,activity,package,article',
            'icon' => 'nullable|string|max:50',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category {$request->name} added successfully.");
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'type' => 'required|in:destination,activity,package,article',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update([
            'name' => $request->name,
            'type' => $request->type,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category {$request->name} updated successfully.");
    }

    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', "Category {$name} deleted successfully.");
    }
}
