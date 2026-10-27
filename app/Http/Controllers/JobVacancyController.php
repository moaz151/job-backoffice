<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\JobCategory;
use App\Http\Requests\JobVacancyCreateRequest;
use App\Http\Requests\JobVacancyUpdateRequest;
use Illuminate\Support\Facades\Auth;


class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = JobVacancy::latest();
        if (Auth::user()->role == 'company-owner') {
            $query->where('company_id', Auth::user()->company->id);
        }
        // Archived
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed();
        }
        $jobVacancies = $query->paginate(10)->onEachSide(1);
        return view("job-vacancy.index", compact("jobVacancies"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        $jobCategories = JobCategory::all();
        return view("job-vacancy.create", compact("companies", "jobCategories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobVacancyCreateRequest $request)
    {
        $validated = $request->validated();
        JobVacancy::create($validated);
        return redirect()->route("job-vacancies.index")->with("success", "Job vacancy created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        return view("job-vacancy.show", compact("jobVacancy"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $companies = Company::all();
        $jobCategories = JobCategory::all();
        return view("job-vacancy.edit", compact("jobVacancy", "companies", "jobCategories"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobVacancyUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        $jobVacancy = JobVacancy::findOrFail($id);
        $jobVacancy->update($validated);
        return redirect()->route("job-vacancies.index")->with("success", "Job vacancy updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $jobVacancy->delete();
        return redirect()->route("job-vacancies.index")->with("success", "Job vacancy deleted successfully");
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        JobVacancy::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route("job-vacancies.index", ['archived' => 'true'])->with("success", "Job vacancy restored successfully");
    }
}
