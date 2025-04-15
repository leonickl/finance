import Form from '@/Components/Form';
import { PageProps } from '@/types';
import { router } from '@inertiajs/react';
import { Money } from '../Accounts/Account';
import { Bank } from '../Bank/Bank';

export default function Upload({
    auth,
    bankAccount,
}: PageProps<{ bankAccount: Bank; balance: Money }>) {
    function save(input: { [key: string]: any }) {
        const hasFile = input.file instanceof File && input.file.name;

        if (hasFile) {
            const formData = new FormData();
            formData.append('file', input.file);

            router.post(route('bank.upload.action', bankAccount.id), formData, {
                forceFormData: true,
            });
        } else {
            router.post(route('bank.upload.action', bankAccount.id), {
                value: input.value,
            });
        }
    }

    return (
        <Form
            auth={auth}
            title="upload"
            fields={[{ name: 'value' }, { name: 'file', type: 'file' }]}
            save={save}
        >
            {bankAccount.bank === 'TRADE_REPUBLIC' && (
                <pre
                    className="w-80"
                    onClick={(e) =>
                        navigator.clipboard.writeText(
                            (e.target as HTMLPreElement).textContent ?? '',
                        )
                    }
                >
                    {
                        "JSON.stringify([...document.querySelectorAll('.timeline__entry:not(.-isNewSection)')].map(entry => ({text: entry.querySelector('.timelineV2Event__title')?.outerText,date: entry.querySelector('.timelineV2Event__subtitle')?.outerText?.split(' - ')?.[0],value: entry.querySelector('.timelineV2Event__price p')?.outerText,})))"
                    }
                </pre>
            )}
        </Form>
    );
}
