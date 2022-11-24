
<div class="main">
    <div class="relleno">
        <div class="cajaRespuesta" id="agregarRespuesta">
                <form class="answerForm">
                    
                    <div class="contenedorArriba">
                    <div class="select">
                    <p class="tagsPre">Tags:
                    <select name="tags" id="tags">
                        <option value="valor1">valor1</option>
                        <option value="valor2">valor2</option>
                    </select>
                    </p>
                    </div>
                    <div class="contenedorPregunta">
                        <p class="añadirPre">Añadir pregunta</p>
                        <input class=questionText type="text" placeholder="Escriba aquí">
                    </div>
                    </div>

                    <div class="contenedorMedio">
                        <p class="añadirDes">Añadir descripción</p>
                        <textarea name="text" placeholder="Escriba aquí" oninput='this.style.height = ""; this.style.height = this.scrollHeight + "px"'></textarea>
                    </div>
                        
                    <div class="questionButtons">
                        <input class=questionButton type="button" value="Enviar">
                        <input class=questionButton type="reset" placeholder="Borrar">
                    </div>
                </form>
          </div>
        </div>
    </div>
</div>