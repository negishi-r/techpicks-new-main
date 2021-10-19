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
     * @param array $array
     */
    public function __invoke(array $array): void
    {
        $this->articleService->create($array);
    }

}
