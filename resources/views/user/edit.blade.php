<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User Details
        </h2>
    </x-slot>
    <div class="overflow-x-auto p-6">
       <x-toast-notification />
        <form action="{{ route('users.update', ['user' => $user->id, 'redirectToList' => request('redirectToList')]) }}" method="POST">
            @csrf
            @method('PUT')
            {{-- Job Application Details--}}
            
            <div class="mb-4">
                <h3 class="text-lg font-medium text-gray-700">User Details</h3>
                <p class="text-sm text-gray-500">Edit the user details below</p>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Name:</label>
                    <span>{{ $user->name }}</span>
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Email:</label>
                    <span>{{ $user->email }}</span>
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Role:</label>
                    <span>{{ $user->role }}</span>
                </div>

                <div class="mb-4">
                    <label for="user_email" class="block text-sm font-medium text-gray-700">User Email</label>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="{{ $errors->has('password') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="{{ $errors->has('confirm_password') ? 'outline-red-600 outline outline-1 ' : '' }} mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('confirm_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            
            <div class="mb-4">
                <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2">
                    Update User Details
                </button>
            </div>
            </div>
        </form>

    </div>
</x-app-layout>