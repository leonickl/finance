import { __, money } from '@/lib/utils';
import { Transaction } from '@/Pages/Transactions/Transaction';
import { useEffect, useRef, useState } from 'react';

export default function ClaimSelect({
    claims,
    setValue,
    classes,
}: {
    claims: Transaction[];
    setValue: (arg0: number) => void;
    classes?: string;
}) {
    const [search, setSearch] = useState(''); // Search input
    const [isOpen, setIsOpen] = useState(false); // Dropdown visibility
    const ref = useRef<HTMLDivElement>(null); // Reference for clicking outside

    // Filtered list based on search input
    const filteredClaims = claims.filter((claim) =>
        claimToString(claim).toLowerCase().includes(search.toLowerCase()),
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

    function claimToString(claim: Transaction) {
        return `${claim.id} - ${claim.text} - ${money(claim.money)}`;
    }

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
                    {filteredClaims.length > 0 ? (
                        filteredClaims.map((claim) => (
                            <li
                                key={claim.id}
                                className="h-12 cursor-pointer rounded px-5 py-3"
                                onClick={() => {
                                    setValue(claim.id);
                                    setSearch(claimToString(claim));
                                    setIsOpen(false);
                                }}
                            >
                                {claimToString(claim)}
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
