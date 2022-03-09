import React, {useState} from 'react';
import NavigationLink from "@/Components/NavigationLink";

function Navigation(props) {
    const [expanded, setExpanded] = useState(false);
    return (
        <React.Fragment>
            <div className={"w-full bg-pr0-dark h-20 shadow-lg flex justify-between items-center px-8 md:px-12"}>
                <div className={""}>
                    <h1 className={"text-white text-4xl"}>pr0verter</h1>
                </div>
                <div className={"gap-12 text-white text-2xl hidden md:flex"}>
                    <NavigationLink path={"/converter"}>Konvertieren</NavigationLink>
                    <NavigationLink path={"/faq"}>FAQ</NavigationLink>
                    <NavigationLink path={"/contact"}>Kontakt</NavigationLink>
                    <NavigationLink path={"/changelog"}>Changelog</NavigationLink>
                </div>
                <div className={"w-8 h-8 border-2 border-white md:hidden"} onClick={() => setExpanded(!expanded)}></div>
            </div>
            {expanded &&
                <div className={"w-full bg-pr0-dark p-8 pt-0 flex flex-col gap-4 md:hidden"}>
                    <NavigationLink path={"/converter"}>Konvertieren</NavigationLink>
                    <NavigationLink path={"/faq"}>FAQ</NavigationLink>
                    <NavigationLink path={"/contact"}>Kontakt</NavigationLink>
                    <NavigationLink path={"/changelog"}>Changelog</NavigationLink>
                </div>
            }
        </React.Fragment>
    );
}

export default Navigation;
