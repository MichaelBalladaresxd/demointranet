<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cinforme extends CI_Controller {
	function __construct() {
		parent:: __construct();	
		$this->load->model('pt/minforme');
		$this->load->model('pt/mtramites');
		
		$this->load->model('mglobales');
		$this->load->library('encryption');
		$this->load->helper(array('form','url','download','html','file'));
		$this->load->library('form_validation');
    }
    
   /** INFORME **/	
   	public function getServicio() {	// Visualizar Servicios en CBO	        
		$resultado = $this->minforme->getServicio();
		echo json_encode($resultado);
	}
    public function getclienteinfor() {	// Visualizar Clientes con informes en CBO	
        
		$resultado = $this->minforme->getclienteinfor();
		echo json_encode($resultado);
	}
    public function getbuscarinforme() {	// Recupera Listado de Informes	
        
		$varnull 			= 	'';

		$fini       = $this->input->post('fdesde');
		$ffin       = $this->input->post('fhasta');
		$dnropropu    = $this->input->post('dnropropu');
		$dnroinfor    = $this->input->post('dnroinfor');
		$vigente    = $this->input->post('vigente');
            
        $parametros = array(
			'@cservicio'    => ($this->input->post('cservicio') == '%') ? '0' : $this->input->post('cservicio'),
			'@fini'         => ($this->input->post('fdesde') == '%') ? NULL : substr($fini, 6, 4).'-'.substr($fini,3 , 2).'-'.substr($fini, 0, 2),
			'@ffin'         => ($this->input->post('fhasta') == '%') ? NULL : substr($ffin, 6, 4).'-'.substr($ffin,3 , 2).'-'.substr($ffin, 0, 2),
			'@ccliente'     => $this->input->post('ccliente'),
			'@dnropropu'    => ($this->input->post('dnropropu') == $varnull) ? '%' : "%".$dnropropu."%",
			'@dnroinfor'    => ($this->input->post('dnroinfor') == $varnull) ? '%' : "%".$dnroinfor."%",
			'@vigente'      => $vigente,
		);		
		$resultado = $this->minforme->getbuscarinforme($parametros);
		echo json_encode($resultado);
	}
    public function getpropuevaluar() {	// Visualizar NRO propuestas a evaluar en CBO	
		
		$parametros = array(
            '@IDCLIE'   => $this->input->post('ccliente')
        );
		$resultado = $this->minforme->getpropuevaluar($parametros);
		echo json_encode($resultado);
	}
    public function getproputramsid() {	// Visualizar NRO propuestas a evaluar en CBO	
		
		$parametros = array(
            '@IDCLIE'   => $this->input->post('ccliente')
        );
		$resultado = $this->minforme->getproputramsid($parametros);
		echo json_encode($resultado);
	}
    public function getservicioevaluar() {	// Visualizar Servicios a evaluar en CBO	
		
		$parametros = array(
            '@IDPTPROPU'   => $this->input->post('idptpropu')
        );
		$resultado = $this->minforme->getservicioevaluar($parametros);
		echo json_encode($resultado);
	}
    public function setevaluacion() { // Registrar evaluacion PT
        $varnull = '';
        
        $parametros = array(
            '@idptevaluacion'   =>  $this->input->post('hdnIdpteval'),
            '@idptpropuesta'    =>  $this->input->post('cboRegPropu'),
            '@accion'           =>  $this->input->post('hdnAccionpteval')
        );
        $retorna = $this->minforme->setevaluacion($parametros);
        echo json_encode($retorna);		
    }
    public function getnroinforme() {	// Obtener numero de informe	
		
		$parametros = array(
            '@yearPropu'   => $this->input->post('yearPropu')
        );
		$resultado = $this->minforme->getnroinforme($parametros);
		echo json_encode($resultado);
	}
    public function setinforme() { // Registrar informe PT
		$varnull = '';
		
		$finfor = $this->input->post('mtxtFinfor');
        
        $parametros = array(
            '@idptinforme'   	=>  $this->input->post('mhdnIdInfor'),
            '@idptevaluacion'   =>  $this->input->post('mhdnIdpteval'),
            '@nro_informe'      =>  $this->input->post('mtxtNroinfor'),
            '@fecha_informe'    =>  substr($finfor, 6, 4).'-'.substr($finfor,3 , 2).'-'.substr($finfor, 0, 2),
            '@idresponsable'    =>  $this->input->post('mcboContacInfor'),
            '@descripcion'      =>  $this->input->post('mtxtDetaInfor'),
            '@accion'           =>  $this->input->post('mhdnAccionInfor')
        );
        $retorna = $this->minforme->setinforme($parametros);
        echo json_encode($retorna);		
    }
	public function subirArchivo() {	// Subir Acrhivo 
        $IDINFOR    = $this->input->post('mhdnIdInfor');
        $ANIO       = substr($this->input->post('mtxtFinfor'),-4);
        $NOMBARCH 	= 'INF'.substr($this->input->post('mtxtNroinfor'),0,4).substr($this->input->post('mtxtNroinfor'),5,4).'PTFS';
        
        $RUTAARCH   = 'FTPfileserver/Archivos/10202/'.$ANIO.'/';

        !is_dir($RUTAARCH) && @mkdir($RUTAARCH, 0777, true);

		//RUTA DONDE SE GUARDAN LOS FICHEROS
		$config['upload_path'] 		= $RUTAARCH;
		$config['allowed_types'] 	= 'pdf|xlsx|docx|xls|doc';
		$config['max_size'] 		= '60048';
		$config['overwrite'] 		= TRUE;
		$config['file_name']        = $NOMBARCH;
		
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		
		if (!($this->upload->do_upload('mtxtArchivoinfor'))) {
			//si al subirse hay algun error 
			$data['uploadError'] = $this->upload->display_errors();
			$error = '';
			return $error;					
		}else{
			$data = $this->upload->data();
			$parametros = array(
                '@idptinforme'   	=>  $IDINFOR,
                '@propu_archivo'    =>  $data['file_name'],
                '@archivo_informe'  =>  $config['upload_path'],
                '@descripcion_archivo'   =>  $this->input->post('mtxtNomarchinfor'),
            );
            $retorna = $this->minforme->subirArchivo($parametros);
            echo json_encode($retorna);
		}	
	}
    public function setinformeedit() { // Registrar informe PT
		$varnull = '';
		
		$finfor = $this->input->post('mtxtFinforedit');
        
        $parametros = array(
            '@idptinforme'   	=>  $this->input->post('mhdnIdInforedit'),
            '@idptevaluacion'   =>  $this->input->post('mhdnIdptevaledit'),
            '@nro_informe'      =>  $this->input->post('mtxtNroinforedit'),
            '@fecha_informe'    =>  substr($finfor, 6, 4).'-'.substr($finfor,3 , 2).'-'.substr($finfor, 0, 2),
            '@idresponsable'    =>  $this->input->post('mcboContacInforedit'),
            '@descripcion'      =>  $this->input->post('mtxtDetaInforedit'),
            '@accion'           =>  $this->input->post('mhdnAccionInforedit')
        );
        $retorna = $this->minforme->setinforme($parametros);
        echo json_encode($retorna);		
    }	
	public function subirArchivoEdit() {	// Subir Acrhivo 
        $IDINFOR    = $this->input->post('mhdnIdInforedit');
        $ANIO       = substr($this->input->post('mtxtFinforedit'),-4);
        $NOMBARCH 	= 'INF'.substr($this->input->post('mtxtNroinforedit'),0,4).substr($this->input->post('mtxtNroinforedit'),5,4).'PTFS';
        
        $RUTAARCH   = 'FTPfileserver/Archivos/10202/'.$ANIO.'/';

        !is_dir($RUTAARCH) && @mkdir($RUTAARCH, 0777, true);

		//RUTA DONDE SE GUARDAN LOS FICHEROS
		$config['upload_path'] 		= $RUTAARCH;
		$config['allowed_types'] 	= 'pdf|xlsx|docx|xls|doc';
		$config['max_size'] 		= '60048';
		$config['overwrite'] 		= TRUE;
		$config['file_name']        = $NOMBARCH;
		
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		
		if (!($this->upload->do_upload('mtxtArchivoinforedit'))) {
			//si al subirse hay algun error 
			$data['uploadError'] = $this->upload->display_errors();
			$error = '';
			return $error;					
		}else{
			$data = $this->upload->data();
			$parametros = array(
                '@idptinforme'   	=>  $IDINFOR,
                '@propu_archivo'    =>  $data['file_name'],
                '@archivo_informe'  =>  $config['upload_path'],
                '@descripcion_archivo'   =>  $this->input->post('mtxtNomarchinforedit'),
            );
            $retorna = $this->minforme->subirArchivo($parametros);
            echo json_encode($retorna);
		}	
	}
	public function delinforme() {	// Eliminar de informe	
		$idptinforme = $this->input->post('idptinforme');	
		$resultado = $this->minforme->delinforme($idptinforme);
		echo json_encode($resultado);
	}
    public function getlistinforme() {	// Recupera Listado de Reg. informes        
		$varnull 			= 	'';
            
        $parametros = array(
			'@idptevaluacion' => $this->input->post('idptevaluacion')
		);		
		$resultado = $this->minforme->getlistinforme($parametros);
		echo json_encode($resultado);
	}
    public function gettramiteSid() {	// Recupera Listado de Reg. informes	
        
        $idpttramite = $this->input->post('idpttramite');	
		$resultado = $this->minforme->gettramiteSid($idpttramite);
		echo json_encode($resultado);
	}
    public function settramite() {	// Registrar tramite PT        
        $varnull = '';
		
		$ftram = $this->input->post('mtxtFtram');
        $parametros = array(
            '@idpttramite'   	=>  $this->input->post('mhdnIdTram'),
            '@id_tipotramite'   =>  $this->input->post('mcboTipotram'),
            '@idptpropuesta'    =>  $this->input->post('mcboNropropu'),
            '@fecha_tramite'    =>  substr($ftram, 6, 4).'-'.substr($ftram,3 , 2).'-'.substr($ftram, 0, 2),
            '@idresponsable'    =>  $this->input->post('mcboRespon'),
            '@codigo'           =>  $this->input->post('mtxtCodigo'),
            '@nombproducto'     =>  $this->input->post('mtxtNombprod'),
            '@descripcion'      =>  $this->input->post('mtxtDescrip'),
            '@comentarios'      =>  $this->input->post('mtxtComentario'),
            '@idinforme'      	=>  $this->input->post('mhdnIdinforme'),
            '@accion'           =>  $this->input->post('mhdnAccionTram')
        );
        $retorna = $this->minforme->settramite($parametros);
        echo json_encode($retorna);
    }	
    public function getlistregistro() {	// Recupera Listado de Registros	
         
        $parametros = array(
			'@idptinforme' => $this->input->post('idinforme'),
			'@idptservicio' => $this->input->post('idptservicio')
		);		
		$resultado = $this->minforme->getlistregistro($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregequi() {	// Recupera datos del equipo	
         
        $parametros = array(
			'@idptregequipo' => $this->input->post('idptregequipo')
		);		
		$resultado = $this->minforme->getrecuperaregequi($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregproduc() {	// Recupera datos del producto	
        
		$parametros = array(
			'@idptregproducto' => $this->input->post('idptregproducto')
		);		
		$resultado = $this->minforme->getrecuperaregproduc($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregproducequi() { // Recupera datos del producto y equipo
        
		$parametros = array(
			'@idptregproducto' => $this->input->post('idptregproducto')
		);		
		$resultado = $this->minforme->getrecuperaregproducequi($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregrecinto() { // Recupera datos del producto y equipo
        
		$parametros = array(
			'@idptregrecinto' => $this->input->post('idptregrecinto')
		);		
		$resultado = $this->minforme->getrecuperaregrecinto($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregrecintoproduc() { // Recupera datos del producto y equipo
        
		$parametros = array(
			'@idptregrecinto' => $this->input->post('idptregrecinto')
		);		
		$resultado = $this->minforme->getrecuperaregrecintoproduc($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregequiproduc() { // Recupera datos del producto y equipo
        
		$parametros = array(
			'@idptregequipo' => $this->input->post('idptregequipo')
		);		
		$resultado = $this->minforme->getrecuperaregequiproduc($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregestu() { // Recupera datos del producto y equipo
        
		$parametros = array(
			'@idptregprocestudio' => $this->input->post('idptregprocestudio')
		);		
		$resultado = $this->minforme->getrecuperaregestu($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregestuproduc() { // Recupera datos del producto y equipo
        
		$parametros = array(
			'@idptregprocestudio' => $this->input->post('idptregprocestudio')
		);		
		$resultado = $this->minforme->getrecuperaregestuproduc($parametros);
		echo json_encode($resultado);
	}
    public function getrecuperaregestuproducequi() { // Recupera datos del producto y equipo
        
		$parametros = array(
			'@idptregprocestudio' => $this->input->post('idptregprocestudio')
		);		
		$resultado = $this->minforme->getrecuperaregestuproducequi($parametros);
		echo json_encode($resultado);
	}
    public function getEstudio() {	// Visualizar los Estudios de Registro	
		
		$parametros = array(
            '@IDSERV'   => $this->input->post('cservicio')
        );
		$resultado = $this->minforme->getEstudio($parametros);
		echo json_encode($resultado);
	}
    public function setregistro() { // Grabar Registros PT
		$varnull = '';

		$accion = $this->input->post('hdnAccionptreg');
		
		$idptregistro = $this->input->post('hdnIdptreg');
		$idptinforme = $this->input->post('hdnIdreginfor');
		$RegEstudio = $this->input->post('hdnIdRegEstudio');
		$ptclase = $RegEstudio;
		
		
		if ($RegEstudio == 1){

			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregequipo'    	=>  $this->input->post('hdnIdregequipo'),
				'@descripcion_equipo'   =>  $this->input->post('txtDescriequipoReg01'),
				'@id_tipoequipo'    	=>  $this->input->post('cboTipoequipoReg01'),
				'@id_mediocalienta'    	=>  $this->input->post('cboMediocalientaReg01'),
				'@id_equipofabricante'  =>  $this->input->post('cboFabricanteReg01'),
				'@nro_equipos'    		=>  $this->input->post('txtNroequipoReg01'),
				'@nro_canastillas'    	=>  $this->input->post('txtNracanastReg01'),
				'@identificacion'    	=>  $this->input->post('txtIdenequipoReg01'),
				'@id_envase'    	=>  $this->input->post('cboEnvaseReg01'),
				'@diametro'    	=>  $this->input->post('txtDiamReg01'),
				'@altura'    	=>  $this->input->post('txtAltuReg01'),
				'@grosor'    	=>  $this->input->post('txtGrosReg01'),
				'@accion'           	=>  $accion,
			);
			$retorna = $this->minforme->setregestudio01($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 3){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'   	=>  $this->input->post('txtNombprodReg03'),
				'@id_tipoproducto'    	=>  $this->input->post('cboTipoprodReg03'),
				'@ph_materia_prima'     =>  $this->input->post('txtPHmatprimaReg03'),
				'@ph_producto_final'  	=>  $this->input->post('txtPHprodfinReg03'),
				'@particulas'       	=>  $this->input->post('cbollevapartReg03'),
				'@id_siparticula'   	=>  $this->input->post('cboParticulasReg03'),
				'@id_siparticula_liquido'	=>  $this->input->post('txtLiqgobReg03'),
				'@id_envase'     		=>  $this->input->post('cboEnvaseReg03'),
				'@nroprocal'  			=>  $this->input->post('txtProcalReg03'),
				'@dimension'       		=>  $this->input->post('cboDimenReg03'),
				'@diametro'     		=>  $this->input->post('txtDiamReg03'),
				'@altura'  				=>  $this->input->post('txtAltuReg03'),
				'@grosor'       		=>  $this->input->post('txtGrosReg03'),
				'@idptregequipo'    	=>  $this->input->post('hdnIdregequipo'),
				'@descripcion_equipo'   =>  $this->input->post('txtDescriequipoReg02'),
				'@id_tipoequipo'    	=>  $this->input->post('cboTipoequipoReg02'),
				'@id_mediocalienta'     =>  $this->input->post('cboMediocalientaReg02'),
				'@id_equipofabricante'  =>  $this->input->post('cboFabricanteReg02'),
				'@identificacion'       =>  $this->input->post('txtIdenequipoReg02'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio03($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 6){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'   	=>  $this->input->post('txtNombprodReg06'),
				'@id_tipoproducto'    	=>  $this->input->post('cboTipoprodReg06'),
				'@ph_materia_prima'     =>  $this->input->post('txtPHmatprimaReg06'),
				'@ph_producto_final'  	=>  $this->input->post('txtPHprodfinReg06'),
				'@particulas'       	=>  $this->input->post('cbollevapartReg06'),
				'@id_siparticula'   	=>  $this->input->post('cboParticulasReg06'),
				'@nro_llenadoras'		=>  $this->input->post('txtNrollenaReg06'),
				'@volumen_llenado'     	=>  $this->input->post('txtVolullenaReg06'),
				'@dimension'       		=>  $this->input->post('cboDimenReg06'),
				'@diametro'     		=>  $this->input->post('txtDiamReg06'),
				'@altura'  				=>  $this->input->post('txtAltuReg06'),
				'@grosor'       		=>  $this->input->post('txtGrosReg06'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio06($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 8){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'   	=>  $this->input->post('txtNombprodReg08'),
				'@id_tipoproducto'    	=>  $this->input->post('cboTipoprodReg08'),
				'@ph_materia_prima'     =>  $this->input->post('txtPHmatprimaReg08'),
				'@ph_producto_final'  	=>  $this->input->post('txtPHprodfinReg08'),
				'@particulas'       	=>  $this->input->post('cbollevapartReg08'),
				'@id_siparticula'   	=>  $this->input->post('cboParticulasReg08'),
				'@id_envase'     		=>  $this->input->post('cboEnvaseReg08'),
				'@dimension'       		=>  $this->input->post('cboDimenReg08'),
				'@diametro'     		=>  $this->input->post('txtDiamReg08'),
				'@altura'  				=>  $this->input->post('txtAltuReg08'),
				'@grosor'       		=>  $this->input->post('txtGrosReg08'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio08($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 9){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregrecinto'    	=>  $this->input->post('hdnIdregrecinto'),
				'@id_tiporecinto'   	=>  $this->input->post('cboTiporecintoReg09'),
				'@eval_recinto'    		=>  $this->input->post('cboevaluacionReg09'),
				'@nro_recintos'     	=>  $this->input->post('txtnrorecintosReg09'),
				'@area_evaluada'  		=>  $this->input->post('txtareaevalReg09'),
				'@nro_posiciones'       =>  $this->input->post('txtNroposReg09'),
				'@vol_almacen'   		=>  $this->input->post('txtNrovolalmaReg09'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio09($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 10){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregequipo'    	=>  $this->input->post('hdnIdregequipo'),
				'@nro_equipos'   		=>  $this->input->post('txtnrorecintosReg10'),
				'@area_evaluada'    	=>  $this->input->post('txtareaevalReg10'),
				'@nro_posiciones'     	=>  $this->input->post('txtNroposReg10'),
				'@vol_almacen'  		=>  $this->input->post('txtNrovolalmaReg10'),
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'      =>  $this->input->post('txtNombprodReg10'),
				'@id_siparticula'   	=>  $this->input->post('cboFormaprodReg10'),
				'@id_envase'     		=>  $this->input->post('cboEnvaseReg10'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio10($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 11){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregprocestudio'   =>  $this->input->post('hdnIdregprocestudio'),
				'@tipo_equirecinto'   	=>  $this->input->post('cboRecintoReg11'),
				'@identificacion'    	=>  $this->input->post('txtIdenequipoReg11'),
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'      =>  $this->input->post('txtNombprodReg11'),
				'@id_siparticula'   	=>  $this->input->post('cboFormaprodReg11'),
				'@id_envase'     		=>  $this->input->post('cboEnvaseReg11'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio11($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 12){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregrecinto'    	=>  $this->input->post('hdnIdregrecinto'),
				'@id_tiporecinto'   	=>  $this->input->post('cboRecintoReg12'),
				'@area_evaluada'    	=>  $this->input->post('txtareaevalReg12'),
				'@id_mediocalienta'     =>  $this->input->post('cboMediocalReg12'),
				'@nro_recintos'  		=>  $this->input->post('txtnrorecintosReg12'),
				'@nro_coches'       	=>  $this->input->post('txtnrocochesReg12'),
				'@identificacion'   	=>  $this->input->post('txtIdenrecintoReg12'),
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'      =>  $this->input->post('txtnombproductoReg12'),
				'@id_siparticula'   	=>  $this->input->post('cboPresentaReg12'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio12($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 13){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregequipo'    	=>  $this->input->post('hdnIdregequipo'),
				'@id_tipoequipo'    	=>  $this->input->post('cboTipoequipoReg13'),
				'@id_equipofabricante'  =>  $this->input->post('cboFabricanteReg13'),
				'@capacidad'  			=>  $this->input->post('txtCapacidadReg13'),
				'@id_mediocalienta'     =>  $this->input->post('cboMediocalReg13'),
				'@nro_equipos'   		=>  $this->input->post('txtnrorecintosReg13'),
				'@nro_canastillas'     	=>  $this->input->post('txtnrocochesReg13'),
				'@identificacion'       =>  $this->input->post('txtIdenequipoReg13'),
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'     	=>  $this->input->post('txtnombproductReg13'),
				'@id_siparticula'  		=>  $this->input->post('cboPresentaReg13'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio13($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 14){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregprocestudio'   =>  $this->input->post('hdnIdregprocestudio'),
				'@descripcion_estudio'  =>  $this->input->post('txtDescriocurridoReg14'),
				'@tipo_conclusion'    	=>  $this->input->post('cboTipoconcluReg14'),
				'@comentarios'     		=>  $this->input->post('txtComentocurridoReg14'),
				'@idptregproducto'    	=>  $this->input->post('hdnIdregproducto'),
				'@nombre_producto'   	=>  $this->input->post('txtNombprodReg14'),
				'@lotes'    			=>  $this->input->post('txtLotesReg14'),
				'@id_tipoproducto'     	=>  $this->input->post('cboTipoprodReg14'),
				'@ph_materia_prima'  	=>  $this->input->post('txtPHmatprimaReg14'),
				'@ph_producto_final'  	=>  $this->input->post('txtPHprodfinReg14'),
				'@particulas'       	=>  $this->input->post('cbollevapartReg14'),
				'@id_siparticula'   	=>  $this->input->post('cboParticulasReg14'),
				'@id_siparticula_liquido'	=>  $this->input->post('txtLiqgobReg14'),
				'@id_envase'     		=>  $this->input->post('cboEnvaseReg14'),
				'@nroprocal'  			=>  $this->input->post('txtDevcalReg14'),
				'@dimension'       		=>  $this->input->post('cboDimenReg14'),
				'@diametro'     		=>  $this->input->post('txtDiamReg14'),
				'@altura'  				=>  $this->input->post('txtAltuReg14'),
				'@grosor'       		=>  $this->input->post('txtGrosReg14'),
				'@idptregequipo'    	=>  $this->input->post('hdnIdregequipo'),
				'@descripcion_equipo'   =>  $this->input->post('txtDescriequipoReg14'),
				'@id_tipoequipo'    	=>  $this->input->post('cboTipoequipoReg14'),
				'@id_mediocalienta'     =>  $this->input->post('cboMediocalientaReg14'),
				'@id_equipofabricante'  =>  $this->input->post('cboFabricanteReg14'),
				'@identificacion'       =>  $this->input->post('txtIdenequipoReg14'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio14($parametros);
			echo json_encode($retorna);	
		} elseif ($RegEstudio == 15){
			$parametros = array(
				'@idptregistro'   		=>  $idptregistro,
				'@idptinforme'    		=>  $idptinforme,
				'@idptregestudio'   	=>  $ptclase,
				'@idptregprocestudio'   =>  $this->input->post('hdnIdregprocestudio'),
				'@idservicio'		   	=>  $this->input->post('cboserviciosReg15'),
				'@descripcion_equipo'   =>  $this->input->post('txtEquiposReg15'),
				'@nombre_producto'     	=>  $this->input->post('txtProdLineaReg15'),
				'@accion'           	=>  $accion
			);
			$retorna = $this->minforme->setregestudio15($parametros);
			echo json_encode($retorna);	
		}

        	
    }
    public function setregistroAdjunto() { // Grabar Registros PT
		$varnull = '';		
		$RegAdjunto = $this->input->post('mhdnRegAdjunto');

		if ($RegAdjunto == 2){
			$parametros = array(
				'@idptregequipo'    	=>  $this->input->post('mhdnIdptregequipo02'),
				'@idptregistro'   		=>  $this->input->post('mhdnIdptreg02'),
				'@clase_registro'   	=>  'E',
				'@idptregproducto'    	=>  $this->input->post('mhdnIdptregprod02'),
				'@descripcion_equipo'   =>  $this->input->post('txtDescriequipoReg02'),
				'@id_tipoequipo'    	=>  $this->input->post('cboTipoequipoReg02'),
				'@id_mediocalienta'     =>  $this->input->post('cboMediocalientaReg02'),
				'@id_equipofabricante'  =>  $this->input->post('cboFabricanteReg02'),
				'@identificacion'       =>  $this->input->post('txtIdenequipoReg02'),
				'@accion'           	=>  $this->input->post('mhdnAccionReg02'),
			);
			$retorna = $this->minforme->setregestudio02($parametros);
			echo json_encode($retorna);	
		} elseif ($RegAdjunto == 4){
			$parametros = array(
				'@idptregequipo'    	=>  $this->input->post('idptregequipo'),
				'@idptregistro'   		=>  $this->input->post('idptregistro'),
				'@idptregproducto'    	=>  $this->input->post('idptregproducto'),
				'@clase_registro'   	=>  $this->input->post('clase_registro'),
				'@descripcion_equipo'   =>  $this->input->post('descripcion_equipo'),
				'@id_tipoequipo'    	=>  $this->input->post('id_tipoequipo'),
				'@id_equipofabricante'  =>  $this->input->post('id_equipofabricante'),
				'@identificacion'       =>  $this->input->post('identificacion'),
				'@accion'           	=>  $this->input->post('cserviaccionio'),  
			);
			$retorna = $this->minforme->setregestudio04($parametros);
			echo json_encode($retorna);	
		} elseif ($RegAdjunto == 5){
			$parametros = array(				
				'@idptregequipo'    	=>  $this->input->post('idptregequipo'),
				'@idptregistro'   		=>  $this->input->post('idptregistro'),
				'@idptregproducto'    	=>  $this->input->post('idptregproducto'),
				'@clase_registro'   	=>  'L',
				'@descripcion_equipo'   =>  $this->input->post('descripcion_equipo'),
				'@id_tipoequipo'    	=>  $this->input->post('id_tipoequipo'),
				'@id_equipofabricante'  =>  $this->input->post('id_equipofabricante'),
				'@modelo_equipo'       	=>  $this->input->post('modelo_equipo'),
				'@identificacion'       =>  $this->input->post('identificacion'),
				'@nro_equipos'       	=>  $this->input->post('nro_equipos'),
				'@volumen_llenado'      =>  $this->input->post('volumen_llenado'),
				'@dimension'      		=>  $this->input->post('dimension'),
				'@diametro'       		=>  $this->input->post('diametro'),
				'@altura'       		=>  $this->input->post('altura'),
				'@grosor'       		=>  $this->input->post('grosor'),
				'@accion'           	=>  $this->input->post('cserviaccionio'),
			);
			$retorna = $this->minforme->setregestudio05($parametros);
			echo json_encode($retorna);	
		} elseif ($RegAdjunto == 7){
			$parametros = array(
				'@idptregequipo'    	=>  $this->input->post('idptregequipo'),
				'@idptregistro'   		=>  $this->input->post('idptregistro'),
				'@idptregproducto'    	=>  $this->input->post('idptregproducto'),
				'@clase_registro'   	=>  $this->input->post('clase_registro'),
				'@descripcion_equipo'   =>  $this->input->post('descripcion_equipo'),
				'@id_tipoequipo'    	=>  $this->input->post('id_tipoequipo'),
				'@id_equipofabricante'  =>  $this->input->post('id_equipofabricante'),
				'@accion'           	=>  $this->input->post('cserviaccionio'),  
			);
			$retorna = $this->minforme->setregestudio07($parametros);
			echo json_encode($retorna);	
		}
        	
    }
    public function getlistequipoxprod() {	// Recupera Listado de Registros	
         
        $parametros = array(
			'@idptregproducto' => $this->input->post('Idregprod')
		);		
		$resultado = $this->minforme->getlistequipoxprod($parametros);
		echo json_encode($resultado);
	}
    public function getTipoequipo() {	// Visualizar los Tipo Equipos de Registro	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getTipoequipo($parametros);
		echo json_encode($resultado);
	}
    public function getTiporecinto() {	// Visualizar los Tipo Equipos de Registro	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getTiporecinto($parametros);
		echo json_encode($resultado);
	}
    public function getMediocalen() {	// Visualizar los Medio de de Registro	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getMediocalen($parametros);
		echo json_encode($resultado);
	}
    public function getFabricante() {	// Visualizar los Fabricantes	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getFabricante($parametros);
		echo json_encode($resultado);
	}
    public function getTipoproducto() {	// Visualizar los Tipos de productos	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getTipoproducto($parametros);
		echo json_encode($resultado);
	}
    public function getParticulas() {	// Visualizar los Particulas	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getParticulas($parametros);
		echo json_encode($resultado);
	}
    public function getLiquidogob() {	// Visualizar los Liquido Gobierno	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getLiquidogob($parametros);
		echo json_encode($resultado);
	}
    public function getEnvases() {	// Visualizar los Envases	
		
		$parametros = array(
            '@idptregestudio'   => $this->input->post('idptregestudio')
        );
		$resultado = $this->minforme->getEnvases($parametros);
		echo json_encode($resultado);
	}	
	public function getServicioAudi() {	// Visualizar Servicios en CBO	        
	 	$resultado = $this->minforme->getServicioAudi();
	 	echo json_encode($resultado);
 	}
	public function cloneregistro04() {	// Clonar estudio 04 PT	
		 
		$parametros = array(
			'@idptregequipo'   => $this->input->post('idptregequipo'),
			'@idptregistro'   => $this->input->post('idptregistro'),
			'@idptregproducto'   => $this->input->post('idptregproducto')
		);
		$resultado = $this->minforme->cloneregistro04($parametros);
		echo json_encode($resultado);
	}
	public function cloneregistro05() {	// Clonar estudio 05 PT	
		 
		$parametros = array(
			'@idptregequipo'   => $this->input->post('idptregequipo'),
			'@idptregistro'   => $this->input->post('idptregistro'),
			'@idptregproducto'   => $this->input->post('idptregproducto')
		);
		$resultado = $this->minforme->cloneregistro05($parametros);
		echo json_encode($resultado);
	}
	public function cloneregistro06() {	// Clonar estudio 06 PT	
		 
		$parametros = array(
			'@idptregproducto'   => $this->input->post('idptregproducto'),
			'@idptregistro'   => $this->input->post('idptregistro'),
		);
		$resultado = $this->minforme->cloneregistro06($parametros);
		echo json_encode($resultado);
	}
	public function delregistro() {	// Eliminar de Det Servicio 06
		$idptregestudio = $this->input->post('idptregestudio');

		if($idptregestudio == 6 ){			
			$parametros = array(
				'@idptregistro'   => $this->input->post('idptregistro'),
			);	
			$resultado = $this->minforme->delregistro06($parametros);
		}
		echo json_encode($resultado);
	}
	public function delregistrodet06() {	// Eliminar de Det Servicio 06
		
		$parametros = array(
			'@idptregequipo'   => $this->input->post('idptregequipo'),
		);	
		$resultado = $this->minforme->delregistrodet06($parametros);
		echo json_encode($resultado);
	}

	public function subirArchivoSID() {	// Subir Acrhivo 
        $CCLIE      = $this->input->post('mhdnIdccliente');
        $TRAM       = $this->input->post('mcboTipotram');
        $IDTRAM     = $this->input->post('mhdnIdTram');
        $ANIO       = substr($this->input->post('mtxtFtram'),-4);
        
        $RUTAARCH   = 'FTPfileserver/Archivos/10203/'.$ANIO.'/'.$CCLIE.'/';

        !is_dir($RUTAARCH) && @mkdir($RUTAARCH, 0777, true);
        
        if($TRAM == 1){
            $iniTram = 'REG-FFRN';
        }elseif($TRAM == 2){
            $iniTram = 'REG-FCE';
        }elseif($TRAM == 3){
            $iniTram = 'REG-SID';
        }elseif($TRAM == 4){
            $iniTram = 'REM-ALER-IMP';
        }

        $NOMBARCH = $iniTram.$CCLIE.substr('0000'.$IDTRAM, -4).$ANIO.'PTFS';

		//RUTA DONDE SE GUARDAN LOS FICHEROS
		$config['upload_path'] 		= $RUTAARCH;
		$config['allowed_types'] 	= 'pdf|xlsx|docx|xls|doc';
		$config['max_size'] 		= '60048';
		$config['overwrite'] 		= TRUE;
		$config['file_name']        = $NOMBARCH;
		
		$this->load->library('upload',$config);
		$this->upload->initialize($config);
		
		if (!($this->upload->do_upload('mtxtArchivotram'))) {
			//si al subirse hay algun error 
			$data['uploadError'] = $this->upload->display_errors();
			$error = '';
			return $error;					
		}else{
			$data = $this->upload->data();
			$parametros = array(
                '@idpttramite'   	=>  $IDTRAM,
                '@adj_docum'        =>  $data['file_name'],
                '@ruta_docum'       =>  $config['upload_path'],
				'@nomb_docum'   	=>  $this->input->post('mtxtNombarch'),
            );
            $retorna = $this->mtramites->subirArchivo($parametros);
            echo json_encode($retorna);
		}	
	}

	public function excelinformes() {
	 /*Estilos */
		$titulo = [
			'font'	=> [
				'name' => 'Arial',
				'size' =>12,
				'color' => array('rgb' => 'FFFFFF'),
				'bold' => true,
			], 
			'fill'	=>[
				'fillType' => Fill::FILL_SOLID,
				'startColor' => [
					'rgb' => '29B037'
				]
			],
			'borders'	=>[
				'allBorders' => [
					'borderStyle' => Border::BORDER_THIN,
					'color' => [ 
						'rgb' => '000000'
					]
				]
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
				'wrapText' => true,
			],
		];
        $cabecera = [
            'font'	=> [
                'name' => 'Arial',
                'size' =>10,
                'color' => array('rgb' => 'FFFFFF'),
                'bold' => true,
            ], 
            'fill'	=>[
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '29B037'
                ]
            ],
            'borders'	=>[
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [ 
                        'rgb' => '000000'
                    ]
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        $celdastexto = [
            'borders'	=>[
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [ 
                        'rgb' => '000000'
                    ]
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        $celdasnumero = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        $celdascentro = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
	 /*Estilos */	
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Listado - Informes');

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(9);
		
		$sheet->setCellValue('A1', 'Listado de Informes')
			->mergeCells('A1:H1')
			->setCellValue('A3', '#')
			->setCellValue('B3', 'Nro Informe')
			->setCellValue('C3', 'Fecha Informe')
			->setCellValue('D3', 'Cliente')
			->setCellValue('E3', 'Servicio')
			->setCellValue('F3', 'Tema')
			->setCellValue('G3', 'Responsable')
			->setCellValue('H3', 'Nro Propuesta');

		$sheet->getStyle('A1:H1')->applyFromArray($titulo);
        $sheet->getStyle('A3:H3')->applyFromArray($cabecera);

		$sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(4.10);
		$sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(20.10);
		$sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(14.10);
		$sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(41.10);
		$sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(34.10);
		$sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(45.10);
		$sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(45.10);
		$sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(20.10);
		

		$varnull 			= 	'';
		$celservicio 		= 	'';
		$celestado			= 	'';

		$ccliente   = $this->input->post('cboClie');
		$fini       = $this->input->post('txtFIni');
		$ffin       = $this->input->post('txtFFin');
		$cservicio  = $this->input->post('cboServ');
		$dnropropu    = $this->input->post('txtnropropu');
		$dnroinfor    = $this->input->post('txtnroinfor');
		$vigente    = $this->input->post('swVigencia');

		
		if($vigente == 'on'){
			$cvigente = 'A';
		}else{
			$cvigente = 'I';
		}
            
        $parametros = array(
			'@cservicio'    => ($this->input->post('cboServ') == '') ? '0' : $cservicio,
			'@fini'         => ($this->input->post('txtFIni') == '') ? NULL : substr($fini, 6, 4).'-'.substr($fini,3 , 2).'-'.substr($fini, 0, 2),
			'@ffin'         => ($this->input->post('txtFFin') == '') ? NULL : substr($ffin, 6, 4).'-'.substr($ffin,3 , 2).'-'.substr($ffin, 0, 2),
			'@ccliente'     => ($this->input->post('cboClie') == '') ? '0' : $ccliente,
			'@dnropropu'      => ($this->input->post('txtnropropu') == $varnull) ? '%' : "%".$dnrodet."%",
			'@dnroinfor'      => ($this->input->post('txtnroinfor') == $varnull) ? '%' : "%".$dnrodet."%",
			'@vigente'      => $cvigente,
		);		
		$rpt = $this->minforme->getbuscarinforme($parametros);
		$i = 1;
		$irow = 4;
        if ($rpt){
        	foreach($rpt as $row){
            
				$NROINFOR = $row->NROINFOR;
				$FECHINFOR = $row->FECHINFOR;
				$RAZONSOCIAL = $row->RAZONSOCIAL;
				$DESCRIPSERV = $row->DESCRIPSERV;
				$RESPONSABLE = $row->RESPONSABLE;
				$NROPROPU = $row->NROPROPU;
						

				$sheet->setCellValue('A'.$irow,$i);
				$sheet->setCellValue('B'.$irow,$NROINFOR);
				$sheet->setCellValue('C'.$irow,$FECHINFOR);
				$sheet->setCellValue('D'.$irow,$RAZONSOCIAL);
				$sheet->setCellValue('E'.$irow,$DESCRIPSERV);
				$sheet->setCellValue('F'.$irow,'');
				$sheet->setCellValue('G'.$irow,$RESPONSABLE);
				$sheet->setCellValue('H'.$irow,$NROPROPU);

				$i++;
				$irow++;
			}
		}
		$pos = $irow - 1;
		$sheet->getStyle('A4:H'.$pos)->applyFromArray($celdastexto);
		//$sheet->getStyle('A4:A'.$pos)->applyFromArray($celdasnumero);
		//$sheet->getStyle('H4:H'.$pos)->applyFromArray($celdasnumero);
		//$sheet->getStyle('C4:C'.$pos)->applyFromArray($celdascentro);
		//$sheet->getStyle('E4:E'.$pos)->applyFromArray($celdascentro);

		$sheet->setAutoFilter('B3:H'.$pos);
		
                   
		$writer = new Xlsx($spreadsheet);
		$filename = 'listadoInforme-'.time().'.xlsx';
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}
?>