import { Account, Money } from '../Accounts/Account';

export type Bank = {
    id: number;
    bank: string;
    account_id: number;
    balance: Money | undefined;
    account: Account;
};
