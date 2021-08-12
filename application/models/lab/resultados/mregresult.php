<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** COTIZACION **/ 
class Mregresult extends CI_Model {
	function __construct() {
		parent:: __construct();	
		$this->load->library('session');
    }
    
   /** LISTADO **/ 

    public function getbuscaringresoresult($parametros) { // Buscar Cotizacion	
        $procedure = "call usp_lab_coti_getbuscaringresoresult(?,?,?,?,?,?,?,?)";
		$query = $this->db-> query($procedure,$parametros);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }
    public function getrecuperaservicio($cinternoordenservicio) { // Listar Ensayos	
        $sql = "select c.drazonsocial, convert(varchar,a.fanalisis,103) as 'fanalisis', a.hanalisis, b.dcotizacion, convert(varchar,b.fcotizacion,103) as 'fcotizacion', a.nordenservicio, convert(varchar,a.fordenservicio,103) as 'fordenservicio',
                    a.dobservacionresultados, a.ctipoinforme, b.cinternocotizacion, b.nversioncotizacion, a.cinternoordenservicio,
                    (select count(1) from presultado z where z.cinternocotizacion = a.cinternocotizacion and z.ctipoproducto not in ('0','2')) as 'conttipoprod'
                from pordenserviciotrabajo a
                    join pcotizacionlaboratorio b on b.cinternocotizacion = a.cinternocotizacion AND b.nversioncotizacion = a.nversioncotizacion
                    join mcliente c ON c.ccliente = b.ccliente
                where a.cinternoordenservicio = ".$cinternoordenservicio.";";        
        $query  = $this->db->query($sql);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }
    public function getcbotipoensayo($cinternoordenservicio)
    {
        $sql = "select distinct C.zctipoensayo, D.dregistro as 'tipoensayo' 
                from presultado A   
                    join precepcionmuestra B ON B.cinternocotizacion = A.cinternocotizacion and B.nversioncotizacion = A.nversioncotizacion and B.nordenproducto = A.nordenproducto and B.cmuestra = A.cmuestra
                    join mensayo C ON A.censayo = C.censayo 
                    join ttabla D ON D.ctipo = C.zctipoensayo
                where A.cinternoordenservicio = ".$cinternoordenservicio."
                order by D.dregistro;";
		$query  = $this->db->query($sql);

        $listas = '<option value="%" selected="selected">Todos</option>';
        
        if ($query->num_rows() > 0) {
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->zctipoensayo.'">'.$row->tipoensayo.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }
    public function getlistresultadoscab($cinternoordenservicio)
    {
        $sql = "select distinct A.cmuestra, (A.cmuestra +'-'+ cast(A.nviausado as char(10))) as 'codmuestra', B.drealproducto 
                from presultado A   
                    join precepcionmuestra B ON B.cinternocotizacion = A.cinternocotizacion and B.nversioncotizacion = A.nversioncotizacion and B.nordenproducto = A.nordenproducto and B.cmuestra = A.cmuestra
                where A.cinternoordenservicio = ".$cinternoordenservicio."
                order by A.cmuestra;";
        $query  = $this->db->query($sql);
        
        if (!$query) return [];
        return ($query->num_rows() > 0) ? $query->result() : [];
    }
    public function getlistresultadoscabtipo($cinternoordenservicio,$cmuestra)
    {
        $sql = "select distinct C.zctipoensayo, D.dregistro as 'tipoensayo' 
                from presultado A   
                    join precepcionmuestra B ON B.cinternocotizacion = A.cinternocotizacion and B.nversioncotizacion = A.nversioncotizacion and B.nordenproducto = A.nordenproducto and B.cmuestra = A.cmuestra
                    join mensayo C ON A.censayo = C.censayo 
                    join ttabla D ON D.ctipo = C.zctipoensayo
                where A.cinternoordenservicio = ".$cinternoordenservicio."
                    and A.cmuestra = '".$cmuestra."'
                order by D.dregistro;";
        $query  = $this->db->query($sql);
        
        if (!$query) return [];
        return ($query->num_rows() > 0) ? $query->result() : [];
    }
    public function getlistresultados($parametros) { // Buscar Cotizacion	
        $procedure = "call usp_lab_coti_getlistresultados(?,?,?)";
		$query = $this->db-> query($procedure,$parametros);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }
    public function getcboum() { // Visualizar 	
        
        $sql = "select CTIPO, dregistro from ttabla where CTABLA = '38' and NCORRELATIVO > 0 order by NCORRELATIVO;";
		$query  = $this->db->query($sql);

        $listas = '<option value="0" selected="selected">::Elegir</option>';
        
        if ($query->num_rows() > 0) {
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->CTIPO.'">'.$row->dregistro.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }
    public function getum() { // Visualizar 	
        
        $sql = "select CTIPO, dregistro from ttabla where CTABLA = '38' and NCORRELATIVO > 0 order by NCORRELATIVO;";
		$query  = $this->db->query($sql);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}
    }

    public function setresultados($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_resultados_setresultados(?,?,?,?,?,?,?,?,?,?,?,?);";
        $query = $this->db->query($procedure,$parametros);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
            return $query->result(); 
        }   
    }

    public function setresultadosold($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_resultados_setresultadosold(?,?,?,?,?,?,?,?,?,?);";
        $query = $this->db->query($procedure,$parametros);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
            return $query->result(); 
        }   
    }

    public function setservicio($cinternoordenservicio,$fanalisis,$hanalisis,$dobservacionresultados,$ctipoinforme)
    {
        return $this->db->update('pordenserviciotrabajo', [
            'fanalisis' => $fanalisis,
            'hanalisis' => $hanalisis,
            'dobservacionresultados' => trim($dobservacionresultados),
            'ctipoinforme' => $ctipoinforme,
        ], ['cinternoordenservicio' => $cinternoordenservicio]);
    }
}
?>