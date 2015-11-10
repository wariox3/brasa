<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurProgramacionType;
use Brasa\TurnoBundle\Form\Type\TurProgramacionDetalleType;
class ProgramacionController extends Controller
{
    var $strListaDql = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->eliminarExamen($arrSeleccionados);
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }
        
        $arProgramaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:lista.html.twig', array(
            'arProgramaciones' => $arProgramaciones, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoProgramacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        if($codigoProgramacion != 0) {
            $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        }else{
            $arProgramacion->setFecha(new \DateTime('now'));            
        }        
        $form = $this->createForm(new TurProgramacionType, $arProgramacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProgramacion = $form->getData();            
            $em->persist($arProgramacion);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_programacion_nuevo', array('codigoProgramacion' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $arProgramacion->getCodigoProgramacionPk())));
            }
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:nuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'form' => $form->createView()));
    }        

    public function detalleAction($codigoProgramacion) {
        $em = $this->getDoctrine()->getManager(); 
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->formularioDetalle($arProgramacion);
        $form->handleRequest($request);
        if($form->isValid()) {            
            if ($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);                                                            
                    if($arrControles['TxtDia1'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia1($arrControles['TxtDia1'.$intCodigo]);                                                
                    }
                    if($arrControles['TxtDia2'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia2($arrControles['TxtDia2'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia3'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia3($arrControles['TxtDia3'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia4'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia4($arrControles['TxtDia4'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia5'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia5($arrControles['TxtDia5'.$intCodigo]);                  
                    }                    
                    if($arrControles['TxtDia6'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia6($arrControles['TxtDia6'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia7'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia7($arrControles['TxtDia7'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia8'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia8($arrControles['TxtDia8'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia9'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia9($arrControles['TxtDia9'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia10'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia10($arrControles['TxtDia10'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia11'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia11($arrControles['TxtDia11'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia12'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia12($arrControles['TxtDia12'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia13'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia13($arrControles['TxtDia13'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia14'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia14($arrControles['TxtDia14'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia15'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia15($arrControles['TxtDia15'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia16'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia16($arrControles['TxtDia16'.$intCodigo]);                  
                    }
                    if($arrControles['TxtDia17'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia17($arrControles['TxtDia17'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia18'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia18($arrControles['TxtDia18'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia19'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia19($arrControles['TxtDia19'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia20'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia20($arrControles['TxtDia20'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia21'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia21($arrControles['TxtDia21'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia22'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia22($arrControles['TxtDia22'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia23'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia23($arrControles['TxtDia23'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia24'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia24($arrControles['TxtDia24'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia25'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia25($arrControles['TxtDia25'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia26'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia26($arrControles['TxtDia26'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia27'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia27($arrControles['TxtDia27'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia28'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia28($arrControles['TxtDia28'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia29'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia29($arrControles['TxtDia29'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia30'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia30($arrControles['TxtDia30'.$intCodigo]);                 
                    }
                    if($arrControles['TxtDia31'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia31($arrControles['TxtDia31'.$intCodigo]);                 
                    }                    
                    $em->persist($arProgramacionDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }            
        }

        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array ('codigoProgramacionFk' => $codigoProgramacion));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalle.html.twig', array(
                    'arProgramacion' => $arProgramacion,
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoProgramacion, $codigoProgramacionDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        if($codigoProgramacionDetalle != 0) {
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigoProgramacionDetalle);
        }       
        $form = $this->createForm(new TurProgramacionDetalleType, $arProgramacionDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProgramacionDetalle = $form->getData();            
            $arProgramacionDetalle->setProgramacionRel($arProgramacion);
            $em->persist($arProgramacionDetalle);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle_nuevo', array('codigoProgramacion' => $codigoProgramacion, 'codigoProgramacionDetalle' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'form' => $form->createView()));
    }   
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurProgramacion')->listaDQL();
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest(); 
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroAprobadoExamen', $form->get('estadoAprobado')->getData());
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAprobadoExamen')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonDetalleActualizar['disabled'] = true;
        }        
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)    
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)            
                    ->getForm();  
        return $form;
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'NOMBRES Y APELLIDOS')
                    ->setCellValue('D1', 'EDAD')
                    ->setCellValue('E1', 'SEXO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'ENTIDAD / LABORATORIO')
                    ->setCellValue('I1', 'CIUDAD')
                    ->setCellValue('J1', 'FECHA EXAMEN')
                    ->setCellValue('K1', 'AÑO EXAMEN')
                    ->setCellValue('L1', 'MES EXAMEN')
                    ->setCellValue('M1', 'DIA EXAMEN')
                    ->setCellValue('N1', 'TIPO EXAMEN')
                    ->setCellValue('O1', 'TOTAL')
                    ->setCellValue('P1', 'APROBADO')
                    ->setCellValue('Q1', 'COMENTARIOS GENERALES')
                    ->setCellValue('R1', 'EXAMEN 1')
                    ->setCellValue('S1', 'ESTADO')
                    ->setCellValue('T1', 'OBSERVACIONES')
                    ->setCellValue('U1', 'EXAMEN 2')
                    ->setCellValue('V1', 'ESTADO')
                    ->setCellValue('W1', 'OBSERVACIONES')
                    ->setCellValue('X1', 'EXAMEN 3')
                    ->setCellValue('Y1', 'ESTADO')
                    ->setCellValue('Z1', 'OBSERVACIONES')
                    ->setCellValue('AA1', 'EXAMEN 4')
                    ->setCellValue('AB1', 'ESTADO')
                    ->setCellValue('AC1', 'OBSERVACIONES')
                    ->setCellValue('AD1', 'EXAMEN 5')
                    ->setCellValue('AE1', 'ESTADO')
                    ->setCellValue('AF1', 'OBSERVACIONES')
                    ->setCellValue('AG1', 'EXAMEN 6')
                    ->setCellValue('AH1', 'ESTADO')
                    ->setCellValue('AI1', 'OBSERVACIONES');

        $i = 2;
        
        $query = $em->createQuery($this->strListaDql);
                $arProgramaciones = new \Brasa\TurnoBundle\Entity\RhuDotacion();
                $arProgramaciones = $query->getResult();
                
        foreach ($arProgramaciones as $arProgramacion) {
            $strNombreCentroCosto = "";
            if($arProgramacion->getCentroCostoRel()) {
                $strNombreCentroCosto = $arProgramacion->getCentroCostoRel()->getNombre();
            }
            $strNombreEntidad = "SIN ENTIDAD";
            if($arProgramacion->getEntidadExamenRel()) {
                $strNombreEntidad = $arProgramacion->getEntidadExamenRel()->getNombre();
            }
            if ($arProgramacion->getEstadoAprobado() == 1){
                $aprobado = "SI";
            } else {
                $aprobado = "NO";
            }
            //Calculo edad
            $varFechaNacimientoAnio = $arProgramacion->getFechaNacimiento()->format('Y');
            $varFechaNacimientoMes =  $arProgramacion->getFechaNacimiento()->format('m');
            $varMesActual = date('m');
            if ($varMesActual >= $varFechaNacimientoMes){
                $varEdad = date('Y') - $varFechaNacimientoAnio;
            } else {
                $varEdad = date('Y') - $varFechaNacimientoAnio -1;
            }
            //Fin calculo edad
            $arDetalleExamen = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoExamenFk' => $arProgramacion->getCodigoExamenPk()));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacion->getCodigoExamenPk())
                    ->setCellValue('B' . $i, $arProgramacion->getIdentificacion())
                    ->setCellValue('C' . $i, $arProgramacion->getNombreCorto())
                    ->setCellValue('D' . $i, $varEdad)
                    ->setCellValue('E' . $i, $arProgramacion->getCodigoSexoFk())
                    ->setCellValue('F' . $i, $arProgramacion->getCargoDescripcion())
                    ->setCellValue('G' . $i, $arProgramacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $strNombreEntidad)
                    ->setCellValue('I' . $i, $arProgramacion->getCiudadRel()->getNombre())
                    ->setCellValue('J' . $i, $arProgramacion->getFecha())
                    ->setCellValue('K' . $i, $arProgramacion->getFecha()->format('Y'))
                    ->setCellValue('L' . $i, $arProgramacion->getFecha()->format('m'))
                    ->setCellValue('M' . $i, $arProgramacion->getFecha()->format('d'))
                    ->setCellValue('N' . $i, $arProgramacion->getExamenClaseRel()->getNombre())
                    ->setCellValue('O' . $i, $arProgramacion->getVrTotal())
                    ->setCellValue('P' . $i, $aprobado)
                    ->setCellValue('Q' . $i, $arProgramacion->getComentarios());
                    $array = array();
                    foreach ($arDetalleExamen as $arDetalleExamen){
                        $array[] = $arDetalleExamen->getCodigoExamenTipoFk();
                        $array[] = $arDetalleExamen->getEstadoAprobado();
                        $array[] = $arDetalleExamen->getComentarios();
                    }
                    
                    
                    foreach ($array as $posicion=>$jugador){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('R' . $i, $jugador)
                            ->setCellValue('S' . $i, $jugador)
                            ->setCellValue('T' . $i, $jugador)
                            ->setCellValue('U' . $i, $jugador)
                            ->setCellValue('V' . $i, $jugador);
                    }
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Examen');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Examenes.xlsx"');
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