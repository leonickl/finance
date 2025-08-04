import { DataRecord } from '@/types';

export type Person = DataRecord & {
    name: string;
};
