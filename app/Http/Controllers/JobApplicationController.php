<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = JobApplication::latest();

        if(Auth::user()->role == 'company-owner'){
            $query->whereHas('JobVacancy', function($query) {
                $query->where('company_id', Auth::user()->company?->id);
            });
        }
        // Archived
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed();
        }
        $jobApplications = $query->paginate(10)->onEachSide(1);
        return view("job-application.index", compact("jobApplications"));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        return view("job-application.show", compact("jobApplication"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        return view("job-application.edit", compact("id", "jobApplication"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        $jobApplication->update([
            'status' => $request->input('status'),
        ]);

        if ($request->query('redirectToList') == 'false') {
            return redirect()->route("job-applications.show", $id)->with("success","Job Application Status Updated Successfully!");
        }
        return redirect()->route("job-applications.index")->with("success","Job Application Status Updated Successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jobApplication = JobApplication::findOrFail($id);
        $jobApplication->delete();
        return redirect()->route("job-applications.index")->with("success","Job Application Deleted Successfully!");
    }

    public function restore(string $id)
    {
        JobApplication::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route("job-applications.index", ['archived' => 'true'])->with("success","Job Application Restored Successfully!");
    }
}
