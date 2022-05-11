import React, {useEffect, useState} from 'react';
import {Head} from '@inertiajs/inertia-react';
import Navigation from "@/Components/Navigation";
import route from "../../../vendor/tightenco/ziggy/src/js";
import {Inertia} from "@inertiajs/inertia";


export default function Progress(props) {
    let [video, setVideo] = useState("0%");
    let [isUpload] = useState(props.conversion.type_info_type.endsWith("Upload"));
    let [text, setText] = useState();
    useEffect(() => {
        let interval = setInterval(() => {
            axios.get(route("showConversion", props.conversion.guid)).then((res) => {
                if (!isUpload) {
                    if (res.data.type_info.progress === null) {
                        setVideo("0%");
                        setText("Warten bis der Download beginnt...");
                    } else if (res.data.type_info.progress !== "100%") {
                        setVideo(res.data.type_info.progress);
                        setText("Video wird heruntergeladen... " + res.data.type_info.eta + " (" + res.data.type_info.rate + ")");
                    } else if (res.data.converter_progress === 0) {
                        setVideo("0%");
                        setText("Warten bis der Converter beginnt...");
                    } else if (res.data.converter_progress < 100) {
                        setVideo(res.data.converter_progress + "%");
                        setText("Video wird konvertiert... " + res.data.converter_remaining + " Sekunden (" + res.converter_rate + " Frames/s)");
                    } else {
                        Inertia.visit(route("finished", props.conversion.guid));
                    }

                } else {
                    if (res.data.converter_progress === 0) {
                        setVideo("0%");
                        setText("Warten bis der Converter beginnt...");
                    } else if (res.data.converter_progress < 100) {
                        setVideo(res.data.converter_progress + "%");
                        setText("Video wird konvertiert... " + res.data.converter_remaining + " Sekunden (" + res.converter_rate + " Frames/s)");
                    } else {
                        Inertia.visit(route("finished", props.conversion.guid));
                    }
                }
                if (res.data.failed === 1) {
                    Inertia.visit(route("error", props.conversion.guid))
                }
            }).catch(err => {
                console.log(err);
                clearInterval(interval)
            })
        }, 200);

        return () => {
            clearInterval(interval);
        }
    }, [])
    return (
        <>
            <Head title="pr0verter"/>
            <div className="bg-pr0-bg w-full min-h-screen flex flex-col">
                <Navigation/>
                <div
                    className={"text-center text-pr0-text flex-grow w-full flex items-center justify-center flex-wrap"}>
                    <div className={"w-full mx-auto"}>
                        <p className={"w-full text-2xl"}>
                            Bitte haben Sie einen Moment Geduld und schalten sie das Internet nicht aus.
                        </p>
                        <div className={"w-2/3 bg-pr0-dark rounded-full shadow-lg h-8 mt-8 mx-auto overflow-hidden"}>
                            <div
                                className={"bg-pr0-main h-full rounded-r-full transition-all duration-300 text-center overflow-hidden text-white flex items-center justify-center"}
                                style={{width: video}}>{video}</div>
                        </div>
                        <p className={"text-pr0-text mt-8 text-xl"}>{text}</p>
                    </div>
                </div>
            </div>
        </>
    );
}
