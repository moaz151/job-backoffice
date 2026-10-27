<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function companyManagementPayload(string $ownerEmail = 'owner@example.com'): array
{
    return [
        'name' => 'Example Company',
        'address' => '1 Main St',
        'industry' => 'Technology',
        'website' => 'https://example.com',
        'owner_name' => 'Company Owner',
        'owner_email' => $ownerEmail,
        'owner_password' => 'password123',
        'owner_confirm_password' => 'password123',
    ];
}

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

test('an administrator can create a company and its owner account', function () {
    $this->actingAs($this->admin)
        ->post('/companies', companyManagementPayload())
        ->assertRedirect(route('companies.index'));

    $owner = User::where('email', 'owner@example.com')->firstOrFail();
    $company = Company::where('name', 'Example Company')->firstOrFail();

    expect($owner->role)->toBe('company-owner')
        ->and(Hash::check('password123', $owner->password))->toBeTrue()
        ->and($company->owner_id)->toBe($owner->id)
        ->and($company->website)->toBe('https://example.com');
});

test('company creation validates required data, unique names, and matching owner passwords', function () {
    $this->actingAs($this->admin)
        ->post('/companies', [...companyManagementPayload(), 'owner_confirm_password' => 'different-password'])
        ->assertSessionHasErrors('owner_confirm_password');

    $payload = companyManagementPayload();
    $this->actingAs($this->admin)->post('/companies', $payload);
    $this->actingAs($this->admin)
        ->post('/companies', [...$payload, 'owner_email' => 'second-owner@example.com'])
        ->assertSessionHasErrors('name');

    expect(Company::count())->toBe(1)
        ->and(User::where('role', 'company-owner')->count())->toBe(1);
});

test('company owners can update their own company and owner profile', function () {
    $owner = User::factory()->create(['role' => 'company-owner']);
    $company = Company::create([
        'name' => 'Original Company',
        'address' => 'Old address',
        'industry' => 'Technology',
        'owner_id' => $owner->id,
    ]);

    $this->actingAs($owner)
        ->put('/my-company', [
            'name' => 'Updated Company',
            'address' => 'New address',
            'industry' => 'Software',
            'website' => 'https://updated.example.com',
            'owner_name' => 'Updated Owner',
            'owner_email' => $owner->email,
            'owner_password' => null,
            'owner_confirm_password' => null,
        ])
        ->assertRedirect(route('my-company.show'));

    expect($company->fresh()->name)->toBe('Updated Company')
        ->and($company->fresh()->industry)->toBe('Software')
        ->and($owner->fresh()->name)->toBe('Updated Owner');
});

test('an administrator can archive and restore a company', function () {
    $owner = User::factory()->create(['role' => 'company-owner']);
    $company = Company::create([
        'name' => 'Archive Company',
        'address' => '1 Main St',
        'industry' => 'Technology',
        'owner_id' => $owner->id,
    ]);

    $this->actingAs($this->admin)
        ->delete("/companies/{$company->id}")
        ->assertRedirect(route('companies.index'));

    expect(Company::find($company->id))->toBeNull()
        ->and(Company::onlyTrashed()->find($company->id))->not->toBeNull();

    $this->actingAs($this->admin)
        ->get("/companies/{$company->id}/restore")
        ->assertRedirect(route('companies.index', ['archived' => 'true']));

    expect(Company::find($company->id))->not->toBeNull();
});
