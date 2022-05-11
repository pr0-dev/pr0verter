import React from 'react';
import {Head} from "@inertiajs/inertia-react";
import Navigation from "@/Components/Navigation";

function FAQ() {
    return (
        <>
            <Head title="pr0verter"/>
            <div className="bg-pr0-bg w-full min-h-screen pb-16">
                <Navigation/>
                <div className={"w-full md:w-1/3 px-4 md:px-0 mx-auto text-white mt-16"}>
                    <p className={"text-2xl font-semibold mb-2"}>Welche Formate unterstützt der pr0verter?</p>
                    <p className={"mt-2"}>Alle gängigen Videoformate und GIFs.</p>
                </div>
                <div className={"w-full md:w-1/3 px-4 md:px-0 mx-auto text-white mt-16"}>
                    <p className={"text-2xl font-semibold mb-2"}>Welche Webseiten unterstützt der pr0verter für den
                        Download?</p>
                    Siehe <a href={"https://github.com/yt-dlp/yt-dlp/blob/master/supportedsites.md"}
                       target={"_blank"}
                       className={"mt-2 text-pr0-main"}>Unterstützte Webseiten</a>.
                </div>
                <div className={"w-full md:w-1/3 px-4 md:px-0 mx-auto text-white mt-16"}>
                    <p className={"text-2xl font-semibold mb-2"}>Irgendetwas funktioniert nicht!!1!11!elf!</p>
                    <p>Schreib eine Nachricht an <a href={"https://pr0gramm.com/user/Insax"}
                                                    target={"_blank"}
                                                    className={"mt-2 text-pr0-main"}>@Insax</a> mit einer
                        halbwegs detaillierten Beschreibung (ob Upload, Download, File etc.).</p>
                </div>
                <div className={"w-full md:w-1/3 px-4 md:px-0 mx-auto text-white mt-16"}>
                    <p className={"text-2xl font-semibold mb-2"}>Ich möchte gerne Feature XYZ haben</p>
                    <p>Schreib eine Nachricht an <a href={"https://pr0gramm.com/user/Insax"}
                                                    target={"_blank"}
                                                    className={"mt-2 text-pr0-main"}>@Insax</a> oder mach direkt
                        einen <a href={"https://github.com/pr0-dev/pr0verter"}
                                 target={"_blank"}
                                 className={"mt-2 text-pr0-main"}>PR</a>.</p>
                </div>
                <div className={"w-full md:w-1/3 px-4 md:px-0 mx-auto text-white mt-16"}>
                    <p className={"text-2xl font-semibold mb-2"}>Was ist interpolation?</p>
                    <p>Im Endeffekt versucht der pr0verter aus einem Video mit x Frames ein Video mit 60 FPS zu machen.</p>
                </div>
                <div className={"w-full md:w-1/3 px-4 md:px-0 mx-auto text-white mt-16"}>
                    <p className={"text-2xl font-semibold mb-2"}>Wenn ich "mit Interpolation" ausgewählt habe dauert das ja eeewig</p>
                    <p>Ja, das ist leider so, da es leider sehr aufwändig ist.</p>
                </div>
            </div>
        </>
    );
}

export default FAQ;
