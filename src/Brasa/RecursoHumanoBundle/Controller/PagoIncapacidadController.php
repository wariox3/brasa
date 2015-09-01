<?php
namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoIncapacidadType;

class PagoIncapacidadController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $strSqlLista = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->listar();          
        if ($form->isValid()) {            
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if ($form->get('BtnEliminar')->isClicked()) {    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->eliminarIncapacidadPagoSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_incapacidades_pagos_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {    
                $this->filtrar($form);
                $this->listar();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }            
        }                      
        $arIncapacidadPagos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Incapacidades/PagoIncapacidades:lista.html.twig', array('arIncapacidadPagos' => $arIncapacidadPagos, 'form' => $form->createView()));
    } 
    
    public function nuevoAction($codigoIncapacidadPago) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arIncapacidadPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago();
        if($codigoIncapacidadPago != 0) {
            $arIncapacidadPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->find($codigoIncapacidadPago);
        }
        $form = $this->createForm(new RhuPagoIncapacidadType, $arIncapacidadPagos);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arIncapacidadPagos = $form->getData();
            $arIncapacidadPagos->setFecha(new \DateTime('now'));
            
            $em->persist($arIncapacidadPagos);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_incapacidades_pagos_nuevo', array('codigoIncacidadPago' => 0)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Incapacidades/PagoIncapacidades:nuevo.html.twig', array(
            'arIncapacidadPagos' => $arIncapacidadPagos,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoIncapacidadPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');                     
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoIncapacidadPagoDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoIncapacidadPagoDetalle();
                $objFormatoIncapacidadPagoDetalle->Generar($this, $codigoIncapacidadPago);
            }
            if($form->get('BtnEliminar')->isClicked()) {                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPagoDetalle')->eliminarDetallesSeleccionados($arrSeleccionados,$codigoIncapacidadPago);
                $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->liquidar($codigoIncapacidadPago);
                return $this->redirect($this->generateUrl('brs_rhu_incapacidades_pagos_detalle', array('codigoIncapacidadPago' => $codigoIncapacidadPago)));           
            }
            if($form->get('BtnAutorizar')->isClicked()) {
                
                $arIncapacidadPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle();
                $arIncapacidadPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPagoDetalle')->findBy(array('codigoIncapacidadPagoFk' => $codigoIncapacidadPago));
                foreach ($arIncapacidadPagoDetalles AS $arIncapacidadPagoDetalle) {                    
                    $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                    $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($arIncapacidadPagoDetalle->getCodigoIncapacidadFk());
                    $arIncapacidad->setVrPagado($arIncapacidadPagoDetalle->getVrPago());
                    $arIncapacidad->setVrSaldo($arIncapacidad->getVrIncapacidad() - $arIncapacidad->getVrPagado());
                    $em->persist($arIncapacidad);
                }
                $arIncapacidadPago = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago();
                $arIncapacidadPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->find($codigoIncapacidadPago);
                $arIncapacidadPago->setEstadoAutorizado(1);
                $em->persist($arIncapacidadPago);
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->liquidar($codigoIncapacidadPago);
            }
            if ($form->get('BtnActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 1;
                $arIncapacidadPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle();
                $arIncapacidadPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPagoDetalle')->findBy(array('codigoIncapacidadPagoFk' => $codigoIncapacidadPago));
                foreach ($arIncapacidadPagoDetalles AS $arIncapacidadPagoDetalle) {                    
                    $arIncapacidadPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPagoDetalle')->find($arIncapacidadPagoDetalle);
                    $intValorPago = $arrControles['TxtValorPago'][$intIndice];
                    $arIncapacidadPagoDetalle->setVrPago($intValorPago);                                                
                    $em->persist($arIncapacidadPagoDetalle); 
                }
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->liquidar($codigoIncapacidadPago);
            }
        }        
        
        $arIncapacidadPago = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago();
        $arIncapacidadPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->find($codigoIncapacidadPago);
        $arIncapacidadPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle();
        $arIncapacidadPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPagoDetalle')->findBy(array ('codigoIncapacidadPagoFk' => $codigoIncapacidadPago));
        return $this->render('BrasaRecursoHumanoBundle:Incapacidades/PagoIncapacidades:detalle.html.twig', array(
                    'arIncapacidadPago' => $arIncapacidadPago,
                    'arIncapacidadPagoDetalle' => $arIncapacidadPagoDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    public function detalleNuevoAction($codigoIncapacidadPago) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arIncapacidadPago = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago();
        $arIncapacidadPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->find($codigoIncapacidadPago);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadesEntidadSaludCobrar($arIncapacidadPago->getCodigoEntidadSaludFk());
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arIncapacidadPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->find($codigoIncapacidadPago);
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoIncapacidad) {                    
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $arIncapacidadPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle();
                        $arIncapacidadPagoDetalle->setIncapacidadPagoRel($arIncapacidadPago);
                        $arIncapacidadPagoDetalle->setIncapacidadRel($arIncapacidad);
                        $arIncapacidadPagoDetalle->setVrPago($arIncapacidad->getVrSaldo());                                                
                        $em->persist($arIncapacidadPagoDetalle); 
                    }
                    $em->flush();
                }
             
                $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->liquidar($codigoIncapacidadPago);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Incapacidades/PagoIncapacidades:detalleNuevo.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadPago')->listaDQL(
                $session->get('filtroCodigoEntidadSalud')
                );        
    }
    
    private function filtrar ($form) {
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoIncapacidadPago', $controles['entidadSaludRel']);                               
    }
    
    private function generarExcel() {
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
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'ENTIDAD')
                    ->setCellValue('C1', 'TOTAL');
                    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagoExamenes = $query->getResult();
        foreach ($arPagoExamenes as $arPagoExamen) {
            $strNombreEntidad = "";
            if($arPagoExamen->getEntidadExamenRel()) {
                $strNombreEntidad = $arPagoExamen->getEntidadExamenRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoExamen->getCodigoPagoExamenPk())
                    ->setCellValue('B' . $i, $strNombreEntidad)
                    ->setCellValue('C' . $i, $arPagoExamen->getVrTotal());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('PagoExamen');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="pagoExamanes.xlsx"');
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
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('es')                                        
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoEntidadSalud')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadSalud", $session->get('filtroCodigoEntidadSalud'));                                    
        }        
        $form = $this->createFormBuilder()
            ->add('entidadSaludRel', 'entity', $arrayPropiedades)                  
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }   
    
    private function formularioDetalle() {        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnAutorizar', 'submit', array('label'  => 'Autorizar',))
            ->add('BtnActualizar', 'submit', array('label'  => 'Actualizar',))    
            ->getForm();        
        return $form;
    }    
        
}