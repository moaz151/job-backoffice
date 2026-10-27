<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\JobCategoryCreateRequest;
use App\Http\Requests\JobCategoryUpdateRequest;
use App\Models\JobCategory;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobCategory::latest();

        if ($request->input('archived') == 'true') {
            $query->onlyTrashed();
        }

        $categories = $query->paginate(10)->onEachSide(1);
        return view("job-category.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("job-category.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobCategoryCreateRequest $request)
    {
        $validated = $request->validated();
        JobCategory::create($validated);
        return redirect()->route("job-categories.index")->with("success","Job category created successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = JobCategory::findOrFail($id);
        return view("job-category.edit", compact("id", "category"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobCategoryUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        JobCategory::findOrFail($id)->update($validated);
        return redirect()->route("job-categories.index")->with("success","Category Name Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        JobCategory::findOrFail($id)->delete();
        return redirect()->route("job-categories.index")->with("success","Category Name Archived Successfully");
    }

    public function restore(string $id)
    {
        JobCategory::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route("job-categories.index", ['archived' => 'true'])->with("success","Category Name Restored Successfully");
    }
}
