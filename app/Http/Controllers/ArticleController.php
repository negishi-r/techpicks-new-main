<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\PreviewRequest;
use App\Http\Requests\Article\StoreRequest;
use App\Http\Requests\Article\ValidateUrlRequest;
use App\UseCases\Article\ArticleIndexAction;
use App\UseCases\Article\ArticlePreviewAction;
use App\UseCases\Article\ArticleShowAction;
use App\UseCases\Article\ArticleStoreAction;
use App\Utils\Ogp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @param ArticleIndexAction $action
     * @return ViewFactory|View
     */
    public function index(Request $request, ArticleIndexAction $action): ViewFactory|View
    {
        /** @var string $q */
        $q = $request->query('q');
        $searchQuery = addcslashes($q, '%_\\'); // 検索文字列をそのままの文字列として検索したいが、DBのエスケープ文字の場合そのまま渡すと正しく検索できないため、エスケープ文字の場合はバックスラッシュを付加して検索する

        $articles = $action($searchQuery);

        return view('article.index', compact('articles', 'q'));
    }

    /**
     * @param string $articleId
     * @param ArticleShowAction $action
     * @return ViewFactory|View
     */
    public function show(string $articleId, ArticleShowAction $action)
    {

        $data = $action($articleId, Auth::id());

        return view('article.show', $data);
    }

    /**
     * @return ViewFactory|View
     */
    public function create(): ViewFactory|View
    {
        return view('article.create');
    }

    /**
     * @param ValidateUrlRequest $request
     * @return RedirectResponse
     */
    public function validateUrl(ValidateUrlRequest $request): RedirectResponse
    {
        /** @var string $url */
        $url = $request->input('url');
        $encodedUrl = urlencode($url);

        return redirect()->route('article.preview', ['url' => $encodedUrl]);
    }

    /**
     * @param PreviewRequest $request
     * @param ArticlePreviewAction $action
     * @return ViewFactory|View
     */
    public function preview(PreviewRequest $request, ArticlePreviewAction $action): ViewFactory|View
    {
        /** @var string $url */
        $url = $request->query('url');
        $decodedUrl = urldecode($url);
        $ogp = (new Ogp($decodedUrl))();

        $categories = $action();

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
     * @param ArticleStoreAction $action
     * @return RedirectResponse
     */
    public function store(StoreRequest $request, ArticleStoreAction $action): RedirectResponse
    {

        $action([
            'user_id' => Auth::id(),
            'category_id' => $request->input('categoryId'),
            'url' => $request->input('url'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_path' => $request->input('imagePath'),
        ]);

        $request->session()->flash('status', '記事を投稿しました');

        return redirect()->route('dashboard');
    }
}
