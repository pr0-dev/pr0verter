import React, {useState} from 'react';
import {Head, Link, usePage} from "@inertiajs/inertia-react";
import Navigation from "@/Components/Navigation";
import Input from "@/Components/Input";
import FileUpload from "@/Components/FileUpload";
import Switch from "@/Components/Switch";
import Button from "@/Components/Button";

function Converter(props) {
    const {url} = usePage()
    const [mode, setMode] = useState(url.endsWith("/youtube") ? 0 : url.endsWith("/download") ? 1 : 2);
    const [source, setSource] = useState("");
    const [subtitle, setSubtitle] = useState("");
    const [ratio, setRatio] = useState(true);
    const [sound, setSound] = useState(true);
    const [size, setSize] = useState(0);
    const [interpolation, setInterpolation] = useState(true);
    const [start, setStart] = useState(0);
    const [end, setEnd] = useState(0);

    return (
        <>
            <Head title="pr0verter"/>
            <div className="bg-pr0-bg w-full min-h-screen">
                <Navigation></Navigation>

                {
                    // Mode Selection
                }
                <div
                    className={"mt-24 mx-auto w-1/4 flex gap-0.5 text-white text-center text-xl duration-100 transition-all"}>
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
                <div className={"mt-8 w-1/4 mx-auto"}>
                    {mode === 0 ? <div>
                        <Input placeholder={"YouTube URL..."} onChange={setSource}/>
                    </div> : mode === 1 ? <div>
                        <Input placeholder={"Download URL..."} onChange={setSource}/>
                    </div> : <div>
                        <FileUpload></FileUpload>
                    </div>
                    }
                </div>

                {
                    // YouTube specific params
                    mode === 0 &&
                    <div className={"mt-8 w-1/4 mx-auto"}>
                        <div className={"flex justify-between w-full items-center"}>
                            <p className={"text-xl text-pr0-text"}>Untertitel</p>
                            <Switch defaultValue={false} className={"w-32"} onChange={setSubtitle}/>
                        </div>
                    </div>
                }
                {
                    // General Parameters
                }
                <div className={"mt-8 w-1/4 mx-auto"}>
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
                        <Input placeholder={"Größe in MB"} className={"w-1/2"} onChange={setSize}/>
                    </div>
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Schnitt Start</p>
                        <Input placeholder={"Sekunden"} className={"w-1/2"} onChange={setStart}/>
                    </div>
                    <div className={"flex justify-between mt-8 w-full items-center"}>
                        <p className={"text-xl text-pr0-text"}>Schnitt Ende</p>
                        <Input placeholder={"Sekunden"} className={"w-1/2"} onChange={setEnd}/>
                    </div>
                    <div className={"flex justify-between w-full mt-8 items-center"}>
                        <p className={"text-xl text-pr0-text"}>Interpolation</p>
                        <Switch defaultValue={false} className={"w-32"} onChange={setInterpolation}/>
                    </div>
                </div>
                <div className={"w-1/4 mt-8 mx-auto"}>
                    <Button className={"w-1/2"}>Konvertieren</Button>
                </div>
            </div>
        </>
    );
}

export default Converter;
