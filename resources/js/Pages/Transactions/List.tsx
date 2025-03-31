import Table from '@/Components/Table';
import { date, log } from '@/lib/utils';
import { PageProps } from '@/types';
import { Transaction } from './Transaction';

export default function List({
    transactions,
    auth,
}: PageProps<{ transactions: [Transaction] }>) {
    return (
        <Table
            title="transactions"
            showRoute="transaction"
            auth={auth}
            list={log(transactions)}
            header={['debit', 'credit', 'text', 'value', 'date']}
            row={(transaction) => [
                transaction.debit.name,
                transaction.credit.name,
                transaction.text,
                transaction.value + ' ' + transaction.currency.code,
                date(transaction.timestamp),
            ]}
        />
    );
}
