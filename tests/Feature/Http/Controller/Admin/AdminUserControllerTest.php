<?php

namespace Tests\Feature\Http\Controller\Admin;

use App\Events\UserBannedEvent;
use App\Events\UserSuspendedEvent;
use App\Events\UserUnbannedEvent;
use App\Events\UserUnsuspendedEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function testIfBanUser(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $userToBan = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/admin/user/2/ban');

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Event::assertDispatched(UserBannedEvent::class);

        $this->assertNull(User::find(2));
    }

    public function testIfNotBanUserWhenAdmin(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/admin/user/2/ban');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Vous ne pouvez bannir un administrateur');

        Event::assertNotDispatched(UserBannedEvent::class);

        $this->assertNotNull(User::find(2));
    }

    public function testIfUnbanUser(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        User::factory()->create([
            'deleted_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/admin/user/2/unban');

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Event::assertDispatched(UserUnbannedEvent::class);

        $this->assertNull(User::find(2)->deleted_at);
    }

    public function testIfErrorWhenTryingToUnbanNonBannedUser(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/admin/user/2/unban');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'L\'utilisateur n\'es pas banni tu ne peux pas le débannir !');

        Event::assertNotDispatched(UserUnbannedEvent::class);
    }

    public function testIfSuspendUser(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/admin/user/2/suspend');

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Event::assertDispatched(UserSuspendedEvent::class);
        $this->assertTrue((bool) User::find(2)->is_suspended);
    }

    public function testIfErrorWhenSuspendAdmin(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/admin/user/2/suspend');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Vous ne pouvez suspendre un administrateur');

        Event::assertNotDispatched(UserSuspendedEvent::class);

        $this->assertNotNull(User::find(2));
    }

    public function testIfUnsuspendUserWhenNotSuspended(): void
    {
        /** @var User */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/admin/user/2/unsuspend');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'L\'utilisateur n\'es pas suspendu tu ne peux pas le rétablir !');

        Event::assertNotDispatched(UserUnsuspendedEvent::class);
    }
}
