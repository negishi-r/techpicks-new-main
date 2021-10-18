<?php

namespace Tests\Feature\Controller;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_ログインしていない状態で記事一覧ページにアクセスするとログイン画面にリダイレクトされる()
    {
        $response = $this->get('article/index');
        $response->assertRedirect('login');
    }

    public function test_index_ログインしている状態で記事一覧ページにアクセスできる()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('article.index'));

        $response->assertStatus(200)
            ->assertSee('記事一覧');
    }

    public function test_show_記事詳細ページにアクセスできる()
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create();
        $article = Article::factory()
            ->user($this->user->id)
            ->category($category->id)
            ->create();
        $response = $this->get(route('article.show', ['article' => $article]));
        $response->assertStatus(200)
            ->assertSee('記事詳細');
    }

    public function test_create_記事投稿ページにアクセスできる()
    {
        $this->actingAs($this->user);
        $response = $this->get(route('article.create'));
        $response->assertStatus(200)
            ->assertSee('記事投稿');
    }

    public function validateUrlDataProvider()
    {
        return [
            'URLが空白' => ['', false, ['url']],
            'URLが不正' => ['aaaaaaa', false, ['url']],
            'URLが正常' => ['https://ok.example.com', false, []],
            'URLが既に登録済み' => ['https://ng.example.com', true, ['url']],
        ];
    }

    /**
     * @param string $url
     * @param bool $isCreateArticle
     * @param array $errors
     * @dataProvider validateUrlDataProvider
     */
    public function test_validateUrl_不正なURLではバリデーションエラーになる(string $url, bool $isCreateArticle, array $errors)
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create();
        if ($isCreateArticle) {
            Article::factory()
                ->user($this->user->id)
                ->category($category->id)
                ->url($url)
                ->create();
        }
        $response = $this->post(route('article.validateUrl'), ['url' => $url]);
        $response->assertSessionHasErrors($errors);
    }

    public function test_validateUrl_正常なURLでは記事作成プレビューページにリダイレクトされる()
    {
        $this->actingAs($this->user);
        $url = $this->activeRandomUrl();
        $encodedUrl = urlencode($url);
        $response = $this->post(route('article.validateUrl'), ['url' => $url]);
        $response->assertRedirect(route('article.preview', ['url' => $encodedUrl]));
    }


    /**
     * @param string $url
     * @param bool $isCreateArticle
     * @param array $errors
     * @dataProvider validateUrlDataProvider
     */
    public function test_preview_不正なURLではバリデーションエラーになる(string $url, bool $isCreateArticle, array $errors)
    {
        $this->actingAs($this->user);
        $category = Category::factory()->create();
        if ($isCreateArticle) {
            Article::factory()
                ->user($this->user->id)
                ->category($category->id)
                ->url($url)
                ->create();
        }
        $response = $this->get(route('article.preview', ['url' => urlencode($url)]));
        $response->assertSessionHasErrors($errors);
    }

    /**
     * @return void
     */
    public function test_preview_正常なURLでは記事作成プレビューページにアクセスできる()
    {
        $this->actingAs($this->user);
        $url = $this->activeRandomUrl();
        $response = $this->get(route('article.preview', ['url' => urlencode($url)]));
        $response->assertStatus(200)
            ->assertSee('記事投稿 - 確認');
    }



    public function storeDataProvider()
    {
        return [
            '必須未入力' => ['', null, '', '', ['url', 'categoryId', 'title', 'description']],
            '存在しないカテゴリー' => ['https://example.com', -1, 'title', 'description', ['categoryId']],
        ];
    }

    /**
     * @param string $url
     * @param int|null $categoryId
     * @param string $title
     * @param string $description
     * @param array $errors
     * @dataProvider storeDataProvider
     */
    public function test_store_バリデーションエラーになる(string $url, int|null $categoryId, string $title, string $description, array $errors)
    {
        $this->actingAs($this->user);
        $response = $this->post(route('article.store'), [
            'url' => $url,
            'categoryId' => $categoryId,
            'title' => $title,
            'description' => $description,
        ]);
        $response->assertSessionHasErrors($errors);
    }

    private function storeArticle() {
        $category = Category::factory()->create();
        $url = $this->faker->url();
        $categoryId = $category->id;
        $title = $this->faker->title();
        $description = $this->faker->realText(100);
        return compact('url', 'categoryId', 'title', 'description');
    }

    public function test_store_レスポンスのテスト()
    {
        $this->actingAs($this->user);

        $data = $this->storeArticle();

        $response = $this->post(
            route('article.store'),
            $data
        );

        // レスポンスのテスト
        $response->assertRedirect(route('dashboard'))
            ->assertSessionHas('status');
    }

    public function test_store_正常に登録完了()
    {
        $this->actingAs($this->user);

        $data = $this->storeArticle();

        $this->post(
            route('article.store'),
            $data
        );

        // データが正常に登録されたかのテスト(ドメインサービスで登録処理を行っている場合はコントローラーではテストせず、サービス側のテストに任せる)
        $isArticleExists = Article::where('url', $data['url'])
            ->where('category_id', $data['categoryId'])
            ->where('title', $data['title'])
            ->where('description', $data['description'])
            ->exists();
        $this->assertTrue($isArticleExists);
    }

}
