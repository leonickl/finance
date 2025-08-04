import { DataRecord } from '@/types';
import { Account, Money } from '../Accounts/Account';
import { Person } from '../People/Person';

export type Transaction = DataRecord & {
    id: number;
    debit_id: number;
    credit_id: number;
    value: number;
    text: string;
    timestamp: any;
    claim_id: number | null;
    group_uid: string | null;
    person_id: number | null;
    date: Date;
    debit: Account;
    credit: Account;
    person: Person;
    claim: Transaction;
    currency: Currency;
    money: Money;
    repaid: Money | null;
    rest: Money | null;
};

export type Currency = {
    code: string;
};
