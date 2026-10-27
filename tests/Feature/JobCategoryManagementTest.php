<?php

use App\Models\JobCategory;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

test('an administrator can create and update a category', function () {
    $this->actingAs($this->admin)
        ->post('/job-categories', ['name' => 'Engineering'])
        ->assertRedirect(route('job-categories.index'));

    $category = JobCategory::where('name', 'Engineering')->firstOrFail();

    $this->actingAs($this->admin)
        ->put("/job-categories/{$category->id}", ['name' => 'Product'])
        ->assertRedirect(route('job-categories.index'));

    expect($category->fresh()->name)->toBe('Product');
});

test('category names are required and unique', function () {
    JobCategory::create(['name' => 'Engineering']);

    $this->actingAs($this->admin)
        ->post('/job-categories', ['name' => ''])
        ->assertSessionHasErrors('name');

    $this->actingAs($this->admin)
        ->post('/job-categories', ['name' => 'Engineering'])
        ->assertSessionHasErrors('name');
});

test('an administrator can archive and restore a category', function () {
    $category = JobCategory::create(['name' => 'Engineering']);

    $this->actingAs($this->admin)
        ->delete("/job-categories/{$category->id}")
        ->assertRedirect(route('job-categories.index'));

    expect(JobCategory::find($category->id))->toBeNull()
        ->and(JobCategory::onlyTrashed()->find($category->id))->not->toBeNull();

    $this->actingAs($this->admin)
        ->get("/job-categories/{$category->id}/restore")
        ->assertRedirect(route('job-categories.index', ['archived' => 'true']));

    expect(JobCategory::find($category->id))->not->toBeNull();
});
