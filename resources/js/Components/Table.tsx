import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { DataRecord, Pagination } from '@/types';
import { Head, Link } from '@inertiajs/react';
import React, { useState } from 'react';
import PaginationLinks from './PaginationLinks';
import { SearchField } from './SearchField';

type RecordToCells<T> = (arg: T) => React.ReactNode[];

export default function Table<T extends DataRecord>({
    title,
    showRoute,
    createRoute = undefined,
    list,
    header,
    row,
    sub = undefined,
    search = false,
    cols,
}: {
    title: string;
    showRoute: string;
    createRoute?: string | undefined;
    list: T[] | Pagination<T>;
    header: string[];
    row: RecordToCells<T>;
    sub?: (arg0: T, arg1: (arg: React.ReactNode) => void) => React.ReactNode;
    search?: boolean;
    cols?: string;
}) {
    const paginated = !Array.isArray(list);

    const records = paginated ? list.data : list;

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

                                    const cells = [
                                        <a href={route(showRoute, record.id)}>
                                            {record.id}
                                        </a>,
                                        ...row(record),
                                    ].filter((x) => x);

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
                                                <div className="col-span-4 flex flex-col gap-5 px-20 pb-10 pt-5">
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
        </AuthenticatedLayout>
    );
}
