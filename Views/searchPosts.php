<div class="principal">
    <div class="relleno">
        <h3 id="cajaTitulo">Buscar posts</h3>
        <div>
            <form id="filterForm" class="cajaBuscador">
                <div id="textoTitulo" class="lista">
                    <input type="text" name="title" placeholder="Título">
                </div>
                <div id="listaTopics" class="lista">
                    <input list="topics" name="topics" placeholder="Género">
                    <datalist id="topics">
                        <option value="Prueba">
                        </option><option value="La prueba">
                        </option></datalist>
                </div>
                <div id="textoAutor" class="lista">
                    <input type="text" name="author" placeholder="Autor">
                </div>
                <div id="listaOrden" class="lista">
                    <input list="orderBy" name="orderBy" placeholder="Ordenar por">
                    <datalist id="orderBy">
                        <option value="Más recientes" selected="">
                        </option><option value="Más upvotes">
                        </option><option value="Más visitas">
                        </option><option value="Menos recientes">
                        </option><option value="Menos upvotes">
                        </option><option value="Menos visitas">
                        </option></datalist>
                </div>
                <input type="button" value="Enviar" class="answerButton">
            </form></div>
    </div>
    <div id="skeletonContainer" class="contenidoLista">
        <div class="plantillaRespuestas">
            <div class="contenedorLista">
                <div class="contenedorIden">
                    <div class="c-skeleton__figure c-skeleton__text--bigger identificadoAva"></div>
                </div>

                <div class="contenidoIzq">
                    <div class="c-skeleton__text c-skeleton__text--title c-skeleton__text--small-height tituPregunta"></div>
                    <div class="c-skeleton__text c-skeleton__text--bigger "></div>
                    <div class="c-skeleton__text c-skeleton__text--medium"></div>
                </div>

            </div>
            <hr>
        </div>

        <div class="plantillaRespuestas">
            <div class="contenedorLista">
                <div class="contenedorIden">
                    <div class="c-skeleton__figure c-skeleton__text--bigger identificadoAva"></div>
                </div>

                <div class="contenidoIzq">
                    <div class="c-skeleton__text c-skeleton__text--title c-skeleton__text--small-height tituPregunta"></div>
                    <div class="c-skeleton__text c-skeleton__text--bigger "></div>
                    <div class="c-skeleton__text c-skeleton__text--medium"></div>
                </div>

            </div>
            <hr>
        </div><div class="plantillaRespuestas">
            <div class="contenedorLista">
                <div class="contenedorIden">
                    <div class="c-skeleton__figure c-skeleton__text--bigger identificadoAva"></div>
                </div>

                <div class="contenidoIzq">
                    <div class="c-skeleton__text c-skeleton__text--title c-skeleton__text--small-height tituPregunta"></div>
                    <div class="c-skeleton__text c-skeleton__text--bigger "></div>
                    <div class="c-skeleton__text c-skeleton__text--medium"></div>
                </div>

            </div>
            <hr>
        </div><div class="plantillaRespuestas">
            <div class="contenedorLista">
                <div class="contenedorIden">
                    <div class="c-skeleton__figure c-skeleton__text--bigger identificadoAva"></div>
                </div>

                <div class="contenidoIzq">
                    <div class="c-skeleton__text c-skeleton__text--title c-skeleton__text--small-height tituPregunta"></div>
                    <div class="c-skeleton__text c-skeleton__text--bigger "></div>
                    <div class="c-skeleton__text c-skeleton__text--medium"></div>
                </div>

            </div>
            <hr>
        </div><div class="plantillaRespuestas">
            <div class="contenedorLista">
                <div class="contenedorIden">
                    <div class="c-skeleton__figure c-skeleton__text--bigger identificadoAva"></div>
                </div>

                <div class="contenidoIzq">
                    <div class="c-skeleton__text c-skeleton__text--title c-skeleton__text--small-height tituPregunta"></div>
                    <div class="c-skeleton__text c-skeleton__text--bigger "></div>
                    <div class="c-skeleton__text c-skeleton__text--medium"></div>
                </div>

            </div>
            <hr>
        </div><div class="contenedorBoton">
            <div class="separador">
                <input type="button" value="Ver mas" id="mas">
            </div>

        </div>
    </div>
    <div id="postsContainer" class="contenidoLista hidden">
            <div id="contenedorRespuestas">
            </div>
            <div class="contenedorBoton">
                <div class="separador">
                    <button id="mas" onclick="morePosts()">Ver mas</button>
                </div>
                
            </div>
        </div>
</div>