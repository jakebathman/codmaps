<div class="bg-white dark:bg-gray-900 p-6 sm:p-10">

    <div class="max-w-3xl mx-auto">
        <div class="p-8 border-b border-gray-200 flex justify-between">
            <div>
                <h2 class="text-3xl font-medium text-gray-900">Weapons</h2>
            </div>

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
                                wire:click="setType('{{ $id }}')"
                                class="group/item w-full flex items-center px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 focus:outline-hidden dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                            >
                                <div class="flex items-center w-full">
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

        {{-- Attachments --}}
        <div>
            {{-- Counts --}}
            <div>
                <table>
                    <thead>
                        <tr class="text-left">
                            <th class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white">Attachment</th>
                            <th class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white">Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($this->weapon->attachments->groupBy('type') as $type => $attachments)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attachments->count() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Add --}}
            <div>
                <div>
                    <label
                        for="attachment"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >Add Attachment</label>
                    <div class="mt-1">
                        <input
                            type="text"
                            placeholder="Search attachments..."
                            wire:model.live="attachmentSearch"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 dark:bg-white/5 dark:text-white dark:outline-white/10 dark:placeholder:text-gray-500 dark:focus:outline-indigo-500"
                        />

                        {{-- Results --}}
                        <div class="max-h-60 overflow-y-auto mt-2 border border-gray-200 rounded-md dark:border-gray-700">
                            @foreach ($this->attachmentResults as $result)
                                <div
                                    class="mt-2 p-2 border border-gray-200 rounded-md cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800"
                                    wire:click="addAttachment({{ $result->id }})"
                                >
                                    {{ $result->type }}: {{ $result->name }} ({{ $result->label }})
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 flex justify-between border-t border-gray-200 dark:border-gray-700">
                <button
                    wire:click="skip"
                    tabindex="-1"
                    type="button"
                    class="cursor-pointer rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 hover:bg-gray-50 dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20"
                >Skip</button>

            </div>
        </div>
    </div>
