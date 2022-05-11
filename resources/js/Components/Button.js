import React from 'react';

function Button({children, onClick, className, disabled}) {
    return (
        <div className={className}>
            <button className={"text-xl bg-pr0-main py-3 text-center text-white w-full rounded-2xl shadow-lg"}
                    disabled={disabled}
                    onClick={onClick}>{children}</button>
        </div>
    );
}

export default Button;
