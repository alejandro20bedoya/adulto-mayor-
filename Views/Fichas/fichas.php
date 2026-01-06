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
                <form id="adultomy" name="adultomy" enctype="multipart/form-data" method="POST">

                    <!-- Fila 1 -->
                    <div class="row justify-content-center">

                        <div class="col-md-3 mb-3">
                            <label for="cedula" class="form-label">Número de cédula</label>
                            <input type="text" class="form-control"
                                id="cedula" name="cedula"
                                placeholder="Ingrese número de cédula" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control"
                                id="nombre" name="nombre"
                                placeholder="Ingrese nombre completos" required>
                        </div>

                    </div>

                    <!-- Fila 2 (MISMA ESTRUCTURA) -->
                    <div class="row justify-content-center">

                        <!-- Edad alineada con cédula -->
                        <div class="col-md-3 mb-3">
                            <label for="edad" class="form-label">Edad</label>
                            <input type="number"
                                class="form-control form-control-sm text-center"
                                id="edad" name="edad"
                                placeholder="0" min="0" required
                                style="max-width: 80px;">
                        </div>

                        <!-- Video alineado con nombre -->
                        <div class="col-md-6 mb-3">
                            <label for="video" class="form-label">Subir video</label>
                            <input type="file"
                                class="form-control"
                                id="video" name="video"
                                accept="video/*" required>
                        </div>

                    </div>

                    <!-- Botón -->
                    <div class="row mt-1">
                        <div class="col-md-2 offset-md-9">
                            <button type="submit" class="btn btn-success px-1">
                                Guardar información
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>

</main>
<?php footerAdmin($data);
getModal('modalFichas', $data);
?>