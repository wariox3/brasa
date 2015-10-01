<?php
namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPagoBancoType;

class PagoBancoController extends Controller
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
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->eliminarPagoBancoSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_pago_banco_lista'));
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
        $arPagoBancos = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:PagoBanco/:lista.html.twig', array('arPagoBancos' => $arPagoBancos, 'form' => $form->createView()));
    } 
    
    public function nuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco();
        if($codigoPagoBanco != 0) {
            $arPagoBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->find($codigoPagoBanco);
        }
        $form = $this->createForm(new RhuPagoBancoType, $arPagoBanco);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arPagoBanco = $form->getData();
            $em->persist($arPagoBanco);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pago_banco_nuevo', array('codigoPagoBanco' => 0)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:PagoBanco:nuevo.html.twig', array(
            'arPagoBanco' => $arPagoBanco,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoPagoBanco) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');                     
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPagoExamenDetalle();
                $objFormatoPagoExamenDetalle->Generar($this, $codigoPagoExamen);
            }
            if($form->get('BtnEliminar')->isClicked()) {                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->liquidar($codigoPagoExamen);
                return $this->redirect($this->generateUrl('brs_rhu_pago_examen_detalle', array('codigoPagoExamen' => $codigoPagoExamen)));           
            }
        }        
        
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        $arPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
        $arPagoExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamenDetalle')->findBy(array ('codigoPagoExamenFk' => $codigoPagoExamen));
        return $this->render('BrasaRecursoHumanoBundle:Examen/PagoExamen:detalle.html.twig', array(
                    'arPagoExamen' => $arPagoExamen,
                    'arPagoExamenDetalle' => $arPagoExamenDetalle,
                    'form' => $form->createView()
                    ));
    }
    
    public function detalleNuevoAction($codigoPagoBanco) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamen();
        $arPagoExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->find($codigoPagoExamen);
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoEntidadExamenFk' => $arPagoExamen->getCodigoEntidadExamenFk(), 'estadoPagado' => 0));
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) { 
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamen) {                    
                        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                        $arPagoExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle();
                        $arPagoExamenDetalle->setPagoExamenRel($arPagoExamen);
                        $arPagoExamenDetalle->setExamenRel($arExamen);
                        $arPagoExamenDetalle->setVrPrecio($arExamen->getVrTotal());                                                
                        $em->persist($arPagoExamenDetalle); 
                        $arExamen->setEstadoPagado(1);
                        $em->persist($arExamen);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExamen')->liquidar($codigoPagoExamen);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Examen/PagoExamen:detalleNuevo.html.twig', array(
            'arExamenes' => $arExamenes,
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoBanco')->listaDQL(
                
                );        
    }
    
    private function filtrar ($form) {
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoEntidadExamen', $controles['entidadExamenRel']);                               
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
        /*$arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ee')                                        
                    ->orderBy('ee.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoEntidadExamen')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadExamen", $session->get('filtroCodigoEntidadExamen'));                                    
        }  */      
        $form = $this->createFormBuilder()
            //->add('entidadExamenRel', 'entity', $arrayPropiedades)                  
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
            ->getForm();        
        return $form;
    }    
        
}