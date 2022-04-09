import React, {useEffect, useState} from 'react';
import {Head, Link} from '@inertiajs/inertia-react';
import Navigation from "@/Components/Navigation";
import Button from "@/Components/Button";
import route from "../../../vendor/tightenco/ziggy/src/js";

export default function Welcome() {
    let [data, setData] = useState({});
    useEffect(() => {
        axios.get(route(("systemStats"))).then(res => {
            setData(res.data);
        })
    }, [])
    return (
        <>
            <Head title="pr0verter"/>
            <div className="bg-pr0-bg w-full min-h-screen">
                <Navigation></Navigation>
                <div className={"w-2/3 mx-auto mt-32 text-center text-xl text-white"}>
                    Der pr0verter ist ein Tool um Videos aus dem Internet zu laden und sie in ein für das pr0gramm
                    passendes Format zu bringen
                </div>
                <div className={"w-2/3 mx-auto flex mt-8 gap-8"}>
                    <div className={"w-1/2 bg-pr0-dark rounded-2xl p-8"}>
                        <p className={"text-white"}>Konvertierte Videos</p>
                        <p className={"text-4xl text-white mt-4"}>{data.totalConvertCount}</p>
                    </div>
                    <div className={"w-1/2 bg-pr0-dark rounded-2xl p-8"}>
                        <p className={"text-white"}>Konvertierte Videos Heute</p>
                        <p className={"text-4xl text-white mt-4"}>{data.convertToday}</p>
                    </div>
                </div>
                <div className={"w-2/3 mx-auto mt-8 gap-8 flex"}>
                    <div className={"w-1/2 bg-pr0-dark rounded-2xl p-8"}>
                        <p className={"text-white"}>Daten Transferiert</p>
                        <p className={"text-4xl text-white mt-4"}>{data.dataTransferred?.toFixed(2)} GB</p>
                    </div>
                    <div className={"w-1/2 bg-pr0-dark rounded-2xl p-8"}>
                        <p className={"text-white"}>Durchschnittliche Konvertierungsdauer</p>
                        <p className={"text-4xl text-white mt-4"}>{data.avgConvertTime ? parseInt(data.avgConvertTime).toFixed(2) : 0} Sekunden</p>
                    </div>
                </div>
                <div className={"w-2/3 mx-auto flex mt-8 gap-8"}>
                    <div className={"w-1/3 bg-pr0-dark rounded-2xl p-8"}>
                        <p className={"text-white"}>RAM Auslastung</p>
                        <p className={"text-4xl text-white mt-4"}>{data.memoryUsed?.toFixed(2)} %</p>
                    </div>
                    <div className={"w-1/3 bg-pr0-dark rounded-2xl p-8"}>
                        <p className={"text-white"}>CPU Auslastung</p>
                        <p className={"text-4xl text-white mt-4"}>{data.cpuUsed?.toFixed(2)} %</p>
                    </div>
                    <div className={"w-1/3 bg-pr0-dark rounded-2xl p-8"}>
                        <p className={"text-white"}>Speicher Auslastung</p>
                        <p className={"text-4xl text-white mt-4"}>{data.spaceUsed?.toFixed(2)} %</p>
                    </div>
                </div>
                <div className={"w-max mx-auto"}>
                    <Link href={"/converter"} className={"w-max"}>
                        <Button className={"w-64 mx-auto mt-8"}>Jetzt Konvertieren</Button>
                    </Link>
                </div>
            </div>
        </>
    );
}
