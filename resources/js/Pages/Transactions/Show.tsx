import Record from '@/Components/Record';
import RecordLink from '@/Components/RecordLink';
import { __, date, money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Money } from '../Accounts/Account';
import { Transaction } from './Transaction';

export default function Show({
    transaction,
    repayments,
    auth,
}: PageProps<{
    transaction: Transaction;
    repayments: Transaction[];
}>) {
    console.log(transaction, repayments);

    return (
        <Record
            auth={auth}
            title="transaction"
            record={transaction}
            map={(record) => ({
                id: record.id,
                value: money(record.value, record.currency),
                text: record.text,
                date: date(record.timestamp),
                debit: (
                    <RecordLink
                        auth={auth}
                        dest="account"
                        label={record.debit.name}
                        id={record.debit.id}
                    />
                ),
                credit: (
                    <RecordLink
                        auth={auth}
                        dest="account"
                        label={record.credit.name}
                        id={record.credit.id}
                    />
                ),
                person: record.person?.name,
                claim: record.claim && (
                    <RecordLink
                        auth={auth}
                        dest="transaction"
                        label={record.claim.text}
                        id={record.claim.id}
                    />
                ),
                repaid: money(record.repaid),
                rest: money(record.rest),
            })}
        >
            <div className="mt-10 flex w-full flex-col items-center gap-10">
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {__('repayments')}
                </h2>

                {repayments.length === 0 && <p>{__('found_no_repayments')}</p>}

                <div className="grid w-[80%] grid-cols-[100px_150px_150px_300px] gap-10">
                    {repayments.map((transaction) => (
                        <>
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
                                    auth={auth}
                                    dest="account"
                                    id={transaction.debit.id}
                                    label={transaction.debit.name}
                                />
                            </div>
                        </>
                    ))}
                </div>
            </div>
        </Record>
    );
}
