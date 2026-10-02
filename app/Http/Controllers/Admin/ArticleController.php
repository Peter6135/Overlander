<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('category')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title_en', 'like', "%{$request->search}%")
                    ->orWhere('title_id', 'like', "%{$request->search}%");
            });
        }

        $articles = $query->paginate(15)->withQueryString();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::where('type', 'article')->orderBy('name')->get();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('uploads/articles', 'public');
        }

        $data['slug'] = Str::slug($data['title_en']);
        $data['author_id'] = auth()->id();
        $data['published_at'] = $request->boolean('is_published') ? now() : null;

        $article = Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', "Article {$article->title} added successfully.");
    }

    public function edit(Article $article)
    {
        $categories = Category::where('type', 'article')->orderBy('name')->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $this->validated($request);

        if ($request->hasFile('cover_photo')) {
            if ($article->cover_photo && str_starts_with($article->cover_photo, 'uploads/')) {
                Storage::disk('public')->delete($article->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('uploads/articles', 'public');
        }

        if ($request->boolean('is_published') && ! $article->is_published) {
            $data['published_at'] = now();
        } elseif (! $request->boolean('is_published')) {
            $data['published_at'] = null;
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', "Article {$article->title} updated successfully.");
    }

    public function destroy(Article $article)
    {
        $title = $article->title;
        $article->delete();
        return redirect()->route('admin.articles.index')
            ->with('success', "Article {$title} deleted successfully.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title_en' => 'required|string|max:255',
            'title_id' => 'nullable|string|max:255',
            'excerpt_en' => 'nullable|string|max:300',
            'excerpt_id' => 'nullable|string|max:300',
            'content_en' => 'required|string',
            'content_id' => 'nullable|string',
            'cover_photo' => 'nullable|image|max:4096',
            'is_published' => 'nullable|boolean',
        ]);
    }
}
