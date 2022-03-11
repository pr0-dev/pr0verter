import React, {useEffect, useState} from 'react';

function Input({placeholder, onChange, className, min, max, type, inputRef}) {
    const [value, setValue] = useState(null);

    useEffect(() => {
        if(typeof onChange === "function") {
            onChange(value);
        }
    }, [value]);

    return (
        <div className={className}>
            <input
                className={"bg-pr0-dark py-3 px-6 text-xl placeholder-pr0-text w-full rounded-2xl text-white shadow-lg border-0"}
                placeholder={placeholder}
                value={value}
                min={min}
                max={max}
                type={type}
                ref={inputRef}
                onChange={(e) => setValue(e.target.value)}
            />
        </div>
    );
}

export default Input;
