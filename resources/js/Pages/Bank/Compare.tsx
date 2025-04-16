import AccountSelect from '@/Components/AccountSelect';
import RecordLink from '@/Components/RecordLink';
import Table from '@/Components/Table';
import fetcher from '@/fetcher';
import { __, money } from '@/lib/utils';
import { classes } from '@/style';
import { PageProps } from '@/types';
import React, { useState } from 'react';
import { Account } from '../Accounts/Account';
import { Transaction } from '../Transactions/Transaction';
import { Bank } from './Bank';
import { BankTransaction } from './BankTransaction';

export default function Compare({
    bankTransactions,
    accounts,
    csrf,
}: PageProps<{
    bankAccount: Bank;
    bankTransactions: BankTransaction[];
    accounts: Account[];
}>) {
    const [usedTransactions, setUsedTransactions] = useState<number[]>([]);

    function useTransactionId(id: number) {
        setUsedTransactions((old) => [...old, id]);
    }

    bankTransactions = bankTransactions.map((bt) => ({
        ...bt,
        possibleTransactions: bt.possibleTransactions.filter(
            (t) => !usedTransactions.includes(t.id),
        ),
    }));

    return (
        <Table
            title="bank_accounts"
            showRoute="bank.show"
            list={bankTransactions}
            header={['date', 'text', 'money']}
            row={(bankTransaction) => [
                bankTransaction.date,
                bankTransaction.text,
                money(bankTransaction.money),
            ]}
            sub={(bankTransaction, setSubRow) => (
                <CompareRow
                    csrf={csrf}
                    bankTransaction={bankTransaction}
                    accounts={accounts}
                    setSubRow={setSubRow}
                    useTransactionId={useTransactionId}
                />
            )}
            cols="grid-cols-[50px_200px_auto_200px]"
        />
    );
}

function CompareRow({
    csrf,
    bankTransaction,
    accounts,
    setSubRow,
    useTransactionId,
}: {
    csrf: string;
    bankTransaction: BankTransaction;
    accounts: Account[];
    setSubRow: (arg: React.ReactNode) => void;
    useTransactionId: (arg: number) => void;
}) {
    function link(bankTransactionId: number, transactionId: number): void {
        fetcher({
            url: route('bank.link'),
            csrf,
            body: { bankTransactionId, transactionId },
        }).then((bankTransaction: BankTransaction) => {
            useTransactionId(transactionId);
            
            setSubRow(
                <LinkedTransactionIndicator
                    transaction={bankTransaction.transaction}
                />,
            );
        });
    }

    return (
        <>
            {...bankTransaction.possibleTransactions.map((t) => (
                <div className="flex flex-row gap-5">
                    <div className={`${classes} w-max`} key={t.id}>
                        <RecordLink
                            dest="transaction"
                            id={t.id}
                            label={`${t.debit.name} to ${t.credit.name}, ${t.text}`}
                        />
                    </div>

                    <button
                        onClick={() => link(bankTransaction.id, t.id)}
                        className={classes}
                    >
                        {__('link')}
                    </button>
                </div>
            ))}

            <CompareForm
                bankTransaction={bankTransaction}
                accounts={accounts}
            />
        </>
    );
}

function CompareForm({
    bankTransaction,
    accounts,
}: {
    bankTransaction: BankTransaction;
    accounts: Account[];
}) {
    const [text, setText] = useState(bankTransaction.text);
    const [accountId, setAccountId] = useState<string>('');

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        console.log(text, accountId);
    }

    return (
        <form onSubmit={handleSubmit} className="flex flex-row gap-5">
            <input
                className={classes}
                value={text}
                onChange={(e) => setText(e.target.value)}
            />

            <AccountSelect accounts={accounts} setValue={setAccountId} />

            <input type="submit" value={__('save')} className={classes} />
        </form>
    );
}

function LinkedTransactionIndicator({
    transaction,
}: {
    transaction: Transaction;
}) {
    return (
        <div className="flex flex-row gap-5">
            {__('linked_transaction')}:{' '}
            <RecordLink
                dest="transaction"
                id={transaction.id}
                label={transaction.text}
            />
        </div>
    );
}
