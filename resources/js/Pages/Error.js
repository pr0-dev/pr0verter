import React from 'react';
import {Link, Head} from '@inertiajs/inertia-react';
import Navigation from "@/Components/Navigation";
import qr from "../../assets/qrcode.jpeg";

export default function Error(props) {
    return (
        <>
            <Head title="pr0verter"/>
            <div className="bg-pr0-main w-full min-h-screen pl-48 pt-16 flex flex-col justify-between">
                <div className={"w-full"}>
                    <p className={"text-white"} style={{fontSize: "220px"}}>:(</p>
                    <p className={"text-white text-3xl mt-10 mb-6"}>
                        Bei dieser Konvertierung ist ein Problem aufgetreten.
                        <br/>Entweder ist der pr0verter abgebrannt oder du musst es einfach nochmal Versuchen.
                        <br/>Genauere Informationen findest du unten</p>
                    <Link href={"/converter"} className={"text-white text-2xl underline"}>Zurück zum pr0verter</Link>
                </div>
                <div className={"flex w-full mt-32 pb-48"}>
                    <div className={"w-56 h-56 bg-white"}>
                        <img src={qr} alt={"qr"} className={"border-8 border-white"} />
                    </div>
                    <div className={"pl-8 text-2xl flex flex-col justify-between py-4"}>
                        <div className={""}>
                            <p className={"text-white"}>Es ist ein Fehler beim Download Vorgang aufgetreten.</p>
                            <p className={"text-white"}>Youtube ist aktuell nicht erreichbar</p>
                        </div>
                        <div className={""}>
                            <p className={"text-white mt-8"}>Halten Sie bitte folgende Information bereit falls Sie
                                den
                                Support kontaktieren:</p>
                            <p className={"text-white"}>YOUTUBE_IST_OFFLINE</p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
