import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __, obj } from '@/lib/utils';
import { DataRecord, PageProps } from '@/types';
import { Head } from '@inertiajs/react';
import React, { useMemo, useState } from 'react';

export default function Record<T extends DataRecord>({
    title,
    record,
    map,
    editable = (f: any) => ({}),
    children,
}: PageProps<{
    title: string;
    record: T;
    map: (arg0: T) => { [key: string]: React.ReactNode };
    editable?: (
        arg0: T,
        arg1: (arg0: string) => void,
    ) => { [key: string]: React.ReactNode };
    children?: React.ReactNode;
}>) {
    const edit = useMemo(() => editable(record, hide), [record]);

    const [status, setStatus] = useState<{ [key: string]: boolean }>(
        obj(edit).map(([key]) => [key, false]),
    );

    function toggleField(key: string) {
        return () =>
            setStatus((old) => ({
                ...old,
                [key]: !old[key],
            }));
    }

    function hide(key: string) {
        setStatus((old) => ({
            ...old,
            [key]: false,
        }));
    }

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {__(title) + ' ' + record.id}
                </h2>
            }
        >
            <Head title={__(title) + ' ' + record.id} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid grid-cols-2 p-4">
                                {obj<string, React.ReactNode>(
                                    map(record),
                                ).export(([key, value]) => (
                                    <>
                                        <div className="border-b border-gray-300 p-3 font-bold">
                                            {__(key)}
                                        </div>
                                        <div className="flex flex-row justify-between border-b border-gray-300 p-3">
                                            {key in edit ? (
                                                <>
                                                    {status[key]
                                                        ? edit[key]
                                                        : value}

                                                    <button
                                                        onClick={toggleField(
                                                            key,
                                                        )}
                                                    >
                                                        {status[key]
                                                            ? __('show')
                                                            : __('edit')}
                                                    </button>
                                                </>
                                            ) : (
                                                value
                                            )}
                                        </div>
                                    </>
                                ))}
                            </div>

                            {children}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
