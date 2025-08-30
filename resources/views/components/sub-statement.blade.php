<div style="margin-left:1.25rem; display:flex; flex-direction:column; gap:1.5rem;">
    <div style="display:flex; flex-direction:row; justify-content:space-between; font-weight:800; text-decoration:underline;">
        <div style="font-weight:700;">{{ $statement->name }}</div>
        <div style="text-align:right;">{{ $statement->balance }}</div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0.5rem;">
        @foreach($statement->accounts as $account)
            @if(! $account->balance()->isZero())
                <div style="font-weight:700; color:#9ca3af;">
                    <a href={{ route('filament.finance.resources.accounts.index', $account) }}>{{ $account->name }}</a>
                </div>
                <div style="text-align:right; color:#9ca3af;">
                    {{ $account->balance() }}
                </div>
            @endif
        @endforeach
    </div>

    @foreach ($statement->children as $child)
        <x-sub-statement :statement="$child->statement()" />
    @endforeach
</div>