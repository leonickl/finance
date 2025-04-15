import Table from '@/Components/Table';
import { money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Bank } from './Bank';
import { BankTransaction } from './BankTransaction';

export default function Compare({
    bankAccount,
    auth,
    bankTransactions,
}: PageProps<{ bankAccount: Bank; bankTransactions: BankTransaction[] }>) {
    console.log(bankTransactions);

    return (
        <Table
            title="bank_accounts"
            showRoute="bank.show"
            auth={auth}
            list={bankTransactions}
            header={['date', 'text', 'money', 'possible_transactions']}
            row={(bankTransaction) => [
                bankTransaction.date,
                bankTransaction.text,
                money(bankTransaction.money),

                <div className="flex flex-col gap-5">
                    {bankTransaction.possibleTransactions.map((t) => (
                        <p>
                            {`${t.id}: ${t.debit.name} to ${t.credit.name}, ${t.text}`}
                        </p>
                    ))}
                </div>,
            ]}
            cols="grid-cols-[50px_200px_500px_200px_20px]"
        />
    );
}
