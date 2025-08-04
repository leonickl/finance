import { DataRecord } from '@/types';
import { Account, Money } from '../Accounts/Account';

export type Bank = DataRecord & {
    bank: string;
    account_id: number;
    balance: Money | undefined;
    account: Account;
};
