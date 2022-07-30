<?php

namespace Tests\Feature\Http\Controller;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;


    public function testIfCreateCommentWhenAllValid (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/1/submit-comment', [
                'comment_content' => 'Contenu d\'un commentaire'
            ]);

            $this->assertCount(1, Comment::all());
            $response->assertSessionHas('success', 'Votre commentaire a bien été publié !');
            $response->assertRedirect();
    }

    public function testIfErrorSubmitCommentWithoutAuthenticated (): void
    {
        $response = $this->post('/article/1/submit-comment', [
            'comment_content' => 'Contenu d\'un commentaire'
        ]);

        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorSubmitCommentWithNonExistentArticleId (): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/2/submit-comment', [
                'comment_content' => 'Contenu d\'un commentaire',
            ]);

        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorCreateCommentWithSubmittedDataError (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/1/submit-comment', [
                'comment_content' => 123456789
            ]);

        $response->assertSessionHasErrors(['comment_content']);
        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorCreateCommentWithoutData (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/1/submit-comment');

        $response->assertSessionHasErrors(['comment_content']);
        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorWhenDeleteCommentWithoutCommentId (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this->delete('/article/delete-comment');

        $this->assertCount(1, Comment::all());
        $response->assertSessionHasErrors(['msg' => 'Il est nécéssaire de spécifier un commentaire pour le supprimer']);
        $response->assertRedirect();
    }

    public function testIfErrorWhenDeleteCommentWithoutAuthenticated (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this->delete('/article/delete-comment?cid=1');

        $this->assertCount(1, Comment::all());
        $response->assertSessionHasErrors(['msg' => 'Je ne sais pas comment vous avez trouvé ça mais vous devez être connecté pour vous en servir !']);
        $response->assertRedirect();
    }

    public function testIfErrorWhenDeleteCommentForNonExistingComment (): void
    {
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/article/delete-comment?cid=2');

        $this->assertCount(1, Comment::all());
        $response->assertSessionHasErrors(['msg' => 'Le commentaire spécifié n\'existe pas !']);
        $response->assertRedirect();
    }

    public function testIfErrorWhenDeleteCommentOfOther (): void
    {
        $user1 = User::factory()->create();
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/article/delete-comment?cid=1');

        $this->assertCount(1, Comment::all());
        $response->assertSessionHasErrors(['msg' => 'Ce n\'est pas bien de vouloir supprimer les commentaires des autres !']);
        $response->assertRedirect();
    }
}
