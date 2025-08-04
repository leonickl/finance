import { Pagination } from '@/types/data';
import { Link } from '@inertiajs/react';

export default function PaginationLinks<T>({
    pagination,
}: {
    pagination: Pagination<T>;
}) {
    return (
        <nav
            role="navigation"
            aria-label="Pagination Navigation"
            className="flex items-center justify-between"
        >
            {/* Mobile View */}
            <div className="flex flex-1 justify-between sm:hidden">
                {pagination.prev_page_url ? (
                    <Link
                        href={pagination.prev_page_url}
                        className="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"
                    >
                        Previous
                    </Link>
                ) : (
                    <span className="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-600">
                        Previous
                    </span>
                )}

                {pagination.next_page_url ? (
                    <Link
                        href={pagination.next_page_url}
                        className="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 ring-gray-300 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300"
                    >
                        Next
                    </Link>
                ) : (
                    <span className="relative ml-3 inline-flex cursor-default items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-600">
                        Next
                    </span>
                )}
            </div>

            {/* Desktop View */}
            <div className="hidden flex-col gap-5 sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p className="text-sm leading-5 text-gray-700 dark:text-gray-400">
                        Showing {pagination.from} to {pagination.to} of{' '}
                        {pagination.total} results
                    </p>
                </div>

                <div>
                    <span className="relative z-0 inline-flex rounded-md shadow-sm rtl:flex-row-reverse">
                        {/* Previous Page Link */}
                        {pagination.prev_page_url ? (
                            <Link
                                href={pagination.prev_page_url}
                                className="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm leading-5 font-medium text-gray-500 ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-200 hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-600 dark:focus:border-blue-800 dark:active:bg-gray-700"
                                aria-label="Previous"
                            >
                                <svg
                                    className="h-5 w-5"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                            </Link>
                        ) : (
                            <span
                                aria-disabled="true"
                                className="relative inline-flex cursor-default items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm leading-5 font-medium text-gray-500 hover:bg-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-600"
                                aria-hidden="true"
                            >
                                <svg
                                    className="h-5 w-5"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                            </span>
                        )}

                        {/* Pagination Links */}
                        {pagination.links
                            .filter(
                                (link) =>
                                    !link.label.includes('Next') &&
                                    !link.label.includes('Previous'),
                            )
                            .map((link) =>
                                !link.active && link.url ? (
                                    <Link
                                        key={link.label}
                                        href={link.url}
                                        className="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-200 hover:text-gray-500 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-gray-300 dark:focus:border-blue-800 dark:active:bg-gray-700"
                                        aria-label={`Go to page ${link.label}`}
                                    >
                                        {link.label}
                                    </Link>
                                ) : (
                                    <span
                                        key={link.label}
                                        aria-current="page"
                                        className="relative inline-flex cursor-default items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-500 hover:bg-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-600"
                                    >
                                        {link.label}
                                    </span>
                                ),
                            )}

                        {/* Next Page Link */}
                        {pagination.next_page_url ? (
                            <Link
                                href={pagination.next_page_url}
                                className="relative -ml-px inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm leading-5 font-medium text-gray-500 ring-gray-300 transition duration-150 ease-in-out hover:bg-gray-200 hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:ring focus:outline-none active:bg-gray-100 active:text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-600 dark:focus:border-blue-800 dark:active:bg-gray-700"
                                aria-label="Next"
                            >
                                <svg
                                    className="h-5 w-5"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                            </Link>
                        ) : (
                            <span
                                aria-disabled="true"
                                aria-label="Next"
                                className="relative -ml-px inline-flex cursor-default items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm leading-5 font-medium text-gray-500 hover:bg-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-600"
                                aria-hidden="true"
                            >
                                <svg
                                    className="h-5 w-5"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                            </span>
                        )}
                    </span>
                </div>
            </div>
        </nav>
    );
}
