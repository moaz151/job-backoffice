<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Job Applicant Status
        </h2>
    </x-slot>
    <div class="overflow-x-auto p-6">
       <x-toast-notification />
        <form action="{{ route('job-applications.update', ['job_application' => $jobApplication->id, 'redirectToList' => request('redirectToList')]) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- Job Application Details--}}
            
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-700">Job Application Details</h3>
                <p class="text-sm text-gray-500">Edit the job Application details below</p>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Applicant Name:</label>
                    <span>{{ $jobApplication->user->name }}</span>
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Job Vacancy:</label>
                    <span>{{ $jobApplication->jobVacancy->title }}</span>
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Company:</label>
                    <span>{{ $jobApplication->jobVacancy->company->name }}</span>
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">AI Generated Score:</label>
                    <span>{{ $jobApplication->aiGeneratedScore }}</span>
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">AI Generated Feedback:</label>
                    <span>{{ $jobApplication->aiGeneratedFeedback }}</span>
                </div>
            
            
            
            {{-- status Select Dropdown --}}
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="pending" {{ old('status', $jobApplication->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ old('status', $jobApplication->status) == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ old('status', $jobApplication->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <a href="{{ route('job-applications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    Update Applicant Status
                </button>
            </div>
            </div>
        </form>

    </div>
</x-app-layout>