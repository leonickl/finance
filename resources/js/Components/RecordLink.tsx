import { PageProps } from '@/types';

export default function RecordLink({
    dest,
    id,
    label,
}: PageProps<{ dest: string; id: number; label: string }>) {
    return (
        <a
            href={route(dest, id)}
            className="underline hover:text-gray-400 hover:no-underline"
        >
            {`(${id}) ${label}`}
        </a>
    );
}
