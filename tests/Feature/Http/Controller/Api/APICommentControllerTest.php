<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function PHPSTORM_META\type;

class APICommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIfThrowErrorWhenNonExistentArticle(): void
    {
        $response = $this->getJson('/api/comments/1');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'msg' => 'Aucun article ne correspond à cet id !',
        ]);
    }

    public function testIfReturnExpectedValue(): void
    {
        $user = User::factory()->create();
        Article::factory()->create([
            'author_id' => 1,
        ]);
        Comment::factory()->create([
            'content' => 'Commentaire #1',
            'author_id' => 1,
        ]);
        Comment::factory()->create([
            'content' => 'Commentaire #2',
            'author_id' => 1,
        ]);

        $response = $this->getJson('/api/comments/1?orderby=asc');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'data' => [
                [
                    'content' => 'Commentaire #1',
                ],
                [
                    'content' => 'Commentaire #2',
                ],
            ],
        ]);
    }

    public function testIfReturnDataOrderedDesc(): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create([
            'content' => 'Commentaire #1',
        ]);
        Comment::factory()->create([
            'content' => 'Commentaire #2',
        ]);

        $request = $this->get('/api/comments/1');
        $request->assertStatus(200);
        $request->assertJson([
            'status' => 200,
            'data' => [
                [
                    'id' => 2,
                ],
                [
                    'id' => 1,
                ],
            ],
        ]);
    }

    public function testIfReturnDataOrderedAsc(): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create([
            'content' => 'Commentaire #1',
        ]);
        Comment::factory()->create([
            'content' => 'Commentaire #2',
        ]);

        $request = $this->get('/api/comments/1?orderby=asc');
        $request->assertStatus(200);
        $request->assertJson([
            'status' => 200,
            'data' => [
                [
                    'id' => 1,
                ],
                [
                    'id' => 2,
                ],
            ],
        ]);
    }

    public function testIfDataPaginated(): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory(65)->create();

        $response = $this->getJson('/api/comments/1');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'lastPage' => 3,
            'total' => 65,
        ]);
        $this->assertCount(30, $response->json('data'));
    }

    public function testIfCreateNewCommentReply(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comment = Comment::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/api/comments/new/1', [
                'comment_content' => 'Contenu du commentaire',
                'parent' => 1
            ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 201
        ]);

        $this->assertCount(2, Comment::all());
        $this->assertCount(1, Comment::find(1)->replies);
    }

    public function testIfCreateNewCommentReplyWhenNonExistingParent(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/api/comments/new/1', [
                'comment_content' => 'Contenu du commentaire',
                'parent' => 1
            ]);

        $response->assertStatus(500);
        $response->assertJson([
            'status' => 500,
        ]);

        dd($response->getContent());

        $this->assertCount(0, Comment::all());
    }

    public function testIfCreateComment(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/api/comments/new/1', [
                'comment_content' => 'Contenu du commentaire'
            ]);

        $response->assertStatus(201);

        $response->assertJson([
            'status' => 201,
            'data' => [
                'id' => 1,
                'content' => 'Contenu du commentaire',
                'article_id' => 1,
                'author_id' => 1,
                'parent' => null
            ]
            ]);

        $this->assertCount(1, Comment::all());
    }

    public function testIfCreateCommentWhenNonAuthenticated(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this
            ->post('/api/comments/new/1', [
                'comment_content' => 'Contenu du commentaire'
            ]);

        $response->assertStatus(401);
        $response->assertJson([
            'status' => 401
        ]);

        $this->assertCount(0, Comment::all());
    }

    public function testIfCreateCommentErroredData(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/api/comments/new/1');

        $response->assertStatus(500);
        $response->assertJson([
            'status' => 500,
            'msg' => 'Fields validation error'
        ]);
            
        $this->assertCount(0, Comment::all());
    }

    public function testIfDeleteComment(): void 
    {
        /** @var User $user */
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $request = $this
            ->actingAs($user)
            ->delete('/api/comments/delete/1');

        $request->assertStatus(200);
        $request->assertJson([
            'status' => 200
        ]);

        $this->assertCount(0, Comment::all());
    }

    public function testIfDeleteCommentWhenHasReplies(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();
        Comment::factory()->create([
            'parent' => 1
        ]);
        Comment::factory()->create([
            'parent' => 1
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/api/comments/delete/1');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200
        ]);

        $this->assertCount(0, Comment::all());
    }

    public function testIfDeleteCommentWhenUserIsNotAuthor(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var User $user2 */
        $user2 = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this
            ->actingAs($user2)
            ->delete('/api/comments/delete/1');

        $response->assertStatus(403);
        $response->assertJson([
            'status' => 403
        ]);

        $this->assertCount(1, Comment::all());
    }
}
