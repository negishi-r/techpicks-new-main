<?php

namespace App\Http\Controllers;

use App\DomainServices\ArticleService;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;


class DashboardController extends Controller
{
    /**
     * @param ArticleService $articleService
     * @return ViewFactory|View
     */
    public function __invoke(ArticleService $articleService)
    {
        $articles = $articleService->getRecentPosts();

        return view('dashboard', compact('articles'));
    }
}
