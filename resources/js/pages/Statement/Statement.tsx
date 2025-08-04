import AppLayout from '@/layouts/app-layout';
import { __ } from '@/lib/utils';

import { Head } from '@inertiajs/react';
import { Statement as StatementType } from './Statement';
import SubStatement from './SubStatement';

export default function Statement({ statement }: { statement: StatementType }) {
    const assets = statement.children.find((child) => child.name === 'ASSETS');
    const liabilities = statement.children.find(
        (child) => child.name === 'LIABILITIES',
    );

    return (
        <AppLayout breadcrumbs={[]}>
            <Head title={__('statement')} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="flex flex-row justify-between gap-10 p-6 text-gray-900 dark:text-gray-200">
                            <div className="flex-1">
                                <SubStatement statement={assets} />
                            </div>
                            <div className="flex-1">
                                {' '}
                                <SubStatement statement={liabilities} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
