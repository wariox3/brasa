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
        $arRequisicion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito();
        $arRequisicion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->find($codigoSeleccionRequisito);
        $arRequisicionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
        $arRequisicionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->findBy(array ('codigoSeleccionRequisitoFk' => $codigoSeleccionRequisito));
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnImprimir')->isClicked()) {                
                $objSeleccionRequisito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSeleccionRequisito();
                $objSeleccionRequisito->Generar($this, $codigoSeleccionRequisito);
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if($arRequisicion->getEstadoCerrado() == 0) {
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->eliminarDetallesSeleccionados($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_rhu_seleccionrequisito_detalle', array('codigoSeleccionRequisito' => $codigoSeleccionRequisito)));
                } else {
                    $objMensaje->Mensaje('error', 'No se puede eliminar, la requisicion esta cerrada', $this);
                }
            }
            if($form->get('BtnAprobarDetalle')->isClicked()) {
                if($arRequisicion->getEstadoCerrado() == 0) {
                    $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->aprobarDetallesSeleccionados($arrSeleccionados);
                    if ($strRespuesta == ''){
                        return $this->redirect($this->generateUrl('brs_rhu_seleccionrequisito_detalle', array('codigoSeleccionRequisito' => $codigoSeleccionRequisito)));
                    }else{
                        $objMensaje->Mensaje('error', $strRespuesta, $this);
                    }
                } else {
                    $objMensaje->Mensaje('error', 'No se puede aprobar, la requisicion esta cerrada', $this);
                }
            }
            if($form->get('BtnExcelAspirante')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                ob_clean();
                // Set document properties
                $objPHPExcel->getProperties()->setCreator("EMPRESA")
                    ->setLastModifiedBy("EMPRESA")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
                for($col = 'A'; $col !== 'AR'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
                }
                $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CODIGO')
                            ->setCellValue('B1', 'IDENTIFICACION')
                            ->setCellValue('C1', 'NOMBRE')
                            ->setCellValue('D1', 'APROBADO');
                $i = 2;
                //$arBancos = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->findAll();
                
                foreach ($arRequisicionDetalle as $arRequisicionDetalle) {
                    if ($arRequisicionDetalle->getEstadoAprobado() == 1){
                        $estado = "SI";
                    } else {
                        $estado = "NO";
                    }
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arRequisicionDetalle->getCodigoAspiranteFk())
                            ->setCellValue('B' . $i, $arRequisicionDetalle->getAspiranteRel()->getNumeroIdentificacion())
                            ->setCellValue('C' . $i, $arRequisicionDetalle->getAspiranteRel()->getNombreCorto())
                            ->setCellValue('D' . $i, $estado);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('aspirantes');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="aspirantes.xlsx"');
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
        
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuSeleccion c where c.codigoSeleccionRequisitoFk = $codigoSeleccionRequisito";
        $query = $em->createQuery($dql);        
        $arSeleccion = $query->getResult();        
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/SeleccionRequisito:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arRequisito' => $arRequisicion,
                    'arRequisicionDetalle' => $arRequisicionDetalle,
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
        $session->set('filtroAbiertoSeleccionRequisito', $form->get('estadoCerrado')->getData());
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
            ->add('estadoCerrado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAbiertoSeleccionRequisito'))) 
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
            ->add('BtnAprobarDetalle', 'submit', array('label'  => 'Aprobar',))
            ->add('BtnEliminarDetalle', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcelAspirante', 'submit', array('label'  => 'Excel'))    
            ->getForm();        
        return $form;
    }    
    
    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
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
                    ->setCellValue('J1', 'EDAD MINIMA')
                    ->setCellValue('K1', 'EDAD MAXIMA')
                    ->setCellValue('L1', 'NRO HIJOS')
                    ->setCellValue('M1', 'SEXO')
                    ->setCellValue('N1', 'TIPO VEHICULO')
                    ->setCellValue('O1', 'RELIGION')
                    ->setCellValue('P1', 'EXPERIENCIA')
                    ->setCellValue('Q1', 'DISPONIBILIDAD')
                    ->setCellValue('R1', 'LICENCIA CARRO')
                    ->setCellValue('S1', 'LICENCIA MOTO')
                    ->setCellValue('T1', 'CERRADO')
                    ->setCellValue('U1', 'COMENTARIOS');
                    

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
            if ($arSeleccionRequisito->getEstadoCerrado() == 1){
                $cerrado = "SI";
            } else {
                $cerrado = "NO";
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
            if ($arSeleccionRequisito->getCodigoExperienciaRequisicionFk() != null){
                $experiencia = $arSeleccionRequisito->getExperienciaRequisicionRel()->getNombre();
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
                    ->setCellValue('J' . $i, $arSeleccionRequisito->getEdadMinima())
                    ->setCellValue('K' . $i, $arSeleccionRequisito->getEdadMaxima())
                    ->setCellValue('L' . $i, $arSeleccionRequisito->getNumeroHijos())
                    ->setCellValue('M' . $i, $sexo)
                    ->setCellValue('N' . $i, $tipoVehiculo)
                    ->setCellValue('O' . $i, $religion)
                    ->setCellValue('P' . $i, $experiencia)
                    ->setCellValue('Q' . $i, $disponibilidad)
                    ->setCellValue('R' . $i, $licenciaCarro)
                    ->setCellValue('S' . $i, $licenciaMoto)
                    ->setCellValue('T' . $i, $cerrado)
                    ->setCellValue('U' . $i, $arSeleccionRequisito->getComentarios());
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
