import React from 'react';
import { Link, Head } from '@inertiajs/inertia-react';
import Navigation from "@/Components/Navigation";

export default function Welcome(props) {
    return (
        <>
            <Head title="pr0verter" />
            <div className="bg-pr0-bg w-full min-h-screen">
                <Navigation></Navigation>
            </div>
        </>
    );
}
