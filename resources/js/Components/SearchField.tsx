import { PageProps } from '@/types';
import { router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export function SearchField({}: PageProps<{}>) {
    const { url } = usePage();

    const urlObj = new URL(url, window.location.origin);

    const [searchTerm, setSearchTerm] = useState(
        urlObj.searchParams.get('search') ?? '',
    );

    const searchParams = Object.fromEntries([...urlObj.searchParams.entries()]);

    useEffect(() => {
        router.get(
            urlObj.pathname,
            {
                ...searchParams,
                search: searchTerm,
            },
            {
                preserveState: true, // Keeps the state of the page
                replace: true, // Avoids adding to the browser history stack
            },
        );
    }, [searchTerm]);

    function resetSearch() {
        router.get(
            urlObj.pathname,
            {
                ...searchParams,
                search: undefined,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    return (
        <div className="flex flex-row items-center gap-5">
            <input
                type="search"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="rounded shadow dark:bg-gray-800"
            />

            <button
                onClick={resetSearch}
                className="font-lg h-6 w-6 rounded-xl bg-red-500 font-bold text-gray-900 shadow hover:text-white dark:bg-red-700"
            >
                &times;
            </button>
        </div>
    );
}
