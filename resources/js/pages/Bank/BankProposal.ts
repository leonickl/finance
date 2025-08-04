import { DataRecord } from '@/types/data';

export type BankProposal = DataRecord & {
    value_is_positive: boolean;
    text_contains: string;
    account_proposal: number;
    text_proposal: string;
};
