import Form from '@/Components/Form';
import { PageProps } from '@/types';
import { router } from '@inertiajs/react';
import { Money } from '../Accounts/Account';
import { Bank } from '../Bank/Bank';

export default function Upload({
    auth,
    bankAccount,
}: PageProps<{ bankAccount: Bank; balance: Money }>) {
    function save(input: { [key: string]: string }) {
        router.post(route('bank.upload.action', bankAccount.id), {
            value: input.value,
        });
    }

    return (
        <Form
            auth={auth}
            title="upload"
            fields={[{ name: 'value' }, { name: 'file', type: 'file' }]}
            save={save}
        />
    );
}
