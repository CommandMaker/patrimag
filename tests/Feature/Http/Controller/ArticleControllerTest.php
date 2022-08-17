<?php

namespace Tests\Feature\Http\Controller;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIfCreateCommentWhenAllValid(): void
    {
        /** @var User */
        $user = User::factory()->create();
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/1/submit-comment', [
                'comment_content' => 'Contenu d\'un commentaire',
            ]);

        $this->assertCount(1, Comment::all());
        $response->assertSessionHas('success', 'Votre commentaire a bien été publié !');
        $response->assertRedirect();
    }

    public function testIfErrorSubmitCommentWithoutAuthenticated(): void
    {
        $response = $this->post('/article/1/submit-comment', [
            'comment_content' => 'Contenu d\'un commentaire',
        ]);

        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorSubmitCommentWithNonExistentArticleId(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/2/submit-comment', [
                'comment_content' => 'Contenu d\'un commentaire',
            ]);

        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorCreateCommentWithSubmittedDataError(): void
    {
        /** @var User */
        $user = User::factory()->create();
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/1/submit-comment', [
                'comment_content' => 123456789,
            ]);

        $response->assertSessionHasErrors(['comment_content']);
        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorCreateCommentWithoutData(): void
    {
        /** @var User */
        $user = User::factory()->create();
        Article::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/article/1/submit-comment');

        $response->assertSessionHasErrors(['comment_content']);
        $this->assertCount(0, Comment::all());
        $response->assertRedirect();
    }

    public function testIfErrorWhenDeleteCommentWithoutCommentId(): void
    {
        /** @var User */
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/article/delete-comment');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Il est nécéssaire de spécifier un commentaire pour le supprimer');
        $this->assertCount(1, Comment::all());
    }

    public function testIfErrorWhenDeleteCommentWithoutAuthenticated(): void
    {
        /** @var User */
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this->get('/article/delete-comment?cid=1');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Je ne sais pas comment vous avez trouvé ça mais vous devez être connecté pour vous en servir !');
        $this->assertCount(1, Comment::all());
    }

    public function testIfErrorWhenDeleteCommentForNonExistingComment(): void
    {
        /** @var User */
        $user = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/article/delete-comment?cid=2');

        $this->assertCount(1, Comment::all());
        $response->assertSessionHas('error', 'Le commentaire spécifié n\'existe pas !');
        $response->assertRedirect();
    }

    public function testIfErrorWhenDeleteCommentOfOther(): void
    {
        /** @var User */
        $user = User::factory()->create();
        $user1 = User::factory()->create();
        Article::factory()->create();
        Comment::factory()->create([
            'author_id' => 2
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/article/delete-comment?cid=1');

        $this->assertCount(1, Comment::all());
        $response->assertSessionHas('error', 'Ce n\'est pas bien de vouloir supprimer les commentaires des autres !');
        $response->assertRedirect();
    }
}
