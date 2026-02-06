<div>
    @if ($open)
        {{-- Modal overlay --}}
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            {{-- Background overlay --}}
            <div
                class="fixed inset-0 bg-gray-500/50 transition-opacity dark:bg-gray-900/50"
                wire:click="closeModal"
            ></div>

            {{-- Modal panel --}}
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    {{-- Close button --}}
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span class="sr-only">Close</span>
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal content --}}
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                            <h3
                                class="text-xl font-semibold leading-6 text-gray-900 dark:text-white"
                                id="modal-title"
                            >Add New Weapon</h3>
                            <div class="mt-6 space-y-4">
                                {{-- Weapon Name --}}
                                <div>
                                    <label
                                        for="weapon-name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Weapon Name</label>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            id="weapon-name"
                                            wire:model="name"
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                                            placeholder="Enter weapon name"
                                        />
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Weapon Type --}}
                                <div>
                                    <label
                                        for="weapon-type"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Weapon Type</label>
                                    <div class="mt-2">
                                        <select
                                            id="weapon-type"
                                            wire:model="type"
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:focus:outline-indigo-500"
                                        >
                                            @foreach ($types as $weaponType)
                                                <option value="{{ $weaponType }}">{{ $weaponType }}</option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Weapon Code Prefix --}}
                                <div>
                                    <label
                                        for="weapon-code-prefix"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Weapon Code Prefix</label>
                                    <div class="mt-2">
                                        <input
                                            type="text"
                                            id="weapon-code-prefix"
                                            wire:model="codePrefix"
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                                            placeholder="Enter weapon code prefix"
                                        />
                                        @error('codePrefix')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal actions --}}
                    <div class="mt-6 sm:flex sm:flex-row-reverse gap-3">
                        <button
                            type="button"
                            wire:click="save"
                            class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:w-auto"
                        >Create Weapon</button>
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20 sm:mt-0 sm:w-auto"
                        >Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
