<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class PagosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')                                        
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,                            
                'empty_value' => "Todos",
                'mapped' => false,
                'data' => '182',
                
            ))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                            
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => $session->get('filtroPagoNumero')))                                                   
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        $form->handleRequest($request);        
        if($form->isValid()) {
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
                            ->setCellValue('B1', 'Identificacion')
                            ->setCellValue('C1', 'Empleado')
                            ->setCellValue('D1', 'Neto');

                $i = 2;
                $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findAll();
                foreach ($arPagos as $arPago) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                            ->setCellValue('B' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('C' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('D' . $i, $arPago->getVrNeto());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Pagos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Pagos.xlsx"');
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
            if($form->get('BtnFiltrar')->isClicked()) {
                $objCentroCosto = $form->get('centroCostoRel')->getData();
                if($objCentroCosto != null) {
                    $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
                } else {
                    $codigoCentroCosto = "";
                }
                $session->set('dqlPago', $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->ListaDQL(
                        $form->get('TxtNumero')->getData(),
                        $codigoCentroCosto));
                $session->set('filtroPagoNumero', $form->get('TxtNumero')->getData());
                $session->set('filtroCentroCosto', $codigoCentroCosto);                
            }            
        } else {
           $session->set('dqlPago', $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->ListaDQL(
                   $session->get('filtroPagoNumero'),
                   $session->get('filtroCentroCosto')
                   ));            
        }
        $query = $em->createQuery($session->get('dqlPago'));        
        $arPagos = $paginator->paginate($query, $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Pagos:lista.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');        
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnReliquidar', 'submit', array('label'  => 'Reliquidar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPago();
                $objFormatoPago->Generar($this, $codigoPago);
            }
            if($form->get('BtnReliquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->liquidar($codigoPago);
                return $this->redirect($this->generateUrl('brs_rhu_pagos_detalle', array('codigoPago' => $codigoPago)));
            }
        }        
        
        return $this->render('BrasaRecursoHumanoBundle:Pagos:detalle.html.twig', array(
                    'arPago' => $arPago,
                    'arPagoDetalles' => $arPagoDetalles,
                    'form' => $form->createView()
                    ));
    }        
}
