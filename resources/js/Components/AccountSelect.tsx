import { __ } from '@/lib/utils';
import { Account } from '@/Pages/Accounts/Account';
import { PageProps } from '@/types';
import { useEffect, useRef, useState } from 'react';

export default function AccountSelect({
    accounts,
    setValue,
    classes,
}: PageProps<{
    accounts: Account[];
    setValue: (arg0: string) => void;
    classes?: string;
}>) {
    const [search, setSearch] = useState(''); // Search input
    const [isOpen, setIsOpen] = useState(false); // Dropdown visibility
    const ref = useRef<HTMLDivElement>(null); // Reference for clicking outside

    // Filtered list based on search input
    const filteredAccounts = accounts.filter((account) =>
        `${account.type} - ${account.name}`
            .toLowerCase()
            .includes(search.toLowerCase()),
    );

    // Close dropdown when clicking outside
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (ref.current && !ref.current.contains(event.target as Node)) {
                setIsOpen(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);

        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    return (
        <div className="relative w-96" ref={ref}>
            {/* Input Field */}
            <input
                type="text"
                value={search}
                onChange={(e) => {
                    setSearch(e.target.value);
                    setIsOpen(true);
                }}
                onClick={() => setIsOpen(true)}
                placeholder={__('search')}
                className={`${classes} w-96`}
            />

            {/* Dropdown */}
            {isOpen && (
                <ul className="rounded border border-gray-200 dark:bg-gray-800">
                    {filteredAccounts.length > 0 ? (
                        filteredAccounts.map((account) => (
                            <li
                                key={account.id}
                                className="h-12 cursor-pointer rounded px-5 py-3"
                                onClick={() => {
                                    setValue(account.id.toString());
                                    setSearch(
                                        `${account.type} - ${account.name}`,
                                    );
                                    setIsOpen(false);
                                }}
                            >
                                {`${__(account.type.toString())} - ${account.name}`}
                            </li>
                        ))
                    ) : (
                        <li className="p-2 text-gray-500">No results found</li>
                    )}
                </ul>
            )}
        </div>
    );
}
