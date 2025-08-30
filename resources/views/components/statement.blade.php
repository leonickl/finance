<div className="flex flex-row justify-between gap-10 p-6 text-gray-900 dark:text-gray-200">
    <div className="flex-1">
        <x-sub-statement :statement="$assets()" />
    </div>
    <div className="flex-1">
        <x-sub-statement :statement="$liabilities()" />
    </div>
</div>