<div style="display:flex; flex-direction:row; justify-content:space-between; gap:2.5rem; padding:1.5rem;">
    <div style="flex:1;">
        <x-sub-statement :statement="$assets()" />
    </div>
    <div style="flex:1;">
        <x-sub-statement :statement="$liabilities()" />
    </div>
</div>