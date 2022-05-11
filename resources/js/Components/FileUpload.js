import React, {useRef, useState} from 'react';

function FileUpload({onChange, uploadRef}) {

    let inputRef = useRef();
    let [name, setName] = useState("Kein Video ausgewählt");
    return (
        <div className={"w-full bg-pr0-dark p-4 rounded-2xl shadow-lg cursor-pointer"} ref={uploadRef} onClick={() => {
            inputRef.current.click()
        }}>
            <input type={"file"} className={"hidden"} ref={inputRef} multiple={false} accept={"video/*"} onChange={(e) => {
                onChange(e.target.files[0]);
                setName(e.target.files[0].name)
            }}/>
            <p className={"text-pr0-text text-2xl font-semibold text-center mt-4 italic"}>{name}</p>
            <p className={"text-pr0-text text-center italic my-4"}>Klicke auf dieses Feld, um ein Video aus zu
                wählen <br/>oder ziehe es per drag and drop hier hinein</p>
        </div>
    );
}

export default FileUpload;
