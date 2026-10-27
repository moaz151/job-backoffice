<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobVacancy;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $analytics = $this->adminDashboard();
        } else {
            $analytics = $this->companyOwnerDashboard();
        }
        return view("dashboard.index", compact('analytics'));
    }

    private function adminDashboard() {
        // Active users
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
        ->where('role', 'job-seeker')->count();
        // Total Jobs
        $totalJobs = JobVacancy::count();
        // Total Applications
        $totalApplications = JobApplication::whereNull('deleted_at')->count();

        // Most Applied Jobs
        $mostAppliedJobs = JobVacancy::withCount('JobApplications as totalCount')
        ->orderBy('totalCount', 'desc')
        ->limit(3)
        ->whereNull('deleted_at') // Laravel Handled it Already but just in case 
        ->get();


        // Conversion Rate
        $conversionRate = JobVacancy::withCount('JobApplications as totalCount')
            ->whereHas('JobApplications')
            ->limit(5)
            ->orderByDesc('totalCount')
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($job) {
                if ($job->viewCount > 0) {
                    $job->conversionRate = round(($job->totalCount / $job->viewCount) * 100, 2);
                } else {
                    $job->conversionRate = 0;
                }
                return $job;
        });

        $analytics = [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionRates'=> $conversionRate
        ];

        return $analytics;
    }

    private function companyOwnerDashboard() {
        // filter active users by appling to jobs of the company
        $company = Auth::user()->company;
        $activeUsers = User::where('last_login_at','>=', now()->subDays(30))
        ->where('role', 'job-seeker')
        ->wherehas('JobApplication', function($query) use ($company) {
            $query->whereIn('job_vacancy_id', $company->jobVacancies->pluck('id'));
        })
        ->count();
        // total jobs of the company
        $totalJobs = $company->JobVacancies->count();

        // total applications of the company
        $totalApplications = JobApplication::whereIn('job_vacancy_id', $company->jobVacancies->pluck('id'))->count();

        // Most Applied Jobs of the company
        $mostAppliedJobs = JobVacancy::withCount('JobApplications as totalCount')
        ->whereIn('id', $company->jobVacancies->pluck('id'))
        ->orderByDesc('totalCount')
        ->limit(3)
        ->whereNull('deleted_at') // Laravel Handled it Already but just in case 
        ->get();

        // Conversion Rate Of the company
        $conversionRate = JobVacancy::withCount('JobApplications as totalCount')
        ->whereIn ('id', $company->jobVacancies->pluck('id'))
        ->whereHas('JobApplications')
        ->limit(5)
        ->orderByDesc('totalCount')
        ->whereNull('deleted_at')
        ->get()
        ->map(function ($job) {
            if ($job->viewCount > 0) {
                $job->conversionRate = round(($job->totalCount / $job->viewCount) * 100, 2);
            } else {
                $job->conversionRate = 0;
            }
            return $job;
        });

        $analytics = [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionRates' => $conversionRate
        ];

        return $analytics;
    }
}
