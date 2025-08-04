import Table from '@/components/table';
import { money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Bank } from './Bank';

export default function List({
    bankAccounts,
}: PageProps<{ bankAccounts: Bank[] }>) {
    return (
        <Table
            title="bank_accounts"
            showRoute="bank.show"
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
