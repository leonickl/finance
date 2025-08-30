<div className="ml-5 flex flex-col gap-6">
    <div className="flex flex-row justify-between font-extrabold underline">
        <div className="font-bold">{{ $statement->name }}</div>
        <div className="text-right">{{ $statement->balance }}</div>
    </div>

    <div className="grid grid-cols-2">
        @foreach($statement->accounts as $account)
            @if(! $account->balance()->isZero())
                <div className="font-bold text-gray-400">
                    <a href="/finance/accounts/{{ $account->id }}">{{ $account->name }}</a>
                </div>
                <div className="text-right text-gray-400">
                    {{ $account->balance() }}
                </div>
            @endif
        @endforeach
    </div>

    @foreach ($statement->children as $child)
        <x-sub-statement :statement="$child->statement()" />
    @endforeach
</div>