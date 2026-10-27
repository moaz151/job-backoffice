<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $jobApplication->user->name }} | Applied To: {{ $jobApplication->jobVacancy?->title ?? 'N/A' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('job-applications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to Job Applications
            </a>
        </div>
        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
            <div>
                <h3 class="text-lg font-bold">Job Application Details</h3>
                <p><strong>Applicant Name:</strong> {{ $jobApplication->user->name }}</p>
                <p><strong>Position (Job Vacancy):</strong> {{ $jobApplication->jobVacancy?->title ?? 'N/A' }}</p>
                <p><strong>Company:</strong> {{ $jobApplication->jobVacancy?->company?->name ?? 'N/A' }}</p>
                <p><strong>Status:</strong> <span class=" @if ($jobApplication->status == 'accepted') text-green-500 @elseif ($jobApplication->status == 'rejected') text-red-500 @else text-yellow-500 @endif">{{ $jobApplication->status }}</span></p>
                <p><strong>Resume:</strong> 
                    <a class="text-blue-500 hover:text-blue-700 underline" href="{{ $jobApplication->resume?->file_url }}" target="_blank">
                        {{ $jobApplication->resume?->file_url }}
                    </a></p>
            </div>
            {{-- Edit and Archive Buttons --}}
            <div class="flex justify-end space-x-4 mb-6">
                <a class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" href="{{ route('job-applications.edit', ['job_application' => $jobApplication->id, 'redirectToList' => 'false']) }}">Edit</a>
                <form action="{{ route('job-applications.destroy', $jobApplication->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Archive
                    </button>
                </form>
            </div>

            {{-- Tabs Navigations --}}
            <div class="mb-6">
                <ul class="flex space-x-4">
                    <li>
                        <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'resume']) }}" class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'resume' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">Resume</a>    
                    </li>
                    <li>
                        <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'aiGeneratedFeedback']) }}" class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'aiGeneratedFeedback' ? 'border-b-2 border-blue-500' : '' }}">AI Feedback</a>    
                    </li>
                </ul>
            </div>

            {{-- Tab Content --}}
            <div class="mb-6">
                {{-- Resume Tab --}}
                <div id="resume" class="{{ request('tab') == 'resume' || request('tab') == '' ? 'block' : 'hidden' }}">
                    <table class="min-w-full bg-gray-50 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">Summary</th>
                                <th class="py-2 px-4 text-left bg-gray-100">Skills</th>
                                <th class="py-2 px-4 text-left bg-gray-100">Experience</th>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tr-lg">Education</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->summary }}</td>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->skills }}</td>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->experience }}</td>
                                    <td class="py-2 px-4">{{ $jobApplication->resume->education }}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
                {{-- AI Feedback Tab --}}
                <div id="aiGeneratedFeedback" class="{{ request('tab') == 'aiGeneratedFeedback' || request('tab') == '' ? 'block' : 'hidden' }}">
                    <table class="min-w-full bg-gray-50 rounded-lg shadow">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">AI Score</th>
                                <th class="py-2 px-4 text-left bg-gray-100 rounded-tl-lg">AI Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="py-2 px-4">{{ $jobApplication->aiGeneratedScore }}</td>
                                    <td class="py-2 px-4">{{ $jobApplication->aiGeneratedFeedback }}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>