import Record from '@/Components/Record';
import { __, money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Link } from '@inertiajs/react';
import { Money } from '../Accounts/Account';
import { Bank } from '../Bank/Bank';

export default function Show({
    bankAccount,
    balance,
    auth,
}: PageProps<{ bankAccount: Bank; balance: Money }>) {
    return (
        <Record
            auth={auth}
            title="bank_account"
            record={bankAccount}
            map={(record) => ({
                id: record.id,
                name: record.account.name,
                balance: money(balance),
            })}
        >
            <Link href={route('bank.upload', bankAccount.id)}>
                {__('upload')}
            </Link>

            <Link href={route('bank.compare', bankAccount.id)}>
                {__('compare')}
            </Link>
        </Record>
    );
}
