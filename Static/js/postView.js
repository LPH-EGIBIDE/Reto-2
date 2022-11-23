async function getPost(postId) {
   return  fetch(`/api/posts/managePost`, {
        method: 'POST',
        headers: {
           "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            postId: postId
        })
    });
}

async function getPostAnswers(postId) {
    return  fetch(`/api/posts/manageAnswers`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            id: postId
        })
    });
}


function createPost(data) {
    return `
            <div class="conTags">
                <p class="tags">${data.topic.name}</p>
            </div>
            <div class="conPreguntaTitu">
                <h3 class="preguntaTitu">${data.title}</h3>
            </div>
            <div class="conTextoPre">
                    <p class="textoPre">${data.description}</p>
            </div>
            <div class="conAdjunto">
                <p class="adjunto"><span style="font-weight: bold">Publicado por: </span><a href="/user/${data.author.id}">${data.author.username}</a></p>
            </div>
    `;
}

function createPostAnswer(data) {
    //TODO: Attachments && Clickable upvote and favorite

    return `
        <div class="cajaRespuesta" answer-id="${data.id}">
            <div class="conTextoRes">
                <p class="textoRes">${data.message}</p>
            </div>
            <div class="conAdjunto2">
                <p class="adjunto">Archivos adjuntos:</p>
            </div>
            <div class="conArchivosRe">
                <p class="archivosRe">Falta de programar!!!!!</p>
            </div>
            <div class="contenedorAbajo">
                <div class="conUsuario">
                    <p><span style="font-weight: bold">Publicado por: </span><a href="/user/${data.author.id}">${data.author.username}</a></p>
                </div>
                <div class="parteDrc">
                   <span class="star"><i class="fa-solid fa-star"></i> ${data.favourites}</span>
                   <span class="up" onclick="upvotePost(this)"><i class="fa-solid fa-up"></i> ${data.upvotes}</span>
                </div>
            </div>
        </div>
    `;
}



function loadPost() {
    //get the id parameter from the url
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('id');
    if (!postId || postId.length < 1) {
        window.location.href = '/';
    }
    getPost(postId).then((response) => {
        return response.json();
    }).then((data) => {
        if (data.status === "success") {
            // Remove skeleton and add post to the parent of the skeleton
            let postSkeleton = document.getElementById("cajaPreguntaSkeleton");
            let postParent = postSkeleton.parentElement;
            //Generate a div with the class cajaPregunta
            let post = document.createElement("div");
            post.classList.add("cajaPregunta");
            post.innerHTML = createPost(data.data);
            //Replace the skeleton with the post
            postParent.replaceChild(post, postSkeleton);
        } else {
            showToast(data.message, "error", () => {
                window.location.href = "/";
            });
        }
    }).catch((error) => { showToast("Error desconocido", "error", () => {window.location.href = "/"; }); });

    getPostAnswers(postId).then((response) => {
        return response.json();
    }).then((data) => {
        let answersContainer = document.getElementById("contenedorRespuestas");
        if (data.status === "success") {
            // Empty the container
            answersContainer.innerHTML = "";
            // Add the answers
            if (data.data.answers.length > 0) {
            data.data.answers.forEach((answer) => {
                answersContainer.innerHTML += createPostAnswer(answer);
            });
            } else {
                answersContainer.innerHTML = `<h4 class="centrado">No hay respuestas aun en este post</h4>`;
            }
        }

    }).catch((error) => { showToast("Error desconocido obteniendo las respuestas", "error", () => {}); });
}

function upvotePost(event) {
    //Get the id of the answer via the parent that has the attribute answer-id
    console.log(event);
    const answerId = event.parentElement.parentElement.parentElement.getAttribute("answer-id");
    fetch(`/api/posts/manageAnswers`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            id: answerId,
            action: "upvote"
        })
    }).then((response) => {
        return response.json();
    }).then((data) => {
        if (data.status === "success") {
            showToast(data.message, "success", () => {});
        } else {
            showToast(data.message, "error", () => {});
        }
    }).catch((error) => { showToast("Error desconocido", "error", () => {}); });

}


window.addEventListener("load", () => {
    loadPost();
});