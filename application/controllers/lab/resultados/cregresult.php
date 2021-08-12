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
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cregresult extends CI_Controller {
	function __construct() {
		parent:: __construct();	
		$this->load->model('lab/resultados/mregresult');
		$this->load->model('lab/coti/mcotizacion');
		$this->load->model('lab/consinf/mconsinf');
		$this->load->model('mglobales');
		$this->load->library('encryption');
		$this->load->helper(array('form','url','download','html','file'));
		$this->load->library('form_validation');
    }
    
   /** COTIZACION **/
    public function getbuscaringresoresult() { // Buscar Cotizacion
		$varnull = '';

		$ccliente   = $this->input->post('ccliente');
		$fini       = $this->input->post('fini');
		$ffin       = $this->input->post('ffin');
		$descr      = $this->input->post('numero');
        $tipobuscar = $this->input->post('buspor');
		$prodmuestra      = $this->input->post('prodmuestra');
		$ensayo      = $this->input->post('ensayo');
        
        $parametros = array(
			'@CCIA'         => '2',
			'@CCLIENTE'     => ($this->input->post('ccliente') == '') ? '0' : $ccliente,
			'@FINI'         => ($this->input->post('fini') == '%') ? NULL : substr($fini, 6, 4).'-'.substr($fini,3 , 2).'-'.substr($fini, 0, 2),
			'@FFIN'         => ($this->input->post('ffin') == '%') ? NULL : substr($ffin, 6, 4).'-'.substr($ffin,3 , 2).'-'.substr($ffin, 0, 2),
			'@DESCR'		=> ($this->input->post('numero') == '') ? '%' : '%'.$descr.'%',
			'@TIPOBUSCAR'	=> ($this->input->post('tieneot') == '%') ? '%' : $tipobuscar,
			'@DESCRPRODMUESTRA'		=> ($this->input->post('prodmuestra') == '') ? '%' : '%'.$prodmuestra.'%',
			'@DESCRENSAYO'  => ($this->input->post('ensayo') == '') ? '%' : '%'.$ensayo.'%',
        );
        $retorna = $this->mregresult->getbuscaringresoresult($parametros);
        echo json_encode($retorna);		
    }

    public function getrecuperaservicio() {	// Visualizar Maquilador por proveedor en CBO	
        
        $cinternoordenservicio = $this->input->post('cinternoordenservicio');
		$resultado = $this->mregresult->getrecuperaservicio($cinternoordenservicio);
		echo json_encode($resultado);
	}
    
    public function getcbotipoensayo() {	// Visualizar los Tipo Equipos de Registro	
		
        $cinternoordenservicio = $this->input->post('cinternoordenservicio');
		$resultado = $this->mregresult->getcbotipoensayo($cinternoordenservicio);
		echo json_encode($resultado);
    }
    
    public function getlistresultados() { // Buscar Cotizacion
        
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $cinternoordenservicio = $this->input->get('cinternoordenservicio');

        $retornacab = $this->mregresult->getlistresultadoscab($cinternoordenservicio);
        foreach ($retornacab as $rowcab){
            ?>
            <tr class="trcab">
                <td></td>
                <td colspan="11"><b><?php echo $rowcab->codmuestra .' :: '. $rowcab->drealproducto?></b></td>
            </tr>
            <?php
            $cmuestra = $rowcab->cmuestra;
            $retornatipo = $this->mregresult->getlistresultadoscabtipo($cinternoordenservicio,$cmuestra);
            foreach ($retornatipo as $rowtipo){
                ?>
                <tr class="trtipo">
                    <td></td>
                    <td></td>
                    <td colspan="10"><b><?php echo $rowtipo->tipoensayo?></b></td>
                </tr>
                <?php
                $parametros = array(
                    '@cinternoordenservicio'     => $cinternoordenservicio,
                    '@cmuestra'     => $rowcab->cmuestra,
                    '@zctipoensayo'     => $rowtipo->zctipoensayo,
                );
                $retorna = $this->mregresult->getlistresultados($parametros);
                foreach ($retorna as $row){
                    ?>
                    <tr>
                        <td><?php echo $row->cinternoordenservicio.';'.$row->nordenproducto.';'.$row->cmuestra.';'.$row->censayo ?></td>
                        <td><?php echo $row->POS ?></td>
                        <td><?php echo $row->censayofs ?></td>
                        <td><?php echo $row->densayo ?></td>
                        <td><?php echo $row->unidadmedida ?></td>
                        <td><?php echo $row->condi_espe ?></td>
                        <td><?php echo round($row->valor_espe, 3); ?></td>
                        <td><?php echo round($row->valexpo_espe, 0); ?></td>
                        <td><?php echo $row->condi_resul ?></td>
                        <td><?php echo round($row->valor_resul, 3); ?></td>
                        <td><?php echo round($row->valexpo_resul, 0);  ?></td>
                        <td><?php echo $row->sresultado ?></td>
                    </tr>
                    <?php
                }
            }
        }
        
		/*$varnull = '';

		$cinternoordenservicio   = $this->input->post('cinternoordenservicio');
        
        $parametros = array(
			'@cinternoordenservicio'     => $cinternoordenservicio,
        );
        $retorna = $this->mregresult->getlistresultados($parametros);
        echo json_encode($retorna);	*/	
    }

    public function getlistresultadosold() { // Buscar Cotizacion
        
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $cinternoordenservicio = $this->input->get('cinternoordenservicio');

        $retornacab = $this->mregresult->getlistresultadoscab($cinternoordenservicio);
        foreach ($retornacab as $rowcab){
            ?>
            <tr class="trcab">
                <td></td>
                <td colspan="9"><b><?php echo $rowcab->codmuestra .' :: '. $rowcab->drealproducto?></b></td>
            </tr>
            <?php
            $cmuestra = $rowcab->cmuestra;
            $retornatipo = $this->mregresult->getlistresultadoscabtipo($cinternoordenservicio,$cmuestra);
            foreach ($retornatipo as $rowtipo){
                ?>
                <tr class="trtipo">
                    <td></td>
                    <td></td>
                    <td colspan="8" ><b><?php echo $rowtipo->tipoensayo?></b></td>
                </tr>
                <?php
                $parametros = array(
                    '@cinternoordenservicio'     => $cinternoordenservicio,
                    '@cmuestra'     => $rowcab->cmuestra,
                    '@zctipoensayo'     => $rowtipo->zctipoensayo,
                );
                $retorna = $this->mregresult->getlistresultados($parametros);
                foreach ($retorna as $row){
                    ?>
                    <tr>
                        <td><?php echo $row->cinternoordenservicio.';'.$row->nordenproducto.';'.$row->cmuestra.';'.$row->censayo ?></td>
                        <td><?php echo $row->POS ?></td>
                        <td><?php echo $row->censayofs ?></td>
                        <td><?php echo $row->densayo ?></td>
                        <td><?php echo $row->unidadmedida ?></td>
                        <td><?php echo $row->despecificacion ?></td>
                        <td><?php echo $row->despecificacionexp ?></td>
                        <td><?php echo $row->dresultado ?></td>
                        <td><?php echo $row->dresultadoexp ?></td>
                        <td><?php echo $row->sresultado ?></td>
                    </tr>
                    <?php
                }
            }
        }
    }
    
    public function getcboum() {	// Visualizar los Tipo Equipos de Registro	
		
		$resultado = $this->mregresult->getcboum();
		echo json_encode($resultado);
	}
    public function getum() {	// Visualizar los Tipo Equipos de Registro	
		
		$resultado = $this->mregresult->getum();
		echo json_encode($resultado);
	}

	public function pdfInformeMuestra($cinternoordenservicio,$cmuestra) { // recupera los cPTIZACION
        $this->load->library('pdfgenerator');

        $html = '<html>
                <head>
                    <title>IE</title>
                    <style>
                        @page {
                             margin: 0.3in 0.3in 0.3in 0.3in;
                        }
                        .teacherPage {
                            page: teacher;
                            page-break-after: always;
                        }
                        body{
                            font-family: Arial, Helvetica, sans-serif;
                            font-size: 9pt;
                        }  
                        .cuerpo {
                            text-align: justify;
                        }
                        img.izquierda {
                            float: left;
                        }
                        img.derecha {
                            float: right;
                        }

                        .list-unstyled {
                            padding-left: 0;
                            list-style: none;
                        }

                        th { 
                            text-align: center; 
                            border: 1px solid black;
                            background-color:#D5D7DE;
                        }
                    </style>
                </head>
                <body>';

        $html .= '<div>
                    <table width="700px" align="center">
                        <tr>
                            <td width="80px" align="center" >
                                <img src="'.public_url_ftp().'Imagenes/formatos/2/logoFSC.jpg" width="100" height="60" />    
                            </td>
                            <td align="center">
                                <ul class="list-unstyled">
                                    <li>LABORATORIO DE ENSAYO ACREDITADO POR EL</li>
                                    <li>ORGANISMO PERUANO DE ACREDITACIÓN INACAL - DA</li>
                                    <li>CON EL REGISTRO N° LE-073</li>
                                </ul>
                            </td>
                            <td width="130px" align="center" >
                                <img src="'.public_url_ftp().'Imagenes/formatos/2/logoFSC.jpg" width="100" height="60" /> 
                            </td>
                        </tr>
                    </table>';
                    
        $parametros = array(
			'@cinternoordenservicio'         => $cinternoordenservicio,
			'@cmuestra'       => $cmuestra,
        );
        $res = $this->mconsinf->getinfxmuestras_caratula($parametros);
        if ($res){
            foreach($res as $row){
				$NROINFORME        = $row->NROINFORME;
				$CLIENTE       = $row->CLIENTE;
				$DIRECCION               = $row->DIRECCION;
				$NROORDEN          = $row->NROORDEN;
				$PROCEDENCIA  = $row->PROCEDENCIA;
				$FMUESTRA          = $row->FMUESTRA;
				$FRECEPCION              = $row->FRECEPCION;
                $FANALISIS        = $row->FANALISIS;
                $LUGARMUESTRA          = $row->LUGARMUESTRA;
                $CMUESTRA          = $row->CMUESTRA;
                $DPRODUCTO               = $row->DPRODUCTO;
                $DTEMPERATURA         = $row->DTEMPERATURA;
                $DLCLAB         = $row->DLCLAB;
                $OBSERVACION         = $row->OBSERVACION;                
			}
        }
        
        $html .= '
                    <table width="600px" align="center" cellspacing="0" cellpadding="2" >
                        <tr>
                            <td width="100%" align="center" colspan="3">
                                <h2>INFORME DE ENSAYO N° '.$NROINFORME.'</h2>   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Nombre del cliente</b>   
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$CLIENTE.'   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Dirección del Cliente</b>  
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$DIRECCION.'   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>N° Orden de Servicio</b>   
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$NROORDEN.'   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Procedencia de la Muestra</b>  
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$PROCEDENCIA.'   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Fecha de Muestreo</b>    
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$FMUESTRA.'   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Fecha de Recepción</b>    
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$FRECEPCION.'<   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Fecha de Análisis</b>    
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$FANALISIS.'  
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Lugar de Muestreo</b>   
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$LUGARMUESTRA.'  
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Muestra / Descripción</b>    
                            </td>
                            <td width="8px" align="left">
                                : '.$CMUESTRA.'   
                            </td>
                            <td width="75%" align="left">
                                 '.$DPRODUCTO.'   
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" align="left">
                                <b>Temperatura de Recepción</b>    
                            </td>
                            <td width="75%" align="left" colspan="2">
                                : '.$DTEMPERATURA.'   
                            </td>
                        </tr>
                        <tr>
                            <td width="100%" align="center" colspan="3">
                            &nbsp;   
                            </td>
                        </tr>
                        <tr>
                            <td width="100%" align="center" colspan="3" style=" border-top:solid 3px #000000">
                                <h3>RESULTADOS DE ENSAYO</h3>   
                            </td>
                        </tr>
                    </table>';
                    /*RESULTADOS MICROBIOLOGIA*/
                    $resmicro = $this->mconsinf->getinfxmuestras_resmicro($parametros);
                    if ($resmicro){
                        $html .= '<table align="center" style="border-collapse: collapse; table-layout: fixed; width: 600px; border: 1px solid black;" border="1">
                        <tr>
                            <th width="45%"> <b>Ensayo</b> </th>
                            <th width="25%"> <b>Unidades</b> </th>
                            <th width="5%"> <b>Via</b> </th>
                            <th width="25%"> <b>Resultado</b> </th>
                        </tr>';
                        foreach($resmicro as $rowmicro){
                            $DENSAYO = $rowmicro->DENSAYO;
                            $UNIDADMEDIDA = $rowmicro->UNIDADMEDIDA;
                            $VIA = $rowmicro->VIA;
                            $RESULT_FINAL = $rowmicro->RESULT_FINAL;
                            $html .= '<tr>
                                <td>'.$DENSAYO.'</td>
                                <td align="center">'.$UNIDADMEDIDA.'</td>
                                <td align="center">'.$VIA.'</td>
                                <td align="center">'.$RESULT_FINAL.'</td>
                            </tr>';
                        }
                        $html .= '</table><br>';
                    }  
                    /*RESULTADOS FISICOQUIMICO*/
                    $resfq = $this->mconsinf->getinfxmuestras_resfq($parametros);
                    if ($resfq){
                        $html .= '<table align="center" style="border-collapse: collapse; table-layout: fixed; width: 600px; border: 1px solid black;" border="1">
                        <tr>
                            <th width="45%"> <b>Ensayo</b> </th>
                            <th width="25%"> <b>Unidades</b> </th>
                            <th width="5%"> <b>Via</b> </th>
                            <th width="25%"> <b>Resultado</b> </th>
                        </tr>';
                        foreach($resfq as $rowfq){
                            $DENSAYO = $rowfq->DENSAYO;
                            $UNIDADMEDIDA = $rowfq->UNIDADMEDIDA;
                            $VIA = $rowfq->VIA;
                            $RESULT_FINAL = $rowfq->RESULT_FINAL;
                            $html .= '<tr>
                                <td>'.$DENSAYO.'</td>
                                <td align="center">'.$UNIDADMEDIDA.'</td>
                                <td align="center">'.$VIA.'</td>
                                <td align="center">'.$RESULT_FINAL.'</td>
                            </tr>';
                        }
                        $html .= '</table><br>'.$DLCLAB;
                    }
        
        $resNOTA1 = $this->mconsinf->getinfxmuestras_nota01($parametros);
        if ($resNOTA1){
            foreach($resNOTA1 as $rowNOTA1){
				$NOTA01        = $rowNOTA1->NOTA01;
			}
        }
        
        $html .= '
                <table width="600px" align="center" cellspacing="0" cellpadding="2" >
                    <tr>
                        <td width="100%" align="center" colspan="3">
                            <h3>METODOS DE ENSAYO</h3>   
                        </td>
                    </tr>
                </table>';
        $html .= '
                <table width="600px" align="center" cellspacing="0" cellpadding="2" >
                    <tr>
                        <td width="100%" align="center" >
                            '.$NOTA01.'   
                        </td>
                    </tr> 
                </table>';
    
        /*RESULTADOS METODOS DE ENSAYO*/
        $resmetensa = $this->mconsinf->getmetodosensayos($parametros);
        if ($resmetensa){
            $html .= '<table align="center" style="border-collapse: collapse; table-layout: fixed; width: 600px; border: 1px solid black;" border="1">
                        <tr>
                            <th>
                                <b>Ensayo</b>   
                            </th>
                            <th>
                                <b>Norma o Referencia</b>    
                            </th>
                        </tr>';
                foreach($resmetensa as $rowmetensa){
                    $METDENSAYO = $rowmetensa->DENSAYO;
                    $METDNORMA = $rowmetensa->DNORMA;
                    $html .= '<tr>
                        <td width="40%">'.$METDENSAYO.'</td>
                        <td width="60%">'.$METDNORMA.'</td>
                    </tr>';
                }
            $html .= '</table><br>';
        }  
        $html .= '<table width="600px" align="center" cellpadding="2" style="border: 1px solid black;">
                    <tr>
                        <td width="100%" align="left" >
                            Observaciones .-   
                        </td>
                    </tr> 
                    <tr>
                        <td width="100%" align="center" >
                            <div style="text-align: justify;">'.$OBSERVACION.'</div>   
                        </td>
                    </tr> 
                </table> <br>';

        $resfooter = $this->mconsinf->getinfxmuestras_firmas($parametros);
        if ($resfooter){
            foreach($resfooter as $rowfooter){
				$FECHA      = $rowfooter->FECHA;
				$NOMBREM    = $rowfooter->NOMBREM;
				$NOMBREFQS  = $rowfooter->NOMBREFQS;
				$CARGOM     = $rowfooter->CARGOM;     
				$CARGOFQS   = $rowfooter->CARGOFQS;     
				$CODIGOM    = $rowfooter->CODIGOM;   
				$CODIGOFQS  = $rowfooter->CODIGOFQS;            
			}
        }        
        $html .= '<table width="600px" align="center" cellspacing="0" cellpadding="2">
                    <tr>
                        <td align="left">
                            Lima,'.$FECHA.'    
                        </td>
                    </tr>
                    <tr>
                        <td> 
                            <br>
                            <br>
                            <br>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" align="center">
                            '.$NOMBREFQS.'    
                        </td>
                        <td> 
                        </td>
                        <td width="50%" align="center">
                            '.$NOMBREM.'    
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            '.$CARGOFQS.'    
                        </td>
                        <td> 
                        </td>
                        <td align="center">
                            '.$CARGOM.'    
                        </td>
                    </tr> 
                    <tr>
                        <td align="center">
                            '.$CODIGOFQS.'    
                        </td>
                        <td> 
                        </td>
                        <td align="center">
                            '.$CODIGOM.'    
                        </td>
                    </tr> 
                </table> <br>';


        $html .= '</div></body></html>';
        $filename = 'IE-'.$NROINFORME;
        $this->pdfgenerator->generate($html, $filename, TRUE, 'A4', 'portrait');
        //echo $html;
    }

    public function exportexcellistcoti(){
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
					   'rgb' => '042C5C'
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
					   'rgb' => '042C5C'
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
           $celdastextocentro = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
           ];			
           $celdasnumerodec = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0.0',
                ],
           ];		
           $celdasnumero = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0',
                ],
           ];
        /*Estilos */	
        
        $varnull = '';

		$ccliente   = $this->input->post('cboclieserv');
		$chkFreg       = $this->input->post('chkFreg');
		$fini       = $this->input->post('txtFIni');
		$ffin       = $this->input->post('txtFFin');
		$descr      = $this->input->post('txtdescri');
		$estado      = $this->input->post('cboestado');
		$tieneot      = $this->input->post('cbotieneot');
        
        $parametros = array(
			'@CCIA'         => '2',
			'@CCLIENTE'     => ($this->input->post('cboclieserv') == '') ? '0' : $ccliente,
			'@FINI'         => ($this->input->post('chkFreg') == NULL) ? NULL : substr($fini, 6, 4).'-'.substr($fini,3 , 2).'-'.substr($fini, 0, 2),
			'@FFIN'         => ($this->input->post('chkFreg') == NULL) ? NULL : substr($ffin, 6, 4).'-'.substr($ffin,3 , 2).'-'.substr($ffin, 0, 2),
			'@DESCR'		=> ($this->input->post('txtdescri') == '') ? '%' : '%'.$descr.'%',
			'@ESTADO'		=> ($this->input->post('cboestado') == '%') ? '%' : $estado,
			'@TIENEOT'		=> ($this->input->post('cbotieneot') == '%') ? '%' : $tieneot,
			'@ACTIVO'       => 'A',
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(9);
        
        $sheet->setCellValue('A1', 'LISTADO DE COTIZACIONES')
			->mergeCells('A1:S1')
			->setCellValue('A3', 'Fecha Cotización')
			->setCellValue('B3', 'Nro Cotización')
			->setCellValue('C3', 'Estado Coti.')
			->setCellValue('D3', 'Cliente')
			->setCellValue('E3', 'Proveedor')
			->setCellValue('F3', 'Contacto')
			->setCellValue('G3', 'Elaborado por')
			->setCellValue('H3', 'Tipo de Pago')
			->setCellValue('I3', 'Moneda')
			->setCellValue('J3', 'Muestreo')
			->setCellValue('K3', 'Sub Total')
			->setCellValue('L3', 'Descuento %')
			->setCellValue('M3', 'Descuento')
			->setCellValue('N3', 'Monto sin IGV')
			->setCellValue('O3', 'IGV')
			->setCellValue('P3', 'Monto Total')
			->setCellValue('Q3', 'Nro OT')
			->setCellValue('R3', 'Fecha OT')
			->setCellValue('S3', 'Observación');

        $sheet->getStyle('A1:S1')->applyFromArray($titulo);
        $sheet->getStyle('A3:S3')->applyFromArray($cabecera);
		
		$sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(12.10);
		$sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(19.10);
		$sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(10.10);
		$sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(54.10);
		$sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(54.10);
		$sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(35.10);
		$sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(35.10);
		$sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(17.10);
		$sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(9.10);
		$sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(10.10);
		$sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(12.10);
		$sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(11.10);
		$sheet->getColumnDimension('M')->setAutoSize(false)->setWidth(11.10);
		$sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(12.10);
		$sheet->getColumnDimension('O')->setAutoSize(false)->setWidth(11.10);
		$sheet->getColumnDimension('P')->setAutoSize(false)->setWidth(12.10);
		$sheet->getColumnDimension('Q')->setAutoSize(false)->setWidth(19.10);
		$sheet->getColumnDimension('R')->setAutoSize(false)->setWidth(12.10);
        $sheet->getColumnDimension('S')->setAutoSize(false)->setWidth(72.10);
        
        $sheet->getStyle('Q')->getAlignment()->setWrapText(true);
        $sheet->getStyle('S')->getAlignment()->setWrapText(true);

		$rpt = $this->mcotizacion->getexcellistcoti($parametros);
		$irow = 4;
        if ($rpt){
        	foreach($rpt as $row){
                $DFECHA     = $row->DFECHA;
                $NROCOTI = $row->NROCOTI;
                $DESTADO = $row->DESTADO;
                $DCLIENTE = $row->DCLIENTE;
                $CPROVEEDOR = $row->CPROVEEDOR;
                $CONTACTO = $row->CONTACTO;
                $ELABORADO = $row->ELABORADO;
                $TIPOPAGO = $row->TIPOPAGO;
                $MONEDA = $row->MONEDA;
                $IMUESTREO = $row->IMUESTREO;
                $ISUBTOTAL = $row->ISUBTOTAL;
                $PDESCUENTO = $row->PDESCUENTO;
                $DDESCUENTO = $row->DDESCUENTO;
                $MONTOSINIGV = $row->MONTOSINIGV;
                $DIGV = $row->DIGV;
                $ITOTAL = $row->ITOTAL;
                $NROOT = $row->NROOT;
                $FOT = $row->FOT;
                $OBSERVA = $row->OBSERVA;

                $sheet->setCellValue('A'.$irow,$DFECHA);
                $sheet->setCellValue('B'.$irow,$NROCOTI);
                $sheet->setCellValue('C'.$irow,$DESTADO);
                $sheet->setCellValue('D'.$irow,$DCLIENTE);
                $sheet->setCellValue('E'.$irow,$CPROVEEDOR);
                $sheet->setCellValue('F'.$irow,$CONTACTO);
                $sheet->setCellValue('G'.$irow,$ELABORADO);
                $sheet->setCellValue('H'.$irow,$TIPOPAGO);
                $sheet->setCellValue('I'.$irow,$MONEDA);
                $sheet->setCellValue('J'.$irow,$IMUESTREO);
                $sheet->setCellValue('K'.$irow,$ISUBTOTAL);
                $sheet->setCellValue('L'.$irow,$PDESCUENTO);
                $sheet->setCellValue('M'.$irow,$DDESCUENTO);
                $sheet->setCellValue('N'.$irow,$MONTOSINIGV);
                $sheet->setCellValue('O'.$irow,$DIGV);
                $sheet->setCellValue('P'.$irow,$ITOTAL);
                $sheet->setCellValue('Q'.$irow,$NROOT);
                $sheet->setCellValue('R'.$irow,$FOT);
                $sheet->setCellValue('S'.$irow,$OBSERVA);

				$irow++;
			}
        }
        $posfin = $irow - 1;

        $sheet->getStyle('A4:S'.$posfin)->applyFromArray($celdastexto);
        $sheet->getStyle('A4:A'.$posfin)->applyFromArray($celdastextocentro);
        $sheet->getStyle('J4:K'.$posfin)->applyFromArray($celdasnumerodec);
        $sheet->getStyle('L4:L'.$posfin)->applyFromArray($celdasnumero);
        $sheet->getStyle('M4:P'.$posfin)->applyFromArray($celdasnumerodec);
        $sheet->getStyle('I4:I'.$posfin)->applyFromArray($celdastextocentro);
        
		$sheet->setTitle('Listado - Cotización');            
		$writer = new Xlsx($spreadsheet);
		$filename = 'listCotizacion-'.time();
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
    }
    
    public function setresultados() { // Registrar informe PT
		$varnull = '';
		$id_ser = $this->input->post('ID');
        $id_array = preg_split("/;/",$id_ser);

        $cinternoordenservicio  = $id_array[0];
		$nordenproducto 	    = $id_array[1];
        $cmuestra 	            = $id_array[2];
        $censayo 	            = $id_array[3];
        $zctipounidadmedida     = $this->input->post('unidadmedida');
        $condi_espe 	        = $this->input->post('condi_espe');
        $valor_espe 	        = $this->input->post('valor_espe');
        $valexpo_espe 	        = $this->input->post('valexpo_espe');
        $condi_resul 	        = $this->input->post('condi_resul');
        $valor_resul 	        = $this->input->post('valor_resul');
        $valexpo_resul 	        = $this->input->post('valexpo_resul');
        $sresultado 	        = $this->input->post('sresultado');

        $parametros = array(
            '@cinternoordenservicio'    =>  $cinternoordenservicio,
            '@nordenproducto'      		=>  $nordenproducto,
            '@cmuestra'    		        =>  $cmuestra,
            '@censayo'    		        =>  $censayo,
            '@zctipounidadmedida'    	=>  $zctipounidadmedida,
            '@condi_espe'    		    =>  $condi_espe,
            '@valor_espe'    		    =>  $valor_espe,
            '@valexpo_espe'    		    =>  $valexpo_espe,
            '@condi_resul'    		    =>  $condi_resul,
            '@valor_resul'    		    =>  $valor_resul,
            '@valexpo_resul'    		=>  $valexpo_resul,
            '@sresultado'    		    =>  $sresultado,
        );
        $retorna = $this->mregresult->setresultados($parametros);
        echo json_encode($retorna);	
    }
    
    public function setresultadosold() { // Registrar informe PT
		$varnull = '';
		$id_ser = $this->input->post('ID');
        $id_array = preg_split("/;/",$id_ser);

        $cinternoordenservicio  = $id_array[0];
		$nordenproducto 	    = $id_array[1];
        $cmuestra 	            = $id_array[2];
        $censayo 	            = $id_array[3];
        $zctipounidadmedida     = $this->input->post('unidadmedida');
        $despecificacion 	    = $this->input->post('despecificacion');
        $despecificacionexp     = $this->input->post('despecificacionexp');
        $dresultado 	        = $this->input->post('dresultado');
        $dresultadoexp 	        = $this->input->post('dresultadoexp');
        $sresultado 	        = $this->input->post('sresultado');

        $parametros = array(
            '@cinternoordenservicio'    =>  $cinternoordenservicio,
            '@nordenproducto'      		=>  $nordenproducto,
            '@cmuestra'    		        =>  $cmuestra,
            '@censayo'    		        =>  $censayo,
            '@zctipounidadmedida'    	=>  $zctipounidadmedida,
            '@despecificacion'          =>  $despecificacion,
            '@despecificacionexp'       =>  $despecificacionexp,
            '@dresultado'    		    =>  $dresultado,
            '@dresultadoexp'            =>  $dresultadoexp,
            '@sresultado'    		    =>  $sresultado,
        );
        $retorna = $this->mregresult->setresultadosold($parametros);
        echo json_encode($retorna);	
    }

    public function setservicio() { // Registrar informe PT
        $varnull = '';

        $fanalisis = $this->input->post('mtxtFanali');

        $cinternoordenservicio = $this->input->post('txtidordenservicio');        
        $fanalisis = ($this->input->post('mtxtFanali') == '%') ? NULL : substr($fanalisis, 6, 4).'-'.substr($fanalisis,3 , 2).'-'.substr($fanalisis, 0, 2);
        $hanalisis = $this->input->post('mtxtHanali');
        $dobservacionresultados = $this->input->post('txtobserva');
        $ctipoinforme = $this->input->post('mcbotipoinforme');

        $retorna = $this->mregresult->setservicio($cinternoordenservicio,$fanalisis,$hanalisis,$dobservacionresultados,$ctipoinforme);
        echo json_encode($retorna);	
    }


}
?>