async function getUsersPosts(userId) {
    return fetch(`/api/posts/getPosts`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            userId: userId
        })
    });
}

async function getUsersFavuriteAnswers(userId) {
    return fetch(`/api/posts/getPosts`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            userId: userId,
            method: 'userFavourites'
        })
    });
}

async function getUsersAnswers(userId) {
    return fetch(`/api/posts/getPosts`, {
        method: 'POST',
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            userId: userId,
            method: 'userAnswers'
        })
    });
}


function postTemplate(post){
    return `
    <div class="respuesta">
                <div class="contenedorLista">
                    <div class="contenedorIden">
                        <img src="${post.author.avatar}" alt="img Avatar" class="identificadoAva">
                    </div>
                    <div class="contenidoIzq">
                        <p class="tituPregunta">${post.title}</p>
                        <p class="decripPre">${post.description}</p>
                        <ul class="listContent">
                            <li><p class="autor">${post.author.username}</p></li>
                            <li><p class="topics">${post.topic.name}</p></li>
                            <li><i class="fa-regular fa-eye" id="visitas"><span class="numisitas">${post.views}</span></i></li>
                        </ul>
                    </div> 
                    
                </div>
               <hr>
            </div>`

}
