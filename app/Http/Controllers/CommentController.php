<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\StoreOrUpdateRequest;
use App\UseCases\Comment\CommentStoreOrUpdateAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @param StoreOrUpdateRequest $request
     * @param string $articleId
     * @param CommentStoreOrUpdateAction $action
     * @return RedirectResponse
     */
    public function storeOrUpdate(StoreOrUpdateRequest $request, string $articleId, CommentStoreOrUpdateAction $action): RedirectResponse
    {
        $data = $action($articleId, Auth::id(), $request->input('comment'));

        $flashMessage = $data['isUpdate'] ? 'コメントを更新しました' : '記事にコメントしました';
        $request->session()->flash('status', $flashMessage);

        return redirect()->route('article.show', $data['article']);
    }
}
