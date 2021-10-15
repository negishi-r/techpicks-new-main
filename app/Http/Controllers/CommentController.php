<?php

namespace App\Http\Controllers;

use App\DomainServices\ArticleService;
use App\DomainServices\CommentService;
use App\Http\Requests\Comment\StoreOrUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @param StoreOrUpdateRequest $request
     * @param string $articleId
     * @param ArticleService $articleService
     * @param CommentService $commentService
     * @return RedirectResponse
     */
    public function storeOrUpdate(StoreOrUpdateRequest $request, string $articleId, ArticleService $articleService, CommentService $commentService)
    {

        $ownComment = $commentService->getOwnComment($articleId, Auth::id());

        $commentService->upsert(Auth::id(), $articleId, $request->input('comment'));

        $flashMessage = $ownComment ? 'コメントを更新しました' : '記事にコメントしました';
        $request->session()->flash('status', $flashMessage);

        $article = $articleService->find($articleId);

        return redirect()->route('article.show', compact('article'));
    }
}
