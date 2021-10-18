<?php

declare(strict_types=1);

namespace App\DomainServices;

use App\Models\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use function PHPUnit\Framework\returnArgument;

final class CommentService
{
    /**
     * @param Comment $comment
     */
    public function __construct(
        private Comment $comment
    ) {
    }

    /**
     * 投稿コメントを取得
     * @param string $articleId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getArticleComments(string $articleId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->comment
            ->where('article_id', $articleId)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * 自分の投稿コメントを取得
     * @param string $articleId
     * @param string $userId
     * @return string|null
     */
    public function getOwnComment(string $articleId, string $userId): ?string
    {

        $comment = $this->comment->where([
            ['article_id', $articleId],
            ['user_id', $userId]
        ])->first();

        if (! $comment) {
            return null;
        }

        return $comment->body;
    }

    /**
     * 一件取得
     * @param int $id
     * @return Comment
     */
    public function find(int $id): Comment {
        return $this->comment->find($id);
    }

    /**
     * コメントをアップサート
     * @param string $userId
     * @param string $articleId
     * @param string $comment
     */
    public function upsert(string $userId, string $articleId, string $comment): void {
        $this->comment->updateOrCreate(
            ['user_id' => $userId, 'article_id' => $articleId],
            ['body' => $comment],
        );
    }
}
