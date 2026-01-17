<?php
headerAdmin($data);
?>
<style>
    #modalVideo .modal-content {
        width: 480px;
        /* Modal más pequeño */
        border-radius: 12px;
    }

    #modalVideo video {
        max-height: 300px;
        /* Controla el tamaño del video */
        object-fit: contain;
    }
</style>

<div id="contentAjax"></div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="bi bi-list-stars"></i> <?= $data['page_title'] ?></h1>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="bi bi-house"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/adultomayor"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tableAdultoMayor">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">Numero de cedula</th>
                                    <th class="text-center">Nombre completo</th>
                                    <th class="text-center">Edad</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Telefono</th>
                                    <th class="text-center">video</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php footerAdmin($data);
getModal('modalProgramas', $data);
?>