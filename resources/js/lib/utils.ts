import { Money } from '@/Pages/Accounts/Account';
import { Currency } from '@/Pages/Transactions/Transaction';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function __(str: string) {
    return str;
}

export function log<T>(value: T) {
    console.log(value, typeof value);
    return value;
}

export function obj(o: object) {
    return {
        only: (keys: string[] | null) =>
            keys
                ? obj(
                      Object.fromEntries(
                          Object.entries(o).filter(([key]) =>
                              keys.includes(key),
                          ),
                      ),
                  )
                : obj(o),
        map: (f: <T>([arg0, arg1]: [string, T]) => [string, T]) =>
            obj(Object.fromEntries(Object.entries(o).map(f))),
        export: <T>(f: (args: [string, any]) => T): T[] =>
            Object.entries(o).map(f),
    };
}

export function yesno(value: boolean) {
    return value ? __('yes') : __('no');
}

export function date(d: string) {
    return new Date(d).toDateString();
}

export function money(
    value: number | Money | undefined,
    currency: Currency | null = null,
): string {
    if (!value) {
        return '---';
    }

    return typeof value === 'number'
        ? `${value} ${currency?.code}`
        : money(value.value, value.currency);
}
