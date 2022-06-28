function tt_download_file(filename, data) {
    //https://stackoverflow.com/questions/3665115/how-to-create-a-file-in-memory-for-user-to-download-but-not-through-server/33542499#33542499
    const blob = new Blob([data], {type: 'text/csv'});
    if(window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveBlob(blob, filename);
    }
    else{
        const elem = window.document.createElement('a');
        elem.href = window.URL.createObjectURL(blob);
        elem.download = filename;
        elem.style.display = 'none';        
        document.body.appendChild(elem);
        elem.click();        
        document.body.removeChild(elem);
        URL.revokeObjectURL(blob);
    }
}