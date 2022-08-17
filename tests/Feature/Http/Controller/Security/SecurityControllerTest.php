<?php

namespace Tests\Feature\Http\Controller\Security;

use App\Models\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIfErrorWhenRegisterWithBannedMail(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'email' => 'johndoe@gmail.com',
            'deleted_at' => now(),
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
            'deleted_at' => now(),
        ]);

        $response = $this->post('/inscription', [
            '_username' => 'John Doe',
            '_email' => 'johndoe@gmail.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ce nom d\'utilisateur a été banni. Veuillez en choisir un autre');

        $this->assertCount(1, User::withTrashed()->get());
    }

    public function testIfErrorWhenEditProfileWithAlreadyTakenUsername(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
        ]);
        /** @var User */
        $user1 = User::factory()->create([
            'name' => 'John Doe1',
        ]);

        $response = $this
            ->actingAs($user1)
            ->post('/profil', [
                'update' => null,
                'username' => 'John Doe',
                'email' => $user1->email,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['username' => 'Ce nom d\'utilisateur est déjà pris !']);

        $this->assertEquals($user1->name, User::find(2)->name);
    }

    public function testIfErrorWhenEditProfileWithAlreadyTakenEmail(): void
    {
        $user = User::factory()->create([
            'email' => 'johndoe@gmail.com',
        ]);
        /** @var User */
        $user1 = User::factory()->create([
            'name' => 'johndoe1@gmail.com',
        ]);

        $response = $this
            ->actingAs($user1)
            ->post('/profil', [
                'update' => null,
                'username' => $user1->email,
                'email' => 'johndoe@gmail.com',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'Cet adresse email est déjà prise !']);

        $this->assertEquals($user1->email, User::find(2)->email);
    }

    public function testIfErrorWhenEditPasswordWithoutTrueDefaultPassword(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'password' => Hash::make('123456789'),
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/profil', [
                'password' => null,
                'actual_password' => '12345678',
                'new_password' => '12345678910',
                'confirm_new_password' => '12345678910',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['actual_password' => 'Le mot de passe n\'est pas identique à votre mot de passe actuel']);
    }

    public function testIfErrorWhenEditPasswordWithoutSamePassword(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'password' => Hash::make('123456789'),
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/profil', [
                'password' => null,
                'actual_password' => '123456789',
                'new_password' => '12345678910',
                'confirm_new_password' => '1234567891',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['confirm_new_password' => 'Vos mots de passes ne correspondent pas !']);
    }
}
