import AccountSelect from '@/Components/AccountSelect';
import ClaimSelect from '@/Components/ClaimSelect';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { classes } from '@/style';
import { PageProps } from '@/types';
import { Head, router } from '@inertiajs/react';
import { FormEvent, useMemo, useState } from 'react';
import { Account } from '../Accounts/Account';
import { Transaction } from './Transaction';

export default function Create({
    auth,
    accounts,
    claims,
}: PageProps<{ accounts: Account[]; claims: Transaction[] }>) {
    const [debitId, setDebitId] = useState<string>();
    const [creditId, setCreditId] = useState<string>();
    const [value, setValue] = useState<string>('');
    const [currency, setCurrency] = useState<string>('EUR');
    const [text, setText] = useState<string>('');
    const [date, setDate] = useState<string>(
        new Date().toISOString().split('T')[0],
    );
    const [claimId, setClaimId] = useState<number>();

    const credit = useMemo(
        () =>
            accounts.find(
                (account) => account.id === parseInt(creditId ?? '0'),
            ),
        [creditId],
    );

    function valueToFloat() {
        setValue(parseFloat(value.replace(',', '.')).toFixed(2));
    }

    function submitHandler(e: FormEvent<HTMLFormElement>) {
        e.preventDefault();

        valueToFloat();

        router.post(route('store-transaction'), {
            debitId,
            creditId,
            value,
            currency,
            text,
            date,
            claimId,
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
                                    <AccountSelect
                                        accounts={accounts}
                                        setValue={setDebitId}
                                    />

                                    <AccountSelect
                                        accounts={accounts}
                                        setValue={setCreditId}
                                    />

                                    <div className="flex justify-between">
                                        <input
                                            type="float"
                                            value={value}
                                            onChange={(e) =>
                                                setValue(e.target.value)
                                            }
                                            onBlur={valueToFloat}
                                            className={classes}
                                            placeholder="0.00"
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

                                    {credit?.type.toString() === 'CLAIM' && (
                                        <ClaimSelect
                                            claims={claims.filter(
                                                (claim) =>
                                                    claim.debit_id ===
                                                    credit.id,
                                            )}
                                            setValue={setClaimId}
                                            classes={classes}
                                        />
                                    )}

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
