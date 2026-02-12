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
    </div>
</div>
