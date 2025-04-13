import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { PageProps } from '@/types';
import { Head } from '@inertiajs/react';
import React, { useState } from 'react';

export default function Form({
    title,
    fields,
    save,
}: PageProps<{
    title: string;
    fields: string[];
    save: (input: { [key: string]: string }) => void;
}>) {
    const [input, setInput] = useState<{ [key: string]: string }>({});

    function changeHandler(field: string) {
        return (e: React.ChangeEvent<HTMLInputElement>) =>
            setInput((old) => ({ ...old, [field]: e.target.value }));
    }

    function handlesubmit(e: React.FormEvent) {
        e.preventDefault();

        save(input);
    }

    const classes =
        'h-12 rounded border border-gray-800 shadow dark:border-gray-500 dark:bg-gray-800 px-5 py-3';

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
                        <div className="flex flex-col items-center p-6 text-gray-900 dark:text-gray-100">
                            <form
                                className="flex w-max min-w-96 flex-col gap-5"
                                onSubmit={handlesubmit}
                            >
                                {fields.map((field) => (
                                    <input
                                        value={input[field] ?? ''}
                                        onChange={changeHandler(field)}
                                        className={classes}
                                    />
                                ))}

                                <input
                                    type="submit"
                                    value={__('save')}
                                    className={classes}
                                />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
