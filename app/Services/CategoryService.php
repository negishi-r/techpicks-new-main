<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final class CategoryService
{

    /**
     * @param Category $category
     */
    public function __construct(
        private Category $category
    ) {
    }

    /**
     * 全件取得
     * @return Collection
     */
    public function all(): Collection {
        return $this->category->all();
    }
}
