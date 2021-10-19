<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\Article\StoreRequest;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class ArticleService
{

    /**
     * @param Article $article
     */
    public function __construct(
        private Article $article
    ) {
    }

    /**
     * 投稿の検索
     * @param string $searchQuery
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function searchArticles(string $searchQuery, int $perPage = 10): LengthAwarePaginator
    {
        return $this->article::with(['user', 'comments'])
            ->where('title', 'like', "%{$searchQuery}%")
            ->orWhere('description', 'like', "%{$searchQuery}%")
            ->orWhereHas('comments', function ($query) use ($searchQuery) {
                $query->where('body', 'like', "%{$searchQuery}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * 一件取得
     * @param string $id
     * @return Article
     */
    public function find(string $id): Article {
        return $this->article->find($id);
    }

    /**
     * 作成
     * @param array $array
     */
    public function create(array $array) {
        $this->article::create($array);
    }

    /**
     * 最近の投稿を取得
     * @param int $limt
     * @return Collection
     */
    public function getRecentPosts(int $limt = 10): Collection {
        return $this->article->orderBy('created_at', 'desc')
            ->limit($limt)
            ->get();
    }
}
