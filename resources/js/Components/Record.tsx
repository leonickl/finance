import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __, obj } from '@/lib/utils';
import { DataRecord, PageProps } from '@/types';
import { Head } from '@inertiajs/react';

export default function Record<T extends DataRecord>({
    title,
    record,
    map,
}: PageProps<{
    title: string;
    record: T;
    map: (arg0: T) => object;
}>) {
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
                    <div className="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid grid-cols-2 p-4">
                                {obj(map(record)).export(([key, value]) => (
                                    <>
                                        <div className="border-b border-gray-300 p-3 font-bold">
                                            {__(key)}
                                        </div>
                                        <div className="border-b border-gray-300 p-3">
                                            {value}
                                        </div>
                                    </>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
