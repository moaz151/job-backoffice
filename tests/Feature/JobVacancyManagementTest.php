<?php

use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\User;

function createVacancyTestCompany(User $owner, string $name): Company
{
    return Company::create([
        'name' => $name,
        'address' => '1 Main St',
        'industry' => 'Technology',
        'owner_id' => $owner->id,
    ]);
}

function vacancyTestPayload(Company $company, JobCategory $category): array
{
    return [
        'title' => 'Software Engineer',
        'description' => 'Build web applications.',
        'location' => 'Remote',
        'salary' => 120000,
        'type' => 'full-time',
        'company_id' => $company->id,
        'category_id' => $category->id,
    ];
}

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->owner = User::factory()->create(['role' => 'company-owner']);
    $this->company = createVacancyTestCompany($this->owner, 'Example Company');
    $this->category = JobCategory::create(['name' => 'Engineering']);
});

test('an administrator can create, update, archive, and restore a vacancy', function () {
    $payload = vacancyTestPayload($this->company, $this->category);

    $this->actingAs($this->admin)
        ->post('/job-vacancies', $payload)
        ->assertRedirect(route('job-vacancies.index'));

    $vacancy = JobVacancy::where('title', 'Software Engineer')->firstOrFail();

    $this->actingAs($this->admin)
        ->put("/job-vacancies/{$vacancy->id}", [...$payload, 'title' => 'Senior Engineer'])
        ->assertRedirect(route('job-vacancies.index'));

    expect($vacancy->fresh()->title)->toBe('Senior Engineer');

    $this->actingAs($this->admin)
        ->delete("/job-vacancies/{$vacancy->id}")
        ->assertRedirect(route('job-vacancies.index'));

    expect(JobVacancy::find($vacancy->id))->toBeNull()
        ->and(JobVacancy::onlyTrashed()->find($vacancy->id))->not->toBeNull();

    $this->actingAs($this->admin)
        ->get("/job-vacancies/{$vacancy->id}/restore")
        ->assertRedirect(route('job-vacancies.index', ['archived' => 'true']));

    expect(JobVacancy::find($vacancy->id))->not->toBeNull();
});

test('vacancy validation rejects invalid salary, type, company, and category', function () {
    $payload = vacancyTestPayload($this->company, $this->category);
    $payload['salary'] = -1;
    $payload['type'] = 'internship';
    $payload['company_id'] = 'not-a-uuid';
    $payload['category_id'] = 'not-a-uuid';

    $this->actingAs($this->admin)
        ->post('/job-vacancies', $payload)
        ->assertSessionHasErrors(['salary', 'type', 'company_id', 'category_id']);

    expect(JobVacancy::count())->toBe(0);
});

test('company owners only see their own vacancies', function () {
    $otherOwner = User::factory()->create(['role' => 'company-owner']);
    $otherCompany = createVacancyTestCompany($otherOwner, 'Other Company');

    $ownVacancy = JobVacancy::create(vacancyTestPayload($this->company, $this->category));
    $otherVacancy = JobVacancy::create([
        ...vacancyTestPayload($otherCompany, $this->category),
        'title' => 'Other Company Role',
    ]);

    $response = $this->actingAs($this->owner)->get('/job-vacancies')->assertOk();
    $vacancies = $response->viewData('jobVacancies')->getCollection();

    expect($vacancies->modelKeys())->toContain($ownVacancy->id)
        ->and($vacancies->modelKeys())->not->toContain($otherVacancy->id);
});
