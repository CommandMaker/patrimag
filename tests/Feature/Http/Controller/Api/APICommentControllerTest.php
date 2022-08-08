<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class APICommentControllerTest extends TestCase
{
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
        Article::factory()->create();
        Comment::factory()->create([
            'content' => 'Commentaire #1',
        ]);
        Comment::factory()->create([
            'content' => 'Commentaire #2',
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
}
