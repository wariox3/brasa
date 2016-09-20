<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenTercerosType;



class BaseTercerosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 101, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arTerceros = new \Brasa\GeneralBundle\Entity\GenTercero();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoTercero) {
                    $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
                    $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->find($codigoTercero);
                    $em->remove($arTercero);
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
                $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Nit')
                            ->setCellValue('C1', 'Dígito Verificación')
                            ->setCellValue('D1', 'Nombre');

                $i = 2;
                $arTerceros = $em->getRepository('BrasaGeneralBundle:GenTercero')->findAll();
                
                foreach ($arTerceros as $arTerceros) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arTerceros->getCodigoTerceroPk())
                            ->setCellValue('B' . $i, $arTerceros->getNit())
                            ->setCellValue('C' . $i, $arTerceros->getDigitoVerificacion())
                            ->setCellValue('D' . $i, $arTerceros->getNombreCorto());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Terceros');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Terceros.xlsx"');
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
        $arTerceros = new \Brasa\GeneralBundle\Entity\GenTercero();
        $query = $em->getRepository('BrasaGeneralBundle:GenTercero')->findAll();
        $arTerceros = $paginator->paginate($query, $this->get('request')->query->get('page', 1),200);

        return $this->render('BrasaGeneralBundle:Base/Terceros:lista.html.twig', array(
                    'arTerceros' => $arTerceros,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoTercero) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
        if ($codigoTercero != 0)
        {
            $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->find($codigoTercero);
        }    
        $form = $this->createForm(new GenTercerosType(), $arTercero);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arTercero = $form->getData();
            $em->persist($arTercero);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_base_terceros'));
        }
        return $this->render('BrasaGeneralBundle:Base/Terceros:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
        
}
