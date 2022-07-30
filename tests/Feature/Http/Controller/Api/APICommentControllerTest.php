<?php

namespace Tests\Feature\Http\Controller\Api;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class APICommentControllerTest extends TestCase
{

    public function testIfThrowErrorWhenNonExistentArticle (): void
    {
        $response = $this->getJson('/api/comments/1');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'msg' => 'Aucun article ne correspond à cet id !'
        ]);
    }

    public function testIfReturnExpectedValue (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create([
            'content' => 'Commentaire #1'
        ]);
        Comment::factory()->create([
            'content' => 'Commentaire #2'
        ]);

        $response = $this->getJson('/api/comments/1');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'data' => [
                [
                    'content' => 'Commentaire #1'
                ],
                [
                    'content' => 'Commentaire #2'
                ]
            ]
        ]);
    }

    public function testIfDataPaginated (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory(65)->create();

        $response = $this->getJson('/api/comments/1');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'lastPage' => 3,
            'total' => 65
        ]);
        $this->assertCount(30, $response->json('data'));
    }
}
