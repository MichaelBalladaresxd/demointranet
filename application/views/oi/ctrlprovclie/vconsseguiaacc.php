<?php
    $idusu  = $this -> session -> userdata('s_idusuario');
    $cusu   = $this -> session -> userdata('s_cusuario');
    $cclie  = $this -> session -> userdata('s_ccliente');  
?>

<style>    
    .fullModal {
        margin: 0 !important;
        margin-right: auto !important;
        margin-left: auto !important;
        width: 100% !important;
      }
      @media (min-width: 576px) {
        .fullModal {
            width: 700px !important;
            max-width: 700px !important;
        }
      }
      @media (min-width: 768px) {
        .fullModal {
            width: 800px !important;
            max-width: 800px !important;
        }
      }
      @media (min-width: 992px) {
        .fullModal {
            width: 1070px !important;
            max-width: 1070px !important;
        }
      }
      @media (min-width: 1200px) {
        .fullModal {
            width: 1300px !important;
            max-width: 1300px !important;
        }
      }
      @media (min-width: 1500px) {
        .fullModal {
            width: 1500px !important;
            max-width: 1500px !important;
        }
    
    }
</style>

<!-- content-header -->
<div class="content-header">   
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Seguimiento de Acciones Correctivas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo public_base_url(); ?>cpanel"> <i class="fas fa-tachometer-alt"></i>Home</a></li>
          <li class="breadcrumb-item active">Ctrl. Prov.</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">            
                        <ul class="nav nav-tabs" id="tabhallazgocalif" style="background-color: #17a2b8;" role="tablist">                    
                            <li class="nav-item">
                                <a class="nav-link active" style="color: #000000;" id="tabhallazgocalif-list-tab" data-toggle="pill" href="#tabhallazgocalif-list" role="tab" aria-controls="tabhallazgocalif-list" aria-selected="true">LISTADO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: #000000;" id="tabhallazgocalif-det-tab" data-toggle="pill" href="#tabhallazgocalif-det" role="tab" aria-controls="tabhallazgocalif-det" aria-selected="false">DETALLE</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="tabhallazgocalif-tabContent">
                            <div class="tab-pane fade show active" id="tabhallazgocalif-list" role="tabpanel" aria-labelledby="tabhallazgocalif-list-tab">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">BUSQUEDA</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>

                                    <form class="form-horizontal" id="frmconsseguiaacc" name="frmconsseguiaacc" action="<?= base_url('oi/ctrlprovclie/cctrlprovclieExport/excelconsseguiaacc')?>" method="POST" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <input type="hidden" class="form-control" id="hdnIdUsuario" name="hdnIdUsuario" value="<?php echo $idusu ?>">
                                            <input type="hidden" class="form-control" id="hdnCUsuario" name="hdnCUsuario" value="<?php echo $cusu ?>">
                                            <input type="hidden" class="form-control" id="hdnCCliente" name="hdnCCliente" value="<?php echo $cclie ?>">
                                        </div>
                                        
                                        <div class="row">      
                                            <div class="col-md-4">
                                                <label>&nbsp;&nbsp;</label> 
                                                <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="rdPeriodo" name="rFbuscar" checked>
                                                    <label for="rdPeriodo">
                                                        Por Periodo
                                                    </label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="rdFechas" name="rFbuscar" >
                                                    <label for="rdFechas">
                                                        Entre Fechas
                                                    </label>
                                                </div>
                                                </div>
                                                <input type="hidden" class="form-control" id="hrdbuscar" name="hrdbuscar" value="P">
                                            </div>   
                                            <div class="col-md-2">
                                                <div class="form-group" id="divAnio"> 
                                                    <label>Año</label>
                                                    <select class="form-control" id="cboAnio" name="cboAnio" style="width: 100%;">
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" id="divDesde">    
                                                    <label>Desde</label>                      
                                                    <div class="input-group date" id="txtFDesde" data-target-input="nearest">
                                                        <input type="text" id="txtFIni" name="txtFIni" class="form-control datetimepicker-input" data-target="#txtFDesde" />
                                                        <div class="input-group-append" data-target="#txtFDesde" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" id="divMes">
                                                    <label>Mes</label>
                                                    <select class="form-control" id="cboMes" name="cboMes" style="width: 100%;">
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" id="divHasta">       
                                                    <label>Hasta</label>                      
                                                    <div class="input-group date" id="txtFHasta" data-target-input="nearest">
                                                        <input type="text" id="txtFFin" name="txtFFin" class="form-control datetimepicker-input" data-target="#txtFHasta"/>
                                                        <div class="input-group-append" data-target="#txtFHasta" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Área del Cliente</label>
                                                    <select class="form-control select2bs4" id="cboareaclie" name="cboareaclie" style="width: 100%;">
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
                                                    <button type="button" class="btn btn-primary" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
                                                    <button type="submit" class="btn btn-info" id="btnexcel" disabled="true"><i class="fa fw fa-file-excel-o"></i> Exportar Excel</button>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div> 
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h3 class="card-title"><b>LISTADO DE SEGUIMIENTO DE AACC</b></h3>
                                            </div>                                       
                                            <div class="card-body" style="overflow-x: scroll;">
                                                <table id="tblconsseguiaacc" class="table table-striped table-bordered compact" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Categoria</th>
                                                        <th>Nro. de Proveedores</th>
                                                        <th>Nro. de Inspecciones</th>
                                                        <th>Inspecciones con Acciones Correctivas Concluídas</th>
                                                        <th>% de Inspecciones con Acciones Correctivas</th>
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
                            <div class="tab-pane fade" id="tabhallazgocalif-det" role="tabpanel" aria-labelledby="tabhallazgocalif-det-tab">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">BUSQUEDA</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div> 
                                    
                                    <form class="form-horizontal" id="frmconsexcelconsdetseguiaacc" name="frmconsexcelconsdetseguiaacc" action="<?= base_url('oi/ctrlprovclie/cctrlprovclieExport/excelconsdetseguiaacc')?>" method="POST" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <input type="hidden" class="form-control" id="hddnmdetccliente" name="hddnmdetccliente">
                                        <input type="hidden" class="form-control" id="hddnmdetanio" name="hddnmdetanio">
                                        <input type="hidden" class="form-control" id="hddnmdetmes" name="hddnmdetmes">  
                                        <input type="hidden" class="form-control" id="hddnmdetfini" name="hddnmdetfini"> 
                                        <input type="hidden" class="form-control" id="hddnmdetffin" name="hddnmdetffin">  
                                        <input type="hidden" class="form-control" id="hddnmdetarea" name="hddnmdetarea">                                       
                                        <div class="row">      
                                            <div class="col-md-6">
                                                <label>Archivo de AA.CC.</label> 
                                                <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="rdTodosarchivo" name="rFiltrar" checked>
                                                    <label for="rdTodosarchivo">
                                                        Todos
                                                    </label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="rdSiarchivo" name="rFiltrar" >
                                                    <label for="rdSiarchivo">
                                                        Si
                                                    </label>
                                                </div>
                                                <div class="icheck-primary d-inline">
                                                    <input type="radio" id="rdNoarchivo" name="rFiltrar" >
                                                    <label for="rdNoarchivo">
                                                        No
                                                    </label>
                                                </div>
                                                </div>
                                            </div>               
                                        </div>   
                                    </div>
                                    <div class="card-footer justify-content-between" style="background-color: #E0F4ED;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-right">
                                                    <button type="button" class="btn btn-secondary" id="btnRetornarLista"><i class="fas fa-undo-alt"></i> Retornar</button>
                                                    <button type="submit" class="btn btn-info" id="btnexcelDet" disabled="true"><i class="fa fw fa-file-excel-o"></i> Exportar Excel</button>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div> 
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Detalle de Seguimiento de AACC</h3>
                                            </div>                                       
                                            <div class="card-body" style="overflow-x: scroll;">
                                                <table id="tbldetseguiaacc" class="table table-striped table-bordered compact" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Proveedor</th>
                                                        <th>Linea de Proceso</th>
                                                        <th>Nro. de Informe</th>
                                                        <th>Resultado (%)</th>
                                                        <th>Fecha Envio Acc. Corr.</th>
                                                        <th>Acc. Corr. Concluidas</th>
                                                        <th>Acc. Corr. por Realizar</th>
                                                        <th>Total Acc. Corr.</th>
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
                        </div>
                    </div>                
                </div>
            </div>
        </div>     
    </div>
</section>
<!-- /.Main content -->


<!-- /.Modal-Listado-AACC --> 
<div class="modal fade" id="modalAACC" data-backdrop="static" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg fullModal">
    <div class="modal-content" id="contenedorListaacc">      

        <div class="modal-header text-center bg-lightblue">
            <h4 class="modal-title w-100 font-weight-bold">Listado AACC</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">         
            <div class="row"> 
                <div class="col-12" style="overflow-x: scroll;">
                    <table id="tbllistaacc" class="table table-striped table-bordered compact" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Requisito</th>
                            <th>Excluyente</th>
                            <th>Tipo de Hallazgo</th>
                            <th>Hallazgo</th>
                            <th>Acción Correctiva</th>
                            <th>Responsable por Cliente</th>
                            <th>Fecha Corrección</th>
                            <th>Aceptar Acción</th>
                            <th>Comentarios</th>
                            <th>Valorado</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal-footer">  

        </div>
                
    </div>
  </div>
</div> 
<!-- /.Modal-->
