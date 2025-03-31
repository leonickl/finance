import { PageProps } from '@/types';
import { Link } from '@inertiajs/react';

export default function RecordLink({
    dest,
    id,
    label,
}: PageProps<{ dest: string; id: number; label: string }>) {
    return (
        <Link
            href={route(dest, id)}
            className="underline hover:text-gray-400 hover:no-underline"
        >
            {`(${id}) ${label}`}
        </Link>
    );
}
