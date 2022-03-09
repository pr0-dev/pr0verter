import React from 'react';
import NavigationLink from "@/Components/NavigationLink";

function Navigation(props) {
    return (
        <div className={"w-full bg-pr0-dark h-20 shadow-lg flex justify-between items-center px-12"}>
            <div className={""}><h1 className={"text-white text-4xl"}>pr0verter</h1></div>
            <div className={"flex gap-12 text-white text-2xl"}>
                <NavigationLink path={"/converter"}>Konvertieren</NavigationLink>
                <NavigationLink path={"/faq"}>FAQ</NavigationLink>
                <NavigationLink path={"/contact"}>Kontakt</NavigationLink>
                <NavigationLink path={"/changelog"}>Changelog</NavigationLink>
            </div>
        </div>
    );
}

export default Navigation;
