import React, {useState} from 'react';
import NavigationLink from "@/Components/NavigationLink";
import {Link} from "@inertiajs/inertia-react";

function Navigation(props) {
    const [expanded, setExpanded] = useState(false);
    return (
        <React.Fragment>
            <div className={"w-full bg-pr0-dark h-16 shadow-lg flex justify-between items-center px-8 md:px-12"}>
                <div className={""}>
                    <Link href={"/"}>
                        <h1 className={"text-white text-3xl"}>pr0verter</h1>
                    </Link>
                </div>
                <div className={"gap-12 text-white text-xl hidden md:flex"}>
                    <NavigationLink path={"/converter"}>Konvertieren</NavigationLink>
                    <NavigationLink path={"/faq"}>FAQ</NavigationLink>
                    <NavigationLink path={"/contact"}>Kontakt</NavigationLink>
                    <NavigationLink path={"/changelog"}>Changelog</NavigationLink>
                    <a className={"hover:text-pr0-main text-xl"} href={"https://github.com/pr0-dev/pr0verter"}>GitHub</a>
                </div>
                <div className={"w-8 h-8 border-2 border-white md:hidden"} onClick={() => setExpanded(!expanded)}/>
            </div>
            {expanded &&
                <div className={"w-full bg-pr0-dark p-8 pt-0 flex flex-col gap-4 md:hidden"}>
                    <NavigationLink path={"/converter"}>Konvertieren</NavigationLink>
                    <NavigationLink path={"/faq"}>FAQ</NavigationLink>
                    <NavigationLink path={"/contact"}>Kontakt</NavigationLink>
                    <NavigationLink path={"/changelog"}>Changelog</NavigationLink>
                    <a className={"text-white text-xl hover:text-pr0-main"} href={"https://github.com/pr0-dev/pr0verter"}>GitHub</a>
                </div>
            }
        </React.Fragment>
    );
}

export default Navigation;
