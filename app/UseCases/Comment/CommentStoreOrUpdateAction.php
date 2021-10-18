<?php

namespace App\UseCases\Comment;

use App\DomainServices\ArticleService;
use App\DomainServices\CommentService;
use App\Http\Requests\Article\StoreRequest;
use Illuminate\Support\Facades\Auth;

class CommentStoreOrUpdateAction
{
    public function __construct(
        private ArticleService $articleService,
        private CommentService $commentService
    ){}

    /**
     * @param string $articleId
     * @param string $userId
     * @param string $comment
     * @return array
     */
    public function __invoke(string $articleId, string $userId, string $comment): array
    {
        $ownComment = $this->commentService->getOwnComment($articleId, $userId);
        $isUpdate = !!$ownComment;
        $this->commentService->upsert($userId, $articleId, $comment);
        $article = $this->articleService->find($articleId);
        return compact('article', 'isUpdate');
    }

}
