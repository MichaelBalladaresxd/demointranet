<?php
    $idusu  = $this -> session -> userdata('s_idusuario');
    $cusu   = $this -> session -> userdata('s_cusuario');
    $cclie  = $this -> session -> userdata('s_ccliente');  
?>

<style>
</style>

<!-- content-header -->
<div class="content-header">   
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Consulta Checklist</h1>
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
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><b>BUSQUEDA</b></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
          
            <form class="form-horizontal" id="frmconschecklist" name="frmconschecklist" action="<?= base_url('oi/ctrlprovclie/cctrlprovclieExport/excelconschecklist')?>" method="POST" enctype="multipart/form-data" role="form">
            <div class="card-body">
                <input type="hidden" class="form-control" id="hdnIdUsuario" name="hdnIdUsuario" value="<?php echo $idusu ?>">
                <input type="hidden" class="form-control" id="hdnCUsuario" name="hdnCUsuario" value="<?php echo $cusu ?>">
                <input type="hidden" class="form-control" id="hdnCCliente" name="hdnCCliente" value="<?php echo $cclie ?>">
            </div>             
                        
            <div class="card-footer justify-content-between"> 
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
                        <h3 class="card-title"><b>LISTADO DE CHECKLIST</b></h3>
                    </div>
                
                    <div class="card-body" style="overflow-x: scroll;">
                        <table id="tblconschecklist" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Check List Descripción</th>
                                <th>Servicio</th>
                                <th>Sistema</th>
                                <th>Organismo</th>
                                <th>Rubro</th>
                                <th>Uso</th>
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
</section>
<!-- /.Main content -->

<!-- /.modal-leyenda--> 
<div class="modal fade" id="modalDetalle" data-backdrop="static" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg fullModal">
    <div class="modal-content">
        <div class="modal-header text-center bg-success">
            <h4 class="modal-title w-100 font-weight-bold">Vista del Checklist</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form class="form-horizontal" id="frmdetchecklist" name="frmdetchecklist" action="<?= base_url('oi/ctrlprovclie/cctrlprovclieExport/exceldetchecklist')?>" method="POST" enctype="multipart/form-data" role="form">
        <div class="modal-body"> 
            <input type="hidden" class="form-control" id="hddnmdetcchecklist" name="hddnmdetcchecklist">
            <input type="hidden" class="form-control" id="hddnmdetdchecklist" name="hddnmdetdchecklist">
            <div class="form-group">  
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="text-info text-center"> <label id="nameChkl" name="nameChkl"></label></div>
                    </div> 
                </div>     
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-left">
                            <button type="reset" class="btn btn-secondary" id="btnCerrar" data-dismiss="modal"><i class="fas fa-undo-alt"></i> Retornar</button>
                            <button type="submit" class="btn btn-info" id="btnexcelDet" disabled="true"><i class="fa fw fa-file-excel-o"></i> Exportar Excel</button>  
                        </div>
                    </div>
                </div>        
                <div class="row"> 
                    <div class="col-12" style="overflow-x: scroll;">
                        <table id="tbllistchecklist" class="table table-striped table-bordered compact" style="width:100%">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Aspecto Evaluado</th>
                                <th>Valor Maximo</th>
                                <th>Normativa</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>           
        </div>

        <div class="modal-footer justify-content-between" style="background-color: #dff0d8;">
        </div>
    </div>
  </div>
</div> 
<!-- /.modal-->