<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCreditoTipoType;

/**
 * RhucreditoTipo controller.
 *
 */
class BaseCreditoTipoController extends Controller
{

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', 'submit', array('label'  => 'PDF'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arCreditoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCreditoTipoPk) {
                    $arCreditoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
                    $arCreditoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->find($codigoCreditoTipoPk);
                    $em->remove($arCreditoTipo);
                    $em->flush();
                }
            }
        
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoTipoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoTipoCredito();
                $objFormatoTipoCredito->Generar($this);
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
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Nombre');

                $i = 2;
                $arCreditoTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->findAll();
                
                foreach ($arCreditoTipos as $arCreditoTipo) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCreditoTipo->getcodigoCreditoTipoPk())
                            ->setCellValue('B' . $i, $arCreditoTipo->getnombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Creditos_Tipos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="CreditosTipos.xlsx"');
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
        $arCreditoTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->findAll();
        $arCreditoTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/CreditoTipo:listar.html.twig', array(
                    'arCreditoTipos' => $arCreditoTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoCreditoTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCreditoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        if ($codigoCreditoTipoPk != 0)
        {
            $arCreditoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->find($codigoCreditoTipoPk);
        }    
        $formCreditoTipo = $this->createForm(new RhuCreditoTipoType(), $arCreditoTipo);
        $formCreditoTipo->handleRequest($request);
        if ($formCreditoTipo->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arCreditoTipo);
            $arCreditoTipo = $formCreditoTipo->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_creditotipo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/CreditoTipo:nuevo.html.twig', array(
            'formCreditoTipo' => $formCreditoTipo->createView(),
        ));
    }
    
}
