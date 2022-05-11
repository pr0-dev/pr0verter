import React from 'react';
import { Link, Head } from '@inertiajs/inertia-react';
import Navigation from "@/Components/Navigation";
import route from "../../../vendor/tightenco/ziggy/src/js";
import Button from "@/Components/Button";

export default function Finished(props) {
    return (
        <>
            <Head title="pr0verter" />
            <div className="bg-pr0-bg w-full min-h-screen">
                <Navigation/>
                <video controls className={"w-1/2 mx-auto mt-28 bg-black shadow-lg rounded-2xl"}>
                    <source src={route("downloadConversion", props.conversion.guid)} type="video/mp4" />
                </video>
                <p className={"mx-auto text-center mt-6 text-xl text-pr0-main p-3 bg-pr0-dark w-1/2 rounded-2xl shadow-lg"}>{route("downloadConversion", props.conversion.guid)}</p>
                <Link href={route("downloadConversion", props.conversion.guid)}><Button className={"w-1/4 mx-auto mt-6 pb-8"}>Herunterladen</Button></Link>
            </div>
        </>
    );
}
