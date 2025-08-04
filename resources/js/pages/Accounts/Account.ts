import { DataRecord } from '@/types/data';
import { Currency } from '../Transactions/Transaction';

export type Account = DataRecord & {
    name: string;
    archived: boolean;
    recurring: boolean;
    interest_rate: number;
    type: AccountType;
};

export enum AccountType {
    ROOT = 0,
    CASH = 1,
    BANK = 2,
    CLAIM = 3,
    CLAIM_INTEREST = 4,
    INCOME = 5,
    EXPENSES = 6,
    COMPENSATION = 7,
    EQUITY = 8,
    INVESTMENT = 9,
    ASSETS = 10,
    LIABILITIES = 11,
    FUTURE_INCOME = 14,
    FUTURE_EXPENSES = 15,
}

export type Money = {
    value: number;
    currency: Currency;
};
