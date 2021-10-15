<?php

namespace App\Http\Controllers;

use App\DomainServices\CategoryService;
use App\Http\Requests\Article\PreviewRequest;
use App\Http\Requests\Article\StoreRequest;
use App\Http\Requests\Article\ValidateUrlRequest;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Utils\Ogp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;

use App\DomainServices\ArticleService;
use App\DomainServices\CommentService;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @param ArticleService $articleService
     * @return ViewFactory|View
     */
    public function index(Request $request, ArticleService $articleService)
    {
        /** @var string $q */
        $q = $request->query('q');
        $searchQuery = addcslashes($q, '%_\\'); // 検索文字列をそのままの文字列として検索したいが、DBのエスケープ文字の場合そのまま渡すと正しく検索できないため、エスケープ文字の場合はバックスラッシュを付加して検索する
        $articles = $articleService->searchArticles($searchQuery);

        return view('article.index', compact('articles', 'q'));
    }

    /**
     * @param string $article_id
     * @param ArticleService $articleService
     * @param CommentService $commentService
     * @return ViewFactory|View
     */
    public function show(string $articleId, ArticleService $articleService, CommentService $commentService)
    {

        $article = $articleService->find($articleId);
        $comments = $commentService->getArticleComments($articleId);
        $ownComment = $commentService->getOwnComment($articleId, Auth::id());

        return view('article.show', compact('article', 'comments', 'ownComment'));
    }

    /**
     * @return ViewFactory|View
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * @param ValidateUrlRequest $request
     * @return RedirectResponse
     */
    public function validateUrl(ValidateUrlRequest $request)
    {
        /** @var string $url */
        $url = $request->input('url');
        $encodedUrl = urlencode($url);

        return redirect()->route('article.preview', ['url' => $encodedUrl]);
    }

    /**
     * @param PreviewRequest $request
     * @param CategoryService $categoryService
     * @@return ViewFactory|View
     */
    public function preview(PreviewRequest $request, CategoryService $categoryService)
    {
        /** @var string $url */
        $url = $request->query('url');
        $decodedUrl = urldecode($url);
        $ogp = (new Ogp($decodedUrl))();
        $categories = $categoryService->all();

        return view('article.preview', [
            'url' => $decodedUrl,
            'title' => $ogp->title,
            'description' => $ogp->description,
            'imagePath' => $ogp->imagePath,
            'categories' => $categories,
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param ArticleService $articleService
     * @return RedirectResponse
     */
    public function store(StoreRequest $request, ArticleService $articleService) {

        $articleService->create(Auth::id(), $request);

        $request->session()->flash('status', '記事を投稿しました');

        return redirect()->route('dashboard');
    }
}
