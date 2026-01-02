<div class="flex items-center justify-between gap-2">
    <div class="flex flex-wrap items-center gap-2">
        <x-nav-button
            routeName="home"
            title="Go back home"
        >
            &lt;
        </x-nav-button>
        <x-nav-button
            routeName="maps"
            title="Edit maps"
        >
            Maps
        </x-nav-button>
        <x-nav-button
            routeName="filters"
            title="Edit filters"
        >
            Filters
        </x-nav-button>
        <x-nav-button
            routeName="games"
            title="Edit games"
        >
            Games
        </x-nav-button>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button
            type="submit"
            class="rounded-md bg-gray-200 px-3 py-2 text-xs font-semibold text-gray-800 shadow-xs hover:bg-gray-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:bg-white/10 dark:text-gray-100 dark:hover:bg-white/20 dark:focus-visible:outline-white/30"
        >Logout</button>
    </form>
</div>
