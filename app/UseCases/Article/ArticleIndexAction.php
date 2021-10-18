<?php

namespace App\UseCases\Article;

use App\Services\ArticleService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleIndexAction
{
    public function __construct(
        private ArticleService $articleService
    ){}

    /**
     * @param string $searchQuery
     * @return LengthAwarePaginator
     */
    public function __invoke(string $searchQuery): LengthAwarePaginator
    {
        return $this->articleService->searchArticles($searchQuery);
    }

}
