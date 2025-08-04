import Button from '@/Components/Button';
import Table from '@/Components/Table';
import { __ } from '@/lib/utils';
import { PageProps } from '@/types';
import { BankProposal } from '../Bank/BankProposal';

export default function List({
    proposals,
}: PageProps<{ proposals: BankProposal[] }>) {
    return (
        <Table
            title="bank_proposals"
            list={proposals}
            header={[
                'value_is_positive',
                'text_contains',
                'account_proposal',
                'text_proposal',
                '',
            ]}
            row={(proposal) => [
                `${proposal.value_is_positive}`,
                proposal.text_contains,
                proposal.account_proposal,
                proposal.text_proposal,
                <Button
                    key={proposal.id}
                    method="delete"
                    link={route('proposals.destroy', proposal.id)}
                    label={__('delete')}
                />,
            ]}
            createRoute="proposals.create"
        />
    );
}
