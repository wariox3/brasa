<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionGrupoType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionGrupoFiltroType;

class SeleccionGrupoController extends Controller
{
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
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->eliminarSeleccionGrupos($arrSeleccionados);
                //$em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->devuelveNumeroDetalleGrupo($arrSeleccionados); 
            }
            if ($form->get('BtnEstadoAbierto')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->estadoAbiertoSeleccionGrupos($arrSeleccionados); 
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
        $arGrupos = $paginator->paginate($em->createQuery($session->get('dqlSeleccionGrupoLista')), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:SeleccionGrupo:lista.html.twig', array('arGrupos' => $arGrupos, 'form' => $form->createView()));     
    } 
    
    public function nuevoAction($codigoSeleccionGrupo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arGrupo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
        if($codigoSeleccionGrupo != 0) {
            $arGrupo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccionGrupo);
        }
        $form = $this->createForm(new RhuSeleccionGrupoType, $arGrupo);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arGrupo = $form->getData();
            $arGrupo->setFecha(new \DateTime('now'));
            $em->persist($arGrupo);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_selecciongrupo_nuevo', array('codigoSeleccionGrupo' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_selecciongrupo_lista'));
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:SeleccionGrupo:nuevo.html.twig', array(
            'arGrupo' => $arGrupo,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoSeleccionGrupo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');                     
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnImprimir')->isClicked()) {                
                $objSeleccionGrupo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSeleccionGrupo();
                $objSeleccionGrupo->Generar($this, $codigoSeleccionGrupo);
            }
                      
        }        
        
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuSeleccion c where c.codigoSeleccionGrupoFk = $codigoSeleccionGrupo";
        $query = $em->createQuery($dql);        
        $arSeleccion = $query->getResult();
        $arGrupo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
        $arGrupo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccionGrupo);
        return $this->render('BrasaRecursoHumanoBundle:SeleccionGrupo:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arGrupo' => $arGrupo,
                    'form' => $form->createView()
                    ));
    }
 
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlSeleccionGrupoLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->listaDQL($session->get('filtroNombreSeleccionGrupo'), $session->get('filtroAbiertoSeleccionGrupo')));  
    }
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $session->set('filtroNombreSeleccionGrupo', $form->get('TxtNombre')->getData());                
        $session->set('filtroAbiertoSeleccionGrupo', $form->get('estadoAbierto')->getData());                
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
                    ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Nombre')
                    ->setCellValue('C1', 'Centro costo');

        $i = 2;
        $query = $em->createQuery($session->get('dqlSeleccionGrupoLista'));
        $arSeleccionGrupos = $query->getResult();
        foreach ($arSeleccionGrupos as $arSeleccionGrupo) {
            $strNombreCentroCosto = "";
            if($arSeleccionGrupo->getCentroCostoRel()) {
                $strNombreCentroCosto = $arSeleccionGrupo->getCentroCostoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSeleccionGrupo->getCodigoSeleccionGrupoPk())
                    ->setCellValue('B' . $i, $arSeleccionGrupo->getNombre())
                    ->setCellValue('C' . $i, $strNombreCentroCosto);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('GruposSeleccion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="GruposSeleccion.xlsx"');
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
            ->add('estadoAbierto', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAbiertoSeleccionGrupo'))) 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnEstadoAbierto', 'submit', array('label'  => 'Abrir / Cerrar',))
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
}
