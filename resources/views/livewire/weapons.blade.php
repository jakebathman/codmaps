<div class="bg-white dark:bg-gray-900 p-6 sm:p-10">

    <div class="max-w-6xl mx-auto">
        <div class="p-8 border-b border-gray-200 flex justify-between">
            <div class="flex items-center gap-4">
                <h2 class="text-3xl font-medium text-gray-900">Weapons</h2>
                <button
                    wire:click="$dispatch('open-new-weapon-modal')"
                    class="cursor-pointer rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >+ New</button>
            </div>

            <div class="flex gap-4">
                {{-- Type dropdown --}}
                <el-dropdown class="inline-block">
                    <button class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20 capitalize">
                        <div class="flex items-center">
                            <div class="flex items-center w-full pr-2">

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
                            @foreach ($types as $id => $type)
                                <button
                                    wire:click="setType('{{ $type }}')"
                                    class="group/item w-full flex items-center px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                                >
                                    <div class="flex items-center w-full">
                                        <div class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold capitalize {{ $currentType === $type ? 'font-semibold' : '' }}">
                                            {{ $type }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </el-menu>
                </el-dropdown>

                {{-- Weapon dropdown --}}
                <el-dropdown class="inline-block">
                    <button class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20 capitalize">
                        <div class="flex items-center">
                            <div class="flex items-center w-full pr-2">

                                <div class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold capitalize ">
                                    {{ $this->weapon?->name ?? 'Select Weapon' }}
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
                            @foreach ($this->weaponsInType as $id => $weapon)
                                <button
                                    wire:click="setWeapon('{{ $weapon->id }}')"
                                    class="group/item w-full flex items-center px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                                >
                                    <div class="flex items-center w-full">
                                        <div class="ml-3 block truncate font-normal group-aria-selected/option:font-semibold capitalize {{ $this->weapon?->id === $weapon->id ? 'font-semibold' : '' }}">
                                            {{ $weapon->name }}
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </el-menu>
                </el-dropdown>
            </div>
        </div>

        {{-- Weapon info --}}
        <div class="p-6 flex flex-col gap-4 max-w-3xl mx-auto">
            <div class="flex flex-col gap-2 max-w-lg mx-auto">
                <div class="flex items-baseline">
                    <div class="w-32">Type</div>
                    <div class="font-bold text-xl capitalize">{{ $this->weapon->type }}</div>
                </div>
                <div class="flex items-baseline">
                    <div class="w-32">Name</div>
                    <div class="font-bold text-xl">{{ $this->weapon->name }}</div>
                </div>
            </div>
        </div>

        @if ($this->needsExpectedCounts === true)
            <div class="flex gap-6 w-full justify-between p-6 flex-wrap">
                @foreach ($attachmentTypes as $t)
                    <div>
                        <div class="mt-4 w-10 max-w-lg mx-auto flex flex-col items-center">
                            <label
                                for="countInput_{{ Str::slug($t) }}"
                                class="block text-sm/6 font-medium text-gray-900 dark:text-white whitespace-nowrap text-center capitalize"
                            >{{ $t }}</label>
                            <div class="mt-2 w-full max-w-3xs mx-auto">
                                <input
                                    wire:key="key_countInput_{{ Str::slug($t) }}"
                                    id="countInput_{{ Str::slug($t) }}"
                                    type="text"
                                    name="countInput_{{ Str::slug($t) }}"
                                    wire:model.live="expectedAttachmentCounts.{{ $t }}"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                                />
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
            <button
                wire:click="saveExpectedCounts"
                class="mt-6 mx-auto block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >Save Expected Counts</button>
        @else
            {{-- Attachments --}}
            <div class="p-6 flex justify-between border-t border-gray-200 dark:border-gray-700 w-full">
                <button
                    wire:click="skip"
                    tabindex="-1"
                    type="button"
                    class="cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20"
                >Skip</button>
                <button
                    wire:click="editCounts"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >Edit Expected Counts</button>

            </div>

            <div>
                {{-- Counts --}}
                <div class="flex gap-6 w-full justify-between p-6 flex-wrap">
                    @foreach ($attachmentTypes as $t)
                        <div>
                            <div class="mt-4 w-10 max-w-lg mx-auto flex flex-col items-center">
                                <label
                                    for="countReadOnly_{{ Str::slug($t) }}"
                                    class="block text-sm/6 font-medium text-gray-900 dark:text-white whitespace-nowrap text-center capitalize"
                                >{{ $t }}</label>
                                <div class="mt-2 w-full max-w-3xs mx-auto">
                                    <div
                                        wire:key="key_countReadOnly_{{ Str::slug($t) }}"
                                        class="text-lg font-semibold text-center whitespace-nowrap {{ $this->countMatchesExpected($t) === 1 ? 'text-green-600 dark:text-green-400' : ($this->countMatchesExpected($t) === -1 ? 'text-pink-300 dark:text-pink-300' : ($this->countMatchesExpected($t) === 2 ? 'text-red-600 dark:text-red-400 font-bold border-b border-b-red-600 dark:border-b-red-400' : 'text-gray-500')) }}"
                                    >
                                        {{ $this->attachmentCountDisplay($t) }} / {{ $this->weapon->expected_attachment_counts[$t] ?? 0 }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Add --}}
                <div>
                    <div>
                        <label
                            for="attachment"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >Add Attachment</label>
                        <div
                            class="mt-1"
                            x-data
                        >
                            <input
                                type="text"
                                placeholder="Search attachments..."
                                wire:model.live="attachmentSearch"
                                x-ref="attachmentSearch"
                                @keydown.down.prevent="$el.nextElementSibling?.firstElementChild?.focus()"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                            />

                            {{-- Results --}}
                            <div class="max-h-60 overflow-y-auto mt-2 border border-gray-200 rounded-md dark:border-gray-700">
                                @foreach ($this->attachmentResults as $result)
                                    <div
                                        class="mt-2 p-2 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                        @click="let el = $refs.attachmentSearch; $wire.addAttachment({{ $result->id }}).then(() => { el.focus(); el.select(); })"
                                        @keydown.enter="let el = $refs.attachmentSearch; $wire.addAttachment({{ $result->id }}).then(() => { el.focus(); el.select(); })"
                                        @keydown.down.prevent="$el.nextElementSibling?.focus()"
                                        @keydown.up.prevent="$el.previousElementSibling ? $el.previousElementSibling.focus() : $refs.attachmentSearch.focus()"
                                        tabindex="0"
                                    >
                                        {{ $result->name }} ({{ $result->label }})
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Added attachments --}}
                <div>
                    <div>
                        <div class="grid grid-cols-1 md:hidden">
                            <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                            <select
                                aria-label="Select a tab"
                                wire:model.live="activeTab"
                                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-white/5 dark:text-gray-100 dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-indigo-500"
                            >
                                @foreach ($attachmentTypes as $t)
                                    <option
                                        class="capitalize"
                                        value="{{ $t }}"{{ $activeTab === $t ? ' selected' : '' }}
                                    >{{ $t }} ({{ $this->attachmentCountDisplay($t) }})</option>
                                @endforeach
                            </select>
                            <svg
                                viewBox="0 0 16 16"
                                fill="currentColor"
                                data-slot="icon"
                                aria-hidden="true"
                                class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500 dark:fill-gray-400"
                            >
                                <path
                                    d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd"
                                    fill-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="hidden md:block">
                            <div class="border-b border-gray-200 dark:border-white/10">
                                <nav
                                    aria-label="Tabs"
                                    class="-mb-px flex space-x-3"
                                >
                                    @foreach ($attachmentTypes as $t)
                                        <a
                                            href="#"
                                            @click.prevent="$wire.setActiveTab('{{ $t }}').then(() => { let el = document.querySelector('[x-ref=attachmentSearch]'); if(el) { el.value = ''; el.focus(); el.dispatchEvent(new Event('input')); } })"
                                            class="flex border-b-2 border-transparent px-1 py-4 text-sm font-medium whitespace-nowrap  {{ $activeTab === $t ? 'border-indigo-500 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-200 dark:hover:border-white/20 hover:text-gray-700 dark:hover:text-white' }}"
                                        >
                                            {{ ucfirst($t) }}
                                            <span class="ml-3 hidden rounded-full px-2.5 py-0.5 text-xs font-medium md:inline-block {{ $activeTab === $t ? 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400' : 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-gray-300' }}">{{ $this->attachmentCountDisplay($t) }}</span>
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                    </div>

                    @foreach ($this->weapon->attachments->where('type', $activeTab) as $attachment)
                        <div class="mt-4 p-4 border border-gray-200 rounded-md flex justify-between items-center dark:border-gray-700">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $attachment->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attachment->label }}</div>
                            </div>
                            <button
                                wire:click="removeAttachment({{ $attachment->id }})"
                                wire:confirm="Are you sure you want to remove this attachment?"
                                class="ml-4 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline  focus-visible:outline-offset-2 focus-visible:outline-red-600"
                            >Remove</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <livewire:new-weapon-modal />
</div>
