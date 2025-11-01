<nav class="backdrop-blur-xs sticky top-5 z-10 mx-4 flex items-center justify-between rounded-2xl bg-white/80 px-4 py-2 shadow-md"
    aria-label="top navigation bar">
    <div class="flex items-center justify-center gap-2">
        {{-- Sidebar toggle button for small screens --}}
        <button type="button"
            class="inline-block text-neutral-600 lg:hidden"
            x-on:click="sidebarIsOpen = true">
            <i class="ri-menu-2-fill text-2xl"></i>
            <span class="sr-only">sidebar toggle</span>
        </button>

        {{-- Title Page --}}
        <p class="inline-block text-lg font-semibold text-black">{{ $title }}</p>
    </div>

    {{-- Profile Menu --}}
    <x-profile-menu />
</nav>
