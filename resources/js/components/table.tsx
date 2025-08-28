import AppLayout from '@/layouts/app-layout';
import { __ } from '@/lib/utils';
import { DataRecord, Pagination } from '@/types/data';
import { Head, Link } from '@inertiajs/react';
import React, { useState } from 'react';
import PaginationLinks from './pagination-links';
import { SearchField } from './search-field';

type RecordToCells<T> = (arg: T) => React.ReactNode[];

export default function Table<T extends DataRecord>({
    title,
    showRoute = undefined,
    createRoute = undefined,
    list,
    header,
    row,
    sub = undefined,
    search = false,
    cols,
    injectBefore = undefined,
}: {
    title: string;
    showRoute?: string | undefined;
    createRoute?: string | undefined;
    list: T[] | Pagination<T>;
    header: string[];
    row: RecordToCells<T>;
    sub?: (arg0: T, arg1: (arg: React.ReactNode) => void) => React.ReactNode;
    search?: boolean;
    cols?: string;
    injectBefore?: React.ReactNode;
}) {
    const paginated = !Array.isArray(list);

    const records: T[] = paginated ? list.data : list;

    const [status, setStatus] = useState(
        Object.fromEntries(records.map((record) => [record.id, undefined])),
    );

    function colsClass(len: number) {
        if (cols) return cols;

        if (len === 1) return `grid-cols-1`;
        if (len === 2) return `grid-cols-2`;
        if (len === 3) return `grid-cols-3`;
        if (len === 4) return `grid-cols-4`;
        if (len === 5) return `grid-cols-5`;
        if (len === 6) return `grid-cols-6`;
        if (len === 7) return `grid-cols-7`;

        return 'grid-cols-auto';
    }

    return (
        <AppLayout breadcrumbs={[]}>
            <Head title={__(title)} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            {injectBefore}

                            {createRoute && (
                                <Link
                                    href={route(createRoute)}
                                    className="rounded border px-5 py-3 shadow"
                                >
                                    {__('new')}
                                </Link>
                            )}

                            {(search || paginated) && (
                                <div className="flex flex-row justify-evenly sm:flex-col sm:items-center">
                                    {search && <SearchField />}

                                    {paginated && (
                                        <div className="my-5">
                                            <PaginationLinks
                                                pagination={list}
                                            />
                                        </div>
                                    )}
                                </div>
                            )}

                            <div
                                className={`grid ${colsClass(header.length + 1)} p-4`}
                            >
                                <div className="border-b border-gray-300 p-3 text-center font-bold dark:border-gray-600"></div>

                                {header.map((key) => (
                                    <div
                                        key={key}
                                        className="border-b border-gray-300 p-3 text-center font-bold dark:border-gray-600"
                                    >
                                        {__(key)}
                                    </div>
                                ))}

                                {records.map((record: T) => {
                                    function setSubRow(value: React.ReactNode) {
                                        setStatus((old) => ({
                                            ...old,
                                            [record.id]: value,
                                        }));
                                    }

                                    const id = showRoute ? (
                                        <a href={route(showRoute, record.id)}>
                                            {record.id}
                                        </a>
                                    ) : (
                                        <span>{record.id}</span>
                                    );

                                    const cells = [id, ...row(record)];

                                    return (
                                        <>
                                            {cells.map((cell, index) => (
                                                <div
                                                    key={index}
                                                    className="border-t border-gray-300 p-3 text-center dark:border-gray-600"
                                                >
                                                    {cell}
                                                </div>
                                            ))}
                                            {sub && (
                                                <div className="col-span-4 flex flex-col gap-5 px-20 pt-5 pb-10">
                                                    {status[record.id]
                                                        ? status[record.id]
                                                        : sub(
                                                              record,
                                                              setSubRow,
                                                          )}
                                                </div>
                                            )}
                                        </>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
