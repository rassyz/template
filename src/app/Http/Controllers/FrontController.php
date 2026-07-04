<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Comment;
use App\Models\Category;
use App\Models\BannerAds;
use App\Models\ArticleNews;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(  )
    {
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
            ->where('is_featured', 'not_featured')
            ->latest()
            ->take(6)
            ->get();

        $featured_articles = ArticleNews::with(['category'])
            ->where('is_featured', 'featured')
            ->inRandomOrder()
            ->take(6)
            ->get();

        $authors = Author::all();

        $bannerads = BannerAds::where('is_active', 'active')
            ->where('type', 'banner')
            ->inRandomOrder()
            // ->take(1)
            ->first();

        $tech_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Teknologi');
        })
            ->where('is_featured', 'not_featured')
            ->latest()
            ->take(6)
            ->get();

        $tech_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Teknologi');
        })
            ->where('is_featured', 'featured')
            ->inRandomOrder()
            ->first();

        $politic_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Politik');
        })
            ->where('is_featured', 'not_featured')
            ->latest()
            ->take(6)
            ->get();

        $politic_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Politik');
        })
            ->where('is_featured', 'featured')
            ->inRandomOrder()
            ->first();

        $oto_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Otomotif');
        })
            ->where('is_featured', 'not_featured')
            ->latest()
            ->take(6)
            ->get();

        $oto_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Otomotif');
        })
            ->where('is_featured', 'featured')
            ->inRandomOrder()
            ->first();

        return view('frontend.index', compact(
            'categories',
            'articles',
            'featured_articles',
            'authors',
            'bannerads',
            'tech_articles',
            'tech_featured_articles',
            'politic_articles',
            'politic_featured_articles',
            'oto_articles',
            'oto_featured_articles'
        ));
    }

    public function category (Category $category)
    {
        $categories = Category::all();

        $bannerads = BannerAds::where('is_active', 'active')
            ->where('type', 'banner')
            ->inRandomOrder()
            // ->take(1)
            ->first();

        return view('frontend.category', compact('category', 'categories', 'bannerads'));
    }

    public function author (Author $author)
    {
        $categories = Category::all();

        $bannerads = BannerAds::where('is_active', 'active')
            ->where('type', 'banner')
            ->inRandomOrder()
            // ->take(1)
            ->first();

        return view('frontend.author', compact('author', 'bannerads', 'categories'));
    }

    public function search (Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
        ]);

        $categories = Category::all();

        $keyword = $request->keyword;

        $articles = ArticleNews::with(['category', 'author'])
            ->latest()
            ->where('name', 'like', '%' . $keyword . '%')->paginate(6);

        return view('frontend.search', compact('categories', 'articles', 'keyword'));
    }

    public function details (ArticleNews $articleNews)
    {
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
            ->where('is_featured', 'not_featured')
            ->where('id', '!=', $articleNews->id)
            ->latest()
            ->take(3)
            ->get();

        $bannerads = BannerAds::where('is_active', 'active')
            ->where('type', 'banner')
            ->inRandomOrder()
            // ->take(1)
            ->first();

        $squareads = BannerAds::where('is_active', 'active')
            ->where('type', 'square')
            ->inRandomOrder()
            ->take(2)
            ->get();

            if ($squareads->count() < 2) {
                $squareads_1 = $squareads->first();
                $squareads_2 = null;
            } else {
                $squareads_1 = $squareads->get(0);
                $squareads_2 = $squareads->get(1);
            }

        $author_news = ArticleNews::where('author_id', $articleNews->author_id)
            ->where('id', '!=', $articleNews->id)
            ->latest()
            ->take(5)
            ->get();


        return view('frontend.details', compact('author_news', 'articleNews', 'categories', 'articles', 'bannerads', 'squareads', 'squareads_1', 'squareads_2'));
    }

    public function comment (Request $request, ArticleNews $articleNews)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:255'],
        ]);

        $badWords = ['bodoh', 'goblok', 'bangsat', 'anjing'];
        $comment = strtolower($request->comment);

        foreach ($badWords as $badWord) {
            if (str_contains($comment, $badWord)) {
                return redirect()->to(url()->previous() . '#comment-section')->withErrors(['comment' => 'Komentar mengandung kata yang tidak pantas.']);
            }
        }

        $articleNews->comments()->create([
            'name' => $request->name,
            'review' => $request->comment,
        ]);

        $comments = $articleNews->comments()->latest()->get();

        return redirect()->to(url()->previous() . '#comment-section')->with('success', 'Comment added successfully')->with('comments', $comments);
    }

    public function reply(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'reply' => ['required', 'string', 'max:255'],
        ]);

        $badWords = ['bodoh', 'goblok', 'bangsat', 'anjing'];
        $replyLower = strtolower($validated['reply']);

        foreach ($badWords as $badWord) {
            if (str_contains($replyLower, $badWord)) {
                return back()->withErrors(['reply' => 'Reply mengandung kata yang tidak pantas.'])
                            ->withInput()
                            ->withFragment('comment-section');
            }
        }

        $comment->replyComments()->create([
            'name' => $validated['name'],
            'review' => $validated['reply'],
        ]);

        return back()->with('success', 'Reply berhasil ditambahkan.')
                    ->withFragment('comment-section');
    }

}
