import { Link } from '@inertiajs/react';

export default function RecordLink({
    dest,
    id,
    label = undefined,
}: {
    dest: string;
    id: number;
    label?: string;
}) {
    return (
        <Link
            href={route(dest, id)}
            className="hover:text-gray-400 hover:underline"
        >
            {label ? `(${id}) ${label ?? '---'}` : `(${id})`}
        </Link>
    );
}
