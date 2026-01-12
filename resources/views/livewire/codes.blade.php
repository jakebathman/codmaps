<div class="bg-white dark:bg-gray-900 p-6 sm:p-10">

    <div class="max-w-3xl mx-auto">
        <div class="p-8 border-b border-gray-200 flex justify-between">
            <div>
                <h2 class="text-3xl font-medium text-gray-900">Weapon Attachments</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter the weapon attachment code found in-game for this attachment.
                </p>
            </div>

            {{-- Type dropdown --}}
            <el-dropdown class="inline-block">
                <button class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20 capitalize">
                    <div class="flex items-center">
                        <div class="flex items-center w-full pr-2">
                            <div class="flex items-center">
                                <div
                                    aria-hidden="true"
                                    class="inline-block size-2 shrink-0 rounded-full border border-transparent {{ $typesWithCounts[$currentType]['percent_complete'] == 100 ? 'bg-green-400' : ($typesWithCounts[$currentType]['percent_complete'] > 60 ? 'bg-blue-400' : ($typesWithCounts[$currentType]['percent_complete'] > 40 ? 'bg-yellow-400' : ($typesWithCounts[$currentType]['percent_complete'] > 0 ? 'bg-orange-400' : 'bg-red-400'))) }} forced-colors:bg-[Highlight]"
                                ></div>
                                <div class="ml-1">{{ $typesWithCounts[$currentType]['percent_complete'] }}%</div>
                            </div>
                            <div class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold capitalize">
                                {{ $currentType }}
                            </div>
                        </div>

                        <svg
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            data-slot="icon"
                            aria-hidden="true"
                            class="-mr-1 size-5 text-gray-400"
                        >
                            <path
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                            />
                        </svg>
                </button>

                <el-menu
                    anchor="bottom end"
                    popover="auto"
                    class="w-56 origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
                >
                    <div class="py-1">
                        @foreach ($typesWithCounts as $type => $counts)
                            <button
                                wire:click="setType('{{ $type }}')"
                                class="group/item w-full flex items-center px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                            >
                                <div class="flex items-center w-full">
                                    <div class="flex items-center w-14">
                                        <div
                                            aria-hidden="true"
                                            class="inline-block size-2 shrink-0 rounded-full border border-transparent {{ $counts['percent_complete'] == 100 ? 'bg-green-400' : ($counts['percent_complete'] > 60 ? 'bg-blue-400' : ($counts['percent_complete'] > 40 ? 'bg-yellow-400' : ($counts['percent_complete'] > 0 ? 'bg-orange-400' : 'bg-red-400'))) }} forced-colors:bg-[Highlight]"
                                        ></div>
                                        <div class="ml-1">{{ $counts['percent_complete'] }}%</div>
                                    </div>
                                    <div class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold capitalize">
                                        {{ $type }}
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </el-menu>
            </el-dropdown>
        </div>

        {{-- Attachment info --}}
        <div class="p-6 flex flex-col gap-4 max-w-3xl mx-auto">
            @if (!$this->attachment)
                <div class="flex self-center items-center justify-center h-72 text-xl text-gray-600 dark:text-gray-400">All attachments of this type have been entered.</div>
            @else
                <div class="flex flex-col gap-2 max-w-lg mx-auto">
                    <div class="flex items-baseline">
                        <div class="w-32">Type</div>
                        <div class="font-bold text-xl capitalize">{{ $this->attachment->type }}</div>
                    </div>
                    <div class="flex items-baseline">
                        <div class="w-32">Name</div>
                        <div class="font-bold text-xl">{{ $this->attachment->name }}</div>
                    </div>
                    <div class="flex items-baseline">
                        <div class="w-32">Label</div>
                        <div class="font-bold text-xl">{{ $this->attachment->label }}</div>
                    </div>
                    <div class="flex items-baseline">
                        <div class="w-32">Unlocked On</div>
                        <div class="font-bold text-xl">{{ $this->attachment->weapon_unlock }}</div>
                    </div>
                </div>

                <div class="mt-4 w-1/3 max-w-lg mx-auto">
                    <label
                        for="codeInput"
                        class="block text-sm/6 font-medium text-gray-900 dark:text-white"
                    >Weapon Code</label>
                    <div class="mt-2 w-full max-w-3xs mx-auto">
                        <input
                            wire:keydown.enter="saveAndNext"
                            autofocus
                            id="codeInput"
                            type="text"
                            name="codeInput"
                            placeholder="A01-ABC12-XYZ89-1"
                            wire:model.live="codeInput"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                        />
                    </div>
                </div>

                <div class="flex justify-around gap-6 w-full max-w-xl mx-auto">
                    <div class="flex flex-col gap-2 text-center">
                        <div class="font-bold">Input Value</div>
                        <div>{{ $codeInput }}</div>
                    </div>
                    <div class="flex flex-col gap-2 text-center">
                        <div class="font-bold">Base34 Value</div>
                        <div>{{ $this->attachmentsCode }}</div>
                    </div>
                    <div class="flex flex-col gap-2 text-center">
                        <div class="font-bold">Decoded</div>
                        <div>{{ $this->decoded }}</div>
                    </div>
                </div>
            @endif
        </div>

        <div class="max-w-3xl mx-auto">
            @if ($this->isDuplicate)
                <p class="text-sm text-yellow-600 text-center">This code has already been entered for another attachment.</p>
            @elseif (!$this->isValid && strlen($this->codeInput) > 0)
                <p class="text-sm text-red-600 text-center">The code entered is not valid. Please check and try again.</p>
            @endif

        </div>

        <div class="p-6 flex justify-between border-t border-gray-200 dark:border-gray-700">
            <button
                wire:click="skip"
                tabindex="-1"
                type="button"
                class="cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20"
            >Skip</button>

            <button
                wire:click="saveAndNext"
                type="button"
                class="cursor-pointer rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 shadow-xs hover:bg-indigo-100 dark:bg-indigo-500/20 dark:text-indigo-400 dark:shadow-none dark:hover:bg-indigo-500/30 disabled:dark:bg-indigo-500/10 disabled:bg-gray-100 disabled:text-gray-400 disabled:dark:text-gray-500 disabled:cursor-not-allowed"
                :disabled="{{ !$this->isValid }}"
            >Save & Next</button>
        </div>
    </div>

    <div>
        <h2 class="text-3xl font-medium text-gray-900 capitalize">{{ $currentType }} Attachments ({{ $typesWithCounts[$currentType]['filled'] ?? 0 }}/{{ $typesWithCounts[$currentType]['total'] ?? 0 }})</h2>

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="relative min-w-full divide-y divide-gray-300 dark:divide-white/15">
                            <thead>
                                <tr>
                                    <th
                                        scope="col"
                                        class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold whitespace-nowrap text-gray-900 sm:pl-0 dark:text-white"
                                    >Name</th>
                                    <th
                                        scope="col"
                                        class="px-2 py-3.5 text-left text-sm font-semibold whitespace-nowrap text-gray-900 dark:text-white"
                                    >Base 34</th>
                                    <th
                                        scope="col"
                                        class="px-2 py-3.5 text-left text-sm font-semibold whitespace-nowrap text-gray-900 dark:text-white"
                                    >Base 10</th>
                                    <th
                                        scope="col"
                                        class="px-2 py-3.5 text-left text-sm font-semibold whitespace-nowrap text-gray-900 dark:text-white"
                                    >Weapon Unlock</th>
                                    <th
                                        scope="col"
                                        class="py-3.5 pr-4 pl-3 whitespace-nowrap sm:pr-0"
                                    >
                                        <span>Updated At</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                                @foreach ($attachments as $attachment)
                                    <tr>
                                        <td class="py-2 pr-3 pl-4 text-sm whitespace-nowrap text-gray-500 sm:pl-0 dark:text-gray-400">
                                            <div class="text-sm text-gray-400">{{ $attachment->name }}</div>
                                            <div>{{ $attachment->label }}</div>
                                        </td>
                                        <td class="px-2 py-2 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400 uppercase">{{ $attachment->code_base34 }}</td>
                                        <td class="px-2 py-2 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $attachment->code_base10 }}</td>
                                        <td class="px-2 py-2 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $attachment->weapon_unlock }}</td>
                                        <td class="px-2 py-2 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $attachment->updated_at }}</td>

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
