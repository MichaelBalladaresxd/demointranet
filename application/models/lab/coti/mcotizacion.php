<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** COTIZACION **/ 
class Mcotizacion extends CI_Model {
	function __construct() {
		parent:: __construct();	
		$this->load->library('session');
    }
    
   /** LISTADO **/ 
    public function getcboclieserv() { // Visualizar Clientes del servicio en CBO 
        $sql = "select distinct b.ccliente, b.drazonsocial 
                from pcotizacionlaboratorio a join mcliente b on b.ccliente = a.ccliente
                order by b.drazonsocial asc;";
        $query  = $this->db->query($sql);
            
        if ($query->num_rows() > 0) {
 
             $listas = '<option value="0">Elige</option>';
             
             foreach ($query->result() as $row){
                 $listas .= '<option value="'.$row->ccliente.'">'.$row->drazonsocial.'</option>';  
             }
                return $listas;
         }{
             return false;
         }		   
    }

    public function getbuscarcotizacion($parametros) { // Buscar Cotizacion	
        $procedure = "call usp_lab_coti_getbuscarcotizacion(?,?,?,?,?,?,?,?,?)";
		$query = $this->db-> query($procedure,$parametros);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }

   /** REGISTRO **/ 
    public function getcboregserv() { // Visualizar Servicios en CBO
        $sql = "select cservicio, csubservicio, dsubservicio 
                from msubservicio  
                where ( ccompania = '2' ) and  
                    ( carea = '02' ) and  
                    ( cservicio in ( '07','08' ) ) and  
                    ( sregistro = 'A' )   
                order by dsubservicio asc;";
        $query  = $this->db->query($sql);
            
        if ($query->num_rows() > 0) {
 
             $listas = '<option value="0"></option>';
             
             foreach ($query->result() as $row){
                 $listas .= '<option value="'.$row->csubservicio.'">'.$row->dsubservicio.'</option>';  
             }
                return $listas;
         }{
             return false;
         }		   
    }

