import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { DataRecord, PageProps, Pagination } from '@/types';
import { Head } from '@inertiajs/react';
import PaginationLinks from './PaginationLinks';
import { SearchField } from './SearchField';

export default function Table<T extends DataRecord>({
    title,
    showRoute,
    list,
    header,
    row,
    auth,
    search = false,
}: PageProps<{
    title: string;
    showRoute: string;
    list: T[] | Pagination<T>;
    header: string[];
    row: (arg: T) => string[];
    search?: boolean;
}>) {
    function withLink(f: (arg: T) => string[]) {
        return (x: T) => [<a href={route(showRoute, x.id)}>{x.id}</a>, ...f(x)];
    }

    function cols(len: number) {
        if (len === 1) return `grid-cols-1`;
        if (len === 2) return `grid-cols-2`;
        if (len === 3) return `grid-cols-3`;
        if (len === 4) return `grid-cols-4`;
        if (len === 5) return `grid-cols-5`;
        if (len === 6) return `grid-cols-6`;
        if (len === 7) return `grid-cols-7`;

        return 'grid-cols-auto';
    }

    const paginated = !Array.isArray(list);

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {__(title)}
                </h2>
            }
        >
            <Head title={__(title)} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            {(search || paginated) && (
                                <div className="flex flex-row justify-evenly sm:flex-col sm:items-center">
                                    {search && <SearchField auth={auth} />}

                                    {paginated && (
                                        <div className="my-5">
                                            <PaginationLinks
                                                auth={auth}
                                                pagination={list}
                                            />
                                        </div>
                                    )}
                                </div>
                            )}

                            <div
                                className={`grid ${cols(header.length + 1)} p-4`}
                            >
                                <div className="border-b border-gray-300 p-3 text-center font-bold dark:border-gray-600"></div>

                                {header.map((key) => (
                                    <div className="border-b border-gray-300 p-3 text-center font-bold dark:border-gray-600">
                                        {__(key)}
                                    </div>
                                ))}

                                {(paginated ? list.data : list)
                                    .flatMap(withLink(row))
                                    .map((cell) => (
                                        <div className="border-b border-gray-300 p-3 text-center dark:border-gray-600">
                                            {cell}
                                        </div>
                                    ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
