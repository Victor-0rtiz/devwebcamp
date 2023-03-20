<main class="auth">
    <h2 class="auth__heading">
        <?php echo $titulo; ?>
    </h2>
    <p class="auth__texto">
        Tu cuenta de DevWebCamp
    </p>
    <?php
    include_once __DIR__ . "./../templates/alertas.php";
    ?>
    <?php
    if (isset($alertas["exito"])) { ?>
        <div class="acciones acciones--centrado">
            <a href="/login" class="acciones__enlace">Inicia sesion aquí</a>

        </div>
    <?php
    }
    ?>



</main>