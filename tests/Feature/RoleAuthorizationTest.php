<?php

use App\Models\Company;
use App\Models\User;

test('guests are redirected to login for backoffice routes', function () {
    $this->get('/')->assertRedirect('/login');
    $this->get('/companies')->assertRedirect('/login');
});

test('job seekers cannot access backoffice dashboards or resources', function () {
    $jobSeeker = User::factory()->create(['role' => 'job-seeker']);

    $this->actingAs($jobSeeker)->get('/')->assertForbidden();
    $this->actingAs($jobSeeker)->get('/job-vacancies')->assertForbidden();
    $this->actingAs($jobSeeker)->get('/companies')->assertForbidden();
});

test('company owners can access shared resources but not admin-only resources', function () {
    $owner = User::factory()->create(['role' => 'company-owner']);
    Company::create([
        'name' => 'Owner Company',
        'address' => '1 Main St',
        'industry' => 'Technology',
        'owner_id' => $owner->id,
    ]);

    $this->actingAs($owner)->get('/')->assertOk();
    $this->actingAs($owner)->get('/job-vacancies')->assertOk();
    $this->actingAs($owner)->get('/companies')->assertForbidden();
    $this->actingAs($owner)->get('/job-categories')->assertForbidden();
});

test('administrators can access admin resources', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->get('/')->assertOk();
    $this->actingAs($admin)->get('/companies')->assertOk();
    $this->actingAs($admin)->get('/job-categories')->assertOk();
    $this->actingAs($admin)->get('/users')->assertOk();
});
