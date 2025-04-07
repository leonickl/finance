import AccountSelect from '@/Components/AccountSelect';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { PageProps } from '@/types';
import { Head, router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';
import { Account } from '../Accounts/Account';

export default function Create({
    auth,
    accounts,
}: PageProps<{ accounts: Account[] }>) {
    const [debitId, setDebitId] = useState<string>();
    const [creditId, setCreditId] = useState<string>();
    const [value, setValue] = useState<number>(0.0);
    const [currency, setCurrency] = useState<string>('EUR');
    const [text, setText] = useState<string>('');
    const [date, setDate] = useState<string>(
        new Date().toISOString().split('T')[0],
    );

    function submitHandler(e: FormEvent<HTMLFormElement>) {
        e.preventDefault();

        console.log(fetch);

        router.post(route('store-transaction'), {
            debitId,
            creditId,
            value,
            currency,
            text,
            date,
        });

        // fetch(route()
        //     .then(console.log)
        //     .catch(console.error);
    }

    const classes =
        'h-12 rounded border border-gray-800 shadow dark:border-gray-500 dark:bg-gray-800 px-5 py-3';

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {__('create_transaction')}
                </h2>
            }
        >
            <Head title={__('create_transaction')} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid items-center justify-center p-4">
                                <form
                                    onSubmit={submitHandler}
                                    className="flex w-96 flex-col gap-5"
                                >
                                    <AccountSelect
                                        auth={auth}
                                        accounts={accounts}
                                        setValue={setDebitId}
                                        classes={classes}
                                    />

                                    <AccountSelect
                                        auth={auth}
                                        accounts={accounts}
                                        setValue={setCreditId}
                                        classes={classes}
                                    />

                                    <div className="flex justify-between">
                                        <input
                                            type="float"
                                            value={value}
                                            onChange={(e) =>
                                                setValue(
                                                    parseFloat(
                                                        e.target.value.replace(
                                                            ',',
                                                            '.',
                                                        ),
                                                    ),
                                                )
                                            }
                                            className={classes}
                                        />

                                        <input
                                            type="text"
                                            value={currency}
                                            onChange={(e) =>
                                                setCurrency(e.target.value)
                                            }
                                            className={`${classes} w-20`}
                                        />
                                    </div>

                                    <input
                                        type="text"
                                        value={text}
                                        onChange={(e) =>
                                            setText(e.target.value)
                                        }
                                        className={classes}
                                    />

                                    <input
                                        type="date"
                                        value={date}
                                        onChange={(e) =>
                                            setDate(e.target.value)
                                        }
                                        className={classes}
                                    />

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
            </div>
        </AuthenticatedLayout>
    );
}
