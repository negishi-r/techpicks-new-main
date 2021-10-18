<?php

namespace App\UseCases\Dashboard;

use App\DomainServices\ArticleService;
use \Illuminate\Database\Eloquent\Collection;

class DashboardAction
{
    public function __construct(
        private ArticleService $articleService
    ){}

    /**
     * @return Collection
     */
    public function __invoke(): Collection
    {
        return $this->articleService->getRecentPosts();
    }
}
