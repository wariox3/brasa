<?php
namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoExamenType;
//use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenDetalleType;
class PagoExamenController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if ($form->get('BtnEliminar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->eliminarPagoExamenSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_lista'));
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
        $arPagoExamenes = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Examen/PagoExamen:lista.html.twig', array('arPagoExamenes' => $arPagoExamenes, 'form' => $form->createView()));
    } 
    
    public function nuevoAction($codigoPagoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        if($codigoPagoExamen != 0) {
            $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        }
        $form = $this->createForm(new RhuPagoExamenType, $arPagoExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arPagoExamen = $form->getData();
            $em->persist($arPagoExamen);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_nuevo', array('codigoPagoExamen' => 0)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Examen/PagoExamen:nuevo.html.twig', array(
            'arPagoExamen' => $arPagoExamen,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoPagoExamen) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');                     
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnImprimir')->isClicked()) {                
                //$objExamen = new \Brasa\RecursoHumanoBundle\Formatos\FormatoExamenDetalle();
                //$objExamen->Generar($this, $codigoEntidadPagoExamen);
            }
            if($form->get('BtnEliminar')->isClicked()) {                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->liquidar($codigoPagoExamen);
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_detalle', array('codigoPagoExamen' => $codigoPagoExamen)));           
            }
        }        
        
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        $arPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
        $arPagoExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findBy(array ('codigoPagoExamenFk' => $codigoPagoExamen));
        return $this->render('BrasaRecursoHumanoBundle:Examen/PagoExamen:detalle.html.twig', array(
                    'arPagoExamen' => $arPagoExamen,
                    'arPagoExamenDetalle' => $arPagoExamenDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    public function detalleNuevoAction($codigoPagoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoEntidadExamenFk' => $arPagoExamen->getCodigoEntidadExamenFk(), 'estadoPagado' => 0));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamen) {                    
                        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                        $arPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
                        $arPagoExamenDetalle->setPagoExamenRel($arPagoExamen);
                        $arPagoExamenDetalle->setExamenRel($arExamen);
                        $arPagoExamenDetalle->setVrPrecio($arExamen->getVrTotal());                                                
                        $em->persist($arPagoExamenDetalle); 
                        $arExamen->setEstadoPagado(1);
                        $em->persist($arExamen);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->liquidar($codigoPagoExamen);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Examen/PagoExamen:detalleNuevo.html.twig', array(
            'arExamenes' => $arExamenes,
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->listaDQL(
                ""
                );        
    }
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $session->set('filtroNombreExamen', $form->get('TxtNombre')->getData());                
        $session->set('filtroEntidadExamen', $form->get('EntidadExamen')->getData());                
    }
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("JG Efectivos")
            ->setLastModifiedBy("JG Efectivos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'ENTIDAD')
                    ->setCellValue('C1', 'CENTRO_COSTOS')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'IDENTIFICACION')
                    ->setCellValue('F1', 'NOMBRE')
                    ->setCellValue('G1', 'APROBADO');
                    
        $i = 2;
        $query = $em->createQuery($session->get('dqlExamenLista'));
        $arExamenes = $query->getResult();
        foreach ($arExamenes as $arExamen) {
            $strNombreCentroCosto = "";
            if($arExamen->getCentroCostoRel()) {
                $strNombreCentroCosto = $arExamen->getCentroCostoRel()->getNombre();
            }
            $strNombreEntidad = "SIN ENTIDAD";
            if($arExamen->getEntidadExamenRel()) {
                $strNombreEntidad = $arExamen->getEntidadExamenRel()->getNombre();
            }
            if ($arExamen->getEstadoAprobado() == 1){
                $aprobado = "SI";
            } else {
                $aprobado = "NO";
            }
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arExamen->getCodigoExamenPk())
                    ->setCellValue('B' . $i, $strNombreEntidad)
                    ->setCellValue('C' . $i, $strNombreCentroCosto)
                    ->setCellValue('D' . $i, $arExamen->getFecha())
                    ->setCellValue('E' . $i, $arExamen->getIdentificacion())
                    ->setCellValue('F' . $i, $arExamen->getNombreCorto())
                    ->setCellValue('G' . $i, $aprobado);
                    
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
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombreSeleccionGrupo')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }   
    
    private function formularioDetalle() {        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();        
        return $form;
    }    
        
}