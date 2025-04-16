import { __ } from '@/lib/utils';
import { Link } from '@inertiajs/react';

export default function Button({
    link,
    label,
}: {
    link: string;
    label: string;
}) {
    return <Link href={link} className='px-5 py-3 bg-blue-200 border border-blue-500 rounded-md shadow'>{__(label)}</Link>;
}
