import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { __ } from '@/lib/utils';
import { PageProps } from '@/types';
import { Head } from '@inertiajs/react';
import { Statement as StatementType } from './Statement';
import SubStatement from './SubStatement';

export default function Statement({
    statement,
    auth,
}: PageProps<{ statement: StatementType }>) {
    const assets = statement.children.find((child) => child.name === 'ASSETS');
    const liabilities = statement.children.find(
        (child) => child.name === 'LIABILITIES',
    );

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {__('statement')}
                </h2>
            }
        >
            <Head title={__('statement')} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div className="flex flex-row justify-between p-6 text-gray-900 dark:text-gray-200 gap-10">
                            <div className='flex-1'>
                                <SubStatement auth={auth} statement={assets} />
                            </div>
                            <div className='flex-1'>
                                {' '}
                                <SubStatement
                                    auth={auth}
                                    statement={liabilities}
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