    public function getcliente() { // Visualizar Clientes con propuestas en CBO	
        
        $procedure = "call sp_appweb_pt_getclienteinterno()";
		$query = $this->db-> query($procedure);
        
        if ($query->num_rows() > 0) {

            $listas = '<option value="0" selected="selected"></option>';
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->CCLIENTE.'">'.$row->RAZONSOCIAL.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }

    public function getprovcliente($ccliente) { // Visualizar Clientes con propuestas en CBO	
        
        $sql = "select b.ccliente, b.drazonsocial
                from mrclienteproveedor a 
                join mcliente b on a.cproveedorcliente = b.ccliente 
                where a.ccliente = '".$ccliente."'
                order by b.drazonsocial;";
		$query  = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {

            $listas = '<option value="%" selected="selected">::Elegir</option>';
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->ccliente.'">'.$row->drazonsocial.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }	

    public function getcontaccliente($ccliente) { // Visualizar Clientes con propuestas en CBO	
        
        $sql = "select ccontacto,  (dapepat+' '+dapemat+' '+dnombre) as 'dcontacto'
                from mcontacto  
                where ccliente = '".$ccliente."'
                order by dcontacto;";
		$query  = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {

            $listas = '<option value="%" selected="selected">::Elegir</option>';
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->ccontacto.'">'.$row->dcontacto.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }	

    public function setcotizacion($parametros) {  // Registrar evaluacion PT
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_setcotizacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
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

    public function getlistarproducto($idcoti,$nversion) { // Listar Productos	
        $sql = "select a.cinternocotizacion as 'IDCOTI', a.nversioncotizacion as 'NVERSION', a.nordenproducto as 'IDPROD', '' as 'SPACE', 
                        if isnull(b.dcortaestablecimiento,'0') = '0' or b.dcortaestablecimiento = '' then '' else b.dcortaestablecimiento+ ' - ' end if +  b.destablecimiento + ' - ' +  b.ddireccion   as 'LOCALCLIE', 
                        a.dproducto as 'PRODUCTO', c.dregistro as 'CONDI', a.nmuestra as 'NMUESTRA', b.ccliente as 'CCLIENTE' , a.imonto as 'IMONTO',
                        a.clocalcliente as 'CLOCALCLIE', a.zctipocondicionpdto as 'CCONDI', a.zctipoprocedencia as 'CPROCEDE', a.dcantidadminima as 'CANTMIN', a.setiquetanutri as 'ETIQUETA', a.ctipoproducto as 'CTIPOPROD', isnull(a.ntamanoporcion,0) as 'PORCION', a.umporcion as 'CUM'  
                from pproductoxcotizacion a
                    join mestablecimientocliente b on b.cestablecimiento = a.clocalcliente
                    join ttabla c on c.ctipo = a.zctipocondicionpdto
                where ( a.cinternocotizacion =  '".$idcoti."'  ) and  
                        ( a.nversioncotizacion =  ".$nversion."  ) and  
                        ( c.ctabla = '37' ) and
                        ( a.sregistro = 'A'  )
                order by a.nordenproducto;";
        $query  = $this->db->query($sql);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }

    public function getcboregLocalclie($ccliente) { // Visualizar Clientes con propuestas en CBO	
        
        $sql = "select cestablecimiento as 'clocal', ((if isnull(dcortaestablecimiento,'') = ''  then '' else dcortaestablecimiento+ ' - ' end if) +  destablecimiento + ' - ' +  ddireccion ) as 'dlocal'
                from mestablecimientocliente  
                where ccliente = '".$ccliente."'   
                    and sregistro = 'A'  
                order by destablecimiento asc;";
		$query  = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {

            $listas = '<option value="0" selected="selected">::Elegir</option>';
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->clocal.'">'.$row->dlocal.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }

    public function getcboregcondi() { // Visualizar Clientes con propuestas en CBO	
        
        $sql = "select ctipo as 'ccondi', dregistro as 'dcondi' from ttabla where ctabla = 37 and ncorrelativo <> 0 order by dregistro;";
		$query  = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {

            $listas = '<option value="0" selected="selected">::Elegir</option>';
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->ccondi.'">'.$row->dcondi.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }

    public function getcboregprocede() { // Visualizar Clientes con propuestas en CBO	
        
        $sql = "select ctipo as 'cprocede', dregistro as 'dprocede' from ttabla where ctabla = 47 and ncorrelativo <> 0 order by dregistro;";
		$query  = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {

            $listas = '<option value="0" selected="selected">::Elegir</option>';
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->cprocede.'">'.$row->dprocede.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }

    public function setproductoxcotizacion($parametros) {  // Registrar evaluacion PT
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_setproductoxcotizacion(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
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

    public function getlistarensayo($idcoti,$nversion,$idproduc) { // Listar Ensayos	
        $sql = "select a.cinternocotizacion, a.nversioncotizacion, a.nordenproducto, a.censayo as 'CENSAYO', b.censayofs as 'CODIGO', b.densayo as 'DENSAYO', b.naniopublicacion as 'ANIO', b.dnorma as 'NORMA',
                    a.icostoclienteparcial as 'CONSTOENSAYO', a.nvias as 'NVIAS', a.ncantidad as 'CANTIDAD', a.icostorealparcial as 'COSTO', a.ctipoproducto as 'TIPOPROD',
                    if sacnoac = 'N' then 'NO AC' ELSE 'AC' end if as 'ACRE', c.dproducto, a.claboratorio, '' as 'SPACE', number(*) as 'ENUMERAR'
                from pensayoproducto a   
                    join mensayo b on b.censayo = a.censayo
                    join pproductoxcotizacion c on c.cinternocotizacion = a.cinternocotizacion and c.nversioncotizacion = a.nversioncotizacion and c.nordenproducto = a.nordenproducto   
                where ( a.cinternocotizacion = '".$idcoti."' ) and  
                    ( a.nversioncotizacion = ".$nversion." ) and  
                    ( a.nordenproducto = '".$idproduc."' )  and  
                    ( a.sregistro = 'A' )
                order by b.censayofs desc;";
        $query  = $this->db->query($sql);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }

    public function getpdfdatoscoti($idcoti,$nversion) { // Listar Ensayos	
        $sql = "select a.dcotizacion, DATEFORMAT(a.fcotizacion,'dd/mm/yyyy') as 'fcotizacion', b.drazonsocial, b.nruc, b.ddireccioncliente,  (c.dnombre + ' ' + c.dapepat) as 'dcontacto', isnull(c.dmail,'') as 'dmail', isnull(c.dtelefono,'') as 'dtelefono',
                        a.imuestreo, a.irecojo, (a.isubtotal + a.imuestreo + a.irecojo) as 'subtotalimuestra', a.isubtotal, a.pigv, a.digv, a.pdescuento, a.ddescuento, (a.isubtotal - a.ddescuento) as 'condescuento', a.itotal, cast(a.nvigencia as char(50))+ ' días' as 'diascoti', a.dobservacion,
                        (UPPER(SUBSTRING(d.DNOMBRE, 1, 1 )+''+d.DAPEPAT)) as 'usuariocrea', a.ctipocambio, a.dtipocambio, smuestreo, srecojo,
                        (cast(a.ntiempoentregainforme as char(50))+' '+if a.stiempoentregainforme = 'C' then 'dias Calendario' else 'dias Utiles' end if) as 'entrega',   
                        (select count(1) from pproductoxcotizacion z where z.cinternocotizacion = a.cinternocotizacion and z.nversioncotizacion = a.nversioncotizacion) as 'cantprod',
                        (select sum(nmuestra) from pproductoxcotizacion z where z.cinternocotizacion = a.cinternocotizacion and z.nversioncotizacion = a.nversioncotizacion) as 'summuestra',
                        (select z.dregistro from ttabla z where z.ctipo = a.zctipoformapago) as 'cforma_pago',
                        (select z.dregistro from ttabla z WHERE z.ctipo = '553') as 'banco',
                        (select z.dregistro from ttabla z WHERE z.ctipo = '554') as 'detraccion',
                        (select list(' Prod. '+cast(z.nordenproducto as char(3))+' - '+z.dcantidadminima) from pproductoxcotizacion z where z.cinternocotizacion = a.cinternocotizacion) as 'dcantidadminima',
                        if a.npermanenciamuestra = 0 then 'NO APLICA' else cast(a.npermanenciamuestra as char(50))+ ' días' end if as 'diaspermanecia', a.smostrarprecios as 'verprecios',(SUBSTRING(a.dcotizacion,1,4)+'-'+cast(year(a.fcotizacion) as char(4))) as 'namefile' 
                from pcotizacionlaboratorio a   
                    join mcliente b on b.ccliente = a.ccliente
                    left join mcontacto c on c.ccontacto = a.ccontacto 
                    join musuario d on d.cusuario = a.cusuariocrea 
                where ( a.cinternocotizacion = '".$idcoti."' ) and  
                    ( a.nversioncotizacion = ".$nversion." ) ;";
        $query  = $this->db->query($sql);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }

    public function getpdfdatosprod($idcoti,$nversion) { // Listar Ensayos	
        $sql = "select b.destablecimiento, a.nordenproducto ,a.dproducto, c.dregistro as 'condicion',
                (select z.dregistro from ttabla z  where z.ctipo = a.zctipoprocedencia) as 'procedencia',
                a.dcantidadminima, a.imonto  as 'subtotal'
                from pproductoxcotizacion a
                join mestablecimientocliente b on b.cestablecimiento = a.clocalcliente
                join ttabla c on c.ctipo = a.zctipocondicionpdto
                where ( a.cinternocotizacion = '".$idcoti."' ) and  
                    ( a.nversioncotizacion = ".$nversion." ) and  
                    ( a.sregistro = 'A' ) ;";
        $query  = $this->db->query($sql);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }

    public function setduplicarprodxcoti($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_setduplicarprodxcoti(?,?,?);";
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

    public function precioxcoti($cinternocotizacion,$nversioncotizacion,$smostrarprecios) { // Recuperar Password
		
		$data = array("smostrarprecios" => $smostrarprecios);

        $this->db->where("cinternocotizacion", $cinternocotizacion)
                 ->where("nversioncotizacion", $nversioncotizacion);
		if($this->db->update("pcotizacionlaboratorio", $data)){
			return TRUE;
		}
	}

    public function deleteprodxcoti($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_deleteprodxcoti(?,?,?);";
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

    public function getbuscarensayos($parametros) { // Visualizar Clientes con propuestas en CBO	
        
        $procedure = "call usp_lab_coti_getbuscarensayos(?,?,?)";
		$query = $this->db-> query($procedure,$parametros);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}
    }

    public function setregensayoxprod($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_setregensayoxprod(?,?,?,?,?,?,?,?);";
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

    public function deleteensayoxprod($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_deleteensayoxprod(?,?,?,?);";
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

    public function cerrarcotizacion($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_cerrarcotizacion(?,?);";
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

    public function abrircotizacion($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_abrircotizacion(?,?);";
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

    public function getmcbobustipoensayo() { // Visualizar Clientes con propuestas en CBO	
        
        $sql = "select ctipo as 'idtipoensayo' , dregistro as 'dtipoensayo'  from ttabla where ctabla = '18' and ncorrelativo <> 0 order by ncorrelativo asc;";
		$query  = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {

            $listas = '<option value="%" selected="selected">::Elegir</option>';
            
            foreach ($query->result() as $row)
            {
                $listas .= '<option value="'.$row->idtipoensayo.'">'.$row->dtipoensayo.'</option>';  
            }
               return $listas;
        }{
            return false;
        }	
    }	

    public function getexcellistcoti($parametros) { // Buscar Cotizacion	
        $procedure = "call usp_lab_coti_getexcellistcoti(?,?,?,?,?,?,?,?)";
		$query = $this->db-> query($procedure,$parametros);

		if ($query->num_rows() > 0) { 
			return $query->result();
		}{
			return False;
		}		
    }

    public function setduplicarcoti($parametros) { // Buscar Cotizacion
        $this->db->trans_begin();

        $procedure = "call usp_lab_coti_setduplicarcoti(?,?,?);";
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
}
?>