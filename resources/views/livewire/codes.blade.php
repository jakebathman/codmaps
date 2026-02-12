<div
    class="bg-white p-6 sm:p-10 dark:bg-gray-900"
    x-data="{ isOpen: false, isOpenWeapons: false }"
    x-init="$watch('isOpen', () => {
        $nextTick(() => {
            if (isOpen) {
                $refs.attachmentSearchInput.focus();
                $refs.attachmentSearchInput.select();
            }
        })
    })"
>

    <div class="mx-auto max-w-3xl">
        <div class="flex justify-between border-b border-gray-200 p-8">
            <div>
                <div class="flex items-center gap-4">
                    <h2 class="text-3xl font-medium text-gray-900">Weapon Attachments</h2>
                    <button
                        wire:click="$dispatch('open-new-attachment-modal')"
                        class="cursor-pointer rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >+ New</button>
                </div>
                <p class="mt-2 text-sm text-gray-600">
                    Enter the weapon attachment code found in-game for this attachment.
                </p>
            </div>

            <div class="flex flex-col gap-4">
                {{-- Type dropdown --}}
                <el-dropdown class="flex w-full justify-end">
                    <button class="shadow-xs inset-ring-1 inset-ring-gray-300 dark:inset-ring-white/5 inline-flex justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold capitalize text-gray-900 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:hover:bg-white/20">
                        <div class="flex items-center">
                            <div class="flex w-full items-center pr-2">
                                <div class="flex items-center">
                                    <div
                                        aria-hidden="true"
                                        class="{{ $typesWithCounts[$currentType]['percent_complete'] == 100 ? 'bg-green-400' : ($typesWithCounts[$currentType]['percent_complete'] > 60 ? 'bg-blue-400' : ($typesWithCounts[$currentType]['percent_complete'] > 40 ? 'bg-yellow-400' : ($typesWithCounts[$currentType]['percent_complete'] > 0 ? 'bg-orange-400' : 'bg-red-400'))) }} inline-block size-2 shrink-0 rounded-full border border-transparent forced-colors:bg-[Highlight]"
                                    ></div>
                                    <div class="ml-1">{{ $typesWithCounts[$currentType]['percent_complete'] }}%</div>
                                </div>
                                <div class="ml-3 block truncate font-normal capitalize group-aria-selected/option:font-semibold">
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
                        class="transition-discrete data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in w-56 origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition [--anchor-gap:--spacing(2)] dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
                    >
                        <div class="py-1">
                            @foreach ($typesWithCounts as $type => $counts)
                                <button
                                    wire:click="setType('{{ $type }}')"
                                    class="group/item focus:outline-hidden flex w-full items-center px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                                >
                                    <div class="flex w-full items-center">
                                        <div class="flex w-14 items-center">
                                            <div
                                                aria-hidden="true"
                                                class="{{ $counts['percent_complete'] == 100 ? 'bg-green-400' : ($counts['percent_complete'] > 60 ? 'bg-blue-400' : ($counts['percent_complete'] > 40 ? 'bg-yellow-400' : ($counts['percent_complete'] > 0 ? 'bg-orange-400' : 'bg-red-400'))) }} inline-block size-2 shrink-0 rounded-full border border-transparent forced-colors:bg-[Highlight]"
                                            ></div>
                                            <div class="ml-1">{{ $counts['percent_complete'] }}%</div>
                                        </div>
                                        <div class="ml-3 block truncate font-normal capitalize group-aria-selected/option:font-semibold">
                                            {{ $type }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </el-menu>
                </el-dropdown>

                {{-- Attachment dropdown --}}
                <div
                    class="relative"
                    @click.outside="isOpen = false"
                >
                    <button
                        class="inline-flex w-full cursor-pointer items-center justify-between gap-x-1 text-sm/6 font-semibold text-gray-900 dark:text-white"
                        @click="isOpen = !isOpen"
                        x-ref="attachmentButton"
                    >
                        <div class="flex flex-col text-left">
                            <div class="text-sm text-gray-400 dark:text-gray-500">{{ $this->attachment?->name ?? '—' }}</div>
                            <div class="font-semibold">{{ $this->attachment?->label ?? 'Select Attachment' }}</div>
                        </div>
                        <svg
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            data-slot="icon"
                            aria-hidden="true"
                            class="size-5"
                        >
                            <path
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                            />
                        </svg>
                    </button>

                    <div
                        id="attachment-menu"
                        x-cloak
                        :class="{ 'hidden': !isOpen }"
                        x-anchor="$refs.attachmentButton"
                        class="data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in z-50 w-screen max-w-max rounded-lg bg-transparent px-4 transition [--anchor-gap:--spacing(5)] backdrop:bg-transparent open:flex"
                    >
                        <div class="h-96 overflow-y-scroll rounded-lg bg-white text-sm/6 shadow-lg outline-1 outline-gray-900/5 lg:max-w-5xl dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">
                            <input
                                wire:model.live="attachmentSearchInput"
                                type="text"
                                placeholder="Search attachments..."
                                x-ref="attachmentSearchInput"
                                class="mx-auto mb-2 mt-2 block w-1/2 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                            />

                            <div class="grid grid-cols-2 gap-x-6 gap-y-1 p-4 md:grid-cols-3">

                                @foreach ($this->allAttachments as $attachment)
                                    <div
                                        class="group relative flex cursor-pointer gap-x-4 rounded-lg px-2 py-1 hover:bg-gray-50 dark:hover:bg-white/5"
                                        @click="$wire.setAttachment({{ $attachment->id }}).then(() => { isOpen = false;$refs.codeInput.focus() })"
                                    >
                                        <div>
                                            <a
                                                href="#"
                                                class="font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{ $attachment->label }}
                                                <span class="absolute inset-0"></span>
                                            </a>
                                            <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $attachment->name }}</p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Attachment info --}}
        <div class="mx-auto flex max-w-4xl flex-col gap-4 p-6">
            @if (!$this->attachment)
                <div class="flex h-72 items-center justify-center self-center text-xl text-gray-600 dark:text-gray-400">All attachments of this type have been entered.</div>
            @else
                <div class="mx-auto flex max-w-xl flex-col gap-2">
                    <div class="flex items-baseline">
                        <div class="w-32">ID</div>
                        <div class="text-xl font-bold capitalize">{{ $this->attachment->id }}</div>
                    </div>
                    <div class="flex items-baseline">
                        <div class="w-32">Type</div>
                        <div class="text-xl font-bold capitalize">{{ $this->attachment->type }}</div>
                    </div>
                    <div class="flex items-baseline">
                        <div class="w-32">Name</div>
                        <div class="text-xl font-bold">{{ $this->attachment->name }}</div>
                    </div>
                    <div class="flex items-baseline">
                        <div class="w-32">Label</div>
                        <div class="text-xl font-bold">{{ $this->attachment->label }}</div>
                    </div>
                    <div class="flex items-baseline">
                        <div class="w-32">Unlocked On</div>
                        <div class="text-xl font-bold">{{ $this->attachment->weapon_unlock }}</div>
                    </div>
                </div>

                <div class="mx-auto grid w-full max-w-xl grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="mx-auto w-full">
                        <label
                            for="attachedWeaponsInput"
                            class="block text-sm/6 font-medium text-gray-900 dark:text-white"
                        >Attached Weapons</label>
                        <div class="mx-auto mt-2 w-full">

                            {{-- Attached weapons dropdown --}}
                            <div
                                class="relative"
                                @click.outside="isOpenWeapons = false"
                            >
                                <button
                                    class="inline-flex w-full cursor-pointer items-center justify-between gap-x-1 text-sm/6 font-semibold text-gray-900 dark:text-white"
                                    @click="isOpenWeapons = !isOpenWeapons"
                                    x-ref="attachedWeaponsDropdownButton"
                                >
                                    <div class="flex flex-col gap-2">
                                        @if ($this->attachedWeapons?->count() === 0)
                                            <div class="text-sm text-gray-400 dark:text-gray-500">No weapons</div>
                                        @else
                                            @foreach ($this->attachedWeapons?->groupBy('type') as $type => $weapons)
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 text-sm text-gray-400 dark:text-gray-500">{{ $weapons?->count() ?? '—' }} / {{ $this->allWeapons->where('type', $type)->count() ?? '—' }}</div>
                                                    <div class="font-semibold">{{ $type }}</div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <svg
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        data-slot="icon"
                                        aria-hidden="true"
                                        class="size-5"
                                    >
                                        <path
                                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd"
                                            fill-rule="evenodd"
                                        />
                                    </svg>
                                </button>

                                <div
                                    id="attached-weapons-menu"
                                    x-cloak
                                    :class="{ 'hidden': !isOpenWeapons }"
                                    x-anchor.right-start="$refs.attachedWeaponsDropdownButton"
                                    class="data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in z-50 w-screen max-w-max rounded-lg bg-transparent px-4 transition [--anchor-gap:--spacing(5)] backdrop:bg-transparent open:flex"
                                >
                                    <div class="h-96 overflow-y-scroll rounded-lg bg-white text-sm/6 shadow-lg outline-1 outline-gray-900/5 lg:max-w-5xl dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">

                                        @foreach ($this->allWeapons->groupBy('type') as $type => $weapons)
                                            <div class="px-4 py-2 uppercase text-gray-500 dark:text-gray-400">{{ $type }}</div>

                                            <div class="grid grid-cols-2 gap-x-6 gap-y-1 p-4 md:grid-cols-3">

                                                @foreach ($weapons as $weapon)
                                                    <div class="flex gap-3">
                                                        <div class="flex h-6 shrink-0 items-center">
                                                            <div class="group grid size-4 grid-cols-1">
                                                                <input
                                                                    id="weapon-{{ $weapon->id }}"
                                                                    wire:key="weapon-{{ $weapon->id }}"
                                                                    type="checkbox"
                                                                    name="weapon-{{ $weapon->id }}"
                                                                    wire:model.live="attachedWeaponIds"
                                                                    value="{{ $weapon->id }}"
                                                                    checked
                                                                    class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:checked:border-indigo-500 dark:checked:bg-indigo-500 dark:indeterminate:border-indigo-500 dark:indeterminate:bg-indigo-500 dark:focus-visible:outline-indigo-500 dark:disabled:border-white/5 dark:disabled:bg-white/10 dark:disabled:checked:bg-white/10 forced-colors:appearance-auto"
                                                                />
                                                                <svg
                                                                    viewBox="0 0 14 14"
                                                                    fill="none"
                                                                    class="group-has-disabled:stroke-gray-950/25 dark:group-has-disabled:stroke-white/25 pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white"
                                                                >
                                                                    <path
                                                                        d="M3 8L6 11L11 3.5"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="group-has-checked:opacity-100 opacity-0"
                                                                    />
                                                                    <path
                                                                        d="M3 7H11"
                                                                        stroke-width="2"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        class="group-has-indeterminate:opacity-100 opacity-0"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="text-sm/6">
                                                            <label
                                                                for="weapon-{{ $weapon->id }}"
                                                                class="font-medium text-gray-900 dark:text-white"
                                                            >{{ $weapon->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div class="mx-auto mt-4 w-full">
                            <label
                                for="codeInput"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white"
                            >Weapon Code</label>
                            <div class="mx-auto mt-2 w-full">
                                <input
                                    wire:keydown.enter="saveAndNext"
                                    autofocus
                                    id="codeInput"
                                    x-ref="codeInput"
                                    type="text"
                                    name="codeInput"
                                    placeholder="A01-ABC12-XYZ89-1"
                                    wire:model.live="codeInput"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base uppercase text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                                />
                            </div>
                        </div>

                        <div class="mx-auto w-full">
                            <label
                                for="notesInput"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white"
                            >Notes</label>
                            <div class="mx-auto mt-2 w-full">
                                <textarea
                                    id="notesInput"
                                    x-ref="notesInput"
                                    name="notesInput"
                                    rows="3"
                                    placeholder=""
                                    wire:model.live="notesInput"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mx-auto mt-4 grid w-full max-w-xl grid-cols-3 gap-6">
                    <div class="flex flex-col gap-2 text-center">
                        <div class="font-bold">Input Value</div>
                        <div>{{ $codeInput }}</div>
                    </div>
                    <div class="flex flex-col gap-2 text-center">
                        <div class="font-bold">Base34 Value</div>
                        <div class="flex items-center justify-center gap-1">
                            <div>{{ $this->attachmentsCodeIdExists === null ? '' : ($this->attachmentsCodeIdExists === true ? '✅' : '🚫') }}</div>
                            <div>{{ $this->attachmentsCode }}</div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 text-center">
                        <div class="font-bold">Base10 Value</div>
                        <div>{{ $this->decoded }}</div>
                    </div>
                </div>

                @if ($this->attachment?->code_base34)
                    <div>
                        <div class="mb-2 text-center text-gray-400 dark:text-gray-400">Current values for this attachment:</div>
                        <div class="mx-auto mt-4 grid w-full max-w-xl grid-cols-3 gap-6">
                            <div class="flex flex-col gap-2 text-center">
                                <div class="font-bold">Has Weapon(s)?</div>
                                <div>{{ $this->attachment?->weapons->count() > 0 ? '✅' : '🚫' }}</div>
                            </div>
                            <div class="flex flex-col gap-2 text-center">
                                <div class="font-bold">Base34 Value</div>
                                <div>{{ $this->attachment->code_base34 }}</div>
                            </div>
                            <div class="flex flex-col gap-2 text-center">
                                <div class="font-bold">Base10 Value</div>
                                <div>{{ $this->attachment->code_base10 }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <div class="mx-auto max-w-3xl">
            @if ($this->isDuplicate)
                <p class="text-center text-sm text-yellow-600">This code has already been entered for another attachment.</p>
            @elseif (!$this->isValid && strlen($this->codeInput) > 0)
                <p class="text-center text-sm text-red-600">The code entered is not valid. Please check and try again.</p>
            @endif

        </div>

        <div class="flex justify-between border-t border-gray-200 p-6 dark:border-gray-700">
            <button
                wire:click="skip"
                tabindex="-1"
                type="button"
                class="shadow-xs inset-ring inset-ring-gray-300 dark:inset-ring-white/5 cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:hover:bg-white/20"
            >Skip</button>

            <button
                wire:click="cloneAttachment"
                tabindex="-1"
                type="button"
                class="shadow-xs inset-ring inset-ring-gray-300 dark:inset-ring-white/5 cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:hover:bg-white/20"
            >Clone</button>

            <button
                wire:click="saveAndNext"
                type="button"
                class="shadow-xs cursor-pointer rounded-md bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-100 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400 dark:bg-indigo-500/20 dark:text-indigo-400 dark:shadow-none dark:hover:bg-indigo-500/30 disabled:dark:bg-indigo-500/10 disabled:dark:text-gray-500"
            >Save & Next</button>
        </div>
    </div>

    <div>
        <h2 class="text-3xl font-medium capitalize text-gray-900">{{ $currentType }} Attachments ({{ $typesWithCounts[$currentType]['filled'] ?? 0 }}/{{ $typesWithCounts[$currentType]['total'] ?? 0 }})</h2>

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="relative min-w-full divide-y divide-gray-300 dark:divide-white/15">
                            <thead>
                                <tr>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0 dark:text-white"
                                    >Name</th>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Weapons</th>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Base 34</th>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    ></th>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Base 10</th>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Weapon Unlock</th>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                                    >Notes</th>
                                    <th
                                        scope="col"
                                        class="whitespace-nowrap py-3.5 pl-3 pr-4 sm:pr-0"
                                    >
                                        <span>Updated At</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                                @foreach ($attachments as $attachment)
                                    <tr>
                                        <td
                                            class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-0 dark:text-gray-400"
                                            @click="$wire.setAttachment({{ $attachment->id }}).then(() => { isOpen = false;$refs.codeInput.focus() })"
                                        >
                                            <div class="text-sm text-gray-400">{{ $attachment->name }}</div>
                                            <div>{{ $attachment->label }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm uppercase text-gray-500 dark:text-gray-400">{{ $attachment->weapons?->count() }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm uppercase text-gray-500 dark:text-gray-400">{{ $attachment->code_base34 }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $attachment->validBase34() === false ? '⚠️' : '' }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $attachment->code_base10 }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $attachment->weapon_unlock }}</td>
                                        <td class="px-2 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $attachment->notes }}</td>
                                        <td class="whitespace-nowrap px-2 py-2 text-center text-sm text-gray-500 dark:text-gray-400">{{ $attachment->updated_at }}</td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- New Attachment Modal --}}
    <livewire:new-attachment-modal />
</div>
