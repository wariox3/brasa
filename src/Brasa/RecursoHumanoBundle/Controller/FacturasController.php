<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuFacturaType;
use Doctrine\ORM\EntityRepository;

class FacturasController extends Controller
{
    var $strSqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $strSqlLista = $this->getRequest()->getSession(); 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoFactura) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
                        $arFacturasDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->devuelveNumeroFacturasDetalle($codigoFactura);    
                        if($arFacturasDetalle == 0){
                            $em->remove($arSelecciones);
                            $em->flush();
                        }
                        else {
                            $objMensaje->Mensaje("error", "No se puede eliminar la factura, tiene registros liquidados", $this);
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_lista'));    
                }
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form);
                $this->listar();
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arFacturas = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Facturas:lista.html.twig', array('arFacturas' => $arFacturas, 'form' => $form->createView()));
    }       
    
    public function nuevoAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        if ($codigoFactura != 0) {
            $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        }
        else {
           $arFactura->setFecha(new \DateTime('now'));           
        }
        $form = $this->createForm(new RhuFacturaType(), $arFactura);       
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arFactura = $form->getData(); 
            $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
            $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->find($form->get('terceroRel')->getData());
            $diasPlazo = $arTercero->getPlazoPagoCliente() - 1;
            $fechaVence = date('Y-m-d', strtotime('+'.$diasPlazo.' day')) ;  
            $arFactura->setFechaVence(new \DateTime($fechaVence));
            $em->persist($arFactura);
            $em->flush();                            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_nuevo', array('codigoFactura' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Facturas:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }    
    
    public function detalleAction($codigoFactura) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();                 
        $form = $this->createFormBuilder()                        
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))            
            ->add('BtnRetirarDetalle', 'submit', array('label'  => 'Eliminar',))            
            ->getForm();
        $form->handleRequest($request);        
        $arFactura = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFactura = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->find($codigoFactura);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnRetirarDetalle')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPago');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoFacturaDetalle) {
                        $arFacturaDetalleEliminar = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->find($codigoFacturaDetalle);
                        $arServicioCobrar = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->find($arFacturaDetalleEliminar->getCodigoServicioCobrarFk());
                        $arServicioCobrar->setEstadoCobrado(0);
                        $em->persist($arServicioCobrar);
                        $em->remove($arFacturaDetalleEliminar);                        
                    }
                    $em->flush();  
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->liquidar($codigoFactura);
                    return $this->redirect($this->generateUrl('brs_rhu_facturas_detalle', array('codigoFactura' => $codigoFactura)));
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoFactura = new \Brasa\RecursoHumanoBundle\Formatos\FormatoFactura();
                $objFormatoFactura->Generar($this, $codigoFactura);
            }       
        }
        $arFacturaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle();
        $arFacturaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuFacturaDetalle')->findBy(array('codigoFacturaFk' => $codigoFactura));        
        return $this->render('BrasaRecursoHumanoBundle:Facturas:detalle.html.twig', array(
                    'arFactura' => $arFactura,
                    'arFacturaDetalles' => $arFacturaDetalles,
                    'form' => $form->createView(),
                    ));
    }
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuFactura')->listaDql(
                    $session->get('filtroCodigoTerceros'),
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroNumero'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function filtrar($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoTerceros', $controles['terceroRel']);
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesCentroCosto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        
        $arrayPropiedadesTerceros = array(
                'class' => 'BrasaGeneralBundle:GenTercero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoTerceros')) {
            $arrayPropiedadesTerceros['data'] = $em->getReference("BrasaGeneralBundle:GenTercero", $session->get('filtroCodigoTerceros'));
        }
        
        $form = $this->createFormBuilder()
            ->add('terceroRel', 'entity', $arrayPropiedadesTerceros)
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => $session->get('filtroNumero')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
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
                    ->setCellValue('A1', 'CODIGO FACTURA')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'FECHA VENCE')
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'VR. BRUTO')
                    ->setCellValue('H1', 'VR. NETO')
                    ->setCellValue('I1', 'VR. RETENCION FUENTE')
                    ->setCellValue('J1', 'VR. RETENCION CREE')
                    ->setCellValue('K1', 'VR. RETENCION IVA')
                    ->setCellValue('L1', 'VR. BASE AIU')
                    ->setCellValue('M1', 'VR. TOTAL ADMNISTRACION')
                    ->setCellValue('N1', 'VR. TOTAL INGRESO MISION')
                    ->setCellValue('O1', 'VR. COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arFacturas = new \Brasa\RecursoHumanoBundle\Entity\RhuFactura();
        $arFacturas = $query->getResult();
        foreach ($arFacturas as $arFactura) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getNumero())
                    ->setCellValue('C' . $i, $arFactura->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arFactura->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arFactura->getTerceroRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arFactura->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arFactura->getVrBruto())
                    ->setCellValue('H' . $i, $arFactura->getVrNeto())
                    ->setCellValue('I' . $i, $arFactura->getVrRetencionFuente())
                    ->setCellValue('J' . $i, $arFactura->getVrRetencionCree())
                    ->setCellValue('K' . $i, $arFactura->getVrRetencionIva())
                    ->setCellValue('L' . $i, $arFactura->getVrBaseAIU())
                    ->setCellValue('M' . $i, $arFactura->getVrTotalAdministracion())
                    ->setCellValue('N' . $i, $arFactura->getVrIngresoMision())
                    ->setCellValue('O' . $i, $arFactura->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Facturas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="facturas.xlsx"');
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
