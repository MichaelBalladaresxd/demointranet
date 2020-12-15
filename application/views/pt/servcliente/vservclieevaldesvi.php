<?php
    $idusu = $this -> session -> userdata('s_idusuario');
    $codusu = $this -> session -> userdata('s_cusuario');
    $infousuario = $this->session->userdata('s_infodato');
    $ccliente = $this->session->userdata('s_ccliente');
?>

<style>
</style>

<!-- content-header -->
<div class="content-header">   
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">EVALUACIÓN DE DESVIACIONES</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo public_base_url(); ?>cprincipal/principalClie">Home</a></li>
          <li class="breadcrumb-item active">Gestion Procesos Termicos</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content" style="background-color: #E0F4ED;">
    <div class="container-fluid"> 
        <input type="hidden" id="hdnccliente" name="hdnccliente" value="<?php echo $ccliente ?>">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">ESTUDIOS</h3>
                    </div>                
                    <div class="card-body">
                        <table id="tblListEstudios" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>DESCRIPCION DEL ACUERDO</th>
                                <th>TIPO DE CONCLUSION</th>
                                <th>PRODUCTO</th>
                                <th>DIMENSIONES</th>
                                <th># DEVCAL</th>
                                <th>TIPO EQUIPO</th>
                                <th># ID</th>
                                <th></th>
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
</section>
<!-- /.Main content -->

<script type="text/javascript">
    var baseurl = "<?php echo base_url();?>"; 
</script>