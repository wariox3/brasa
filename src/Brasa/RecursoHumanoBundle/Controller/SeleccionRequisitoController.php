<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionRequisitoType;

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
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
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
        $strSqlLista = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisito')->listaDQL($strSqlLista->get('filtroNombreSeleccionRequisito'), $strSqlLista->get('filtroAbiertoSeleccionRequisito'));  
    }
    
    private function filtrar ($form) {
        $strSqlLista = $this->getRequest()->getSession();
        $strSqlLista->set('filtroNombreSeleccionRequisito', $form->get('TxtNombre')->getData());                
        $strSqlLista->set('filtroAbiertoSeleccionRequisito', $form->get('estadoAbierto')->getData());                
    }
    
    private function formularioFiltro() {
        $strSqlLista = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $strSqlLista->get('filtroNombreSeleccionRequisito')))
            ->add('estadoAbierto', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $strSqlLista->get('filtroAbiertoSeleccionRequisito'))) 
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'CARGO REQUISITO')
                    ->setCellValue('F1', 'CANTIDAD SOLICITADA')
                    ->setCellValue('G1', 'ABIERTO');
                    

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
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSeleccionRequisito->getCodigoSeleccionRequisitoPk())
                    ->setCellValue('B' . $i, $arSeleccionRequisito->getFecha())
                    ->setCellValue('C' . $i, $arSeleccionRequisito->getNombre())
                    ->setCellValue('D' . $i, $strNombreCentroCosto)
                    ->setCellValue('E' . $i, $strCargo)
                    ->setCellValue('F' . $i, $arSeleccionRequisito->getCantidadSolicitida())
                    ->setCellValue('G' . $i, $abierto);
                    
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
