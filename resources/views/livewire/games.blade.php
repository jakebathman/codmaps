<div class="px-4 py-10 sm:px-6 lg:px-8">

    <div class="pt-5 sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Games</h1>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button
                wire:cloak
                wire:show="!showForm"
                wire:click="$set('showForm', !$wire.showForm)"
                type="button"
                class="shadow-xs block cursor-pointer rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500"
            >Add game</button>
        </div>
    </div>

    <div
        wire:cloak
        wire:show="showForm"
    >
        @if ($errors->any())
            <div class="mt-6 rounded bg-red-100 p-4 text-sm text-red-800">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            wire:submit.prevent="save"
            class="mt-6 flex justify-start gap-8"
        >
            <input
                type="hidden"
                wire:model="gameId"
            />

            {{-- Key --}}
            <div>
                <label
                    for="key"
                    class="block text-sm/6 font-medium text-gray-900 dark:text-white"
                >Key</label>
                <div class="mt-2">
                    <input
                        wire:model="key"
                        id="key"
                        type="text"
                        name="key"
                        placeholder="Game key"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                    />
                </div>
            </div>

            {{-- Name --}}
            <div class="min-w-60">
                <label
                    for="name"
                    class="block text-sm/6 font-medium text-gray-900 dark:text-white"
                >Name</label>
                <div class="mt-2">
                    <input
                        wire:model="name"
                        id="name"
                        type="text"
                        name="name"
                        placeholder="Game name"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                    />
                </div>
            </div>

            {{-- Is Active toggle --}}
            <div>
                <label
                    for="isActive"
                    class="block text-sm/6 font-medium text-gray-900 dark:text-white"
                >Is Active</label>

                <label
                    for="isActive"
                    class="has-checked:bg-indigo-500 dark:has-checked:bg-indigo-600 group relative mt-2 block h-6 w-10 cursor-pointer rounded-full bg-gray-300 transition-colors [-webkit-tap-highlight-color:transparent] dark:bg-gray-600"
                >
                    <input
                        wire:model="isActive"
                        type="checkbox"
                        id="isActive"
                        class="peer sr-only"
                    >

                    <span class="absolute inset-y-0 start-0 m-1 grid size-4 place-content-center rounded-full bg-white text-gray-700 transition-[inset-inline-start] *:last:hidden peer-checked:start-4 peer-checked:*:first:hidden peer-checked:*:last:block dark:bg-gray-900 dark:text-gray-200">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18 18 6M6 6l12 12"
                            ></path>
                        </svg>

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m4.5 12.75 6 6 9-13.5"
                            ></path>
                        </svg>
                    </span>
                </label>
            </div>

            {{-- Save & Cancel --}}
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="shadow-xs mt-7 block cursor-pointer rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500"
                >Save game</button>
                <button
                    wire:click="closeForm()"
                    type="button"
                    class="shadow-xs mt-7 block cursor-pointer rounded-md bg-gray-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 dark:bg-gray-500 dark:hover:bg-gray-400 dark:focus-visible:outline-gray-500"
                >Cancel</button>
            </div>
        </form>
    </div>

    <div>
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="relative min-w-full divide-y divide-gray-300 dark:divide-white/15">
                            <thead>
                                <tr>
                                    <th
                                        scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3 dark:text-white"
                                    >Id</th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Key</th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Name</th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    ># Filters</th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Is active</th>
                                    <th
                                        scope="col"
                                        class="py-3.5 pl-3 pr-4 sm:pr-3"
                                    >
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @foreach ($games as $game)
                                    <tr
                                        wire:key="game-{{ $game['id'] }}"
                                        class="{{ $gameId == $game['id'] ? 'bg-indigo-50 dark:bg-indigo-900' : '' }}"
                                    >
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3 dark:text-white">{{ $game['id'] }} </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $game['key'] }} </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $game['name'] }} </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $game['filters']->count() }} </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $game['is_active'] ? 'Yes' : 'No' }}</td>
                                        <td class="cursor-pointer whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <button
                                                wire:click="edit({{ $game['id'] }})"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                            >Edit<span class="sr-only">, {{ $game['name'] }}</span></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
