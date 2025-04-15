import Table from '@/Components/Table';
import { money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Bank } from './Bank';

export default function List({
    bankAccounts,
    auth,
}: PageProps<{ bankAccounts: Bank[] }>) {
    return (
        <Table
            title="bank_accounts"
            showRoute="bank.show"
            auth={auth}
            list={bankAccounts}
            header={['name', 'bank', 'balance']}
            row={(bankAccount) => [
                bankAccount.account.name,
                bankAccount.bank,
                money(bankAccount.balance),
            ]}
        />
    );
}
