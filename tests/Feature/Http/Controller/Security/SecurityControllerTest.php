<?php

namespace Tests\Feature\Http\Controller\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SecurityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIfErrorWhenRegisterWithBannedMail(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'email' => 'johndoe@gmail.com',
            'deleted_at' => now()
        ]);

        $response = $this->post('/inscription', [
            '_username' => 'John Doe',
            '_email' => 'johndoe@gmail.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Cet adresse mail a été bannie. Veuillez en choisir une autre');

        $this->assertCount(1, User::withTrashed()->get());
    }

    public function testIfErrorWhenRegisterWithBannedUsername(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'name' => 'John Doe',
            'deleted_at' => now()
        ]);

        $response = $this->post('/inscription', [
            '_username' => 'John Doe',
            '_email' => 'johndoe@gmail.com'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ce nom d\'utilisateur a été banni. Veuillez en choisir un autre');

        $this->assertCount(1, User::withTrashed()->get());
    }
}
