<?php

namespace App\UseCases\Article;

use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;

class ArticlePreviewAction
{
    public function __construct(
        private CategoryService $categoryService
    ){}

    /**
     * @return Collection
     */
    public function __invoke(): Collection
    {
        return $this->categoryService->all();
    }

}
