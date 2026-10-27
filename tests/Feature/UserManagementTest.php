<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->user = User::factory()->create(['role' => 'job-seeker']);
});

test('an administrator can change a user password', function () {
    $this->actingAs($this->admin)
        ->put("/users/{$this->user->id}", [
            'password' => 'new-password',
            'confirm_password' => 'new-password',
        ])
        ->assertRedirect(route('users.index'));

    expect(Hash::check('new-password', $this->user->fresh()->password))->toBeTrue();
});

test('user password updates reject short or mismatched passwords', function (string $password, string $confirmation) {
    $this->actingAs($this->admin)
        ->put("/users/{$this->user->id}", [
            'password' => $password,
            'confirm_password' => $confirmation,
        ])
        ->assertSessionHasErrors();

    expect(Hash::check('password', $this->user->fresh()->password))->toBeTrue();
})->with([
    'short password' => ['short', 'short'],
    'mismatched confirmation' => ['new-password', 'different-password'],
]);

test('an administrator can archive and restore a user', function () {
    $this->actingAs($this->admin)
        ->delete("/users/{$this->user->id}")
        ->assertRedirect(route('users.index'));

    expect(User::find($this->user->id))->toBeNull()
        ->and(User::onlyTrashed()->find($this->user->id))->not->toBeNull();

    $this->actingAs($this->admin)
        ->get("/users/{$this->user->id}/restore")
        ->assertRedirect(route('users.index', ['archived' => 'true']));

    expect(User::find($this->user->id))->not->toBeNull();
});
