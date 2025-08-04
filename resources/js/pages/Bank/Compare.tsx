import AccountSelect from '@/components/select-account';
import RecordLink from '@/components/record-link';
import Table from '@/components/table';
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
    console.log(bankTransactions);

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
                        className={`${classes} bg-green-200 border-green-800 text-green-800`}
                    >
                        {__('link')}
                    </button>
                </div>
            ))}

            <CompareForm
                bankTransaction={bankTransaction}
                accounts={accounts}
                csrf={csrf}
                setSubRow={setSubRow}
            />
        </>
    );
}

function CompareForm({
    bankTransaction,
    accounts,
    csrf,
    setSubRow,
}: {
    bankTransaction: BankTransaction;
    accounts: Account[];
    csrf: string;
    setSubRow: (arg: React.ReactNode) => void;
}) {
    const [text, setText] = useState(
        bankTransaction.proposal?.text_proposal ?? bankTransaction.text,
    );
    const [accountId, setAccountId] = useState<string>(
        bankTransaction.proposal?.account_proposal.toString() ?? '',
    );

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        fetcher({
            url: route('bank.create-and-link'),
            csrf,
            body: {
                bankTransactionId: bankTransaction.id,
                text,
                accountId,
            },
        }).then((bankTransaction: BankTransaction) =>
            setSubRow(
                <LinkedTransactionIndicator
                    transaction={bankTransaction.transaction}
                />,
            ),
        );
    }

    return (
        <form onSubmit={handleSubmit} className="flex flex-row gap-5">
            <input
                className={classes}
                value={text}
                onChange={(e) => setText(e.target.value)}
            />

            <AccountSelect
                accounts={accounts}
                initialValue={accountId}
                setValue={setAccountId}
            />

            <input
                type="submit"
                value={bankTransaction.proposal ? __('accept') : __('save')}
                className={`${classes} ${bankTransaction.proposal ? 'bg-green-200 border-green-800 text-green-800' : 'bg-blue-200 border-blue-800 text-blue-800'}`}
            />
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
