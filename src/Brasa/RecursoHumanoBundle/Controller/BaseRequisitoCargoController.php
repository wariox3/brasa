<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRequisitoCargoType;

/**
 * RhuRequisitoCargo controller.
 *
 */
class BaseRequisitoCargoController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista(); 
        $form->handleRequest($request);     
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoLicenciaTipoPk) {
                    $arRequisitoCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->find($codigoLicenciaTipoPk);
                    $em->remove($arRequisitoCargo);
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
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'PAGO CONCEPTO')
                            ->setCellValue('D1', 'EFECTA SALUD')
                            ->setCellValue('E1', 'AUSENTIMSO');

                $i = 2;
                $arRequisitoCargos = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->findAll();
                
                foreach ($arRequisitoCargos as $arRequisitoCargo) {
                    if($arRequisitoCargo->getAfectaSalud() == 1){
                        $afectaSalud = "SI";
                    }else{
                        $afectaSalud = "NO";
                    }
                    if($arRequisitoCargo->getAusentismo() == 1){
                        $ausentismo = "SI";
                    }else{
                        $ausentismo = "NO";
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arRequisitoCargo->getcodigoLicenciaTipoPk())
                            ->setCellValue('B' . $i, $arRequisitoCargo->getNombre())
                            ->setCellValue('C' . $i, $arRequisitoCargo->getPagoConceptoRel()->getNombre())
                            ->setCellValue('D' . $i, $afectaSalud)
                            ->setCellValue('E' . $i, $ausentismo);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Licencias_Tipos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="LicenciasTipos.xlsx"');
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
        $arRequisitosCargos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/RequisitoCargo:lista.html.twig', array(
                    'arRequisitosCargos' => $arRequisitosCargos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoLicenciaTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arRequisitoCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo();
        if ($codigoLicenciaTipoPk != 0)
        {
            $arRequisitoCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->find($codigoLicenciaTipoPk);
        }    
        $formLicenciaTipo = $this->createForm(new RhuRequisitoCargoType(), $arRequisitoCargo);
        $formLicenciaTipo->handleRequest($request);
        if ($formLicenciaTipo->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arRequisitoCargo);
            $arRequisitoCargo = $formLicenciaTipo->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_licenciatipo_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/LicenciaTipo:nuevo.html.twig', array(
            'formLicenciaTipo' => $formLicenciaTipo->createView(),
        ));
    }
    
    private function formularioLista() {
        $form = $this->createFormBuilder()                        
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))                
            ->getForm();        
        return $form;
    }     
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->listaDql();         
    }       
}
