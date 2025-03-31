import Table from '@/Components/Table';
import { PageProps } from '@/types';
import { Account } from './Account';

export default function List({
    accounts,
    auth,
}: PageProps<{ accounts: [Account] }>) {
    return (
        <Table
            title="accounts"
            showRoute="account"
            auth={auth}
            list={accounts}
            header={['name', 'type', 'interest_rate']}
            row={(account) => [
                account.name,
                account.type.toString(),
                account.interest_rate?.toFixed(3) ?? '---',
            ]}
        />
    );
}
