import { Account, Money } from '../Accounts/Account';

export type Statement = {
    name: string;
    balance: Money;
    children: Statement[];
    accounts: (Account & { balance: Money })[];
};
