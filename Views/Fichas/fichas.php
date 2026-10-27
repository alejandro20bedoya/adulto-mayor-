<?php
headerAdmin($data);
?>

<style>
    /* Input activo con color dentro */
    .form-control:focus {
        /* naranja claro */
        border-color: #fd7e14;
        /* naranja Bootstrap */
        box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
        color: #000;
    }

    P {
        font-size: 15px;
        font-weight: bold;
        color: #ff0000ff;
    }
</style>

<div id="contentAjax"></div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="bi bi-card-checklist"></i> <?= $data['page_title'] ?> </h1>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="bi bi-house"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/fichas"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>

    <div class="container-fluid mt-4">

        <div class="row">
            <div class="card-body">
                <div class="container d-flex justify-content-center">
                    <div class="col-md-8">

                        <?php if ($_SESSION['permisosMod']['w']) { ?>

                            <form id="adultomy" name="adultomy" enctype="multipart/form-data" method="POST">

                                <!-- Fila 1 -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cedula" class="form-label">cédula</label>
                                        <input type="text" class="form-control"
                                            id="cedula" name="cedula"
                                            placeholder="Ingrese número de cédula" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="form-label">Nombre completo</label>
                                        <input type="text" class="form-control"
                                            id="nombre" name="nombre"
                                            placeholder="Ingrese nombre completo" required>
                                    </div>
                                </div>

                                <!-- Fila 2 -->
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="edad" class="form-label">Edad</label>
                                        <input type="number"
                                            class="form-control text-center"
                                            id="edad" name="edad"
                                            placeholder="0" min="0" required>
                                    </div>

                                    <div class="col-md-9 mb-3">
                                        <label for="video" class="form-label">Subir video</label>
                                        <input type="file"
                                            class="form-control"
                                            id="video" name="video"
                                            accept="video/*" required>
                                    </div>
                                </div>

                                <!-- Fila 3 -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha" class="form-label">Fecha</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="telefono" class="form-label">Celular</label>
                                        <input type="number" class="form-control" id="telefono" name="telefono" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="vereda" class="form-label">Ubicacción</label>
                                        <input type="text" class="form-control" id="vereda" name="vereda" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="zona" class="form-label">Zona</label>
                                        <select class="form-select" id="zona" name="zona" required>
                                            <option value="">Seleccione una zona</option>
                                            <option value="Rural">Rural</option>
                                            <option value="Urbano">Urbano</option>
                                        </select>
                                    </div>
                                </div>

                                <P>NOMBRE DEL APODERADO DEL ADULTO MAYOR</P>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="apoderado" class="form-label">Nombre del apoderado</label>
                                        <input type="text" class="form-control"
                                            id="apoderado" name="apoderado" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="cedulaApoderado" class="form-label">cédula</label>
                                        <input type="number" class="form-control"
                                            id="cedulaApoderado" name="cedulaApoderado" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="fechapago" class="form-label">Fecha de pago</label>
                                        <input type="date" class="form-control" id="fechapago" name="fechapago" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="Estado" class="form-label">Estado</label>
                                        <select class="form-select" id="Estado" name="Estado" required>
                                            <option value="">Seleccione...</option>
                                            <option value="1">Verificado</option>
                                            <option value="0">No verificado</option>
                                        </select>
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="Ciclo" class="form-label">Numero de ciclo del mes </label>
                                        <input type="number" class="form-control"
                                            id="Ciclo" name="Ciclo" placeholder="0" min="0" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="Descripción" class="form-label">Descripción</label>
                                        <input type="text" class="form-control"
                                            id="descripcion" name="descripcion">
                                    </div>
                                </div>

                                <!-- Botón -->
                                <div class="row mt-3">
                                    <div class="col text-end">
                                        <button type="submit" class="btn btn-success px-3">
                                            Guardar información
                                        </button>
                                    </div>
                                </div>

                            </form>

                        <?php } ?>

                    </div>
                </div>


            </div>
        </div>

    </div>

</main>
<?php footerAdmin($data);
getModal('modalFichas', $data);
?>