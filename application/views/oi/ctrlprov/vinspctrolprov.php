<?php
    $idusu = $this -> session -> userdata('s_idusuario');
    $cusuario = $this -> session -> userdata('s_cusuario');
?>

<style>
    tab {
        display: inline-block; 
        margin-left: 100px; 
    }
    tr.subgroup,
    tr.subgroup:hover {
        background-color: #F2F2F2 !important;
        /* color: blue; */
        font-weight: bold;
    }
    .group{
            background-color:#CDD1DB !important;
            font-size:15px;
            color:#000000!important;
            opacity:0.7;
    }
    .subgroup{
        cursor: pointer;
    }
    td.subgroup {
        border-top: double !important;
    }
    td.expand {
        border-bottom: double !important;
    }
    .modal-lg{
        max-width: 1000px !important;
    }

    .dataTables_scrollBody thead{
        visibility:hidden;
    }
    .hidden {
        display: none;
    }
</style>

<!-- content-header -->
<div class="content-header">   
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">INSPECCIÓN - CTRL. PROV.</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo public_base_url(); ?>cprincipal/principal">Home</a></li>
          <li class="breadcrumb-item active">Ctrl. Prov.</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content" style="background-color: #E0F4ED;">
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">            
                        <ul class="nav nav-tabs bg-lightblue" id="tabctrlprov" role="tablist">                    
                            <li class="nav-item">
                                <a class="nav-link active" style="color: #000000;" id="tabctrlprov-list-tab" data-toggle="pill" href="#tabctrlprov-list" role="tab" aria-controls="tabctrlprov-list" aria-selected="true">LISTADO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: #000000;" id="tabctrlprov-det-tab" data-toggle="pill" href="#tabctrlprov-det" role="tab" aria-controls="tabctrlprov-det" aria-selected="false">CHECKLIST</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="tabctrlprov-tabContent">
                            <div class="tab-pane fade show active" id="tabctrlprov-list" role="tabpanel" aria-labelledby="tabctrlprov-list-tab">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">BUSQUEDA</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>                        
                                    <div class="card-body">
                                        <input type="hidden" name="cusuario" id="cusuario" value="<?php echo $cusuario ?>">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Clientes</label>
                                                    <select class="form-control select2bs4" id="cboclieserv" name="cboclieserv" style="width: 100%;">
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">    
                                                <div class="checkbox"><label>
                                                    <input type="checkbox" id="chkFreg" /> <b>Fecha Registro :: Del</b>
                                                </label></div>                        
                                                <div class="input-group date" id="txtFDesde" data-target-input="nearest" >
                                                    <input type="text" id="txtFIni" name="txtFIni" class="form-control datetimepicker-input" data-target="#txtFDesde" disabled/>
                                                    <div class="input-group-append" data-target="#txtFDesde" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">      
                                                <label>Hasta</label>                      
                                                <div class="input-group date" id="txtFHasta" data-target-input="nearest">
                                                    <input type="text" id="txtFFin" name="txtFFin" class="form-control datetimepicker-input" data-target="#txtFHasta" disabled/>
                                                    <div class="input-group-append" data-target="#txtFHasta" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Proveedor</label>
                                                    <select class="form-control select2bs4" id="cboprovxclie" name="cboprovxclie" style="width: 100%;">
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Maquilador</label>
                                                    <select class="form-control select2bs4" id="cbomaqxprov" name="cbomaqxprov" style="width: 100%;">
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Estado</label>
                                                    <select class="form-control select2bs4" id="cboestado" name="cboestado" multiple="multiple" data-placeholder="Seleccionar" style="width: 100%;">
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer justify-content-between" style="background-color: #E0F4ED;"> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-right">
                                                    <button type="submit" class="btn btn-primary" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-outline card-primary">
                                            <div class="card-header">
                                                <h3 class="card-title">Listado de Inspecciones - <label id="lblCliente"></label></h3>
                                            </div>                                       
                                            <div class="card-body">
                                                <table id="tblListinspctrlprov" class="table table-striped table-bordered" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="12">[Codigo] :: Proveedor - (Maquilador) - Establecimiento</th>
                                                    </tr>
                                                    <tr>
                                                        <th>desc_gral</th>
                                                        <th>Area Cliente</th>
                                                        <th>Linea Proceso</th>
                                                        <th>Periodo</th>
                                                        <th>Estado</th>
                                                        <th>Fecha</th>
                                                        <th>Informe</th>
                                                        <th>Resultado</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="tabctrlprov-det" role="tabpanel" aria-labelledby="tabctrlprov-det-tab">
                                <fieldset class="scheduler-border" id="regInspeccion">
                                    <legend class="scheduler-border text-primary">Reg. Checklist</legend>
                                    <form class="form-horizontal" id="frmRegInsp" action="<?= base_url('at/ctrlprov/cregctrolprov/setreginspeccion')?>" method="POST" enctype="multipart/form-data" role="form">
                                        <input type="hidden" name="mtxtidinsp" id="mtxtidinsp">
                                        <input type="hidden" name="mhdnzctipoestado" id="mhdnzctipoestado">
                                        <input type="hidden" name="mhdnccliente" id="mhdnccliente">
                                        <input type="hidden" name="mhdnAccioninsp" id="mhdnAccioninsp"> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">    
                                                    <label>DATOS</label>
                                                    <textarea class="form-control" cols="20" id="mtxtinspdatos" name="mtxtinspdatos" rows="2" disabled = true></textarea>
                                                    <textarea class="form-control" cols="20" name="mtxtinsparea"id="mtxtinsparea" rows="1" disabled = true></textarea>
                                                    <textarea class="form-control" cols="20" name="mtxtinsplinea"id="mtxtinsplinea" rows="1" disabled = true></textarea> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Periodo</label>
                                                    <input type="month" name="cboinspperiodo" id="cboinspperiodo" class="form-control" pattern="[0-9]{4}-[0-9]{2}" disabled = true>
                                                </div>
                                            </div>
                                            <div class="col-md-3">      
                                                <label>Fecha Inspeccion</label>                      
                                                <div class="input-group date" id="txtFInspeccion" data-target-input="nearest">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text"><a style="cursor:pointer;" onClick="javascript:quitarFecha()"><i class="far fa-calendar-times"></i></a></div>
                                                    </div>
                                                    <input type="text" id="txtFInsp" name="txtFInsp" class="form-control datetimepicker-input" data-target="#txtFInspeccion" disabled = true/>
                                                    <div class="input-group-append" data-target="#txtFInspeccion" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Sistema</label>
                                                    <select class="form-control select2bs4" id="cboinspsistema" name="cboinspsistema" style="width: 100%;" disabled = true>
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Rubro</label>                                                    
                                                    <select class="form-control select2bs4" id="cboinsprubro" name="cboinsprubro" style="width: 100%;" disabled = true>
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Check List</label>
                                                    <select class="form-control select2bs4" id="cboinspcchecklist" name="cboinspcchecklist" style="width: 100%;" disabled = true>
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 text-right"> 
                                                <button type="submit" class="btn btn-success" id="btnRegistrar"><i class="fas fa-save"></i> Grabar</button>    
                                                <button type="button" class="btn btn-secondary" id="btnRetornarLista"><i class="fas fa-undo-alt"></i> Retornar</button>
                                            </div>
                                        </div>
                                    </form>

                                </fieldset>
                                
                                <fieldset class="scheduler-border" >
                                    <legend class="scheduler-border text-primary">Listado Checklist</legend>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-outline card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Listado de Inspecciones - <label id="lblCliente"></label></h3>
                                                </div>                                       
                                                <div class="card-body">
                                                    <table id="tblinspeccionprov" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th>ITEM</th>
                                                            <th></th>
                                                            <th>REQUISITO</th>
                                                            <th>VALOR MAX</th>
                                                            <th>VALOR</th>
                                                            <th>CRITERIO DE HALLAZGO</th>
                                                            <th>HALLAZGO</th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>
                            </div>
                        </div>
                    </div>                
                </div>
            </div>
        </div>     
    </div>
</section>
<!-- /.Main content -->

<div class="modal fade" id="ModalItemList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registro de Checklist</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
            <div class="form-group" class="col-md-12">
                <label for="exampleFormControlSelect1">Puntuacion</label>
                <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>

             <div class="form-group" class="col-md-12">
                <label for="exampleFormControlSelect1">Crit. Hallazgo</label>
                <select class="form-control" id="exampleFormControlSelect1">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>

            <div class="form-group" class="col-md-12">
                <label for="exampleFormControlTextarea1">Hallazgo</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>


<!-- Script Generales -->
<script type="text/javascript">
    var baseurl = "<?php echo base_url();?>"; 
</script>