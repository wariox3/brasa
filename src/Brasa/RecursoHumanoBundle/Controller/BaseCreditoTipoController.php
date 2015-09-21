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
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Cupo Máximo');

                $i = 2;
                $arCreditoTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->findAll();
                
                foreach ($arCreditoTipos as $arCreditoTipo) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCreditoTipo->getcodigoCreditoTipoPk())
                            ->setCellValue('B' . $i, $arCreditoTipo->getnombre())
                            ->setCellValue('C' . $i, $arCreditoTipo->getCupoMaximo());
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
    
    public function detalleAction($codigoCreditoTipoPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()    
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        $form->handleRequest($request);
        $arCreditosTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo();
        $arCreditosTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoTipo')->find($codigoCreditoTipoPk);
        $arCreditosTiposEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditosTiposEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoCreditoTipoFk' => $codigoCreditoTipoPk));
        if($form->isValid()) {
                      
            if($form->get('BtnExcel')->isClicked())$em = $this->getDoctrine()->getManager();{
        $session = $this->getRequest()->getSession();
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
                            ->setCellValue('A1', 'Codigo_Credito')
                            ->setCellValue('B1', 'Tipo_Credito')
                            ->setCellValue('C1', 'Fecha_Credito')
                            ->setCellValue('D1', 'Centro_Costo')
                            ->setCellValue('E1', 'Empleado')
                            ->setCellValue('F1', 'Valor_Credito')
                            ->setCellValue('G1', 'Valor_Cuota')
                            ->setCellValue('H1', 'Valor_Seguro')
                            ->setCellValue('I1', 'Cuotas')
                            ->setCellValue('J1', 'Cuota_Actual')
                            ->setCellValue('K1', 'Pagado')
                            ->setCellValue('L1', 'Aprobado')
                            ->setCellValue('M1', 'Suspendido');

                $i = 2;
                
                foreach ($arCreditosTiposEmpleados as $arCreditosTiposEmpleados) {
                    if ($arCreditosTiposEmpleados->getEstadoPagado() == 1)
                    {
                        $Estado = "SI";
                    }
                    else
                    {
                        $Estado = "NO"; 
                    }
                    if ($arCreditosTiposEmpleados->getAprobado() == 1)
                    {
                        $Aprobado = "SI";
                    }
                    else
                    {
                        $Aprobado = "NO"; 
                    }
                    if ($arCreditosTiposEmpleados->getEstadoSuspendido() == 1)
                    {
                        $Suspendido = "SI";
                    }
                    else
                    {
                        $Suspendido = "NO"; 
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCreditosTiposEmpleados->getCodigoCreditoPk())
                            ->setCellValue('B' . $i, $arCreditosTiposEmpleados->getCreditoTipoRel()->getNombre())
                            ->setCellValue('C' . $i, $arCreditosTiposEmpleados->getFecha())
                            ->setCellValue('D' . $i, $arCreditosTiposEmpleados->getEmpleadoRel()->getCentroCostoRel()->getNombre())
                            ->setCellValue('E' . $i, $arCreditosTiposEmpleados->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arCreditosTiposEmpleados->getVrPagar())
                            ->setCellValue('G' . $i, $arCreditosTiposEmpleados->getVrCuota())
                            ->setCellValue('H' . $i, $arCreditosTiposEmpleados->getSeguro())
                            ->setCellValue('I' . $i, $arCreditosTiposEmpleados->getNumeroCuotas())
                            ->setCellValue('J' . $i, $arCreditosTiposEmpleados->getNumeroCuotaActual())
                            ->setCellValue('K' . $i, $Estado)
                            ->setCellValue('L' . $i, $Aprobado)
                            ->setCellValue('M' . $i, $Suspendido);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Creditos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Creditos.xlsx"');
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
        $arCreditosTiposEmpleados = $paginator->paginate($arCreditosTiposEmpleados, $this->get('request')->query->get('page', 1),30);
        return $this->render('BrasaRecursoHumanoBundle:Base/CreditoTipo:detalle.html.twig', array(
                    'arCreditosTiposEmpleados' => $arCreditosTiposEmpleados,
                    'arCreditosTipo' => $arCreditosTipo,
                    'form' => $form->createView()
                    ));
    }
    
}
