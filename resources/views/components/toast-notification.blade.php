<div class="absolute inset-x-0 top-15 z-50 max-w-2xl mx-auto">
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" xtransition x-init="setTimeout(()=> show = false, 3000)"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif
</div>