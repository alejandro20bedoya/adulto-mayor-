<!-- Modal -->
<div class="modal fade" id="modalFormAdultomayor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Programa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formAdultomayor" name="formAdultomayor" enctype="multipart/form-data" method="POST">
                            <!-- <form id="formPrograma" name="formPrograma" enctype="multipart/form-data" method="POST"> -->
                            <input type="hidden" id="adultoid" name="adultoid" value="">
                            <div class="modal-body">
                                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son
                                    obligatorios.
                                </p>
                                <hr>
                                <p class="text-primary">Datos del adulto mayor</p>
                            </div>

                            <div class="modal-body">
                                <label for="txtNumeroCedula">Numero de Cedula<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control valid validNumber" id="txtNumeroCedula"
                                    name="txtNumeroCedula" required="" maxlength="10"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <!-- <div class="modal-body">
                                <select class="form-select" id="txtNivelPrograma" name="txtNivelPrograma" required=""
                                    maxlength="6" aria-label="Default select example">
                                    <label for="txtNivelPrograma">Nivel</label>
                                    <option value="Tecnologo">Tecnologo</option>
                                    <option value="Complementario">Complementario</option>
                                    <option value="Tecnico">Técnico</option>
                                </select>
                            </div> -->

                            <div class="modal-body">
                                <label for="txtNombreAdulto">Nombre del adulto mayor <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNombreAdulto" name="txtNombreAdulto"
                                    required="">
                            </div>

                            <div class="modal-body">
                                <label for="txtEdadAdulto">Edad del adulto mayor<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control valid validNumber" id="txtEdadAdulto"
                                    name="txtEdadAdulto" required="" maxlength="10"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtFecha" class="form-label">Fecha <span class="required">*</span></label>
                                <input type="date"
                                    class="form-control"
                                    id="txtFecha"
                                    name="txtFecha"
                                    required>
                            </div>


                            <div class="modal-body">
                                <label for="txttelefono">telefono<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control valid validNumber" id="txttelefono"
                                    name="txttelefono" required="" maxlength="10"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="video" class="form-label">Subir video</label>
                                <input type="file"
                                    class="form-control"
                                    id="video" name="video"
                                    accept="video/*">
                            </div>

                            <!-- <div class="modal-body">
                                <select class="form-select" id="txtStatus" name="txtStatus" required=""
                                    maxlength="6" aria-label="Default select example">
                                    <label for="txtNivelPrograma">Nivel</label>
                                    <option value="1">Activo</option>
                                    <option value="0">Inativo</option>

                                </select>
                            </div> -->
                            <div class="modal-body">
                                <label for="txtVereda">Vereda <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtVereda" name="txtVereda"
                                    required="">
                            </div>

                            <P>NOMBRE DEL APODERANTE</P>

                            <div class="modal-body">
                                <label for="txtNombreApoderado">Nombre del apoderdaro <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNombreApoderado" name="txtNombreApoderado"
                                    required="">
                            </div>

                            <div class="modal-body">
                                <label for="txtNumeroCedulaApoderado">Numero de cedula del apoderado<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNumeroCedulaApoderado" name="txtNumeroCedulaApoderado"
                                    required="">
                            </div>

                            <div class="modal-body">
                                <label for="txtFechapago" class="form-label">Fecha <span class="required">*</span></label>
                                <input type="date"
                                    class="form-control"
                                    id="txtFechapago"
                                    name="txtFechapago"
                                    required>
                            </div>

                            <div class="modal-body">
                                <label for="txtEstado">Estados<span
                                        class="required">*</span></label>
                                <select class="form-select" id="txtEstado" name="txtEstado" required>
                                    <option value="">Seleccione...</option>
                                    <option value="1">Verificado</option>
                                    <option value="0">No verificado</option>
                                </select>
                            </div>

                            <div class="modal-body">
                                <label for="txtNumeroCiclos">Numero de ciclos<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNumeroCiclos" name="txtNumeroCiclos"
                                    required="">
                            </div>
                            <div class="modal-body">
                                <label for="txtDescripcion">Descripción</label>
                                <input type="text" class="form-control" id="txtDescripcion" name="txtDescripcion"
                                    required="">
                            </div>

                            <BR></BR>
                            <div class="modal-footer">
                                <button id="btnActionForm" class="btn btn-success" type="submit">
                                    <i class="bi bi-floppy"></i>
                                    <span id="btnText">Guardar</span></button>

                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><i
                                        class="bi bi-x-lg"></i>Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal para ver el video -->
<div class="modal fade" id="modalViewVideo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title">Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-2">
                <video id="videoTagPreview" controls style="width:100%; border-radius:6px;">
                    <source id="videoSource" src="" type="">
                </video>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewPrograma" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos del Adulto mayor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">


                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>ID:</td>
                                    <td id="celIdeAdulto">00000</td>
                                </tr>
                                <tr>
                                    <td>Cedula:</td>
                                    <td id="celCedulaAdulto">00000</td>
                                </tr>
                                <tr>
                                    <td>Nombre:</td>
                                    <td id="celNombreAdulto">Usuario</td>
                                </tr>
                                <tr>
                                    <td>Edad:</td>
                                    <td id="celEdadAdulto">55</td>
                                </tr>
                                <tr>
                                    <td>Fecha:</td>
                                    <td id="celFecha">fecha</td>
                                </tr>
                                <tr>
                                    <td>Telefono:</td>
                                    <td id="celTelefono">0000</td>
                                </tr>
                                <tr>
                                    <td>Tipo de video:</td>
                                    <td id="celVideo">Video</td>
                                </tr>
                                <tr>
                                    <td>Vereda:</td>
                                    <td id="celVereda">Vereda</td>
                                </tr>
                                <tr>
                                    <td>Zona:</td>
                                    <td id="celZona">Zona</td>
                                </tr>
                                <tr>
                                    <td>Apoderado:</td>
                                    <td id="celApoderado">Ususario</td>
                                </tr>
                                <tr>
                                    <td>cedula del apoderado:</td>
                                    <td id="celCedulaApoderado">00000</td>
                                </tr>
                                <tr>
                                    <td>Fecha de pago:</td>
                                    <td id="celFechapago">Fecha</td>
                                </tr>
                                <tr>
                                    <td>Estado:</td>
                                    <td id="celEstado">Estado</td>
                                </tr>
                                <tr>
                                    <td>Ciclo:</td>
                                    <td id="celCiclo">00</td>
                                </tr>
                                <tr>
                                    <td>Descripcion:</td>
                                    <td id="celDescripcion">Descripcion</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"><i
                                class="bi bi-check2"></i>Listo</button>
                    </div>

                </div>
            </div>
        </div>