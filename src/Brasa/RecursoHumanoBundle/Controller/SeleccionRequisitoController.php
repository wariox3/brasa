<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionRequisitoType;
use Symfony\Component\HttpFoundation\Request;

class SeleccionRequisitoController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if ($form->get('BtnEliminar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->eliminarSeleccionRequisitos($arrSeleccionados);                
            }
            if ($form->get('BtnEstadoAbierto')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->estadoAbiertoSeleccionRequisitos($arrSeleccionados); 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form, $request);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arRequisitos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/SeleccionRequisito:lista.html.twig', array('arRequisitos' => $arRequisitos, 'form' => $form->createView()));     
    } 
    
    public function nuevoAction($codigoSeleccionRequisito) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        if($codigoSeleccionRequisito != 0) {
            $arRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccionRequisito);
        }
        $form = $this->createForm(new RhuSeleccionRequisitoType, $arRequisito);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arRequisito = $form->getData();
            $arRequisito->setFecha(new \DateTime('now'));
            $em->persist($arRequisito);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_seleccionrequisito_nuevo', array('codigoSeleccionRequisito' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_seleccionrequisito_lista'));
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/SeleccionRequisito:nuevo.html.twig', array(
            'arRequisito' => $arRequisito,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoSeleccionRequisito) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');                     
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnImprimir')->isClicked()) {                
                $objSeleccionRequisito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSeleccionRequisito();
                $objSeleccionRequisito->Generar($this, $codigoSeleccionRequisito);
            }
                      
        }        
        
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuSeleccion c where c.codigoSeleccionRequisitoFk = $codigoSeleccionRequisito";
        $query = $em->createQuery($dql);        
        $arSeleccion = $query->getResult();
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        $arRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccionRequisito);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/SeleccionRequisito:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arRequisito' => $arRequisito,
                    'form' => $form->createView()
                    ));
    }
 
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->get('session');
        
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->listaDQL(
                $session->get('filtroNombreSeleccionRequisito'),
                $session->get('filtroAbiertoSeleccionRequisito'),
                $session->get('filtroCodigoCargo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta')
                );  
    }
    
    private function filtrar ($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
                
        $session->set('filtroNombreSeleccionRequisito', $form->get('TxtNombre')->getData());                
        $session->set('filtroAbiertoSeleccionRequisito', $form->get('estadoAbierto')->getData());
        $session->set('filtroCodigoCargo', $controles['cargoRel']);
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();        
        $session = $this->get('session');
        $arrayPropiedadesCargo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCargo')) {
            $arrayPropiedadesCargo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCargo", $session->get('filtroCodigoCargo'));
        }
        $form = $this->createFormBuilder()
            ->add('cargoRel', 'entity', $arrayPropiedadesCargo)
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))        
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombreSeleccionRequisito')))
            ->add('estadoAbierto', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '00' => 'NO'), 'data' => $session->get('filtroAbiertoSeleccionRequisito'))) 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnEstadoAbierto', 'submit', array('label'  => 'Cerrar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }
    
    private function formularioDetalle() {        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();        
        return $form;
    }    
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $strSqlLista = $this->getRequest()->getSession();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'CARGO REQUISITO')
                    ->setCellValue('F1', 'CANTIDAD')
                    ->setCellValue('G1', 'CIUDAD')    
                    ->setCellValue('H1', 'ESTADO CIVIL')
                    ->setCellValue('I1', 'NIVEL DE ESTUDIO')
                    ->setCellValue('J1', 'EDAD MIN-MAX')
                    ->setCellValue('K1', 'NRO HIJOS')
                    ->setCellValue('L1', 'SEXO')
                    ->setCellValue('M1', 'TIPO VEHICULO')
                    ->setCellValue('N1', 'RELIGION')
                    ->setCellValue('O1', 'EXPERIENCIA')
                    ->setCellValue('P1', 'DISPONIBILIDAD')
                    ->setCellValue('Q1', 'LICENCIA CARRO')
                    ->setCellValue('R1', 'LICENCIA MOTO')
                    ->setCellValue('S1', 'ABIERTO');
                    

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arSeleccionRequisitos = $query->getResult();
        foreach ($arSeleccionRequisitos as $arSeleccionRequisito) {
            $strNombreCentroCosto = "";
            if($arSeleccionRequisito->getCentroCostoRel()) {
                $strNombreCentroCosto = $arSeleccionRequisito->getCentroCostoRel()->getNombre();
            }
            $strCargo = "";
            if($arSeleccionRequisito->getCargoRel()) {
                $strCargo = $arSeleccionRequisito->getCargoRel()->getNombre();
            }
            if ($arSeleccionRequisito->getEstadoAbierto() == 1){
                $abierto = "SI";
            } else {
                $abierto = "NO";
            }
            $strEstadoCivil = "";
            if($arSeleccionRequisito->getEstadoCivilRel()) {
                $strEstadoCivil = $arSeleccionRequisito->getEstadoCivilRel()->getNombre();
            }
            $strCiudad = "";
            if($arSeleccionRequisito->getCiudadRel()) {
                $strCiudad = $arSeleccionRequisito->getCiudadRel()->getNombre();
            }
            $strEstudioTipo = "";
            if($arSeleccionRequisito->getEstudioTipoRel()) {
                $strEstudioTipo = $arSeleccionRequisito->getEstudioTipoRel()->getNombre();
            }
            $sexo = "";
            if ($arSeleccionRequisito->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            }
            if ($arSeleccionRequisito->getCodigoSexoFk() == "F"){
                $sexo = "FEMENINO";
            }
            if ($arSeleccionRequisito->getCodigoSexoFk() == "I"){
                $sexo = "INDIFERENTE";
            }
            $tipoVehiculo = "";
            if ($arSeleccionRequisito->getCodigoTipoVehiculoFk() == "1"){
                $tipoVehiculo = "CARRO";
            }
            if ($arSeleccionRequisito->getCodigoTipoVehiculoFk() == "2"){
                $tipoVehiculo = "MOTO";
            }
            if ($arSeleccionRequisito->getCodigoTipoVehiculoFk() == "0"){
                $tipoVehiculo = "INDIFERENTE";
            }
            $religion = "";
            if ($arSeleccionRequisito->getCodigoReligionFk() == "1"){
                $religion = "CATOLICO";
            }
            if ($arSeleccionRequisito->getCodigoReligionFk() == "2"){
                $religion = "CRISTIANO";
            }
            if ($arSeleccionRequisito->getCodigoReligionFk() == "3"){
                $religion = "PROTESTANTE";
            }
            if ($arSeleccionRequisito->getCodigoReligionFk() == "4"){
                $religion = "INDIFERENTE";
            }
            $experiencia = "";
            if ($arSeleccionRequisito->getCodigoExperienciaFk() == "1"){
                $experiencia = "1 AÑO";
            }
            if ($arSeleccionRequisito->getCodigoExperienciaFk() == "2"){
                $experiencia = "2 AÑOS";
            }
            if ($arSeleccionRequisito->getCodigoExperienciaFk() == "3"){
                $experiencia = "3-4 AÑOS";
            }
            if ($arSeleccionRequisito->getCodigoExperienciaFk() == "4"){
                $experiencia = "5-10 AÑOS";
            }
            if ($arSeleccionRequisito->getCodigoExperienciaFk() == "5"){
                $experiencia = "GRADUADO";
            }
            if ($arSeleccionRequisito->getCodigoExperienciaFk() == "6"){
                $experiencia = "SIN EXPERIENCIA";
            }
            $disponibilidad = "";
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "1"){
                $disponibilidad = "TIEMPO COMPLETO";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "2"){
                $disponibilidad = "MEDIO TIEMPO";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "3"){
                $disponibilidad = "POR HORAS";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "4"){
                $disponibilidad = "DESDE CASA";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "5"){
                $disponibilidad = "PRACTICAS";
            }
            if ($arSeleccionRequisito->getCodigoDisponibilidadFk() == "0"){
                $disponibilidad = "NO APLICA";
            }
            $licenciaCarro = "";
            if ($arSeleccionRequisito->getCodigoLicenciaCarroFk() == "0"){
                $licenciaCarro = "NO APLICA";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaCarroFk() == "1"){
                $licenciaCarro = "SI";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaCarroFk() == "2"){
                $licenciaCarro = "NO";
            }
            $licenciaMoto = "";
            if ($arSeleccionRequisito->getCodigoLicenciaMotoFk() == "0"){
                $licenciaMoto = "NO APLICA";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaMotoFk() == "1"){
                $licenciaMoto = "SI";
            }
            if ($arSeleccionRequisito->getCodigoLicenciaMotoFk() == "2"){
                $licenciaMoto = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSeleccionRequisito->getCodigoSeleccionRequisitoPk())
                    ->setCellValue('B' . $i, $arSeleccionRequisito->getFecha())
                    ->setCellValue('C' . $i, $arSeleccionRequisito->getNombre())
                    ->setCellValue('D' . $i, $strNombreCentroCosto)
                    ->setCellValue('E' . $i, $strCargo)
                    ->setCellValue('F' . $i, $arSeleccionRequisito->getCantidadSolicitida())
                    ->setCellValue('G' . $i, $strCiudad)
                    ->setCellValue('H' . $i, $strEstadoCivil)
                    ->setCellValue('I' . $i, $strEstudioTipo)
                    ->setCellValue('J' . $i, $arSeleccionRequisito->getEdadMinimaMaxima())
                    ->setCellValue('K' . $i, $arSeleccionRequisito->getNumeroHijos())
                    ->setCellValue('L' . $i, $sexo)
                    ->setCellValue('M' . $i, $tipoVehiculo)
                    ->setCellValue('N' . $i, $religion)
                    ->setCellValue('O' . $i, $experiencia)
                    ->setCellValue('P' . $i, $disponibilidad)
                    ->setCellValue('Q' . $i, $licenciaCarro)
                    ->setCellValue('R' . $i, $licenciaMoto)
                    ->setCellValue('S' . $i, $abierto);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('RequisitosSeleccion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RequisitosSeleccion.xlsx"');
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
