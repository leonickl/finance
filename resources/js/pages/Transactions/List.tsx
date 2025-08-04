import Table from '@/components/table';
import { date, log } from '@/lib/utils';
import { Pagination } from '@/types';
import { Transaction } from './Transaction';

export default function List({
    transactions,
}: {
    transactions: Pagination<Transaction>;
}) {
    return (
        <Table
            title="transactions"
            showRoute="transaction"
            createRoute="create-transaction"
            list={log(transactions)}
            header={['debit', 'credit', 'text', 'value', 'date']}
            row={(transaction) => [
                transaction.debit.type.toString() +
                    ' - ' +
                    transaction.debit.name,
                transaction.credit.type.toString() +
                    ' - ' +
                    transaction.credit.name,
                transaction.text,
                transaction.value + ' ' + transaction.currency.code,
                date(transaction.timestamp),
            ]}
            search={true}
        />
    );
}
