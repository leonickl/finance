import { Money } from '@/pages/Accounts/Account';
import { Currency } from '@/pages/Transactions/Transaction';
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

export function obj<K extends string | number | symbol, V>(o: Record<K, V>) {
    return {
        only: (keys: K[] | null) =>
            keys
                ? (Object.fromEntries(
                      Object.entries(o).filter(([key]) =>
                          keys.includes(key as K),
                      ),
                  ) as Record<K, V>)
                : o,

        map: <U>(f: ([key, value]: [K, V]) => [K, U]) => {
            const mapped = Object.fromEntries(
                Object.entries(o).map(([key, value]) =>
                    f([key as K, value as V]),
                ),
            );
            return mapped as Record<K, U>;
        },

        export: <R>(f: ([key, value]: [K, V]) => R): R[] =>
            Object.entries(o).map(([key, value]) => f([key as K, value as V])),
    };
}

export function yesno(value: boolean) {
    return value ? __('yes') : __('no');
}

export function date(d: string) {
    return new Date(d).toDateString();
}

export function dateShort(d: string) {
    return new Date(d).toLocaleDateString();
}

export function money(
    value: number | Money | undefined | null,
    currency: Currency | undefined | null = null,
): string {
    if (!value) {
        return '---';
    }

    return typeof value === 'number'
        ? `${value} ${currency?.code}`
        : money(value.value, value.currency);
}

export function moneyInverted(
    value: number | Money | undefined,
    currency: Currency | null = null,
): string {
    if (!value) {
        return '---';
    }

    return typeof value === 'number'
        ? `${-value} ${currency?.code}`
        : money(-value.value, value.currency);
}
