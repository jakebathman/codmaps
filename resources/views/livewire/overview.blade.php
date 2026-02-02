<div class="bg-white dark:bg-gray-900 p-6 sm:p-10">

    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Overview</h1>
        <div>
            <h2 class="text-xl font-semibold mb-2">Weapons</h2>
            <table>
                <thead>
                    <tr>
                        <th class="text-left pr-4">Type</th>
                        <th class="text-left pr-4">Name</th>
                        <th class="text-left pr-4">%</th>
                        @foreach ($attachmentTypes as $at)
                            <th class="pr-4 capitalize whitespace-nowrap text-center">{{ $at }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weapons as $weapon)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="pr-4 align-top">{{ $weapon->type }}</td>
                            <td class="pr-4 align-top">{{ $weapon->name }}</td>
                            <td>{{ number_format($weapon->attachmentProgress(),0) }}%</td>
                            @foreach ($attachmentTypes as $at)
                                <td class="pr-4 align-top w-20 text-center">
                                    @php
                                        $count = $weapon->attachments->where('type', $at)->count();
                                        $expected = $weapon->expected_attachment_counts[$at] ?? 0;
                                    @endphp
                                    <span class="whitespace-nowrap {{ $expected === 0 ? 'text-gray-500 dark:text-gray-400' : ($count < $expected ? 'text-pink-300 dark:text-pink-300' : ($count > $expected ? 'text-red-600 dark:text-red-500 font-semibold' : 'text-green-600 dark:text-green-400 font-bold')) }}">
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
