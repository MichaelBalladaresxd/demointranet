<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mconschecklist extends CI_Model {
	function __construct() {
		parent:: __construct();	
		$this->load->library('session');
    }
    
   /** CONTROL DE PROVEEDORES **/ 
    public function getconschecklist($parametros)
    {
        $procedure = "call usp_oi_ctrlprov_conschecklist(?)";
        $query = $this->db-> query($procedure,$parametros);

        if ($query->num_rows() > 0) { 
            return $query->result();
        }{
            return False;
        }		   
    }
    public function getlistchecklist($cchecklist) { // Listar Ensayos	
        $sql = "select MR.SREQUISITO, MR.DNUMERADOR, MR.DREQUISITO, MR.DNORMATIVA, 
                        (SELECT max(M.ndetallevalor) FROM MDETALLEVALOR M WHERE M.cvalor = mr.CVALOR ) as 'valor_maximo'
                FROM MREQUISITOCHECKLIST MR 
                WHERE mr.cchecklist = '".$cchecklist."'
                    and MR.DNUMERADOR <> '0'
                ORDER BY MR.DORDENLISTA ASC ;";
        $query  = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {
            return $query->result();
        }{
            return false;
        }		
    }

}
?>