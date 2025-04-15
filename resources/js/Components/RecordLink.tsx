import { PageProps } from '@/types';
import { Link } from '@inertiajs/react';

export default function RecordLink({
    dest,
    id,
    label,
    short = false,
}: PageProps<{ dest: string; id: number; label?: string; short?: boolean }>) {
    return (
        <Link
            href={route(dest, id)}
            className="underline hover:text-gray-400 hover:no-underline"
        >
            {short ? `(${id})` :`(${id}) ${label ?? '---'}`}
        </Link>
    );
}
