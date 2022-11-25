let currentPage = 1;
let currentFormData = new FormData();
async function filterPosts(formData) {
    return fetch(`/api/posts/filterPosts`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: formData
    });
}

function postTemplate(post){
    //check if post has a post subobject
    let postObj = post.post !== undefined ? post.post : post;
    let author = postObj.author;
    let description = post.message ?? postObj.description;

    return `
    <div class="respuesta">
                <div class="contenedorLista">
                    <div class="contenedorIden">
                        <img src="${htmlEncode(author.avatar)}" alt="img Avatar" class="identificadoAva">
                    </div>
                    <div class="contenidoIzq">
                        <p class="tituPregunta overflow-1"><a class="unstyledLink" href="/post/${htmlEncode(postObj.id)}">${htmlEncode(postObj.title)}</a></p>
                        <p class="decripPre overflow-1">${htmlEncode(description)}</p>
                        <ul class="listContent">
                            <li><p class="autor">Publicado por: <a href="/user/${htmlEncode(author.id)}">${htmlEncode(author.username)}</p></a></li>
                            <li><p class="topics">${htmlEncode(postObj.topic.name)}</p></li>
                            <li><i class="fa-regular fa-eye" id="visitas"><span class="numisitas"> ${htmlEncode(postObj.views)}</span></i></li>
                        </ul>
                    </div> 
                    
                </div>
               <hr>
            </div>`

}

function showPostView() {
    let skeletonContainer = document.getElementById("skeletonContainer");
    let postView = document.getElementById("postsContainer");
    skeletonContainer.classList.add("hidden");
    postView.classList.remove("hidden");
}

function hidePostView() {
    let skeletonContainer = document.getElementById("skeletonContainer");
    let postView = document.getElementById("postsContainer");
    skeletonContainer.classList.remove("hidden");
    postView.classList.add("hidden");

}
function getPosts(reload = false) {
    if (reload) {
        hidePostView();
    }
    filterPosts(currentFormData).then(response => {
        return response.json();
    }).then(data => {
        if (data.status === "success") {
            let postsContainer = document.getElementById("contenedorRespuestas");
            if (reload){
                currentPage = 1
                postsContainer.innerHTML = "";
            }
            let posts = data.posts;
            posts.forEach(post => {
                console.log(post);
                postsContainer.innerHTML += postTemplate(post);
            });
            showPostView();
        } else {
            console.log(data);
            showToast("Error al cargar las preguntas", "error");
        }
    }).catch(error => {
        console.log(error);
        showToast("Error al cargar las preguntas", "error");
    });
}


window.addEventListener("load", () => {
    getPosts(true);
});