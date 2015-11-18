<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenDirectorioType;
use Symfony\Component\HttpFoundation\Response;

class DirectorioController extends Controller
{
    public function listaAction($codigoDirectorioPadre = 0) {
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
                    $arDirectorioArchivo = $em->getRepository('BrasaGeneralBundle:GenDirectorioArchivo')->findBy(array('codigoDirectorioFk' => $codigoDirectorio));
                    if (count($arDirectorioArchivo) == 0){
                        $em->remove($arDirectorio);
                        $em->flush();
                    }
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                    $this->generarExcel();
            }
            if($form->get('BtnCargar')->isClicked()) {
                    $this->cargarArchivo($codigoDirectorioPadre);
            }
        }
        
        $queryDirectorios = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->findBy(array('codigoDirectorioPadre' => $codigoDirectorioPadre));
        $arDirectorios = $paginator->paginate($queryDirectorios, $this->get('request')->query->get('page', 1),500);
        $queryArchivos = $em->getRepository('BrasaGeneralBundle:GenArchivo')->findBy(array('codigoDirectorioFk' => $codigoDirectorioPadre));
        $arArchivos = $paginator->paginate($queryArchivos, $this->get('request')->query->get('page', 1),500);
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:lista.html.twig', array(
                    'arDirectorios' => $arDirectorios,
                    'codigoDirectorioPadre' => $codigoDirectorioPadre,
                    'arArchivos' => $arArchivos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoDirectorioAction($codigoDirectorio,$codigoDirectorioPadre) {
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
            $arDirectorio->setRuta(strtolower($ruta));
            $arDirectorio->setCodigoDirectorioPadre($codigoDirectorioPadre);
            $em->persist($arDirectorio);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_utilidad_gestorarchivo', array('codigoDirectorioPadre' => $codigoDirectorioPadre)));
        }
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:nuevo.html.twig', array(
            'form' => $form->createView(),
            'codigoDirectorioPadre' => $codigoDirectorioPadre,
        ));
    }
    
    public function cargarArchivoAction($codigoDirectorioPadre) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa'); 
        $form = $this->createFormBuilder()
            ->add('attachment', 'file') 
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {                
                $objArchivo = $form['attachment']->getData();
                $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
                $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorioPadre);
                $arArchivo = new \Brasa\GeneralBundle\Entity\GenArchivo();                    
                $arArchivo->setNombre($objArchivo->getClientOriginalName());
                $arArchivo->setArchivo($objArchivo->getClientMimeType());                               
                $arArchivo->setDirectorioRel($arDirectorio);               
                                   
                $em->persist($arArchivo);
                $em->flush();
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strDestino = $arConfiguracion->getRutaDirectorio() . $arDirectorio->getRuta();
                $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                $form['attachment']->getData()->move($strDestino, $strArchivo);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                
            }                                   
        }         
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:cargar.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
    public function descargarArchivoAction($codigoDirectorioArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arArchivo = new \Brasa\GeneralBundle\Entity\GenDirectorioArchivo();
        $arArchivo = $em->getRepository('BrasaGeneralBundle:GenDirectorioArchivo')->find($codigoDirectorioArchivo);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strRuta = $arConfiguracion->getRutaDirectorio() . $arArchivo->getDirectorioRel()->getRuta(). $codigoDirectorioArchivo. "_" .$arArchivo->getNombre();
        // Generate response
        $response = new Response();
        
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', $arArchivo->getArchivo());
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getNombre() . '";');
        //$response->headers->set('Content-length', $arArchivo->getTamano());        
        $response->sendHeaders();
        $response->setContent(readfile($strRuta));        
              
    }
    
    public function generarExcel(){
        $em = $this->getDoctrine()->getManager();
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
                            ->setCellValue('B1', 'RUTA')
                            ->setCellValue('C1', 'NOMBRE');

                $i = 2;
                $arDirectorios = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->findAll();
                
                foreach ($arDirectorios as $arDirectorio) {
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDirectorio->getCodigoDirectorioPk())
                            ->setCellValue('B' . $i, $arDirectorio->getRuta())
                            ->setCellValue('C' . $i, $arDirectorio->getNombre())
                            ;
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Directorio');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Directorio.xlsx"');
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
