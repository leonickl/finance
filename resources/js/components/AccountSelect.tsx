import { __ } from '@/lib/utils';
import { Account } from '@/Pages/Accounts/Account';
import { classes } from '@/style';
import { useEffect, useRef, useState } from 'react';

export default function AccountSelect({
    accounts,
    initialValue = undefined,
    setValue,
}: {
    accounts: Account[];
    initialValue?: string;
    setValue: (arg0: string) => void;
}) {
    const [search, setSearch] = useState(''); // Search input
    const [isOpen, setIsOpen] = useState(false); // Dropdown visibility
    const [selectedValue, setSelectedValue] = useState<string | undefined>(
        initialValue,
    ); // Selected account ID
    const ref = useRef<HTMLDivElement>(null); // Reference for clicking outside

    // Set search text based on initialValue
    useEffect(() => {
        if (initialValue) {
            const initialAccount = accounts.find(
                (acc) => acc.id.toString() === initialValue,
            );
            if (initialAccount) {
                setSearch(`${initialAccount.type} - ${initialAccount.name}`);
            }
        }
    }, [initialValue, accounts]);

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

    // Filtered list based on search input
    const filteredAccounts = accounts.filter((account) =>
        `${account.type} - ${account.name}`
            .toLowerCase()
            .includes(search.toLowerCase()),
    );

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
                                    const value = account.id.toString();
                                    setSelectedValue(value);
                                    setValue(value);
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
