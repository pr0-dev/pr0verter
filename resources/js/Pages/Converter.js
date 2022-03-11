import React, {useEffect, useRef, useState} from 'react';
import {Head, Link, usePage} from "@inertiajs/inertia-react";
import Navigation from "@/Components/Navigation";
import Input from "@/Components/Input";
import FileUpload from "@/Components/FileUpload";
import Switch from "@/Components/Switch";
import Button from "@/Components/Button";

function Converter() {
    const {url} = usePage()
    const [mode, setMode] = useState(url.endsWith("/youtube") ? 0 : url.endsWith("/download") ? 1 : 2);
    const [source, setSource] = useState("");
    const [subtitle, setSubtitle] = useState("");
    const [ratio, setRatio] = useState(true);
    const [sound, setSound] = useState(true);
    const [size, setSize] = useState(0);
    const [bitrate, setBitRate] = useState(0);
    const [interpolation, setInterpolation] = useState(true);
    const [start, setStart] = useState(0);
    const [end, setEnd] = useState(0);
    const [limits, setLimits] = useState({});

    const sizeRef = useRef();
    const startRef = useRef();
    const endRef = useRef();

    useEffect(() => {
        axios.get(route("settings")).then(data => {
            setLimits(data.data);
        }).catch((err) => {
            console.log(err)
        })
    }, [])

    function send() {
        let failed = false;

        if (limits) {
            console.log(limits.minResultSize)
            if (start && end) {
                if (end - start > limits.maxResultLength || start > end) {
                    failed = true;
                    startRef.current.style.border = "2px solid #ff0000";
                    endRef.current.style.border = "2px solid #ff0000";
                    // Clip too long
                } else {
                    startRef.current.style.border = "none";
                    endRef.current.style.border = "none";
                }
            }

            if (size < limits.minResultSize || size > limits.maxResultSize) {
                failed = true;
                sizeRef.current.style.border = "2px solid #ff0000";
            } else {
                sizeRef.current.style.border = "none";
            }

            if (sound) {
                if (bitrate !== 0 && bitrate < limits.minResultAudtioBitrate) {
                    failed = true;
                    // Sound too small
                }

                if (bitrate > limits.maxAudioBitRate) {
                    failed = true;
                    // Sound too big
                }
            }
        } else {
            failed = true;
        }

        if (failed) {
            return;
        }

        if (mode === 0) {
            axios.post(route("storeYoutube"), {
                size: size ?? 0,
                ratio: ratio,
                sound: sound ? bitrate : 0,
                start: start ?? 0,
                end: end ?? 0,
                url: source,
                interpolation: interpolation
            }).then(data => {
                console.log(data);
            }).catch(err => {
                console.log(err);
            })
        } else if (mode === 1) {
            axios.post(route("storeDownload"), {
                size: (size ?? 0) * 8192,
                ratio: ratio,
                sound: sound ? bitrate : 0,
                start: start ?? 0,
                end: end ?? 0,
                url: source,
                interpolation: interpolation
            }).then(data => {
                console.log(data);
            }).catch(err => {
                console.log(err);
            })
        }
    }

    return (
        <>
            <Head title="pr0verter"><title>pr0verter</title></Head>
            <div className="bg-pr0-bg w-full min-h-screen">
                <Navigation/>

                {
                    // Mode Selection
                }
                <div
                    className={"px-4 mt-8 md:mt-24 mx-auto w-full md:w-1/3 flex gap-0.5 text-white text-center text-xl duration-100 transition-all"}>
                    <Link href={"/converter/youtube"} className={"w-1/3"}>
                        <div
                            className={(mode === 0 ? "bg-pr0-main" : "bg-pr0-dark") + " w-full p-3 rounded-l-2xl hover:bg-pr0-main cursor-pointer"}
                            onClick={() => setMode(0)}>YouTube
                        </div>
                    </Link>
                    <Link href={"/converter/download"} className={"w-1/3"}>
                        <div
                            className={(mode === 1 ? "bg-pr0-main" : "bg-pr0-dark") + " w-full p-3 hover:bg-pr0-main cursor-pointer"}
                            onClick={() => setMode(1)}>Download
                        </div>
                    </Link>
                    <Link href={"/converter/upload"} className={"w-1/3"}>
                        <div
                            className={(mode === 2 ? "bg-pr0-main" : "bg-pr0-dark") + " w-full p-3 rounded-r-2xl hover:bg-pr0-main cursor-pointer"}
                            onClick={() => setMode(2)}>Upload
                        </div>
                    </Link>
                </div>

                {
                    // Source Selection
                }
                <div className={"px-4 mt-8 w-full md:w-1/3 mx-auto"}>
                    {mode === 0 ? <div>
                        <Input placeholder={"YouTube URL..."} onChange={setSource}/>
                    </div> : mode === 1 ? <div>
                        <Input placeholder={"Download URL..."} onChange={setSource}/>
                    </div> : <div>
                        <FileUpload/>
                    </div>
                    }
                </div>

                {
                    // YouTube specific params
                    mode === 0 &&
                    <div className={"px-4 mt-8 w-full md:w-1/3 mx-auto"}>
                        <div className={"flex justify-between w-full items-center"}>
                            <p className={"text-xl text-pr0-text"}>Untertitel</p>
                            <Switch defaultValue={false} className={"w-32"} onChange={setSubtitle}/>
                        </div>
                    </div>
                }
                {
                    // General Parameters
                }
                <div className={"px-4 mt-8 w-full md:w-1/3 mx-auto"}>
                    <div className={"flex justify-between w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Auflösung behalten</p>
                        <Switch defaultValue={true} className={"w-32"} onChange={setRatio}/>
                    </div>
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Ton behalten</p>
                        <Switch defaultValue={false} className={"w-32"} onChange={setSound}/>
                    </div>
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Wunschgröße</p>
                        <Input placeholder={"Größe in MB"} type={"number"} className={"w-1/2"} onChange={setSize}
                               inputRef={sizeRef}/>
                    </div>
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Schnitt Start</p>
                        <Input type={"number"} placeholder={"Sekunden"} className={"w-1/2"} onChange={setStart}
                               inputRef={startRef}/>
                    </div>
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Schnitt Ende</p>
                        <Input placeholder={"Sekunden"} type={"number"} className={"w-1/2"} onChange={setEnd}
                               inputRef={endRef}/>
                    </div>
                    {(limits.disabled && !limits.disabled.inputs.interpolation) &&
                        <div className={"flex justify-between w-full mt-8 items-center"}>
                            <p className={"text-xl text-pr0-text"}>Interpolation</p>
                            <Switch defaultValue={false} className={"w-32"} onChange={setInterpolation}/>
                        </div>
                    }
                </div>
                <div className={"px-4 w-full md:w-1/3 mt-8 mx-auto"}>
                    <Button className={"w-full md:w-1/2"} onClick={send}>Konvertieren</Button>
                </div>
            </div>
        </>
    );
}

export default Converter;
