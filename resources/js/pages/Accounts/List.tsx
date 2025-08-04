import Table from '@/components/table';

import { Account } from './Account';

export default function List({ accounts }: { accounts: Account[] }) {
    return (
        <Table
            title="accounts"
            showRoute="account"
            createRoute="account.create"
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
