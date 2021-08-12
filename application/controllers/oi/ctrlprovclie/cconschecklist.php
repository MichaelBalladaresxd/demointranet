<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cconschecklist extends CI_Controller {
	function __construct() {
		parent:: __construct();	
		$this->load->model('oi/ctrlprovclie/mconschecklist');
		$this->load->model('mglobales');
		$this->load->library('encryption');
		$this->load->helper(array('form','url','download','html','file'));
		$this->load->library('form_validation');
    }
    
   /** CONTROL DE PROVEEDORES **/ 
    public function getconschecklist() {	// Busqueda	
		$varnull    =   '';

        $ccliente       = $this->input->post('ccliente');
        
        $parametros = array(
			'@CCLIENTE'		=>  $ccliente,
        );
        
        $resultado = $this->mconschecklist->getconschecklist($parametros);
        echo json_encode($resultado);
    } 
    public function getlistchecklist() {	// Visualizar 	
        $cchecklist = $this->input->post('cchecklist');
		$resultado 	= $this->mconschecklist->getlistchecklist($cchecklist);
		echo json_encode($resultado);
	}
}
?>