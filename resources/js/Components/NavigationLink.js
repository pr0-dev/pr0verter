import React from 'react';
import {Link, usePage} from "@inertiajs/inertia-react";

function NavigationLink({path, children}) {
    const { url } = usePage()
    return (
        <Link href={path} className={"block text-white text-xl hover:text-pr0-main duration-100 transition-all " + (url.startsWith(path) && "text-pr0-main")}>{children}</Link>
    );
}

export default NavigationLink;
