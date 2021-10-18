<?php

namespace App\UseCases\Article;

use App\Services\ArticleService;
use App\Http\Requests\Article\StoreRequest;

class ArticleStoreAction
{
    public function __construct(
        private ArticleService $articleService
    ){}

    /**
     * @param string $userId
     * @param StoreRequest $request
     */
    public function __invoke(string $userId, StoreRequest $request): void
    {
        $this->articleService->create($userId, $request);
    }

}
