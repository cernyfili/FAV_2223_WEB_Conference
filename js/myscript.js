
async function saveFileContentsToPOST() {
    /*let selectedFile = document.getElementById('contribution_files').files[0];
    alert(selectedFile);*/

    /*let formData = new FormData();
    formData.append("file_content", document.getElementById('contribution_files').files[0]);
    alert(formData);

    await fetch('index.php', {
        method: "POST",
        body: formData
    });*/


    const XHR = new XMLHttpRequest();
    const FD = new FormData();

    FD.append("file_content", document.getElementById('contribution_files').files[0]);
    alert("neco");
    alert(document.getElementById('contribution_files').files[0]);

    // Define what happens on successful data submission
    XHR.addEventListener('load', (event) => {
        alert('Yeah! Data sent and response loaded.');
    });

    // Define what happens in case of an error
    XHR.addEventListener('error', (event) => {
        alert('Oops! Something went wrong.');
    });

    // Set up our request
    XHR.open('POST', 'http://localhost:63342/WEB_semestralka/src/index.php?page=new_contribution');

    // Send our FormData object; HTTP headers are set automatically
    XHR.send(FD);

}