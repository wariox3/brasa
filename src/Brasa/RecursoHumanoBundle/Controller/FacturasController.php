<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuFacturaType;

class FacturasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))                            
            ->getForm();
        $form->handleRequest($request);        
        
        $arFacturas = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $dql   = "SELECT f FROM BrasaRecursoHumanoBundle:RhuFactura f";
        $query = $em->createQuery($dql);        
        $arFacturas = $paginator->paginate($query, $request->query->get('page', 1), 3);                       
        if($form->isValid()) {
            if($form->get('BtnExcel')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                // Set document properties
                $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                    ->setLastModifiedBy("Maarten Balliauw")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Periodo')
                            ->setCellValue('D1', 'Abierto');
                $i = 2;
                foreach ($arCentrosCostos as $arCentroCosto) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCentroCosto->getCodigoCentroCostoPk())
                            ->setCellValue('B' . $i, $arCentroCosto->getNombre())
                            ->setCellValue('C' . $i, $arCentroCosto->getPeriodoPagoRel()->getNombre())
                            ->setCellValue('D' . $i, $arCentroCosto->getPagoAbierto());                    
                    $i++;                    
                }
                
                $objPHPExcel->getActiveSheet()->setTitle('ccostos');                
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="01simple.xlsx"');
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
            
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) { 
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
                        if($arCentroCosto->getEstadoActivo() == 1) {
                            $arCentroCosto->setEstadoActivo(0);
                        } else {
                            $arCentroCosto->setEstadoActivo(1);
                        }
                        $em->persist($arCentroCosto);
                    }                    
                    $em->flush();
                }
            }                                        
        } 

        return $this->render('BrasaRecursoHumanoBundle:Facturas:lista.html.twig', array(
            'arFacturas' => $arFacturas,
            'form' => $form->createView()));
    }       
    
    public function nuevoAction($codigoFactura) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();                        
        $form = $this->createForm(new RhuFacturaType(), $arFactura);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arFactura = $form->getData(); 
            $arFactura->setTerceroRel($arFactura->getCentroCostoRel()->getTerceroRel());
            $em->persist($arFactura);
            $em->flush();                            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_nuevo', array('codigoFactura' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_lista'));
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Facturas:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    public function detalleAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();         
        $objMensaje = $this->get('mensajes_brasa');        
        $form = $this->createFormBuilder()                        
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))            
            ->add('BtnRetirarDetalle', 'submit', array('label'  => 'Retirar',))            
            ->getForm();
        $form->handleRequest($request);        
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnRetirarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPago');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoFacturaDetalle) {
                        $arFacturaDetalleEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($codigoFacturaDetalle);
                        $em->remove($arFacturaDetalleEliminar);
                    }
                    $em->flush();                    
                }
            }
        }
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));        
        return $this->render('BrasaRecursoHumanoBundle:Facturas:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalles' => $arFacturaDetalles,
                    'form' => $form->createView(),
                    ));
    }        
}
