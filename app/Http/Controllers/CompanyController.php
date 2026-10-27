<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Active
        $query = Company::latest();
        // Archived
        if ($request->input('archived') == 'true') {
            $query->onlyTrashed();
        }
        $companies = $query->paginate(10)->onEachSide(1);
        return view("company.index", compact("companies"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view("company.create", compact("users"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyCreateRequest $request)
    {
        $validated = $request->validated();
        $owner = User::firstOrCreate([
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'password' => Hash::make($validated['owner_password']),
            'role' => 'company-owner',
            'email_verified_at' => now(),
        ]);
        if(!$owner){
            return redirect()->route("companies.create")->with("error","Failed to create owner!");
        }
        Company::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
            'owner_id' => $owner->id,
        ]);
        return redirect()->route("companies.index")->with("success","Company created successfully!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id = null)
    {
        $company = $this->getCompany($id);
        return view("company.show", compact("id", "company"))->with('tab', request('tab', 'jobs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id = null)
    {
        $company = $this->getCompany($id);

        $users = User::where('role', '!=', 'admin')->get();
        return view("company.edit", compact("company", "users"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id = null)
    {
        $validated = $request->validated();
        $company = $this->getCompany($id);

        $company->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
        ]);
        $ownerData = [];
        $ownerData['name'] = $validated['owner_name'];
        $ownerData['email'] = $validated['owner_email'];

        if($validated['owner_password']){
            $ownerData['password'] = Hash::make($validated['owner_password']);
        }
        $company->user()->update($ownerData);

        if(Auth::user()->role == 'company-owner'){
            return redirect()->route("my-company.show", $id)->with("success","Company Updated Successfully!");
        }

        if($request->query('redirectToList') == 'false'){
            return redirect()->route("companies.show", $id)->with("success","Company Updated Successfully!");

        }
        return redirect()->route("companies.index")->with("success","Company Updated Successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Company::findOrFail($id)->delete();
        return redirect()->route("companies.index")->with("success","Company Archived Successfully!");
    }

    public function restore(string $id)
    {
        Company::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route("companies.index", ['archived' => 'true'])->with("success","Company Restored Successfully!");
    }

    private function getCompany(string $id = null){
        if($id){
            return Company::findOrFail($id);
        }
        return Company::where('owner_id', Auth::user()->id)->first(); 
    }
}
