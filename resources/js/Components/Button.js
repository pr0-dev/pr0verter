import React from 'react';

function Button({children, onClick, className}) {
    return (
        <div className={className}>
            <button className={"text-xl bg-pr0-main py-3 text-center text-white w-full rounded-2xl shadow-lg"}
                    onClick={onClick}>{children}</button>
        </div>
    );
}

export default Button;
