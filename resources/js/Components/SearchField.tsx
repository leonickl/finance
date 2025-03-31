import { PageProps } from '@/types';
import { router } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export function SearchField({}: PageProps<{}>) {
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        router.get(
            window.location.pathname,
            {
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
            window.location.pathname,
            {},
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
                className="rounded shadow dark:bg-gray-700"
            />

            <button
                onClick={resetSearch}
                className="font-lg h-6 w-6 rounded-xl dark:bg-red-700 bg-red-500 font-bold text-gray-900 shadow hover:text-white"
            >
                &times;
            </button>
        </div>
    );
}
