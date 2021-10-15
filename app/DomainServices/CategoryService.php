<?php

declare(strict_types=1);

namespace App\DomainServices;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final class CategoryService
{
    /** @var Category */
    private $category;

    /**
     * @param Category $category
     */
    public function __construct(
        Category $category
    ) {
        $this->category = $category;
    }

    /**
     * 全件取得
     * @return Collection
     */
    public function all(): Collection {
        return $this->category->all();
    }
}
