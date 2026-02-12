<div class="bg-white p-6 sm:p-10 dark:bg-gray-900">

    <div class="mx-auto max-w-6xl">
        <div class="flex justify-between border-b border-gray-200 p-8">
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
                    <button class="shadow-xs inset-ring-1 inset-ring-gray-300 dark:inset-ring-white/5 inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold capitalize text-gray-900 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:hover:bg-white/20">
                        <div class="flex items-center">
                            <div class="flex w-full items-center pr-2">

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
                            @foreach ($types as $id => $type)
                                <button
                                    wire:click="setType('{{ $type }}')"
                                    class="group/item focus:outline-hidden flex w-full items-center px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                                >
                                    <div class="flex w-full items-center">
                                        <div class="{{ $currentType === $type ? 'font-semibold' : '' }} ml-3 block truncate font-normal capitalize group-aria-selected/option:font-semibold">
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
                    <button class="shadow-xs inset-ring-1 inset-ring-gray-300 dark:inset-ring-white/5 inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold capitalize text-gray-900 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:hover:bg-white/20">
                        <div class="flex items-center">
                            <div class="flex w-full items-center pr-2">

                                <div class="ml-3 block truncate font-normal capitalize group-aria-selected/option:font-semibold">
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
                        class="transition-discrete data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in w-56 origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition [--anchor-gap:--spacing(2)] dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10"
                    >
                        <div class="py-1">
                            @foreach ($this->weaponsInType as $id => $weapon)
                                <button
                                    wire:click="setWeapon('{{ $weapon->id }}')"
                                    class="group/item focus:outline-hidden flex w-full items-center px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                                >
                                    <div class="flex w-full items-center">
                                        <div class="{{ $this->weapon?->id === $weapon->id ? 'font-semibold' : '' }} ml-3 block truncate font-normal capitalize group-aria-selected/option:font-semibold">
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
        <div class="mx-auto flex max-w-3xl flex-col gap-4 p-6">
            <div class="mx-auto flex max-w-lg flex-col gap-2">
                <div class="flex items-baseline">
                    <div class="w-32">Type</div>
                    <div class="text-xl font-bold capitalize">{{ $this->weapon->type }}</div>
                </div>
                <div class="flex items-baseline">
                    <div class="w-32">Name</div>
                    <div class="text-xl font-bold">{{ $this->weapon->name }}</div>
                </div>
            </div>
        </div>

        @if ($this->needsExpectedCounts === true)
            <div class="flex w-full flex-wrap justify-between gap-6 p-6">
                @foreach ($attachmentTypes as $t)
                    <div>
                        <div class="mx-auto mt-4 flex w-10 max-w-lg flex-col items-center">
                            <label
                                for="countInput_{{ Str::slug($t) }}"
                                class="block whitespace-nowrap text-center text-sm/6 font-medium capitalize text-gray-900 dark:text-white"
                            >{{ $t }}</label>
                            <div class="max-w-3xs mx-auto mt-2 w-full">
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
                class="mx-auto mt-6 block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >Save Expected Counts</button>
        @else
            {{-- Attachments --}}
            <div class="flex w-full justify-between border-t border-gray-200 p-6 dark:border-gray-700">
                <button
                    wire:click="skip"
                    tabindex="-1"
                    type="button"
                    class="shadow-xs inset-ring inset-ring-gray-300 dark:inset-ring-white/5 cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:hover:bg-white/20"
                >Skip</button>
                <button
                    wire:click="editCounts"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >Edit Expected Counts</button>

            </div>

            <div>
                {{-- Counts --}}
                <div class="flex w-full flex-wrap justify-between gap-6 p-6">
                    @foreach ($attachmentTypes as $t)
                        <div>
                            <div class="mx-auto mt-4 flex w-10 max-w-lg flex-col items-center">
                                <label
                                    for="countReadOnly_{{ Str::slug($t) }}"
                                    class="block whitespace-nowrap text-center text-sm/6 font-medium capitalize text-gray-900 dark:text-white"
                                >{{ $t }}</label>
                                <div class="max-w-3xs mx-auto mt-2 w-full">
                                    <div
                                        wire:key="key_countReadOnly_{{ Str::slug($t) }}"
                                        class="{{ $this->countMatchesExpected($t) === 1 ? 'text-green-600 dark:text-green-400' : ($this->countMatchesExpected($t) === -1 ? 'text-pink-300 dark:text-pink-300' : ($this->countMatchesExpected($t) === 2 ? 'text-red-600 dark:text-red-400 font-bold border-b border-b-red-600 dark:border-b-red-400' : 'text-gray-500')) }} whitespace-nowrap text-center text-lg font-semibold"
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
                            class="mt-1 max-w-3xl"
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
                            <div class="mt-2 h-60 overflow-y-auto rounded-md border border-gray-200 dark:border-gray-700">
                                @forelse ($this->attachmentResults as $result)
                                    <div
                                        class="cursor-pointer border border-gray-200 p-2 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                        @click="let el = $refs.attachmentSearch; $wire.addAttachment({{ $result->id }}).then(() => { el.focus(); el.select(); })"
                                        @keydown.enter="let el = $refs.attachmentSearch; $wire.addAttachment({{ $result->id }}).then(() => { el.focus(); el.select(); })"
                                        @keydown.down.prevent="$el.nextElementSibling?.focus()"
                                        @keydown.up.prevent="$el.previousElementSibling ? $el.previousElementSibling.focus() : $refs.attachmentSearch.focus()"
                                        tabindex="0"
                                    >
                                        <div class="flex justify-between">
                                            <div>{{ $result->name }} ({{ $result->label }})</div>
                                            <div class="text-sm text-gray-400 dark:text-gray-500">{{ $result->formatCode() }}</div>
                                        </div>
                                    </div>

                                @empty
                                    <div class="p-2 text-sm text-gray-500">No attachments found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Added attachments --}}
                <div class="mt-6">
                    <div>
                        <div class="grid grid-cols-1 md:hidden">
                            <select
                                aria-label="Select a tab"
                                wire:model.live="activeTab"
                                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pl-3 pr-8 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-white/5 dark:text-gray-100 dark:outline-white/10 dark:*:bg-gray-800 dark:focus:outline-indigo-500"
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
                                    class="no-scrollbar -mb-px flex max-w-3xl space-x-3 overflow-x-auto"
                                >
                                    @foreach ($attachmentTypes as $t)
                                        <a
                                            href="#"
                                            @click.prevent="$wire.setActiveTab('{{ $t }}').then(() => { let el = document.querySelector('[x-ref=attachmentSearch]'); if(el) { el.value = ''; el.focus(); el.dispatchEvent(new Event('input')); } })"
                                            class="{{ $activeTab === $t ? 'border-indigo-500 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-200 dark:hover:border-white/20 hover:text-gray-700 dark:hover:text-white' }} flex whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium"
                                        >
                                            {{ ucfirst($t) }}
                                            <span class="{{ $activeTab === $t ? 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400' : 'bg-gray-100 dark:bg-white/10 text-gray-900 dark:text-gray-300' }} ml-3 hidden rounded-full px-2.5 py-0.5 text-xs font-medium md:inline-block">{{ $this->attachmentCountDisplay($t) }}</span>
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 max-w-3xl">
                        @foreach ($this->weapon->attachments->where('type', $activeTab)->sortByDesc('updated_at') as $attachment)
                            <div class="mt-2 grid grid-cols-2 grid-rows-2 items-center gap-x-10 rounded-md border border-gray-200 p-3 md:grid-cols-4 md:grid-rows-1 dark:border-gray-700">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $attachment->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attachment->label }}</div>
                                </div>
                                <div class="col-span-2 col-start-1 row-start-2 flex items-center gap-2 md:col-start-2 md:row-start-1 md:pl-10">
                                    <div class="min-w-44 font-mono text-gray-500 dark:text-gray-400">
                                        {{ $attachment->formatCode() }}
                                    </div>

                                    {{-- Clone attachment with a new code --}}
                                    <div
                                        x-data="{ isOpen: false }"
                                        @attachment-cloned.window="isOpen = false".window="isOpen = false"
                                    >

                                        <div
                                            class="relative"
                                            @click.outside="isOpen = false"
                                        >
                                            <button
                                                class="inline-flex w-full cursor-pointer items-center justify-between gap-x-1 p-1 text-sm/6 font-semibold text-gray-900 dark:text-white"
                                                @click="isOpen === true ? isOpen = false : isOpen = true && $nextTick(() => { $refs.attachmentCodeInput.focus(); $refs.attachmentCodeInput.value = '{{ $this->weapon?->code_prefix . '-' }}' })"
                                                x-ref="attachmentCodeDropdownButton"
                                            >
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
                                                x-cloak
                                                :class="{ 'hidden': !isOpen }"
                                                x-anchor="$refs.attachmentCodeDropdownButton"
                                                class="data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in z-50 w-screen max-w-max rounded-lg bg-transparent px-4 transition [--anchor-gap:--spacing(5)] backdrop:bg-transparent open:flex"
                                            >
                                                <div class="rounded-lg bg-white text-sm/6 shadow-lg outline-1 outline-gray-900/5 lg:max-w-5xl dark:bg-gray-800 dark:shadow-none dark:-outline-offset-1 dark:outline-white/10">

                                                    <div class="mt-2 flex">
                                                        <div class="-mr-px grid grow grid-cols-1 focus-within:relative">
                                                            <input
                                                                x-ref="attachmentCodeInput"
                                                                wire:key="key_cloneCodeInput_{{ $attachment->id }}"
                                                                @keydown.enter.prevent="$wire.cloneAttachment({{ $attachment->id }}, $refs.attachmentCodeInput.value)"
                                                                id="cloneCodeInput_{{ $attachment->id }}"
                                                                type="text"
                                                                name="cloneCodeInput_{{ $attachment->id }}"
                                                                wire:model.live="cloneCodeInput"
                                                                placeholder="{{ $attachment->formatCode($this->weapon) }}"
                                                                class="col-start-1 row-start-1 block w-full rounded-l-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-gray-700 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                                                            />
                                                        </div>
                                                        <button
                                                            type="button"
                                                            wire:click="cloneAttachment({{ $attachment->id }}, $refs.attachmentCodeInput.value)"
                                                            class="flex shrink-0 items-center gap-x-1.5 rounded-r-md bg-white px-3 py-2 text-sm text-gray-800 outline-1 -outline-offset-1 outline-gray-300 hover:bg-gray-50 focus:relative focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 dark:bg-white/10 dark:text-white dark:outline-gray-700 dark:hover:bg-white/20 dark:focus:outline-indigo-500"
                                                        >
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke-width="1"
                                                                stroke="currentColor"
                                                                class="size-4"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75"
                                                                />
                                                            </svg>

                                                            Clone
                                                        </button>
                                                    </div>
                                                    <div
                                                        class="mt-2 flex items-center gap-2 p-2 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400"
                                                        wire:show="cloneCodeInput"
                                                    >
                                                        <div>{{ $this->attachmentsCodeIdExists ? '✅' : '⚠️' }}</div>
                                                        <div>{{ $cloneCodeInput }}</div>
                                                    </div>
                                                    <div
                                                        class="mt-2 flex items-center gap-2 p-2 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400"
                                                        wire:show="cloneError"
                                                    >
                                                        <div class="text-red-400">{{ $cloneError }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <button
                                    wire:click="removeAttachment({{ $attachment->id }})"
                                    wire:confirm="Are you sure you want to remove this attachment?"
                                    class="ml-4 cursor-pointer rounded-md bg-gray-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-red-600"
                                >Remove</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <livewire:new-weapon-modal />
</div>
