<div class="main">
    <div class="relleno">
        <div class="cambiaContrasena">
            <div class="avtarContra" onclick="changeAvatar()">
                <img src="/api/attachments/id/<?= $user->getAvatar()->getId() ?>" id="avatar" alt="img avatar">
                <p>Haz click sobre la imagen para editarla</p>
            </div>
            <form class="conCambiaContra" id="changePassword">

                <p>Nueva contraseña:</p>
                <input type="password" name="newPassword" class="nuevaContrasena">
                <p>Contraseña antigua:</p>
                <input type="password" name="oldPassword" class="viejaContrasena">
                <div class="conBoton">
                    <input type="submit" value="Cambiar" class="cambiarContraBoton">
                </div>
                
            </form>
            <form class="conDescripcion" id="changeDescription">
                <p>Descripción</p>
                <label>
                    <textarea rows="10" class="textAreaDes" name="description"><?= $user->getProfileDescription() ?></textarea>
                </label>
                <div class="conBoton">
                    <input type="submit" value="Editar" class="eviarDescrip">
                </div>
                
            </form>
        </div>
    </div>
</div>	