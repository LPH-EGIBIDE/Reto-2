<div class="principal">
    <div class="relleno">
    <h3 id="cajaTitulo">Buscar posts</h3>
        <div class="cajaBuscador">
            <form id="filterForm">
            <div id="textoTitulo" class="lista">
                <input type="text" name="title" placeholder="Título">
            </div>
            <div id="listaTopics" class="lista">
                <input list="topics" name="topics" placeholder="Género">
                <datalist id="topics">
                    <option value="Prueba"/>
                    <option value="La prueba"/>
                </datalist>
            </div>
            <div id="textoAutor" class="lista">
                <input type="text" name="author" placeholder="Autor">
            </div>
            <div id="listaOrden" class="lista">
                <input list="orderBy" name="orderBy" placeholder="Ordenar por">
                <datalist id="orderBy">
                    <option value="Más recientes" selected/>
                    <option value="Más upvotes"/>
                    <option value="Más visitas"/>
                    <option value="Menos recientes"/>
                    <option value="Menos upvotes"/>
                    <option value="Menos visitas"/>
                </datalist>
            </div>
        </div>
        <input type="button" value="Enviar" class="answerButton">
        </form>
    </div>
    <div id="postsContainer" class="contenidoLista">
            <div id="contenedorRespuestas">
            
    <div class="respuesta">
                <div class="contenedorLista">
                    <div class="contenedorIden">
                        <img src="//localhost/api/attachments/id/-1" alt="img Avatar" class="identificadoAva">
                    </div>
                    <div class="contenidoIzq">
                        <p class="tituPregunta overflow-1"><a class="unstyledLink" href="/post/1">¿Cómo enciendo mi ordenador?</a></p>
                        <p class="decripPre overflow-1">Necesito ayuda para encender mi ordenador, ya que es nuevo y no encuentro el botón para encenderlo. Muchas gracias de antemano.</p>
                        <ul class="listContent">
                            <li><p class="autor">Publicado por: <a href="/user/1">test</a></p></li>
                            <li><p class="topics">Telecomunicaciones</p></li>
                            <li><i class="fa-regular fa-eye" id="visitas"><span class="numisitas"> 152</span></i></li>
                        </ul>
                    </div> 
                    
                </div>
               <hr>
            </div></div>
            <div class="contenedorBoton">
                <div class="separador">
                    <button id="mas" onclick="morePosts()">Ver mas</button>
                </div>
                
            </div>
        </div>
</div>