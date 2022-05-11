import React from 'react';
import {Head} from "@inertiajs/inertia-react";
import Navigation from "@/Components/Navigation";

function Contact() {
    return (
        <>
            <Head title="pr0verter"/>
            <div className="bg-pr0-bg w-full min-h-screen">
                <Navigation/>
                <div className={"w-1/3 mx-auto text-white mt-32 text-center"}>
                    <p className={"text-4xl font-semibold mb-2"}>pr0verter Kontakt</p>
                    <p className={"mt-4 text-xl"}>E-Mail: <a href={"mailto:admin@1ns.at"} className={"text-pr0-main"}>admin@1ns.at</a></p>
                    <p className={"mt-2 text-xl"}>pr0gramm: <a href={"https://pr0gramm.com/user/insax"} className={"text-pr0-main"}>@insax</a></p>
                    <p className={"mt-2 text-xl"}>GitHub: <a href={"https://github.com/pr0-dev/pr0verter"} className={"text-pr0-main"}>https://github.com/pr0-dev/pr0verter</a></p>
                </div>
            </div>
        </>
    );
}

export default Contact;
