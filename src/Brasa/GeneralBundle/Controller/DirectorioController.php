<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenDirectorioType;



class DirectorioController extends Controller
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
        $arDirectorios = new \Brasa\GeneralBundle\Entity\GenDirectorio();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoDirectorio) {
                    $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
                    $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorio);
                    $em->remove($arDirectorio);
                    $em->flush();
                }
            }
        if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        
        $query = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->findAll();
        $arDirectorios = $paginator->paginate($query, $this->get('request')->query->get('page', 1),25);

        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:lista.html.twig', array(
                    'arDirectorios' => $arDirectorios,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoDirectorio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
        if ($codigoDirectorio != 0)
        {
            $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorio);
        }    
        $form = $this->createForm(new GenDirectorioType(), $arDirectorio);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arDirectorio = $form->getData();
            $ruta = $arDirectorio->getNombre()."/";
            $arDirectorio->setRuta($ruta);
            $em->persist($arDirectorio);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_directorio'));
        }
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function generarExcel(){
        
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
                            ->setCellValue('B1', 'Nit')
                            ->setCellValue('C1', 'Digito Verificacion')
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
