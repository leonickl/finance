import { DataRecord } from '@/types/data';
import { Money } from '../Accounts/Account';
import { Transaction } from '../Transactions/Transaction';
import { BankProposal } from './BankProposal';

export type BankTransaction = DataRecord & {
    date: string;
    text: string;
    money: Money;
    skipped: boolean;
    src: string;
    proposal: BankProposal;
    possibleTransactions: Transaction[];
    transaction: Transaction;
};
