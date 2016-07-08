<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEstudioTipoAcreditacionType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

//use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
//use Doctrine\DBAL\Driver\PDOException;

/**
 * BaseEstudioTipoAcreditacionController.
 *
 */
class BaseEstudioTipoAcreditacionController extends Controller
{

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arEstudioTipoAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuEstudioTipoAcreditacion();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoEstudioTipoAcreditacionPk) {
                        $arEstudioTipoAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEstudioTipoAcreditacion();
                        $arEstudioTipoAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuEstudioTipoAcreditacion')->find($codigoEstudioTipoAcreditacionPk);
                        $em->remove($arEstudioTipoAcreditacion);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_estudiotipoacreditacion_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el estudio porque esta siendo utilizado', $this);
                  }     
            }   
        
        if($form->get('BtnExcel')->isClicked()) {
            ob_clean();
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
                            ->setCellValue('A1', 'CODIGO')
                            ->setCellValue('B1', 'CODIGO ESTUDIO')
                            ->setCellValue('C1', 'NOMBRE')
                            ->setCellValue('D1', 'CARGO');

                $i = 2;
                $arEstudioTipoAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuEstudioTipoAcreditacion')->findAll();
                
                foreach ($arEstudioTipoAcreditacion as $arEstudioTipoAcreditacion) {
                       
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEstudioTipoAcreditacion->getCodigoEstudioAcreditacionPk())
                            ->setCellValue('B' . $i, $arEstudioTipoAcreditacion->getCodigoAlterno())
                            ->setCellValue('C' . $i, $arEstudioTipoAcreditacion->getNombre())
                            ->setCellValue('D' . $i, $arEstudioTipoAcreditacion->getCargo());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('EstudioTipoAcreditacion');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="EstudioTipoAcreditacion.xlsx"');
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
        $arEstudioTipoAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEstudioTipoAcreditacion();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEstudioTipoAcreditacion')->findAll();
        $arEstudioTipoAcreditacion = $paginator->paginate($query, $this->get('request')->query->get('page', 1),40);

        return $this->render('BrasaRecursoHumanoBundle:Base/EstudioTipoAcreditacion:listar.html.twig', array(
                    'arEstudioTipoAcreditacion' => $arEstudioTipoAcreditacion,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoEstudioTipoAcreditacionPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arEstudioTipoAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEstudioTipoAcreditacion();
        if ($codigoEstudioTipoAcreditacionPk != 0)
        {
            $arEstudioTipoAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuEstudioTipoAcreditacion')->find($codigoEstudioTipoAcreditacionPk);
        }    
        $formEstudioTipoAcreditacion = $this->createForm(new RhuEstudioTipoAcreditacionType(), $arEstudioTipoAcreditacion);
        $formEstudioTipoAcreditacion->handleRequest($request);
        if ($formEstudioTipoAcreditacion->isValid())
        {
            // guardar la tarea en la base de datos
            $arEstudioTipoAcreditacion = $formEstudioTipoAcreditacion->getData();
            $em->persist($arEstudioTipoAcreditacion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_estudiotipoacreditacion_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EstudioTipoAcreditacion:nuevo.html.twig', array(
            'formEstudioTipoAcreditacion' => $formEstudioTipoAcreditacion->createView(),
        ));
    }
    
}
