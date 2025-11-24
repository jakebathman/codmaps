<a
    href="{{ route($routeName) }}"
    title="{{ $title ?? '' }}"
    class="rounded-md {{ url()->livewire_current() === $routeName ? 'bg-red-200 hover:bg-red-300 text-red-800 dark:text-red-100' : 'bg-gray-200 hover:bg-gray-300 text-gray-800 dark:text-gray-100' }} px-3 py-2 text-xs font-semibold  shadow-xs  focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400 dark:bg-white/10  dark:hover:bg-white/20 dark:focus-visible:outline-white/30"
>{{ $slot }}</a>
