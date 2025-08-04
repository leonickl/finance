import { __ } from '@/lib/utils';
import { Method } from '@inertiajs/core';
import { Link } from '@inertiajs/react';

export default function Button({
    link,
    label,
    method = 'get',
}: {
    link: string;
    label: string;
    method?: Method;
}) {
    return (
        <Link
            href={link}
            className="rounded-md border border-blue-500 bg-blue-200 px-5 py-3 shadow"
            method={method}
        >
            {__(label)}
        </Link>
    );
}
