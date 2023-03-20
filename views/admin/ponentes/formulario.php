<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Personal</legend>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre del ponente" class="formulario__input" value="<?php echo $ponente->nombre ?? ""; ?>">
    </div>
    <div class="formulario__campo">
        <label for="apellido" class="formulario__label">Apellido</label>
        <input type="text" name="apellido" id="apellido" placeholder="Apellido del ponente" class="formulario__input" value="<?php echo $ponente->apellido ?? ""; ?>">
    </div>
    <div class="formulario__campo">
        <label for="ciudad" class="formulario__label">Ciudad</label>
        <input type="text" name="ciudad" id="ciudad" placeholder="Ciudad del ponente" class="formulario__input" value="<?php echo $ponente->ciudad ?? ""; ?>">
    </div>
    <div class="formulario__campo">
        <label for="pais" class="formulario__label">País</label>
        <input type="text" name="pais" id="pais" placeholder="País del ponente" class="formulario__input" value="<?php echo $ponente->pais ?? ""; ?>">
    </div>
    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input type="file" name="imagen" id="imagen" class="formulario__input formulario__input--file">
    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Extra </legend>
    <div class="formulario__campo">
        <label for="tags_input" class="formulario__label">Áreas de Experiencias   (Separar con una coma)</label>
        <input type="text" id="tags_input" placeholder="Ej. Node.js, PHP, CSS, Laravel, UX / UI" class="formulario__input">
        <div id="tags" class="formulario__listado"></div>
            <input type="hidden" name="tags" value="<?php echo $ponente->tags ?? ""; ?>">
    </div>
</fieldset>
<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Redes Sociales </legend>
    <div class="formulario__campo">

        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-facebook"></i>
            </div>
            <input type="text" name="redes[facebook]"  placeholder="Facebook" class="formulario__input--sociales" value="<?php echo $ponente->facebook ?? ""; ?>">
           
        </div>

        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-twitter"></i>
            </div>
            <input type="text" name="redes[twitter]"  placeholder="Twitter" class="formulario__input--sociales" value="<?php echo $ponente->twitter ?? ""; ?>">
           
        </div>

        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-youtube"></i>
            </div>
            <input type="text" name="redes[youtube]"  placeholder="Youtube" class="formulario__input--sociales" value="<?php echo $ponente->youtube ?? ""; ?>">
           
        </div>
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-instagram"></i>
            </div>
            <input type="text" name="redes[instagram]"  placeholder="Instagram" class="formulario__input--sociales" value="<?php echo $ponente->instagram ?? ""; ?>">
           
        </div>
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-tiktok"></i>
            </div>
            <input type="text" name="redes[tiktok]"  placeholder="Tiktok" class="formulario__input--sociales" value="<?php echo $ponente->tiktok ?? ""; ?>">
           
        </div>
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-github"></i>
            </div>
            <input type="text" name="redes[github]"  placeholder="Github" class="formulario__input--sociales" value="<?php echo $ponente->github ?? ""; ?>">
           
        </div>

    </div>
</fieldset>