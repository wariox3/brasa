<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuBancoType;

/**
 * RhuBanco controller.
 *
 */
class BaseBancoController extends Controller
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
        
        $arBancos = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoBancoPk) {
                    $arBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
                    $arBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->find($codigoBancoPk);
                    $em->remove($arBanco);
                    $em->flush();
                }
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoBanco = new \Brasa\RecursoHumanoBundle\Formatos\FormatoBanco();
                $objFormatoBanco->Generar($this);
        }    
        
        if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('B1', 'Nombre');

                $i = 2;
                $arBancos = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->findAll();
                
                foreach ($arBancos as $arBancos) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arBancos->getCodigoBancoPk())
                            ->setCellValue('B' . $i, $arBancos->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Entidades_Bancarias');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Bancos.xlsx"');
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
        $arBancos = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->findAll();
        $arBancos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Banco:listar.html.twig', array(
                    'arBancos' => $arBancos,
                    'form'=> $form->createView()
           
        ));
    }
    public function nuevoAction($codigoBancoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        if ($codigoBancoPk != 0)
        {
            $arBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->find($codigoBancoPk);
        }    
        $formBanco = $this->createForm(new RhuBancoType(), $arBanco);
        $formBanco->handleRequest($request);
        if ($formBanco->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arBanco);
            $arBanco = $formBanco->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_banco_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Banco:nuevo.html.twig', array(
            'formBanco' => $formBanco->createView(),
        ));
    }
    
}
