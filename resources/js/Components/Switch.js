import React, {useEffect, useState} from 'react';

function Switch({className, defaultValue, onChange}) {
    const [state, setState] = useState(defaultValue);
    useEffect(() => {
        if (typeof onChange === "function") {
            onChange(state);
        }
    }, [state])
    return (
        <div className={className}>
            <div className={"flex w-full text-center text-white text-lg transition-all duration-100"}>
                <div
                    className={"w-1/2 rounded-l-2xl py-3 select-none cursor-pointer hover:bg-pr0-main shadow-lg " + (state ? "bg-pr0-main" : "bg-pr0-dark")}
                    onClick={() => setState(true)}>
                    An
                </div>
                <div
                    className={"w-1/2 rounded-r-2xl py-3 select-none cursor-pointer hover:bg-pr0-main shadow-lg " + (!state ? "bg-pr0-main" : "bg-pr0-dark")}
                    onClick={() => setState(false)}>
                    Aus
                </div>
            </div>
        </div>
    );
}

export default Switch;
