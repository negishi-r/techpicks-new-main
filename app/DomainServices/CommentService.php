<?php

declare(strict_types=1);

namespace App\DomainServices;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\returnArgument;

final class CommentService
{
    /** @var Comment */
    private $comment;

    /**
     * @param Comment $comment
     */
    public function __construct(
        Comment $comment
    ) {
        $this->comment = $comment;
    }

    /**
     * @param string $articleId
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getByArticleId(string $articleId, int $limit = 20): LengthAwarePaginator
    {
        return $this->comment
            ->where('article_id', $articleId)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit);
    }

    /**
     * @param string $userId
     * @return string|null
     */
    public function getBodyByUserId(string $userId): ?string
    {

        $comment = $this->comment->where('user_id', $userId)->first();

        if (! $comment) {
            return null;
        }

        return $comment->body;
    }

    /**
     * @param string $id
     * @return Comment
     */
    public function find(string $id): Comment {
        return $this->comment->find($id);
    }

    /**
     * @param string $userId
     * @param string $articleId
     * @param string $comment
     */
    public function updateOrCreate(string $userId, string $articleId, string $comment): void {
        $this->comment->updateOrCreate(
            ['user_id' => $userId, 'article_id' => $articleId],
            ['body' => $comment],
        );
    }
}
