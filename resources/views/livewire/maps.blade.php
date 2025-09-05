<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">Maps</h1>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button
                wire:click="create"
                type="button"
                class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500"
            >Add map</button>
        </div>
    </div>

    {{-- Editing inputs --}}
    @if ($editing)
        <div class="mt-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <h2 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">
                @if ($editing === '(new)')
                    Create Map
                @else
                    Editing: {{ $editing }}
                @endif
            </h2>

            <form x-data wire:submit.prevent="save" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input
                        type="text"
                        wire:model="form.name"
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-gray-800 dark:text-white"
                        placeholder="Map name"
                    >
                    @error('form.name')
                        <div class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Game -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Game</label>
                    <select
                        wire:model="form.game"
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-xs focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-gray-800 dark:text-white"
                    >
                        <option value="" disabled>Select a game...</option>
                        @foreach ($games as $key => $game)
                            <option value="{{ $key }}">{{ $game['name'] ?? $key }}</option>
                        @endforeach
                    </select>
                    @error('form.game')
                        <div class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Filters -->
                <div x-data>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Filters</label>

                    <!-- Selected tags -->
                    <div class="mt-1 flex flex-wrap gap-2">
                        @forelse ($form['filters'] as $filter)
                            <span wire:key="selected-filter-{{ md5($filter) }}" class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-xs text-gray-900 dark:bg-white/10 dark:text-gray-100">
                                {{ $filter }}
                                <button type="button" wire:click="removeFilter('{{ $filter }}')" class="-mr-1 rounded p-0.5 hover:bg-gray-200 dark:hover:bg-white/20" aria-label="Remove {{ $filter }}">×</button>
                            </span>
                        @empty
                            <span class="text-xs text-gray-500 dark:text-gray-400">No filters selected</span>
                        @endforelse
                    </div>

                    <!-- Tag input -->
                    <div class="mt-2 flex items-center gap-2">
                        <input
                            type="text"
                            wire:model="filterInput"
                            x-on:keydown.enter.prevent="$wire.addFilter()"
                            x-on:keydown.",".prevent="$wire.addFilter()"
                            class="block w-60 rounded-md border-gray-300 text-sm shadow-xs focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-gray-800 dark:text-white"
                            placeholder="Type filter and press Enter"
                        >
                        <button type="button" wire:click="addFilter" class="rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">Add</button>
                    </div>

                    <!-- Suggestions from selected game -->
                    @php
                        $allowed = collect($filters[$form['game']] ?? []);
                        $selected = collect($form['filters'] ?? []);
                        $suggestions = $allowed->reject(fn ($f) => $selected->contains($f))->values();
                    @endphp

                    @if ($form['game'])
                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-300">Suggestions:</div>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @forelse($suggestions as $s)
                                <button
                                    wire:key="suggestion-{{ md5($s) }}"
                                    type="button"
                                    wire:click="addFilterValue('{{ $s }}')"
                                    class="rounded-md border border-gray-300 px-2 py-1 text-xs text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:text-gray-200 dark:hover:bg-white/10"
                                >{{ $s }}</button>
                            @empty
                                <span class="text-xs text-gray-500 dark:text-gray-400">No suggestions</span>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="pt-2">
                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500"
                        >Save</button>
                        <button
                            type="button"
                            wire:click="cancel"
                            class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-xs hover:bg-gray-50 dark:border-white/10 dark:bg-transparent dark:text-gray-200 dark:hover:bg-white/10"
                        >Cancel</button>
                        @if ($editing !== '(new)')
                            <button
                                type="button"
                                x-on:click.prevent="if (confirm('Delete {{ addslashes($editing) }}? This cannot be undone.')) { $wire.delete() }"
                                class="ml-auto rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 dark:bg-red-500 dark:hover:bg-red-400 dark:focus-visible:outline-red-500"
                            >Delete</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- Maps table --}}
    <div class="-mx-4 mt-8 sm:-mx-0">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-white/15">
            <thead>
                <tr>
                    <th
                        scope="col"
                        class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0 dark:text-white"
                    >Name</th>
                    <th
                        scope="col"
                        class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell dark:text-white"
                    >Game</th>
                    <th
                        scope="col"
                        class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell dark:text-white"
                    >Filters</th>
                    <th
                        scope="col"
                        class="py-3.5 pr-4 pl-3 sm:pr-0"
                    >
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                @foreach ($maps as $map)
                    <tr wire:key="map-{{ md5($map['name']) }}">
                        <td class="w-full max-w-0 py-4 pr-3 pl-4 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-0 dark:text-white">
                            {{ $map['name'] }}
                            <dl class="font-normal lg:hidden">
                                <dt class="sr-only">Game</dt>
                                <dd class="mt-1 truncate text-gray-700 dark:text-gray-300">{{ $map['game'] }}</dd>
                                <dt class="sr-only sm:hidden">Filters</dt>
                                <dd class="mt-1 truncate text-gray-500 sm:hidden dark:text-gray-400">
                                    @foreach ($map['filters'] as $filter)
                                        <flux:badge color="{{ $filterColors[$filter] ?? 'zinc' }}">{{ $filter }}</flux:badge>
                                    @endforeach
                                </dd>
                            </dl>
                        </td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell dark:text-gray-400">{{ $map['game'] }}</td>
                        <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell dark:text-gray-400">
                            @foreach ($map['filters'] as $filter)
                                <flux:badge color="{{ $filterColors[$filter] ?? 'zinc' }}">{{ $filter }}</flux:badge>
                            @endforeach
                        </td>
                        <td class="py-4 pr-4 pl-3 text-right text-sm font-medium sm:pr-0">
                            @if (!$editing)
                                <button
                                    wire:click="edit('{{ $map['name'] }}')"
                                    type="button"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 cursor-pointer"
                                >Edit</button>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
