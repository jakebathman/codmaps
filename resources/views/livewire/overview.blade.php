<div class="bg-white p-6 sm:p-10 dark:bg-gray-900">

    <div class="mx-auto max-w-7xl">
        <h1 class="mb-4 text-2xl font-bold">Overview</h1>
        <div>
            <h2 class="mb-2 text-xl font-semibold">Weapons</h2>
            <table>
                <thead>
                    <tr>
                        <th class="pr-4 text-left">Type</th>
                        <th class="pr-4 text-left">Name</th>
                        <th class="pr-4 text-left">%</th>
                        @foreach ($attachmentTypes as $at)
                            <th class="whitespace-nowrap pr-4 text-center capitalize">{{ $at }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weapons as $weapon)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="pr-4 align-top">{{ $weapon->type }}</td>
                            <td class="pr-4 align-top">{{ $weapon->name }}</td>
                            <td>{{ number_format($weapon->attachmentProgress(), 0) }}%</td>
                            @foreach ($attachmentTypes as $at)
                                <td class="w-20 pr-4 text-center align-top">
                                    @php
                                        $count = $weapon->attachments->where('type', $at)->count();
                                        $expected = $weapon->expected_attachment_counts[$at] ?? 0;
                                    @endphp
                                    <span class="{{ $expected === 0 ? 'text-gray-500 dark:text-gray-400' : ($count < $expected ? 'text-pink-300 dark:text-pink-300' : ($count > $expected ? 'text-red-600 dark:text-red-500 font-semibold' : 'text-green-600 dark:text-green-400 font-bold')) }} whitespace-nowrap">
                                        {{ $count }} / {{ $expected }}
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-10">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold text-gray-900 dark:text-white">Attachments ({{ $this->attachments->count() }})</h1>

                        <div class="flex w-full justify-between">
                            @foreach ($this->typesWithCounts as $type => $counts)
                                <button
                                    wire:click="filterAttachments('{{ $type }}')"
                                    class="group/item focus:outline-hidden {{ $filterAttachmentType === $type ? 'bg-gray-200 dark:bg-white/10 border-2 border-gray-300 dark:border-white/15' : 'hover:bg-gray-100 dark:hover:bg-white/5' }} flex w-full cursor-pointer items-center rounded-md border-2 border-transparent bg-none px-4 py-2 text-sm text-gray-700 focus:bg-gray-100 focus:text-gray-900 dark:text-gray-300 dark:focus:bg-white/5 dark:focus:text-white"
                                >
                                    <div class="flex w-full flex-col items-center">
                                        <div class="{{ $filterAttachmentType === $type ? 'font-semibold' : '' }} block truncate font-normal capitalize">
                                            {{ $type }}
                                        </div>
                                        <div class="flex items-center">
                                            <div
                                                aria-hidden="true"
                                                class="{{ $counts['percent_complete'] == 100 ? 'bg-green-400' : ($counts['percent_complete'] > 60 ? 'bg-blue-400' : ($counts['percent_complete'] > 40 ? 'bg-yellow-400' : ($counts['percent_complete'] > 0 ? 'bg-orange-400' : 'bg-red-400'))) }} inline-block size-2 shrink-0 rounded-full border border-transparent forced-colors:bg-[Highlight]"
                                            ></div>
                                            <div class="ml-2 text-base">{{ $counts['percent_complete'] }}%</div>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $counts['total'] }}</div>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                    </div>
                </div>
                <div class="mt-8 flow-root">
                    <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle">
                            <table class="min-w-full border-separate border-spacing-0">
                                <thead>
                                    <tr>
                                        <th
                                            scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white/75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur-sm backdrop-filter sm:pl-6 lg:pl-8 dark:border-white/15 dark:bg-gray-900/75 dark:text-white"
                                        >
                                            Type</th>
                                        <th
                                            scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white/75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur-sm backdrop-filter dark:border-white/15 dark:bg-gray-900/75 dark:text-white"
                                        >
                                            Name</th>
                                        <th
                                            scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white/75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur-sm backdrop-filter dark:border-white/15 dark:bg-gray-900/75 dark:text-white"
                                        >
                                            Label</th>
                                        <th
                                            scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white/75 px-3 py-3.5 text-left text-sm font-semibold text-gray-900 backdrop-blur-sm backdrop-filter dark:border-white/15 dark:bg-gray-900/75 dark:text-white"
                                        >
                                            Weapons</th>
                                        <th
                                            scope="col"
                                            class="sticky top-0 z-10 border-b border-gray-300 bg-white/75 py-3.5 pl-3 pr-4 text-left backdrop-blur-sm backdrop-filter dark:border-white/15 dark:bg-gray-900/75"
                                        >
                                            Code
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($this->attachments as $attachment)
                                        <tr>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8 dark:border-white/10 dark:text-white">
                                                {{ $attachment->type }}</td>
                                            <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 dark:border-white/10 dark:text-gray-400">
                                                {{ $attachment->name }}</td>
                                            <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 dark:border-white/10 dark:text-gray-400">
                                                {{ $attachment->label }}</td>
                                            <td class="whitespace-nowrap border-b border-gray-200 px-3 py-4 text-sm text-gray-500 dark:border-white/10 dark:text-gray-400">
                                                {{ $attachment->weapons?->count() }}</td>
                                            <td class="whitespace-nowrap border-b border-gray-200 py-4 pl-3 pr-4 text-sm font-medium dark:border-white/10">
                                                {{ $attachment->code_base34 }}
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
</div>
