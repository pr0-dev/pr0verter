import React, {useState} from 'react';

function Dropdown({className, data, nameField, onChange}) {
    let [value, setValue] = useState("");
    let [expanded, setExpanded] = useState(false);
    return (
        <div className={className + " relative"}>
            <div className={"w-full bg-pr0-dark text-pr0-text flex py-3 rounded-2xl shadow-lg px-6 flex items-center"}
                 onClick={() => {if(data.length > 0) {setExpanded(!expanded)}}}>
                <p className={"text-pr0-text flex-grow text-xl"}>{data.length > 0 ? value ? value : "Auswählen..." : "Nicht verfügbar"}</p>
                <p className={"w-8 text-white text-lg text-center leading-none"}>&#9660;</p>
            </div>
            {expanded &&
                <div className={"w-full"}>
                    <div
                        className={"w-full bg-pr0-dark py-3 px-6 mt-1 rounded-2xl shadow-lg text-xl text-white"}
                        onClick={() => {
                            setValue(null);
                            onChange(null);
                            setExpanded(false);
                        }}
                    >
                        Auswahl entfernen
                    </div>
                    {data.map(d =>
                        <div
                            key={d[nameField]}
                            className={"w-full bg-pr0-dark py-3 px-6 mt-1 rounded-2xl shadow-lg text-xl text-white"}
                            onClick={() => {
                                setValue(d[nameField]);
                                onChange(d);
                                setExpanded(false);
                            }}
                        >
                            {d[nameField]}
                        </div>
                    )}
                </div>
            }
        </div>
    );
}

export default Dropdown;
