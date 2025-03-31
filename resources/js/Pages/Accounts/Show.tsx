import Record from '@/Components/Record';
import { __, yesno } from '@/lib/utils';
import { PageProps } from '@/types';
import { Account } from './Account';

export default function Show({
    account,
    auth,
}: PageProps<{ account: Account }>) {
    return (
        <Record
            auth={auth}
            title="account"
            record={account}
            map={(record) => ({
                id: record.id,
                name: record.name,
                archived: yesno(record.archived),
                recurring: yesno(record.recurring),
                interest_rate: record.interest_rate ?? __('none'),
                type: __(record.type.toString()),
            })}
        />
    );
}
