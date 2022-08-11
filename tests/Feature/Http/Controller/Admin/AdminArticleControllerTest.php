<?php

namespace Tests\Feature\Http\Controller\Admin;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIfErrorWhenDeleteNonExistentArticle(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/admin/article/1/delete');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'L\'article spécifié n\'existe pas !');
    }

    public function testIfDeleteArticle(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/admin/article/1/delete');

        $response->assertRedirect();
        $this->assertCount(0, Article::all());
    }

    public function testIfErorWhenEditNonExistentArticle(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/admin/article/1/edit');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'L\'article n\'existe pas !');
    }

    public function testIfErrorWhenEditWithInvalidData(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/admin/article/1/edit', [
                'content' => 'hello',
                'description' => 'hello',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
