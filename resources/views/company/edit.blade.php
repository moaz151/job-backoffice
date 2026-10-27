@php
if(auth()->user()->role == 'admin') {
    $formAction = route('companies.update', ['company' => $company->id, 'redirectToList' => request('redirectToList')]);
} elseif(auth()->user()->role == 'company-owner') {
    $formAction = route('my-company.update');   
}
@endphp


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Company Details 
        </h2>
    </x-slot>
    <div class="overflow-x-auto p-6">
       <x-toast-notification />
        <form action="{{ $formAction }}" method="POST">
            @method('PUT')
            @csrf
            {{-- Company Details--}}
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" class="{{ $errors->has('name') ? 'outline-red-600 outline outline-1 ' : '' }}mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('name')
                    <p class="mt-2 text-sm  text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Company Address</label>
                <input type="text" name="address" id="address" value="{{ old('address', $company->address) }}" class="{{ $errors->has('address') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="industry" class="block text-sm font-medium text-gray-700">Company Industry</label>
                <input type="text" name="industry" id="industry" value="{{ old('industry', $company->industry) }}" class="{{ $errors->has('industry') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('industry')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="website" class="block text-sm font-medium text-gray-700">Company Website (Optional)</label>
                <input type="text" name="website" id="website" value="{{ old('website', $company->website) }}" class="{{ $errors->has('website') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('website')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <h3 class="text-lg font-medium text-gray-700">Owner Details</h3>
            {{-- Owner Details --}}
            <div class="mb-4">
                <label for="owner_name" class="block text-sm font-medium text-gray-700">Owner Name</label>
                <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name', $company->user->name) }}" class="{{ $errors->has('owner_name') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('owner_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="owner_email" class="block text-sm font-medium text-gray-700">Owner Email</label>
                <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email', $company->user->email) }}" class="{{ $errors->has('owner_email') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('owner_email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="owner_password" class="block text-sm font-medium text-gray-700">Owner Password</label>
                <input type="password" name="owner_password" id="owner_password" value="{{ old('owner_password') }}" class="{{ $errors->has('owner_password') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('owner_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="owner_confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="owner_confirm_password" id="owner_confirm_password" value="{{ old('owner_confirm_password') }}" class="{{ $errors->has('owner_confirm_password') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('owner_confirm_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                @if (auth()->user()->role == 'admin')
                    <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2">Cancel</a>
                @endif

                @if (auth()->user()->role == 'company-owner')
                    <a href="{{ route('my-company.show') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2">Cancel</a>
                @endif

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    Edit Company
                </button>
            </div>
        </form>

    </div>
</x-app-layout>