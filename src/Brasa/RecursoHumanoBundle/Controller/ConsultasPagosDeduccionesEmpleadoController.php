<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ConsultasPagosDeduccionesEmpleadoController extends Controller
{
    var $strDqlLista = "";        
            
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arPagosDeduccionesEmpleados = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagosyDeduccionesEmpleados:lista.html.twig', array(
            'arPagosDeduccionesEmpleados' => $arPagosDeduccionesEmpleados,
            'form' => $form->createView()
            ));
    }
           
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaDqlPagosDeducciones(

                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }          
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }   
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'ARP')
                    ->setCellValue('L1', 'EPS')
                    ->setCellValue('M1', 'PENSION')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'SENA')
                    ->setCellValue('Q1', 'CESANTIAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'ADMON')
                    ->setCellValue('T1', 'COSTO')
                    ->setCellValue('U1', 'TOTAL')
                    ->setCellValue('W1', 'NETO')
                    ->setCellValue('X1', 'IBC')
                    ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('Z1', 'DIAS PERIODO')
                    ->setCellValue('AA1', 'SALARIO PERIODO')
                    ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagosDeduccionesEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagosDeduccionesEmpleados = $query->getResult();
        foreach ($arPagosDeduccionesEmpleados as $arPagosDeduccionesEmpleado) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagosDeduccionesEmpleado->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPagosDeduccionesEmpleado->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPagosDeduccionesEmpleado->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPagosDeduccionesEmpleado->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPagosDeduccionesEmpleado->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPagosDeduccionesEmpleado->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPagosDeduccionesEmpleado->getVrSalario())
                    ->setCellValue('H' . $i, $arPagosDeduccionesEmpleado->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPagosDeduccionesEmpleado->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPagosDeduccionesEmpleado->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPagosDeduccionesEmpleado->getVrArp())
                    ->setCellValue('L' . $i, $arPagosDeduccionesEmpleado->getVrEps())
                    ->setCellValue('M' . $i, $arPagosDeduccionesEmpleado->getVrPension())
                    ->setCellValue('N' . $i, $arPagosDeduccionesEmpleado->getVrCaja())
                    ->setCellValue('O' . $i, $arPagosDeduccionesEmpleado->getVrIcbf())
                    ->setCellValue('P' . $i, $arPagosDeduccionesEmpleado->getVrSena())
                    ->setCellValue('Q' . $i, $arPagosDeduccionesEmpleado->getVrCesantias())
                    ->setCellValue('R' . $i, $arPagosDeduccionesEmpleado->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPagosDeduccionesEmpleado->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPagosDeduccionesEmpleado->getVrCosto())
                    ->setCellValue('U' . $i, $arPagosDeduccionesEmpleado->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPagosDeduccionesEmpleado->getVrNeto())
                    ->setCellValue('X' . $i, $arPagosDeduccionesEmpleado->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPagosDeduccionesEmpleado->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPagosDeduccionesEmpleado->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPagosDeduccionesEmpleado->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPagosDeduccionesEmpleado->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagosDeduccionesEmpleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosDeduccionesEmpleados.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }       
}
