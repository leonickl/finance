import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { classes } from '@/style';
import { PageProps } from '@/types';
import { Head, router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';
import { AccountType } from '../Accounts/Account';
export default function Create({
    accountTypes,
}: PageProps<{ accountTypes: AccountType[] }>) {
    console.log(accountTypes);

    const [name, setName] = useState<string>('');
    const [type, setType] = useState<string>('');
    const [recurring, setRecurring] = useState<boolean>();

    function submitHandler(e: FormEvent<HTMLFormElement>) {
        e.preventDefault();

        router.post(route('account.store'), { name, type, recurring });
    }

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
                                    <label>
                                        <input
                                            type="checkbox"
                                            checked={recurring}
                                            onChange={(e) =>
                                                setRecurring(e.target.checked)
                                            }
                                        />{' '}
                                        recurring
                                    </label>

                                    <input
                                        type="text"
                                        value={name}
                                        onChange={(e) =>
                                            setName(e.target.value)
                                        }
                                        className={classes}
                                        placeholder={__('text')}
                                    />

                                    <select
                                        name="text"
                                        className={classes}
                                        value={type}
                                        onChange={(e) =>
                                            setType(e.target.value)
                                        }
                                    >
                                        {Object.entries(accountTypes).map(
                                            ([key, value]) => (
                                                <option value={key} key={key}>
                                                    {value}
                                                </option>
                                            ),
                                        )}
                                    </select>

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
