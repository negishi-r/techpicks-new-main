<?php

namespace Tests\Feature\Service;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Services\ArticleService;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleServiceTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    private ArticleService $articleService;
    private CommentService $commentService;


    public function setUp(): void {
        parent::setUp();
        $this->articleService = app()->make(ArticleService::class);
        $this->commentService = app()->make(CommentService::class);
    }

    private function createArticle(string $url, string $title, string $description, string $comment)
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $article = Article::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'url' => $url,
            'title' => $title,
            'description' => $description,
        ]);
        Comment::updateOrCreate(
            ['user_id' => $user->id, 'article_id' => $article->id],
            ['body' => $comment],
        );
        return $article;
    }

    public function searchArticlesDataProvider()
    {
        $articlesDataList[0] = ['url' => 'あああ', 'title' => 'aaa', 'description' => 'アアア', 'comment' => 'AAA'];
        $articlesDataList[1] = ['url' => 'いいい', 'title' => 'bbb', 'description' => 'イイイ', 'comment' => 'BBB'];
        $articlesDataList[2] = ['url' => 'ううう', 'title' => 'ccc', 'description' => 'ウウウ', 'comment' => 'CCC'];

        return [
            'URLを検索' => [$articlesDataList, 'あ', 1],
            'Titleを検索' => [$articlesDataList, 'bbb', 1],
            'Descriptionを検索' => [$articlesDataList, 'イ', 1],
            'Commentを検索' => [$articlesDataList, 'bbb', 1],
            '検索結果0件' => [$articlesDataList, 'hoge', 0],
        ];
    }

    /**
     * @param array $articlesDataList
     * @param string $searchQuery
     * @param int $resultCount
     * @dataProvider searchArticlesDataProvider
     */
    public function test_searchArticles_検索(array $articlesDataList, string $searchQuery, int $resultCount)
    {

        foreach ($articlesDataList as $articlesData) {
            $this->createArticle(
                $articlesData['url'],
                $articlesData['title'],
                $articlesData['description'],
                $articlesData['comment']
            );
        }

        $articles = $this->articleService->searchArticles($searchQuery);

        $this->assertSame($articles->count(), $resultCount);
    }

}
