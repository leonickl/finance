import Record from '@/components/record';
import RecordLink from '@/components/record-link';
import ClaimSelect from '@/components/select-claim';
import { __, date, money } from '@/lib/utils';
import { classes } from '@/style';

import { router } from '@inertiajs/react';
import React from 'react';
import { Transaction } from './Transaction';

export default function Show({
    transaction,
    repayments,
    claims,
}: PageProps<{
    transaction: Transaction;
    repayments: Transaction[];
    claims: Transaction[];
}>) {
    return (
        <Record
            title="transaction"
            record={transaction}
            map={(record) => ({
                id: record.id,
                value: money(record.value, record.currency),
                text: record.text,
                date: date(record.timestamp),
                debit: (
                    <RecordLink
                        dest="account"
                        label={record.debit.name}
                        id={record.debit.id}
                    />
                ),
                credit: (
                    <RecordLink
                        dest="account"
                        label={record.credit.name}
                        id={record.credit.id}
                    />
                ),
                person: record.person?.name,
                claim: record.claim && (
                    <RecordLink
                        dest="transaction"
                        label={record.claim.text}
                        id={record.claim.id}
                    />
                ),
                repaid: money(record.repaid),
                rest: money(record.rest),
            })}
            editable={(record, hide) => ({
                claim: (
                    <ClaimSelect
                        claims={claims}
                        setValue={(claimId: number) => {
                            router.patch(
                                route('transaction.patch', transaction.id),
                                {
                                    claimId,
                                },
                            );
                            hide('claim');
                        }}
                        classes={classes}
                    />
                ),
            })}
        >
            <div className="mt-10 flex w-full flex-col items-center gap-10">
                <h2 className="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200">
                    {__('repayments')}
                </h2>

                {repayments.length === 0 && <p>{__('found_no_repayments')}</p>}

                <div className="grid w-[80%] grid-cols-[100px_150px_150px_300px] gap-10">
                    {repayments.map((transaction) => (
                        <React.Fragment key={transaction.id}>
                            <div>
                                <a href={route('transaction', transaction.id)}>
                                    {transaction.id}
                                </a>
                            </div>
                            <div>{date(transaction.timestamp)}</div>
                            <div className="text-right">
                                {money(transaction.money)}
                            </div>
                            <div>
                                <RecordLink
                                    dest="account"
                                    id={transaction.debit.id}
                                    label={transaction.debit.name}
                                />
                            </div>
                        </React.Fragment>
                    ))}
                </div>
            </div>
        </Record>
    );
}
