import React, {useEffect, useState} from 'react';
import {Head} from "@inertiajs/inertia-react";
import Navigation from "@/Components/Navigation";
import route from "../../../vendor/tightenco/ziggy/src/js";

function Changelog() {
    let [changelog, setChangelog] = useState([]);

    useEffect(() => {
        axios.get(route("githubInfo")).then(res => {
            console.log(res);
            setChangelog(res.data);
        }).catch(err => {
            console.log(err);
        })
    }, [])
    return (
        <>
            <Head title="pr0verter"/>
            <div className="bg-pr0-bg w-full min-h-screen">
                <Navigation/>
                {changelog.map(e => <div className={"w-1/3 mx-auto text-white mt-32"}>
                    <p className={"text-2xl font-semibold mb-2"}>{e.commit.message}</p>
                    <p className={"mt-2"}>{e.author ? e.author.login : "Unbekannt"}</p>
                    <div className={"flex justify-between"}>
                        <a href={e.html_url} className={"text-pr0-main"}>Commit</a>
                        <p className={"text-pr0-text"}>{new Date(e.commit.author.date).toDateString()}</p>
                    </div>
                </div>)}
            </div>
        </>
    );
}

export default Changelog;
