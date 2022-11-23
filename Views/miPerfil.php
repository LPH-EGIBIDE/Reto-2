<div class="main">
    <div class="relleno">
        <div class="cajaPerfil">
            <div class="cajaPerfilUp">
                <img src="/api/attachments/id/<?= $profileUser->getAvatar()->getId() ?>" alt="img Avatar" id="avatar">
                <p class="nombreUsuario"><?=  $profileUser->getUsername(); ?></p>
                <p class="fechaMiembro">Miembro desde: <span class="fechaMi"> FECHA</span></p>
                <i class="fa-regular fa-trophy" id="rango"><span class="rank">RANKING</span></i>
            <div class="stats">
                <i class="fa-solid fa-star" id="star"><span class="Fav">FAVORITOS</span></i>
                <i class="fa-solid fa-up" id="up"><span class="upVotw">UPVOTE</span></i>
            </div>
            </div>
            <hr>
            <div class="cajaPerfilDown">
            <p class="ultimaPregunta">Ultima pregunta: <span class="ultPregunta">PREGUNTA</span></p>
            <div class="navPerfil">
                <ul class="navOpciones">
                    <li><a  id="linkPre">Preguntas</a></li>
                    <li><a  id="linkRes">Respuestas</a></li>
                    <li><a  id="linkFav">Favoritos</a></li>
                </ul>
            </div>
            </div>
        </div>

        <div class="contenidoLista">
            <div class="plantillaRespuestas">
            <div class="contenedorLista">
                <div class="contenedorIden">
                    <img src="fotoPerfilNone.jpg" alt="img Avatar" id="identificadoAva">
                </div>
                
                <div class="contenidoIzq">
                    <p class="tituPregunta">Titulo pregunta</p>
                    <p class="decripPre">Descripcion</p>
                    <ul class="listContent">
                        <li><p class="autor">Autor</p></li>
                        <li><p class="topics">Topico</p></li>
                        <li><i class="fa-regular fa-eye" id="visitas"><span class="numisitas">382</span></i></li>
                    </ul>
                </div> 
                
            </div>
           <hr>
        </div>
    
            <div class="contenedorBoton">
                <div class="separador">
                    <input type="button" value="Ver mas" id="mas">
                </div>
                
            </div>
        </div>
    </div>
</div>	