<?php

namespace App\UseCases\Article;

use App\DomainServices\ArticleService;
use App\DomainServices\CommentService;

class ArticleShowAction
{
    public function __construct(
        private ArticleService $articleService,
        private CommentService $commentService
    ){}

    /**
     * @param string $articleId
     * @param string $userId
     * @return array
     */
    public function __invoke(string $articleId, string $userId): array
    {
        $article = $this->articleService->find($articleId);
        $comments = $this->commentService->getArticleComments($articleId);
        $ownComment = $this->commentService->getOwnComment($articleId, $userId);
        return compact('article', 'comments', 'ownComment');
    }

}
