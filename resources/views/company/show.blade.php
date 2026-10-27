<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Details') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />
        @if (auth()->user()->role == 'admin')
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Companies
                </a>
            </div>
        @endif
        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
            <div>
                <h3 class="text-lg font-bold">Company Information</h3>
                <p><strong>Name:</strong> {{ $company->name }}</p>
                <p><strong>Address:</strong> {{ $company->address }}</p>
                <p><strong>Industry:</strong> {{ $company->industry }}</p>
                <p><strong>Website:</strong><a class="text-blue-500 hover:text-blue-700 underline" href="{{ $company->website }}"target="_blank"> {{ $company->website }}</a></p>
                <p><strong>Company Owner:</strong> {{ $company->user->name }}</p>
                <p><strong>Company Owner Email:</strong> {{ $company->user->email }}</p>
                {{-- <p><strong>Owner:</strong> {{ $company->owner->name }}</p> --}}
                <p><strong>Created At:</strong> {{ $company->created_at->format('d-m-Y H:i:s') }}</p>
                <p><strong>Updated At:</strong> {{ $company->updated_at->format('d-m-Y H:i:s') }}</p>
            </div>
            {{-- Edit and Archive Buttons --}}
            <div class="flex justify-end space-x-4 mb-6">
                @if (auth()->user()->role == 'company-owner')
                    <a class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route('my-company.edit') }}">Edit</a>
                @else
                    <a class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route('companies.edit', ['company' => $company->id, 'redirectToList' => 'false']) }}">Edit</a>
                @endif
                @if (auth()->user()->role == 'admin')
                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Archive
                        </button>
                    </form>
                @endif
            </div>
            @if (auth()->user()->role == 'admin')
                {{-- Tabs Navigations --}}
                <div class="mb-6">
                    <ul class="flex space-x-4">
                        <li>
                            <a href="{{ route('companies.show', ['company' => $company->id, 'tab' => 'jobs']) }}" class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'jobs' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">Jobs</a>    
                        </li>
                        <li>
                            <a href="{{ route('companies.show', ['company' => $company->id, 'tab' => 'applications']) }}" class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'applications' ? 'border-b-2 border-blue-500' : '' }}">Applications</a>    
                        </li>
                    </ul>
                </div>

                {{-- Tab Content --}}
                <div>
                    {{-- Jobs Tab --}}
                    <div id="jobs" class="{{ request('tab') == 'jobs' || request('tab') == '' ? 'block' : 'hidden' }}">
                        <table class="min-w-full bg-gray-50 rounded-lg shadow">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Title</th>
                                    <th class="py-2 px-4 text-left bg-gray-100">Type</th>
                                    <th class="py-2 px-4 text-left bg-gray-100">Location</th>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->JobVacancies as $job)
                                    <tr>
                                        <td class="py-2 px-4">{{ $job->title }}</td>
                                        <td class="py-2 px-4">{{ $job->type }}</td>
                                        <td class="py-2 px-4">{{ $job->location }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('job-vacancies.show', $job->id) }}" class="text-blue-500 hover:text-blue-700 underline">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Applications Tab --}}
                    <div id="applications" class="{{ request('tab') == 'applications' ? 'block' : 'hidden' }}">
                        <table>
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Applicant Name</th>
                                    <th class="py-2 px-4 text-left bg-gray-100">Job Title</th>
                                    <th class="py-2 px-4 text-left bg-gray-100">Status</th>
                                    <th class="py-2 px-4 text-left bg-gray-100 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->JobApplications as $application)
                                    <tr>
                                        <td class="py-2 px-4">{{ $application->user->name }}</td>
                                        <td class="py-2 px-4">{{ $application->JobVacancy->title }}</td>
                                        <td class="py-2 px-4">{{ $application->status }}</td>
                                        <td class="py-2 px-4">
                                            <a href="{{ route('job-applications.show', $application->id) }}" class="text-blue-500 hover:text-blue-700 underline">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>