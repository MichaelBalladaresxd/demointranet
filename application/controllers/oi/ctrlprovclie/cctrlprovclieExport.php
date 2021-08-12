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

class CctrlprovclieExport extends CI_Controller {
	function __construct() {
		parent:: __construct();
		$this->load->model('oi/ctrlprovclie/mconsseguiaacc');
		$this->load->model('oi/ctrlprovclie/mconschecklist');
		$this->load->model('oi/ctrlprovclie/mconsproxinsp');		
			
	}
	
	public function excelconsseguiaacc() {
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
           $celdastextocentro = [
               'font'	=> [
                   'name' => 'Arial',
                   'size' => 10,
                   'color' => array('rgb' => '000000'),
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
           $celdasnumero = [
               'alignment' => [
                   'horizontal' => Alignment::HORIZONTAL_RIGHT,
                   'vertical' => Alignment::VERTICAL_CENTER,
                   'wrapText' => true,
               ],
           ];			
           $formatonroundec = [
               'numberFormat' => [
                   'formatCode' => '#,##0.0',
               ],
           ];
		/*Estilos */	

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Seguimiento Acc. Corr.');

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(9);
		
		$sheet->setCellValue('A1', 'LISTADO DE SEGUIMIENTO DE ACCIONES CORRECTIVAS')
			->mergeCells('A1:F1')
			->setCellValue('A2', 'CLIENTE:')
			->mergeCells('B2:D2')
			->setCellValue('A4', 'N°')
			->setCellValue('B4', 'Categoria')
			->setCellValue('C4', 'Nro. de Proveedores')
			->setCellValue('D4', 'Nro. de Inspecciones')
			->setCellValue('E4', 'Inspecciones con Acciones Correctivas Concluídas')
			->setCellValue('F4', '% de Inspecciones con Acciones Correctivas');

		$sheet->getStyle('A1:F1')->applyFromArray($titulo);
		$sheet->getStyle('A4:F4')->applyFromArray($cabecera);
		
		$sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(9.10);
		$sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(45.10);
		$sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(20.10);
		$sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(20.10);
		$sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(25.10);
		$sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(25.10);

			$varnull = '';
            
            $ccliente       = $this->input->post('hdnCCliente');
            $anio           = $this->input->post('cboAnio');
            $mes            = $this->input->post('cboMes');
            $fini           = $this->input->post('txtFIni');
            $ffin           = $this->input->post('txtFFin');
            $area           = $this->input->post('cboareaclie');
            $periodo        = $this->input->post('hrdbuscar');

            if($periodo == 'P'){
                $fini = '%';
                $ffin = '%';
            }
        
            $parametros = array(
                '@CCLIENTE'		=>  $ccliente,
                '@ANIO'		    =>  $anio,
                '@MES'		    =>  ($mes == $varnull) ? 0 : $mes,
                '@FINI' 		=>  ($fini == '%') ? NULL : substr($fini, 6, 4).'-'.substr($fini,3 , 2).'-'.substr($fini, 0, 2), 
                '@FFIN' 		=>  ($ffin == '%') ? NULL : substr($ffin, 6, 4).'-'.substr($ffin,3 , 2).'-'.substr($ffin, 0, 2),
                '@AREA'		    =>  ($area == $varnull) ? '%' : $area,
            );	

		$rpt = $this->mconsseguiaacc->getconsseguiaacc($parametros);
		$irow = 5;
		$ipos = 1;
        if ($rpt){
        	foreach($rpt as $row){	

				$CLIENTE = $row->CLIENTE;
				$AREACLIENTE = $row->AREACLIENTE;
				$NROPROVEEDOR = $row->NROPROVEEDOR;
				$TOTALAC = $row->TOTALAC;
				$ACRECUPERADAS = $row->ACRECUPERADAS;
				$PTOTALACRE = $row->PTOTALACRE;

				$sheet->setCellValue('A'.$irow,$ipos);
				$sheet->setCellValue('B'.$irow,$AREACLIENTE);
				$sheet->setCellValue('C'.$irow,$NROPROVEEDOR);
				$sheet->setCellValue('D'.$irow,$TOTALAC);
				$sheet->setCellValue('E'.$irow,$ACRECUPERADAS);
				$sheet->setCellValue('F'.$irow,$PTOTALACRE);
				
				$ipos++;
				$irow++;
			}
		}
		$sheet->setCellValue('B2',$CLIENTE);
		$pos = $irow - 1;

		$sheet->getStyle('A5:F'.$pos)->applyFromArray($celdastexto);
		$sheet->getStyle('C5:F'.$pos)->applyFromArray($celdasnumero);

		$sheet->setAutoFilter('A4:F'.$pos);

		$writer = new Xlsx($spreadsheet);
		$filename = 'RepSeguiAACC-'.time().'.xlsx"';
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename);
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}	
	public function excelconsdetseguiaacc() {
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
           $celdastextocentro = [
               'font'	=> [
                   'name' => 'Arial',
                   'size' => 10,
                   'color' => array('rgb' => '000000'),
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
           $celdasnumero = [
               'alignment' => [
                   'horizontal' => Alignment::HORIZONTAL_RIGHT,
                   'vertical' => Alignment::VERTICAL_CENTER,
                   'wrapText' => true,
               ],
           ];			
           $formatonroundec = [
               'numberFormat' => [
                   'formatCode' => '#,##0.00',
               ],
           ];
		/*Estilos */	

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Detalle Seguimiento Acc. Corr.');

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(9);
		
		$sheet->setCellValue('A1', 'LISTADO DETALLE DE SEGUIMIENTO DE AA. CC.')
			->mergeCells('A1:J1')
			->setCellValue('A2', 'CLIENTE:')
			->setCellValue('A3', 'AREA:')
			->mergeCells('B2:D2')
			->mergeCells('B3:D3')
			->setCellValue('A5', 'N°')
			->setCellValue('B5', 'Proveedor')
			->setCellValue('C5', 'Linea de Proceso')
			->setCellValue('D5', 'Nro. de Informe')
			->setCellValue('E5', 'Resultado (%)')
			->setCellValue('F5', 'Resultado (%)')
			->setCellValue('G5', 'Fecha Envio AA. CC.')
			->setCellValue('H5', 'AA. CC. Concluidas')
			->setCellValue('I5', 'AA. CC. por Realizar')
			->setCellValue('J5', 'Total AA. CC.');

		$sheet->getStyle('A1:J1')->applyFromArray($titulo);
		$sheet->getStyle('A5:J5')->applyFromArray($cabecera);
		
		$sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(9.10);
		$sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(55.10);
		$sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(55.10);
		$sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(20.10);
		$sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(20.10);
		$sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(20.10);
		$sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(22.10);
		$sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(22.10);
		$sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(22.10);
		$sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(22.10);

			$varnull = '';
            
            $ccliente       = $this->input->post('hddnmdetccliente');
            $anio           = $this->input->post('hddnmdetanio');
            $mes            = $this->input->post('hddnmdetmes');
            $fini           = $this->input->post('hddnmdetfini');
            $ffin           = $this->input->post('hddnmdetffin');
            $area           = $this->input->post('hddnmdetarea');
        
            $parametros = array(
                '@CCLIENTE'		=>  $ccliente,
                '@ANIO'		    =>  $anio,
                '@MES'		    =>  ($mes == $varnull) ? 0 : $mes,
                '@FINI' 		=>  ($fini == '%') ? NULL : substr($fini, 6, 4).'-'.substr($fini,3 , 2).'-'.substr($fini, 0, 2), 
                '@FFIN' 		=>  ($ffin == '%') ? NULL : substr($ffin, 6, 4).'-'.substr($ffin,3 , 2).'-'.substr($ffin, 0, 2),
                '@AREA'		    =>  $area,
            );	

		$rpt = $this->mconsseguiaacc->getdetseguiaacc($parametros);
		$irow = 6;
		$ipos = 1;
        if ($rpt){
        	foreach($rpt as $row){	

				$CLIENTE = $row->CLIENTE;
				$AREACLIENTE = $row->AREACLIENTE;
				$PROVEEDOR = $row->PROVEEDOR;
				$LINEACLIENTE = $row->LINEACLIENTE;
				$NROINFORME = $row->NROINFORME;
				$RESULTADOCHECKLIST = $row->RESULTADOCHECKLIST;
				$descripcion_result = $row->descripcion_result;
				$FACEPTACORRECTIVA = $row->FACEPTACORRECTIVA;
				$ACRECUPERADAS = $row->ACRECUPERADAS;
				$ACPORREALIZAR = $row->ACPORREALIZAR;
				$TOTALAC = $row->TOTALAC;

				$sheet->setCellValue('A'.$irow,$ipos);
				$sheet->setCellValue('B'.$irow,$PROVEEDOR);
				$sheet->setCellValue('C'.$irow,$LINEACLIENTE);
				$sheet->setCellValue('D'.$irow,$NROINFORME);
				$sheet->setCellValue('E'.$irow,$RESULTADOCHECKLIST);
				$sheet->setCellValue('F'.$irow,$descripcion_result);
				$sheet->setCellValue('G'.$irow,$FACEPTACORRECTIVA);
				$sheet->setCellValue('H'.$irow,$ACRECUPERADAS);
				$sheet->setCellValue('I'.$irow,$ACPORREALIZAR);
				$sheet->setCellValue('J'.$irow,$TOTALAC);
				
				$ipos++;
				$irow++;
			}
		}
		$sheet->setCellValue('B2',$CLIENTE);
		$sheet->setCellValue('B3',$AREACLIENTE);
		$pos = $irow - 1;

		$sheet->getStyle('A6:J'.$pos)->applyFromArray($celdastexto);
		$sheet->getStyle('D6:D'.$pos)->applyFromArray($celdastextocentro);
		$sheet->getStyle('F6:F'.$pos)->applyFromArray($celdastextocentro);
		$sheet->getStyle('E6:E'.$pos)->applyFromArray($celdasnumero);
		$sheet->getStyle('E6:E'.$pos)->applyFromArray($formatonroundec);
		$sheet->getStyle('G6:J'.$pos)->applyFromArray($celdasnumero);

		$sheet->setAutoFilter('A5:J'.$pos);

		$writer = new Xlsx($spreadsheet);
		$filename = 'RepDetSeguiAACC-'.time().'.xlsx"';
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename);
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
	
	public function excelconschecklist() {
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
           $celdastextocentro = [
               'font'	=> [
                   'name' => 'Arial',
                   'size' => 10,
                   'color' => array('rgb' => '000000'),
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
           $celdasnumero = [
               'alignment' => [
                   'horizontal' => Alignment::HORIZONTAL_RIGHT,
                   'vertical' => Alignment::VERTICAL_CENTER,
                   'wrapText' => true,
               ],
           ];			
           $formatonroundec = [
               'numberFormat' => [
                   'formatCode' => '#,##0.00',
               ],
           ];
		/*Estilos */	

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('List Checklist');

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(9);
		
		$sheet->setCellValue('A1', 'LISTADO DE CHECKLIST')
			->mergeCells('A1:C1')
			->setCellValue('A3', 'Codigo')
			->setCellValue('B3', 'Check List Descripción')
			->setCellValue('C3', 'Servicio')
			->setCellValue('D3', 'Sistema')
			->setCellValue('E3', 'Organismo')
			->setCellValue('F3', 'Rubro')
			->setCellValue('G3', 'Uso');

		$sheet->getStyle('A1:G1')->applyFromArray($titulo);
		$sheet->getStyle('A3:G3')->applyFromArray($cabecera);
		
		$sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(10.10);
		$sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(65.10);
		$sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(30.10);
		$sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(40.10);
		$sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(20.10);
		$sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(45.10);
		$sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(7.10);
			
			$ccliente       = $this->input->post('hdnCCliente');
			
			$parametros = array(
				'@CCLIENTE'		=>  $ccliente,
			);
			
		$rpt = $this->mconschecklist->getconschecklist($parametros);
		$irow = 4;
		$ipos = 1;
        if ($rpt){
        	foreach($rpt as $row){	

				$cchecklist		= $row->cchecklist;
				$dchecklist		= $row->dchecklist;
				$dservicio		= $row->dservicio;
				$dsistema		= $row->dsistema;
				$dnorma			= $row->dnorma;
				$dsubnorma		= $row->dsubnorma;
				$suso			= $row->suso;

				$sheet->setCellValue('A'.$irow,$cchecklist);
				$sheet->setCellValue('B'.$irow,$dchecklist);
				$sheet->setCellValue('C'.$irow,$dservicio);
				$sheet->setCellValue('D'.$irow,$dsistema);
				$sheet->setCellValue('E'.$irow,$dnorma);
				$sheet->setCellValue('F'.$irow,$dsubnorma);
				$sheet->setCellValue('G'.$irow,$suso);
				
				$ipos++;
				$irow++;
			}
		}
		$pos = $irow - 1;

		$sheet->getStyle('A4:G'.$pos)->applyFromArray($celdastexto);

		$sheet->setAutoFilter('A3:G'.$pos);

		$writer = new Xlsx($spreadsheet);
		$filename = 'RepListChecklist-'.time().'.xlsx"';
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename);
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}	
	public function exceldetchecklist() {
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
           $celdastextocentro = [
               'font'	=> [
                   'name' => 'Arial',
                   'size' => 10,
                   'color' => array('rgb' => '000000'),
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
           $celdasnumero = [
               'alignment' => [
                   'horizontal' => Alignment::HORIZONTAL_RIGHT,
                   'vertical' => Alignment::VERTICAL_CENTER,
                   'wrapText' => true,
               ],
           ];			
           $formatonroundec = [
               'numberFormat' => [
                   'formatCode' => '#,##0.00',
               ],
           ];
		/*Estilos */	

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Detalle Checklist');

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(9);
		
		$sheet->setCellValue('A1', 'DETALLE CHECKLIST')
			->mergeCells('A1:C1')
			->setCellValue('A2', 'CHECKLIST:')
			->mergeCells('B2:C2')
			->setCellValue('A4', 'Item')
			->setCellValue('B4', 'Aspecto Evaluado')
			->setCellValue('C4', 'Valor Maximo')
			->setCellValue('D4', 'Normativa');

		$sheet->getStyle('A1:D1')->applyFromArray($titulo);
		$sheet->getStyle('A4:D4')->applyFromArray($cabecera);
		
		$sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(15.10);
		$sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(70.10);
		$sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(10.10);
		$sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(60.10);
			
			$cchecklist	= $this->input->post('hddnmdetcchecklist');
			$dchecklist	= $this->input->post('hddnmdetdchecklist');

		$rpt = $this->mconschecklist->getlistchecklist($cchecklist);
		$irow = 5;
		$ipos = 1;
        if ($rpt){
        	foreach($rpt as $row){	

				$DNUMERADOR		= $row->DNUMERADOR;
				$DREQUISITO		= $row->DREQUISITO;
				$valor_maximo	= $row->valor_maximo;
				$DNORMATIVA		= $row->DNORMATIVA;

				$sheet->setCellValue('A'.$irow,$DNUMERADOR);
				$sheet->setCellValue('B'.$irow,$DREQUISITO);
				$sheet->setCellValue('C'.$irow,$valor_maximo);
				$sheet->setCellValue('D'.$irow,$DNORMATIVA);
				
				$ipos++;
				$irow++;
			}
		}
		$sheet->setCellValue('B2',$dchecklist);
		$pos = $irow - 1;

		$sheet->getStyle('A4:D'.$pos)->applyFromArray($celdastexto);

		$sheet->setAutoFilter('A4:D'.$pos);

		$writer = new Xlsx($spreadsheet);
		$filename = 'RepDetChecklist-'.time().'.xlsx"';
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename);
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}	
	
	public function excelconsproxinsp() {
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
           $celdastextocentro = [
               'font'	=> [
                   'name' => 'Arial',
                   'size' => 10,
                   'color' => array('rgb' => '000000'),
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
           $celdasnumero = [
               'alignment' => [
                   'horizontal' => Alignment::HORIZONTAL_RIGHT,
                   'vertical' => Alignment::VERTICAL_CENTER,
                   'wrapText' => true,
               ],
           ];			
           $formatonroundec = [
               'numberFormat' => [
                   'formatCode' => '#,##0.00',
               ],
           ];
		/*Estilos */	

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('List Prox Insp');

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(9);
		
		$sheet->setCellValue('A1', 'LISTADO DE PROXIMAS INSPECCIONES')
			->mergeCells('A1:C1')
			->setCellValue('A3', 'Nro')
			->setCellValue('B3', 'Fecha Inspección')
			->setCellValue('C3', 'Proveedor')
			->setCellValue('D3', 'Área')
			->setCellValue('E3', 'Línea')
			->setCellValue('F3', 'Observación');

		$sheet->getStyle('A1:F1')->applyFromArray($titulo);
		$sheet->getStyle('A3:F3')->applyFromArray($cabecera);
		
		$sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(7.10);
		$sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(15.10);
		$sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(60.10);
		$sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(30.10);
		$sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(60.10);
		$sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(50.10);

			$varnull    =   '';

			$ccliente       = $this->input->post('hdnCCliente');
			$anio           = $this->input->post('cboAnio');
			$mes            = $this->input->post('cboMes');
			$fini           = $this->input->post('txtFIni');
			$ffin           = $this->input->post('txtFFin');
			$cclienteprov   = $this->input->post('cboProveedor');
			$area           = $this->input->post('cboareaclie');
			$dlinea         = $this->input->post('txtlinea');
            $periodo        = $this->input->post('hrdbuscar');

            if($periodo == 'P'){
                $fini = '%';
                $ffin = '%';
            }			
			
			$parametros = array(
				'@CCLIENTE'		=>  $ccliente,
				'@ANIO'		    =>  $anio,
				'@MES'		    =>  ($this->input->post('cboMes') == $varnull) ? 0 : $mes,
				'@FINI' 		=>  ($fini == '%') ? NULL : substr($fini, 6, 4).'-'.substr($fini,3 , 2).'-'.substr($fini, 0, 2), 
				'@FFIN' 		=>  ($ffin == '%') ? NULL : substr($ffin, 6, 4).'-'.substr($ffin,3 , 2).'-'.substr($ffin, 0, 2),
				'@CCLIENTEPROV'	=>  ($this->input->post('cboProveedor') == $varnull) ? '%' : $cclienteprov, 
				'@AREA'		    =>  ($this->input->post('cboareaclie') == $varnull) ? '%' : $area,
				'@DLINEA'       =>  ($this->input->post('txtlinea') == $varnull) ? '%' : '%'.$dlinea.'%',
			);
			
			
		$rpt = $this->mconsproxinsp->getconsproxinsp($parametros);
		$irow = 4;
		$ipos = 1;
        if ($rpt){
        	foreach($rpt as $row){	

				$FECHAINSPECCION	= $row->FECHAINSPECCION;
				$PROVEEDOR			= $row->PROVEEDOR;
				$AREACLIENTE		= $row->AREACLIENTE;
				$LINEA				= $row->LINEA;
				$OBSERVACION		= $row->OBSERVACION;

				$sheet->setCellValue('A'.$irow,$ipos);
				$sheet->setCellValue('B'.$irow,$FECHAINSPECCION);
				$sheet->setCellValue('C'.$irow,$PROVEEDOR);
				$sheet->setCellValue('D'.$irow,$AREACLIENTE);
				$sheet->setCellValue('E'.$irow,$LINEA);
				$sheet->setCellValue('F'.$irow,$OBSERVACION);
				
				$ipos++;
				$irow++;
			}
		}
		$pos = $irow - 1;

		$sheet->getStyle('A4:F'.$pos)->applyFromArray($celdastexto);

		$sheet->setAutoFilter('A3:F'.$pos);

		$writer = new Xlsx($spreadsheet);
		$filename = 'RepListProxInsp-'.time().'.xlsx"';
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename);
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}
}
?>