import Record from '@/Components/Record';
import RecordLink from '@/Components/RecordLink';
import { date, money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Transaction } from './Transaction';

export default function Show({
    transaction,
    auth,
}: PageProps<{ transaction: Transaction }>) {
    return (
        <Record
            auth={auth}
            title="transaction"
            record={transaction}
            map={(record) => ({
                id: record.id,
                value: money(record.value, record.currency),
                text: record.text,
                date: date(record.timestamp),
                debit: (
                    <RecordLink
                        auth={auth}
                        dest="account"
                        label={record.debit.name}
                        id={record.debit.id}
                    />
                ),
                credit: (
                    <RecordLink
                        auth={auth}
                        dest="account"
                        label={record.credit.name}
                        id={record.credit.id}
                    />
                ),
                person: record.person?.name,
                claim: record.claim && (
                    <RecordLink
                        auth={auth}
                        dest="transaction"
                        label={record.claim.text}
                        id={record.claim.id}
                    />
                ),
            })}
        />
    );
}
