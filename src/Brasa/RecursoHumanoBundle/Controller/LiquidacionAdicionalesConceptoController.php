<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLiquidacionAdicionalesConceptoType;

/**
 * RhuLiquidacionAdicionalesConcepto controller.
 *
 */
class LiquidacionAdicionalesConceptoController extends Controller
{

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arLiquidacionesAdicionalConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoLiquidacionesAdicionalConceptoPk) {
                    $arLiquidacionesAdicionalConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto();
                    $arLiquidacionesAdicionalConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionalesConcepto')->find($codigoLiquidacionesAdicionalConceptoPk);
                    $em->remove($arLiquidacionesAdicionalConcepto);
                    $em->flush();
                }
            }  
        
        if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Nombre');

                $i = 2;
                $arLiquidacionesAdicionalConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionalesConcepto')->findAll();
                
                foreach ($arLiquidacionesAdicionalConceptos as $arLiquidacionesAdicionalConcepto) {
                                            
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arLiquidacionesAdicionalConcepto->getCodigoLiquidacionAdicionalConceptoPk())
                            ->setCellValue('B' . $i, $arLiquidacionesAdicionalConcepto->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('LiquidacionesAdicionalConcepto');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="LiquidacionesAdicionalConcepto.xlsx"');
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
        $arLiquidacionesAdicionalConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionalesConcepto')->findAll();
        $arLiquidacionesAdicionalConceptos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Liquidaciones/LiquidacionesAdicionalConcepto:listar.html.twig', array(
                    'arLiquidacionesAdicionalConceptos' => $arLiquidacionesAdicionalConceptos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoAdicionalConcepto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arLiquidacionAdicionalesConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionalesConcepto();
        if ($codigoAdicionalConcepto != 0)
        {
            $arLiquidacionAdicionalesConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacionAdicionalesConcepto')->find($codigoAdicionalConcepto);
        }    
        $form = $this->createForm(new RhuLiquidacionAdicionalesConceptoType(), $arLiquidacionAdicionalesConceptos);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arLiquidacionAdicionalesConceptos = $form->getData();
            $em->persist($arLiquidacionAdicionalesConceptos);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_liquidacion_adicional_concepto_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Liquidaciones/LiquidacionesAdicionalConcepto:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
}
