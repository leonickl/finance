import AccountSelect from '@/components/select-account';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { classes } from '@/style';
import { PageProps } from '@/types';
import { Head, router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';
import { Account } from '../Accounts/Account';
export default function Create({
    accounts,
}: PageProps<{ accounts: Account[] }>) {
    const [valueIsPositive, setValueIsPositive] = useState<boolean>(false);
    const [textContains, setTextContains] = useState<string>('');
    const [accountProposal, setAccountProposal] = useState<string>();
    const [textProposal, setTextProposal] = useState<string>('');

    function submitHandler(e: FormEvent<HTMLFormElement>) {
        e.preventDefault();

        router.post(route('proposals.store'), {
            value_is_positive: valueIsPositive,
            text_contains: textContains,
            account_proposal: accountProposal,
            text_proposal: textProposal,
        });
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
                                            checked={valueIsPositive}
                                            onChange={(e) =>
                                                setValueIsPositive(
                                                    e.target.checked,
                                                )
                                            }
                                        />{' '}
                                        positive value
                                    </label>
                                    <input
                                        type="text"
                                        value={textContains}
                                        onChange={(e) =>
                                            setTextContains(e.target.value)
                                        }
                                        className={classes}
                                        placeholder={__('text_contains')}
                                    />
                                    <AccountSelect
                                        accounts={accounts}
                                        setValue={setAccountProposal}
                                    />
                                    <input
                                        type="text"
                                        value={textProposal}
                                        onChange={(e) =>
                                            setTextProposal(e.target.value)
                                        }
                                        className={classes}
                                        placeholder={__('text_proposal')}
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
