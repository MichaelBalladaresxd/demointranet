<?php
    $idusu = $this -> session -> userdata('s_idusuario');
    $cusuario = $this -> session -> userdata('s_cusuario');
?>

<style>
    tab {
        display: inline-block; 
        margin-left: 30px; 
    }
    tr.subgroup,
    tr.subgroup:hover {
        background-color: #F2F2F2 !important;
        /* color: blue; */
        font-weight: bold;
    }
    .group{
        background-color:#D5D8DC !important;
        font-size:15px;
        color:#000000!important;
        opacity:0.8;
        cursor: pointer;
    }
    .subgroup{
        cursor: pointer;
        font-weight: normal !important;
        font-size: 15px !important;
    }

    .btn-circle {
        width: 45px;
        height: 45px;
        line-height: 45px;
        text-align: center;
        padding: 0;
        border-radius: 50%;
    }
    
    .btn-circle i {
        position: relative;
        top: -1px;
    }

    .btn-circle-sm {
        width: 35px;
        height: 35px;
        line-height: 35px;
        font-size: 0.9rem;
    }

    .btn-circle-lg {
        width: 55px;
        height: 55px;
        line-height: 55px;
        font-size: 1.1rem;
    }

    .btn-circle-xl {
        width: 70px;
        height: 70px;
        line-height: 70px;
        font-size: 1.3rem;
    }

    .fileUpload {
        position: relative;
        overflow: hidden;
        margin: 0px;
    }
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }
    
    .dropdown-item:hover{
        border-color: #0067ab;
        background-color: #e83e8c !important;
    }

    td.details-control {
        background: url('<?php echo public_base_url(); ?>assets/images/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.details td.details-control {
        background: url('<?php echo public_base_url(); ?>assets/images/details_close.png') no-repeat center center;
    }
</style>

<!-- content-header -->
<div class="content-header">   
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">INGRESO DE RESULTADOS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo public_base_url(); ?>main"> <i class="fas fa-tachometer-alt"></i>Home</a></li>
          <li class="breadcrumb-item active">Laboratorio</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content" id="contenedorCotizacion" style="background-color: #E0F4ED;">
    <div class="container-fluid">  
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline card-tabs">
                    <div class="card-header p-0 pt-1 border-bottom-0">            
                        <ul class="nav nav-tabs" id="tablab" style="background-color: #2875A7;" role="tablist">                    
                            <li class="nav-item">
                                <a class="nav-link active" style="color: #000000;" id="tablab-list-tab" data-toggle="pill" href="#tablab-list" role="tab" aria-controls="tablab-list" aria-selected="true">LISTADO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: #000000;" id="tablab-reg-tab" data-toggle="pill" href="#tablab-reg" role="tab" aria-controls="tablab-reg" aria-selected="false">REGISTRO</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="tablab-tabContent">
                            <div class="tab-pane fade show active" id="tablab-list" role="tabpanel" aria-labelledby="tablab-list-tab">                                
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">BUSQUEDA</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>   
                                    <form class="form-horizontal" id="frmbuscarservlab" name="frmbuscarservlab" action="<?= base_url('lab/resultados/cregresultExport/excelservlab')?>" method="POST" enctype="multipart/form-data" role="form">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Clientes</label>
                                                    <select class="form-control select2bs4" id="cboclieserv" name="cboclieserv" style="width: 100%;">
                                                        <option value="" selected="selected">Cargando...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Buscar por</label>
                                                <div>
                                                    <select class="form-control" id="cbobuspor" name="cbobuspor">
                                                        <option value="C" selected="selected">Cotización</option>
                                                        <option value="T">Orden Trabajo</option>
                                                        <option value="I">Informe</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2"> 
                                                <label id="lblbuscar" name="lblbuscar">Nro. Cotizacion</label> 
                                                <div>
                                                    <input type="text" id="txtbuscarnro" name="txtbuscarnro" class="form-control"  onkeypress="pulsarListarCoti(event)"/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">    
                                                <div class="checkbox"><label>
                                                    <input type="checkbox" id="chkFreg" /> <b>Desde</b>
                                                </label></div>                        
                                                <div class="input-group date" id="txtFDesde" data-target-input="nearest" >
                                                    <input type="text" id="txtFIni" name="txtFIni" class="form-control datetimepicker-input" data-target="#txtFDesde" disabled/>
                                                    <div class="input-group-append" data-target="#txtFDesde" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">      
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
                                            <div class="col-md-4"> 
                                                <label>Nombre Producto/Muestra</label> 
                                                <div>
                                                    <input type="text" id="txtdescri" name="txtdescri" class="form-control"  onkeypress="pulsarListarCoti(event)"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4"> 
                                                <label>Nombre/Codigo Ensayo</label> 
                                                <div>
                                                    <input type="text" id="txtensayo" name="txtensayo" class="form-control"  onkeypress="pulsarListarCoti(event)"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Area de Servicio</label>
                                                    <select class="form-control select2bs4" id="cboareaserv" name="cboareaserv" multiple="multiple" data-placeholder="Seleccionar" style="width: 100%;">
                                                        <option value="%" selected="selected">Todos</option>
                                                        <option value="M">Microbiología</option>
                                                        <option value="F">Fisicoquímica</option>
                                                        <option value="I">Instrumental</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                
                                                
                                    <div class="card-footer justify-content-between"> 
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-right">
                                                    <button type="button" class="btn btn-primary" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button> 
                                                    <button type="submit" class="btn btn-success" id="btnexcel" disabled="true"><i class="far fa-file-excel"></i> Exportar Excel</button>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-outline card-primary">
                                            <div class="card-header">
                                                <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class="card-title"><b>LISTADO DE SERVICIOS LABORATORIO</b></h3>
                                                </div>                                                 
                                                <!--<div class="col-md-4 text-right">
                                                    <button id="btn-show-all-children" type="button">Expandir</button>
                                                    <button id="btn-hide-all-children" type="button">Contraer</button>
                                                </div>  /.Main content -->
                                                </div>                                                
                                            </div>                                        
                                            <div class="card-body">
                                                <table id="tblListServiciolab" class="table table-striped table-bordered compact" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Cliente</th>
                                                        <th>Cotización</th>
                                                        <th>Orden Trabajo</th>
                                                        <th>Producto</th>
                                                        <th>N° Muestra</th>
                                                        <th>Local</th>
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
                            <div class="tab-pane fade" id="tablab-reg" role="tabpanel" aria-labelledby="tablab-reg-tab">
                                <fieldset class="scheduler-border-fsc" id="regCoti">
                                    <legend class="scheduler-border-fsc text-primary">DATOS DEL SERVICIO</legend>
                                    <input type="hidden" name="txtidcotizacion" class="form-control" id="txtidcotizacion">
                                    <input type="hidden" name="txtnroversion" class="form-control" id="txtnroversion">
                                    <input type="hidden" name="txtidordenservicio" class="form-control" id="txtidordenservicio">
                                    <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-info">Cliente</div>
                                            <div>
                                                <input type="text" id="txtcliente" name="txtcliente" class="form-control" disabled = true/>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="text-info">Fecha Analisis</div>
                                            <div class="input-group date" id="mtxtFanalisis" data-target-input="nearest">
                                            <input type="text" id="mtxtFanali" name="mtxtFanali" class="form-control datetimepicker-input" data-target="#mtxtFanalisis"/>
                                            <div class="input-group-append" data-target="#mtxtFanalisis" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            </div>
                                        </div>  
                                        <div class="col-sm-3">
                                            <div class="text-info">Hora Analisis</div>
                                            <div class="input-group date" id="mtxtHanalisis" data-target-input="nearest">
                                            <input type="text" id="mtxtHanali" name="mtxtHanali" class="form-control datetimepicker-input" data-target="#mtxtHanalisis"/>
                                            <div class="input-group-append" data-target="#mtxtHanalisis" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="far fa-clock"></i></div>
                                            </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-info">Cotizacion</div>
                                            <div>
                                                <input type="text" id="txtcotizacion" name="txtcotizacion" class="form-control" disabled = true/>
                                            </div> 
                                        </div>
                                        <div class="col-md-2">
                                            <div class="text-info">Fecha Coti.</div>
                                            <div>
                                                <input type="text" id="txtfcotizacion" name="txtfcotizacion" class="form-control" disabled = true/>
                                            </div> 
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-info">OT</div>
                                            <div>
                                                <input type="text" id="txtnroot" name="txtnroot" class="form-control" disabled = true/>
                                            </div> 
                                        </div>
                                        <div class="col-md-2">
                                            <div class="text-info">Fecha OT</div>
                                            <div>
                                                <input type="text" id="txtfot" name="txtfot" class="form-control" disabled = true/>
                                            </div> 
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row" style="background-color: #dff0d8;">                                                         
                                        <div class="col-6 text-left">
                                            <button type="button" class="btn btn-secondary" id="btnRetornarLista"><i class="fas fa-undo-alt"></i> Retornar</button>
                                        </div>                                                          
                                        <div class="col-6 text-right">
                                            <button type="button" class="btn btn-success" id="btngrabarResult"><i class="fas fa-clipboard-list"></i> Grabar</button>
                                            <button type="button" class="btn btn-info" id="btnverinformes"><i class="fas fa-clipboard-list"></i> Inf. Ensayo</button>
                                        </div>    
                                    </div>
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border-fsc" id="regCoti">
                                    <legend class="scheduler-border-fsc text-primary">INGRESO RESULTADOS</legend>
                                    <div class="form-group">
                                        <div class="row"> 
                                            <div class="col-12">
                                                <div class="card card-outline card-primary">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Listado de Resultados</h3>
                                                    </div>                                        
                                                    <div class="card-body" style="overflow-x: scroll;">                                                        
                                                        <div class="row">                                                         
                                                            <div class="col-12"> 
                                                            <table id="tblListResultados" class="table table-striped table-bordered compact" style="width:100%">
                                                                <thead>
                                                                <tr>
                                                                    <th>N°</th>
                                                                    <th>Tipo Ensayo</th>
                                                                    <th>Cod. Muestra</th>
                                                                    <th>Muestra</th>
                                                                    <th>Cod. Ensayo</th>
                                                                    <th>Ensayo</th>
                                                                    <th>Unidad</th>
                                                                    <th>Especificación</th>
                                                                    <th>Resultado</th>
                                                                    <th>Conclusion</th>
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


