import Record from '@/components/record';
import RecordLink from '@/components/record-link';
import { __, dateShort, money, moneyInverted, yesno } from '@/lib/utils';

import { Transaction } from '../Transactions/Transaction';
import { Account, Money } from './Account';

export default function Show({
    account,
    balance,
    transactions,
}: PageProps<{
    account: Account;
    balance: Money;
    transactions: Transaction[];
}>) {
    const isClaimAccount =
        account.type.toString() === 'CLAIM' ||
        account.type.toString() === 'CLAIM_INTEREST';

    return (
        <Record
            title="account"
            record={account}
            map={(record) => ({
                id: record.id,
                name: record.name,
                archived: yesno(record.archived),
                recurring: yesno(record.recurring),
                interest_rate: record.interest_rate ?? __('none'),
                type: __(record.type.toString()),
                balance: money(balance),
            })}
        >
            <div className="mt-10 flex w-full flex-col items-center">
                <div className="grid w-[80%] grid-cols-[50px_80px_100px_200px_200px_200px] gap-10">
                    {transactions.map((transaction) => (
                        <>
                            <div>
                                <a href={route('transaction', transaction.id)}>
                                    {transaction.id}
                                </a>
                            </div>

                            <div className="text-center">
                                {dateShort(transaction.timestamp)}
                            </div>

                            <div className="text-right">
                                {transaction.debit_id === account.id
                                    ? money(transaction.money)
                                    : moneyInverted(transaction.money)}
                            </div>

                            <div>
                                {transaction.debit_id === account.id ? (
                                    <RecordLink
                                        dest="account"
                                        id={transaction.credit.id}
                                        label={transaction.credit.name}
                                    />
                                ) : (
                                    <RecordLink
                                        dest="account"
                                        id={transaction.debit.id}
                                        label={transaction.debit.name}
                                    />
                                )}
                            </div>

                            <div>{transaction.text}</div>

                            <div className="text-center">
                                {isClaimAccount && transaction.claim && (
                                    <RecordLink
                                        dest="transaction"
                                        id={transaction.claim.id}
                                    />
                                )}
                                {isClaimAccount &&
                                    transaction.debit_id === account.id &&
                                    `${money(transaction.rest)} / ${money(transaction.money)}`}
                            </div>
                        </>
                    ))}
                </div>
            </div>
        </Record>
    );
}
