<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_co_owner_cannot_access_user_management_routes(): void
    {
        $coOwner = User::factory()->create(['role' => 'co_owner']);

        $this->actingAs($coOwner)->get(route('users.index'))->assertForbidden();
        $this->actingAs($coOwner)->post(route('users.store'), [])->assertForbidden();
    }

    public function test_owner_can_create_more_accounts_than_the_initial_two(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $this->actingAs($owner)->post(route('users.store'), [
            'name' => 'Kasir Baru',
            'email' => 'kasir@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'co_owner',
            'aktif' => 1,
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'kasir@example.test',
            'role' => 'co_owner',
            'aktif' => true,
        ]);
        $this->assertDatabaseCount('users', 2);
    }

    public function test_owner_cannot_deactivate_self_or_last_active_owner(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $this->patch(route('users.status', $owner))
            ->assertSessionHasErrors('aktif');

        $this->put(route('users.update', $owner), [
            'name' => $owner->name,
            'email' => $owner->email,
            'role' => 'co_owner',
            'aktif' => 1,
        ])->assertSessionHasErrors('role');

        $this->assertTrue($owner->fresh()->aktif);
        $this->assertSame('owner', $owner->fresh()->role);
    }

    public function test_deactivated_user_cannot_continue_using_an_existing_session(): void
    {
        $disabled = User::factory()->create(['role' => 'co_owner', 'aktif' => false]);

        $this->actingAs($disabled)->get(route('produk.index'))
            ->assertRedirect(route('login'));
    }
}
