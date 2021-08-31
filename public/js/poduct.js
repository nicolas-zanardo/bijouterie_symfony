/**
 * Delete img and set default image
 */
if(document.querySelector(".delete-cross")){
    document.querySelector(".delete-cross").addEventListener('click',(event) => {
        console.log("hello");

        let xhr;

        if (window.XMLHttpRequest) {
            xhr = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            console.log("Votre navigateur n'est pas compatible avec AJAX...");
        }

        const formData = new FormData();
        formData.append('delete-img', "delete");
        xhr.onreadystatechange = responseDeleteImg;
        xhr.open('POST', '/gestion_produit/delete_img', true);
        xhr.send(formData);

        function  responseDeleteImg() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(JSON.parse(xhr.responseText));
                }
            }
        }
    })
}
