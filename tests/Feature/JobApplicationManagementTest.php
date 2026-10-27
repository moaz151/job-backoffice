<?php

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;

function createApplicationFixture(User $owner, string $companyName, User $applicant): array
{
    $company = Company::create([
        'name' => $companyName,
        'address' => '1 Main St',
        'industry' => 'Technology',
        'owner_id' => $owner->id,
    ]);
    $category = JobCategory::create(['name' => "Category {$companyName}"]);
    $vacancy = JobVacancy::create([
        'title' => "Role at {$companyName}",
        'description' => 'A test role.',
        'location' => 'Remote',
        'salary' => 100000,
        'type' => 'full-time',
        'company_id' => $company->id,
        'category_id' => $category->id,
    ]);
    $resume = Resume::create([
        'file_name' => 'resume.pdf',
        'file_url' => 'resumes/resume.pdf',
        'contact_details' => '{}',
        'education' => 'Degree',
        'summary' => 'Experienced candidate',
        'skills' => 'PHP',
        'experience' => 'Five years',
        'user_id' => $applicant->id,
    ]);
    $application = JobApplication::create([
        'status' => 'pending',
        'aiGeneratedScore' => 85,
        'aiGeneratedFeedback' => 'Good match.',
        'user_id' => $applicant->id,
        'resume_id' => $resume->id,
        'job_vacancy_id' => $vacancy->id,
    ]);

    return [$company, $vacancy, $application];
}

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->owner = User::factory()->create(['role' => 'company-owner']);
    $this->applicant = User::factory()->create(['role' => 'job-seeker']);
});

test('administrators can update application status', function (string $status) {
    [, , $application] = createApplicationFixture($this->owner, "Company {$status}", $this->applicant);

    $this->actingAs($this->admin)
        ->put("/job-applications/{$application->id}", ['status' => $status])
        ->assertRedirect(route('job-applications.index'));

    expect($application->fresh()->status)->toBe($status);
})->with(['pending', 'accepted', 'rejected']);

test('company owners only see applications for their vacancies', function () {
    [, , $ownApplication] = createApplicationFixture($this->owner, 'Owner Company', $this->applicant);
    $otherOwner = User::factory()->create(['role' => 'company-owner']);
    [, , $otherApplication] = createApplicationFixture($otherOwner, 'Other Company', $this->applicant);

    $response = $this->actingAs($this->owner)->get('/job-applications')->assertOk();
    $applications = $response->viewData('jobApplications')->getCollection();

    expect($applications->modelKeys())->toContain($ownApplication->id)
        ->and($applications->modelKeys())->not->toContain($otherApplication->id);
});

test('an administrator can archive and restore an application', function () {
    [, , $application] = createApplicationFixture($this->owner, 'Archive Company', $this->applicant);

    $this->actingAs($this->admin)
        ->delete("/job-applications/{$application->id}")
        ->assertRedirect(route('job-applications.index'));

    expect(JobApplication::find($application->id))->toBeNull()
        ->and(JobApplication::onlyTrashed()->find($application->id))->not->toBeNull();

    $this->actingAs($this->admin)
        ->get("/job-applications/{$application->id}/restore")
        ->assertRedirect(route('job-applications.index', ['archived' => 'true']));

    expect(JobApplication::find($application->id))->not->toBeNull();
});
