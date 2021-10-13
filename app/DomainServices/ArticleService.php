<?php

declare(strict_types=1);

namespace App\DomainServices;

use App\Http\Requests\Article\StoreRequest;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ArticleService
{
    /** @var Article */
    private $article;

    /**
     * @param Article $article
     */
    public function __construct(
        Article $article
    ) {
        $this->article = $article;
    }

    /**
     * @param string $searchQuery
     * @return LengthAwarePaginator
     */
    public function searchArticles(string $searchQuery): LengthAwarePaginator
    {
        $articles = $this->article::with(['user', 'comments'])
            ->where('title', 'like', "%{$searchQuery}%")
            ->orWhere('description', 'like', "%{$searchQuery}%")
            ->orWhereHas('comments', function ($query) use ($searchQuery) {
                $query->where('body', 'like', "%{$searchQuery}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $articles;
    }

    public function find(string $id): Article {
        return $this->article->find($id);
    }

    public function store(string $userId, StoreRequest $request) {
        Article::create([
            'user_id' => $userId,
            'category_id' => $request->input('categoryId'),
            'url' => $request->input('url'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image_path' => $request->input('imagePath'),
        ]);
    }
}
