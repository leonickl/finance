import { __, money } from '@/lib/utils';
import { PageProps } from '@/types';
import { Statement as StatementType } from './Statement';

export default function SubStatement({
    statement,
    auth,
}: PageProps<{ statement: StatementType }>) {
    return (
        <div className="ml-5 flex flex-col gap-6">
            <div className="flex flex-row justify-between font-extrabold underline">
                <div className="font-bold"> {__(statement.name)}</div>
                <div className="text-right">{money(statement.balance)}</div>
            </div>

            <div className="grid grid-cols-2">
                {statement.accounts
                    .filter((account) => account.balance.value)
                    .map((account) => (
                        <>
                            <div className="font-bold text-gray-400">
                                {account.name}
                            </div>
                            <div className="text-right text-gray-400">
                                {money(account.balance)}
                            </div>
                        </>
                    ))}
            </div>

            {statement.children.map((child) => (
                <SubStatement auth={auth} statement={child} />
            ))}
        </div>
    );
}
