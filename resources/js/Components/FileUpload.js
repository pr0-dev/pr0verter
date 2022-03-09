import React from 'react';

function FileUpload(props) {
    return (
        <div className={"w-full bg-pr0-dark p-4 rounded-2xl shadow-lg"}>
            <p className={"text-pr0-text text-2xl font-semibold text-center mt-4 italic"}>Kein Video ausgewählt</p>
            <p className={"text-pr0-text text-center italic my-4"}>Klicke auf dieses Feld um ein Video aus zu
                wählen <br/>oder ziehe es per drag and drop hier hinein</p>
        </div>
    );
}

export default FileUpload;
