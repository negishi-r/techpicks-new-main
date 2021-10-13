<?php

declare(strict_types=1);

namespace App\DomainServices;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function all() {
        return $this->category->all();
    }
}
