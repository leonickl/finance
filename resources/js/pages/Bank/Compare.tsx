import RecordLink from '@/components/record-link';
import AccountSelect from '@/components/select-account';
import Table from '@/components/table';
import fetcher from '@/fetcher';
import { __, money } from '@/lib/utils';
import { classes } from '@/style';

import { usePage } from '@inertiajs/react';
import React, { useState } from 'react';
import { Account } from '../Accounts/Account';
import { Transaction } from '../Transactions/Transaction';
import { Bank } from './Bank';
import { BankTransaction } from './BankTransaction';

export default function Compare({
    bankTransactions,
    accounts,
}: {
    bankAccount: Bank;
    bankTransactions: BankTransaction[];
    accounts: Account[];
}) {
    const { csrf } = usePage().props;

    const [usedTransactions, setUsedTransactions] = useState<number[]>([]);

    function withTransactionId(id: number) {
        setUsedTransactions((old) => [...old, id]);
    }

    bankTransactions = bankTransactions.map((bt) => ({
        ...bt,
        possibleTransactions: bt.possibleTransactions.filter(
            (t: Transaction) => !usedTransactions.includes(t.id),
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
                    csrf={csrf as string}
                    bankTransaction={bankTransaction}
                    accounts={accounts}
                    setSubRow={setSubRow}
                    withTransactionId={withTransactionId}
                />
            )}
            cols="grid-cols-[50px_200px_auto_200px]"
            injectBefore={
                <button
                    type="submit"
                    className={`${classes} text-green-800} border-green-800 bg-green-200`}
                >
                    {__('accept_all')}
                </button>
            }
        />
    );
}

function CompareRow({
    csrf,
    bankTransaction,
    accounts,
    setSubRow,
    withTransactionId,
}: {
    csrf: string;
    bankTransaction: BankTransaction;
    accounts: Account[];
    setSubRow: (arg: React.ReactNode) => void;
    withTransactionId: (arg: number) => void;
}) {
    function link(bankTransactionId: number, transactionId: number): void {
        fetcher({
            url: route('bank.link'),
            csrf,
            body: { bankTransactionId, transactionId },
        }).then((bankTransaction: BankTransaction) => {
            withTransactionId(transactionId);

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
                        className={`${classes} border-green-800 bg-green-200 text-green-800`}
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

export function acceptProposal(
    csrf: string,
    bankTransactionId: number,
    text: string,
    accountId: number,
    then: (arg: BankTransaction) => void,
) {
    fetcher({
        url: route('bank.create-and-link'),
        csrf,
        body: {
            bankTransactionId,
            text,
            accountId,
        },
    }).then(then);
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

        acceptProposal(
            csrf,
            bankTransaction.id,
            text,
            Number.parseInt(accountId),
            (bankTransaction: BankTransaction) =>
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
                className={`${classes} ${bankTransaction.proposal ? 'border-green-800 bg-green-200 text-green-800' : 'border-blue-800 bg-blue-200 text-blue-800'}`}
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
