<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información del Evento</legend>
    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre del Evento</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre Evento" class="formulario__input" value="<?php echo $evento->nombre; ?>">
    </div>
    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">Descripción del Evento</label>
        <textarea name="descripcion" id="descripcion" placeholder="Descripción Evento" class="formulario__input" rows="6"><?php echo $evento->descripcion; ?></textarea>
    </div>
    <div class="formulario__campo">
        <label for="categoria" class="formulario__label">Categoría del Evento</label>
        <select class="formulario__select" id="categorias" name="categoria_id">
            <option value="">-- Seleccionar --</option>
            <?php
            foreach ($categorias as $categoria) { ?>
                <option <?php echo ($evento->categoria_id == $categoria->id)? "selected": "" ; ?> value="<?php echo $categoria->id; ?>"> <?php echo $categoria->nombre; ?></option>
            <?php }   ?>
        </select>
    </div>
    <div class="formulario__campo">
        <label for="categoria" class="formulario__label">Selecciona el día del Evento</label>

        <div class="formulario__radio">
            <?php foreach ($dias as $dia) { ?>
                <div>
                    <label for="<?php echo strtolower($dia->nombre); ?>"> <?php echo $dia->nombre; ?></label>
                    <input type="radio" name="dia" id="<?php echo strtolower($dia->nombre); ?>" value="<?php echo $dia->id; ?>">
                </div>

            <?php } ?>
        </div>
        <input type="hidden" name="dia_id" value="">

    </div>


    <div class="formulario__campo" id="horas">
        <label for="horas" class="formulario__label">Seleccionar Hora</label>
        <ul id="horas" class="horas">
            <?php foreach ($horas as $hora) { ?>
                
                <li class="horas__hora"><?php echo $hora->hora; ?></li>
            <?php } 
           ?>
        </ul>
    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Extra</legend>
    <div class="formulario__campo">
        <label for="ponentes" class="formulario__label">Ponentes del Evento</label>
        <input id="ponentes" placeholder="Buscar Ponente" class="formulario__input" >
    </div>
    <div class="formulario__campo">
        <label for="disponibles" class="formulario__label">Lugares Disponibles</label>
        <input type="number" min="1" name="disponibles" id="disponibles" placeholder="Ej. 20" class="formulario__input" value="<?php echo $evento->disponibles; ?>" >
    </div>

</fieldset>