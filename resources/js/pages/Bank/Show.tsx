import Button from '@/Components/Button';
import Record from '@/Components/Record';
import { __, money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Money } from '../Accounts/Account';
import { Bank } from '../Bank/Bank';

export default function Show({
    bankAccount,
    balance,
    auth,
}: PageProps<{ bankAccount: Bank; balance: Money }>) {
    return (
        <Record
            title="bank_account"
            record={bankAccount}
            map={(record) => ({
                id: record.id,
                name: record.account.name,
                balance: money(balance),
            })}
        >
            <div className='flex flex-row gap-5 m-5'>
                <Button
                    link={route('bank.upload', bankAccount.id)}
                    label={__('upload')}
                />
                <Button
                    link={route('bank.compare', bankAccount.id)}
                    label={__('compare')}
                />
            </div>
        </Record>
    );
}
