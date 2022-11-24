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

function hiddenPostAnswer() { 
    return `
        <div class="cajaRespuesta" id="agregarRespuesta">
            <div class="answerForm">
                <form>
                    <p class="respuestaTitu">Añadir respuesta</p>
                    <textarea name="text" placeholder="Escriba aquí" oninput='this.style.height = ""; this.style.height = this.scrollHeight + "px"'></textarea>
                    <p class="respuestaFile">Añadir archivos</p>
                <div class="contenedorAbajo">
                    <input type="file">
                <div class="answerButtons">
                    <input class=answerButton type="button" value="Enviar">
                    <input class=answerButton type="reset" placeholder="Borrar">
                </div>
            </div>
        </form>
        </div>
    `;
}


function createPost(data) {
    return `
            <div class="conTags overflow-1">
                <p class="tags">${data.topic.name}</p>
            </div>
            <div class="conPreguntaTitu centrado">
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
    let attachmentList = "";
    data.attachments.forEach((attachment) => {
        attachmentList += `<li><i class="fa-solid fa-file"></i><a target="_blank" href="/api/attachments/id/${attachment.id}"> ${attachment.filename}</a></li>`;
    });

    return `
        <div class="cajaRespuesta" answer-id="${data.id}">
            <div class="conTextoRes">
                <p class="textoRes">${data.message}</p>
            </div>
            <div class="conAdjunto2">
                <p class="adjunto">Archivos adjuntos:</p>
            </div>
            <div class="conArchivosRe">
                <div class="listaAdjuntos">
                  <ul class="listaFicheros">
                    ${attachmentList}
                  </ul>
                </div>
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
    const postId = window.location.pathname.split("/")[2];
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
            // Delete all but hiddenAnswer
            answersContainer.innerHTML = hiddenPostAnswer();
            // Add the answers
            if (data.data.answers.length > 0) {
            data.data.answers.forEach((answer) => {
                answersContainer.innerHTML += createPostAnswer(answer);
            });
            } else {
                answersContainer.innerHTML = hiddenPostAnswer();
                answersContainer.innerHTML += `<h4 class="centrado">No hay respuestas aun en este post</h4>`;
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

function switchHiddenAnswer( ) {
    $answer = document.getElementById("agregarRespuesta");
    if ($answer.style.display === "none") {
        $answer.style.display = "block";
        document.getElementById("anadir").value = "Ocultar";
    }
    else {
        $answer.style.display = "none";
        document.getElementById("anadir").value = "Añadir";
    }
}
