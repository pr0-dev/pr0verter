import React, {useEffect, useRef, useState} from 'react';
import {Head, Link, usePage} from "@inertiajs/inertia-react";
import Navigation from "@/Components/Navigation";
import Input from "@/Components/Input";
import FileUpload from "@/Components/FileUpload";
import Switch from "@/Components/Switch";
import Button from "@/Components/Button";
import {Inertia} from "@inertiajs/inertia";
import Dropdown from "@/Components/Dropdown";

function Converter() {
    const {url} = usePage()
    const [mode, setMode] = useState(url.endsWith("/youtube") ? 0 : url.endsWith("/upload") ? 2 : 1);
    const [source, setSource] = useState("");
    const [subtitle, setSubtitle] = useState(false);
    const [language, setLanguage] = useState("");
    const [languages, setLanguages] = useState([]);
    const [ratio, setRatio] = useState(true);
    const [sound, setSound] = useState(true);
    const [size, setSize] = useState(0);
    const [bitrate, setBitRate] = useState(128);
    const [interpolation, setInterpolation] = useState(true);
    const [start, setStart] = useState(0);
    const [end, setEnd] = useState(0);
    const [limits, setLimits] = useState({});
    const [error, setError] = useState({});
    const [file, setFile] = useState();

    const sizeRef = useRef();
    const sourceRef = useRef();
    const startRef = useRef();
    const endRef = useRef();

    useEffect(() => {
        if (mode === 0 && source) {
            axios.post(route("youtubeInfo"), {url: source}).then(data => {
                let titles = data.data.availableSubtitles;
                if (titles) {
                    setLanguages(Object.entries(titles).map(([key, value]) => {
                        return {name: value, id: key}
                    }));
                } else {
                    setLanguages([]);
                }
            }).catch(err => {
                setLanguages([]);
                console.log(err);
            })
        }
    }, [source])

    useEffect(() => {
        axios.get(route("settings")).then(data => {
            setLimits(data.data);
        }).catch((err) => {
            console.log(err)
        })
    }, [])

    function send() {
        let failed = false;
        let tmpErrors = {};
        if (limits) {
            console.log(limits.minResultSize)
            if (start && end) {
                if (end - start > limits.maxResultLength || start > end) {
                    failed = true;
                    startRef.current.style.border = "2px solid #ff0000";
                    endRef.current.style.border = "2px solid #ff0000";
                    tmpErrors.time = "Das Video darf maximal " + limits.maxResultLength + " Sekunden lang sein"
                    // Clip too long
                } else {
                    startRef.current.style.border = "none";
                    endRef.current.style.border = "none";
                }
            }

            if (size < limits.minResultSize || size > limits.maxResultSize) {
                failed = true;
                sizeRef.current.style.border = "2px solid #ff0000";
                tmpErrors.size = "Die Wunschgröße muss zwischen " + limits.minResultSize + " MB und " + limits.maxResultSize + " MB sein."
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
                    tmpErrors.bitrate = "Die Audio Bitrate muss unter " + limits.maxAudioBitRate + " sein.";
                    // Sound too big
                }
            }
        } else {
            failed = true;
        }

        if(mode < 2) {
            if(!source) {
                failed = true;
                sourceRef.current.style.border = "2px solid #ff0000";
                tmpErrors.file = "Es wurde keine Quelle angegeben.";
            }else{
                sourceRef.current.style.border = "none";
            }
        }else if(mode === 2) {
            if(!file) {
                failed = true;
                sourceRef.current.style.border = "2px solid #ff0000";
                tmpErrors.file = "Es wurde keine Datei ausgewählt.";
            }else{
                sourceRef.current.style.border = "none";
            }
        }

        if (failed) {
            setError(tmpErrors);
            return;
        }

        if (mode === 0) {
            axios.post(route("storeYoutube"), {
                size: size * 8192 ?? 0,
                ratio: ratio,
                sound: sound ? bitrate : 0,
                start: start ? start : 0,
                end: end ? end : 0,
                url: source,
                interpolation: interpolation,
                subtitle: subtitle && language ? language.id : undefined
            }).then(data => {
                Inertia.visit(route("progress", data.data.guid))
            }).catch(err => {
                if(err.response) {
                    setError(err.response.data.errors);
                    if(typeof err.response.data.errors.url !== "undefined") {
                        sourceRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.end !== "undefined") {
                        endRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.start !== "undefined") {
                        startRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.size !== "undefined") {
                        sizeRef.current.style.border = "2px solid #ff0000";
                    }
                }
            })
        } else if (mode === 1) {
            axios.post(route("storeDownload"), {
                size: (size ?? 0) * 8192,
                ratio: ratio,
                sound: sound ? bitrate : 0,
                start: start ? start : 0,
                end: end ? end : 0,
                url: source,
                interpolation: interpolation
            }).then(data => {
                Inertia.visit(route("progress", data.data.guid))
            }).catch(err => {
                if(err.response) {
                    setError(err.response.data.errors);
                    if(typeof err.response.data.errors.url !== "undefined") {
                        sourceRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.end !== "undefined") {
                        endRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.start !== "undefined") {
                        startRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.size !== "undefined") {
                        sizeRef.current.style.border = "2px solid #ff0000";
                    }
                }
            })
        } else if(mode === 2) {
            let formData = new FormData();
            formData.append("size", (size ?? 0) * 8192);
            formData.append("ratio", ratio ? "true" : "false");
            formData.append("sound", sound ? bitrate : 0);
            formData.append("start", start ? start : 0);
            formData.append("end", end ? end : 0);
            formData.append("video", file);
            formData.append("interpolation", interpolation ? "true" : "false");

            axios.post(route("storeUpload"), formData).then(data => {
                Inertia.visit(route("progress", data.data.guid))
            }).catch(err => {
                if(err.response) {
                    setError(err.response.data.errors);
                    if(typeof err.response.data.errors.video !== "undefined") {
                        sourceRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.end !== "undefined") {
                        endRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.start !== "undefined") {
                        startRef.current.style.border = "2px solid #ff0000";
                    }
                    if(typeof err.response.data.errors.size !== "undefined") {
                        sizeRef.current.style.border = "2px solid #ff0000";
                    }
                }
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
                        <Input placeholder={"YouTube URL..."} onChange={setSource} inputRef={sourceRef}/>
                    </div> : mode === 1 ? <div>
                        <Input placeholder={"Download URL..."} onChange={setSource} inputRef={sourceRef}/>
                    </div> : <div>
                        <FileUpload onChange={(f) => setFile(f)} uploadRef={sourceRef}/>
                    </div>
                    }
                </div>

                {
                    // YouTube specific params
                    (mode === 0 && languages.length > 0) &&
                    <div className={"px-4 mt-8 w-full md:w-1/3 mx-auto"}>
                        <div className={"flex justify-between w-full items-center"}>
                            <p className={"text-xl text-pr0-text"}>Untertitel</p>
                            <Switch defaultValue={false} className={"w-32"} onChange={setSubtitle}/>
                        </div>
                    </div>
                }
                {
                    // YouTube specific params
                    subtitle &&
                    <div className={"px-4 mt-8 w-full md:w-1/3 mx-auto"}>
                        <div className={"flex justify-between w-full items-center"}>
                            <p className={"text-xl text-pr0-text"}>Sprache</p>
                            <Dropdown className={"w-1/2"} nameField={"name"}
                                      data={languages}
                                      onChange={(d) => setLanguage(d)}/>
                        </div>
                    </div>
                }
                {
                    // General Parameters
                }
                <div className={"px-4 mt-8 w-full md:w-1/3 mx-auto"}>
                    <div className={"flex justify-between w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Auflösung behalten</p>
                        <Switch defaultValue={false} className={"w-32"} onChange={setRatio}/>
                    </div>
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Ton behalten</p>
                        <Switch defaultValue={true} className={"w-32"} onChange={setSound}/>
                    </div>
                    {sound &&
                        <div className={"flex justify-between mt-8 w-full items-center"}>
                            <p className={"text-xl text-pr0-text"}>Audio Qualität</p>
                            <input type={"range"} min={limits.minResultAudioBitrate} max={limits.maxResultAudioBitrate}
                                   value={bitrate} onChange={(e) => setBitRate(e.target.value)}
                                   className={"w-1/2 slider"}/>
                        </div>}
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Wunschgröße</p>
                        <Input placeholder={"Größe in MB"} initial={200} type={"number"} className={"w-1/2"}
                               onChange={setSize}
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
                <div className={"px-4 w-full md:w-1/3 mt-8 mx-auto pb-12"}>
                    <p className={"text-red-600 mb-10 text-2xl"}>
                        {Object.values(error).map(v => <React.Fragment>{v}<br/></React.Fragment>)}
                    </p>
                    <Button className={"w-full md:w-1/2"} onClick={send}>Konvertieren</Button>
                </div>
            </div>
        </>
    );
}

export default Converter;
