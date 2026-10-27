<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Job Vacancy 
        </h2>
    </x-slot>
    <div class="overflow-x-auto p-6">
       <x-toast-notification />
        <form action="{{ route('job-vacancies.store') }}" method="POST">
            @csrf
            {{-- Job Vacancy Details--}}
            
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-700">Job Vacancy Details</h3>
                <p class="text-sm text-gray-500">Enter the job vacancy details below</p>
                <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="{{ $errors->has('title') ? 'outline-red-600 outline outline-1 ' : '' }}mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('title')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700">Job Location</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" class="{{ $errors->has('location') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('location')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="owner_name" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" id="type" class="{{ $errors->has('type') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="full-time" {{ old('type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                    <option value="part-time" {{ old('type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                    <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="remote" {{ old('type') == 'remote' ? 'selected' : '' }}>Remote</option>
                    <option value="hybrid" {{ old('type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
                @error('type')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="owner_password" class="block text-sm font-medium text-gray-700">Expected Salary (USD)</label>
                <input type="number" name="salary" id="salary" value="{{ old('salary') }}" class="{{ $errors->has('salary') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('salary')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>
            {{-- Company Select Dropdown --}}
            <div class="mb-4">
                <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>
                <select name="company_id" id="company_id" class="{{ $errors->has('company_id') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
                @error('company_id')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>
            {{-- Job Category Select Dropdown --}}
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Job Category</label>
                <select name="category_id" id="category_id" class="{{ $errors->has('category_id') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @foreach ($jobCategories as $jobCategory)
                    <option value="{{ $jobCategory->id }}" {{ old('category_id') == $jobCategory->id ? 'selected' : '' }}>{{ $jobCategory->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Job Description --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Job Description</label>
                <textarea rows="4" name="description" id="description" class="{{ $errors->has('description') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <a href="{{ route('job-vacancies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    Add Job Vacancy
                </button>
            </div>
        </form>

    </div>
</x-app-layout>