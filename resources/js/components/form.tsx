import AppLayout from '@/layouts/app-layout';
import { __ } from '@/lib/utils';
import { Head } from '@inertiajs/react';
import React, { useState } from 'react';

export type InputType = string | File | null

export default function Form({
    title,
    fields,
    save,
    children,
}: {
    title: string;
    fields: { name: string; type?: string }[];
    save: (input: { [key: string]: InputType }) => void;
    children?: React.ReactNode;
}) {
    const [input, setInput] = useState<{ [key: string]: InputType }>({});

    function changeHandler(field: string, type?: string) {
        return (e: React.ChangeEvent<HTMLInputElement>) => {
            const value =
                type === 'file'
                    ? (e.target.files?.[0] ?? null)
                    : e.target.value;

            setInput((old) => ({ ...old, [field]: value }));
        };
    }

    function handlesubmit(e: React.FormEvent) {
        e.preventDefault();
        save(input);
    }

    const classes =
        'h-12 rounded border border-gray-800 shadow dark:border-gray-500 dark:bg-gray-800 px-5 py-3';

    return (
        <AppLayout breadcrumbs={[]}>
            <Head title={__(title)} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="flex flex-col items-center p-6 text-gray-900 dark:text-gray-100">
                            {children}

                            <form
                                className="flex w-max min-w-96 flex-col gap-5"
                                onSubmit={handlesubmit}
                            >
                                {fields.map((field) => (
                                    <input
                                        key={field.name}
                                        type={field.type ?? 'text'}
                                        value={
                                            field.type === 'file'
                                                ? undefined
                                                : (input[field.name] ?? '')
                                        }
                                        onChange={changeHandler(
                                            field.name,
                                            field.type,
                                        )}
                                        className={classes}
                                        accept={
                                            field.name === 'file'
                                                ? '.csv,text/plain'
                                                : undefined
                                        }
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
        </AppLayout>
    );
}
