<x-filament-panels::page>
    @if (empty($streaks))
        <div class="text-sm text-gray-500 dark:text-gray-400">
            No streaks found.
        </div>
    @endif

    @foreach ($streaks as $entry)
        <x-filament::section>
            <x-slot name="heading">
                {{ $entry['name'] }}
            </x-slot>

            <x-slot name="description">
                {{ $entry['value'] }} {{ $entry['currency'] }}
                &middot;
                Day {{ $entry['day'] }}
                &middot;
                {{ $entry['debit_name'] }} &rarr; {{ $entry['credit_name'] }}
                &middot;
                {{ $entry['first'] }}
                @if ($entry['last'])
                    &ndash; {{ $entry['last'] }}
                @else
                    &ndash; ongoing
                @endif
            </x-slot>

            <div class="overflow-x-auto">
                <table class="w-full text-xs table-fixed border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-2 py-1 text-left font-medium text-gray-500 dark:text-gray-400">Year</th>
                            @foreach (['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                <th class="w-10 px-2 py-1 text-center font-medium text-gray-500 dark:text-gray-400 border-l border-gray-200 dark:border-gray-700">{{ $m }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entry['years'] as $year => $months)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-2 py-1 font-medium">
                                    {{ $year }}
                                </td>
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $found = collect($months)->firstWhere('month', $m);
                                    @endphp
                                    <td class="w-10 px-2 py-1 text-center border-l border-gray-200 dark:border-gray-700">
                                        <div class="flex flex-col items-center">
                                            @if ($found)
                                                @if ($found['count'] > 1)
                                                    <span class="text-amber-600 dark:text-amber-400">
                                                        <x-heroicon-s-check-circle style="height: 30px; width: 30px" />
                                                    </span>
                                                    @foreach ($found['transaction_ids'] as $tid)
                                                        <a
                                                            href="{{ route('filament.finance.resources.transactions.view', $tid) }}"
                                                            class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 underline leading-tight"
                                                        >#{{ $tid }}</a>
                                                    @endforeach
                                                @elseif ($found['count'] === 1)
                                                    <a
                                                        href="{{ route('filament.finance.resources.transactions.view', $found['transaction_ids'][0]) }}"
                                                        class="text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300"
                                                    >
                                                        <x-heroicon-s-check-circle style="height: 30px; width: 30px" />
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-red-400 dark:text-red-500">
                                                    <x-heroicon-s-x-circle style="height: 30px; width: 30px" />
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endforeach
</x-filament-panels::page>
