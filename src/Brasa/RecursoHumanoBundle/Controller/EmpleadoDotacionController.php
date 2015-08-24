<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoDotacionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionElementoType;


class EmpleadoDotacionController extends Controller
{
    var $strListaDql = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');                       
            if($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoEmpleadoDotacion) {
                        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
                        $arEmpleadoDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacion')->find($codigoEmpleadoDotacion);
                        $em->remove($arEmpleadoDotacion);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleado_dotacion_lista'));                    
                }
            }
            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();              
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }          
        
        $arEmpleadoDotaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoDotacion:lista.html.twig', array('arEmpleadoDotaciones' => $arEmpleadoDotaciones, 'form' => $form->createView()));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacion')->listaDQL(                
                $session->get('filtroIdentificacion'),                                 
                $session->get('filtroCodigoCentroCosto')
                );  
    }   

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $arrayPropiedades = array(
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
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));                                    
        }
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                                       
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();        
        return $form;
    }    
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }  
    
    public function nuevoAction($codigoEmpleado, $codigoEmpleadoDotacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
        if($codigoEmpleadoDotacion != 0) {
            $arEmpleadoDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacion')->find($codigoEmpleadoDotacion);
        }            
        $arEmpleadoDotacion->setFecha(new \DateTime('now'));
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arEmpleado->getCodigoCentroCostoFk());
        $arEmpleadoDotacion->setCentroCostoRel($arCentroCosto);
        $form = $this->createForm(new RhuEmpleadoDotacionType, $arEmpleadoDotacion);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arEmpleadoDotacion = $form->getData();            
            $arEmpleadoDotacion->setEmpleadoRel($arEmpleado);
            $em->persist($arEmpleadoDotacion);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleado_dotacion_nuevo', array('codigoEmpleado' => $codigoEmpleado, 'codigoEmpleadoDotacion' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoDotacion:nuevo.html.twig', array(
            'arEmpleadoDotacion' => $arEmpleadoDotacion,
            'form' => $form->createView()));
    }
    
    public function nuevoDotacionAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createFormBuilder()
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('fecha', 'date', array('data' => new \DateTime('now')))
            ->add('codigoInternoReferencia', 'number', array('required' => true))
            ->add('comentarios', 'textarea', array('required' => false))    
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
            $arEmpleadoDotacion = $form->getData();
            
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('numeroIdentificacion' => $form->get('numeroIdentificacion')->getData(), 'estadoActivo' => 1));
            
            
            //$arEmpleadoDotacion->setCentroCostoRel($arEmpleado[0]);
            $arEmpleadoDotacion->setCodigoEmpleadoFk($arEmpleado[0]);
            $em->persist($arEmpleadoDotacion);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoDotacion:nuevoDotacion.html.twig', array(
            
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoEmpleadoDotacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()    
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        $form->handleRequest($request);
        $arEmpleadoDotaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
        $arEmpleadoDotaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacion')->find($codigoEmpleadoDotacion);
        if($form->isValid()) {                      
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoEmpleadoDotacionDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEmpleadoDotacionDetalle();
                $objFormatoEmpleadoDotacionDetalle->Generar($this, $codigoEmpleadoDotacion);
            }     
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleadoDotacionPk) {
                        $arEmpleadoDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle();
                        $arEmpleadoDotacionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacionDetalle')->find($codigoEmpleadoDotacionPk);
                        $em->remove($arEmpleadoDotacionDetalle);                        
                    }
                    $em->flush();
                }                
                return $this->redirect($this->generateUrl('brs_rhu_base_empleado_dotacion_detalle', array('codigoEmpleadoDotacion' => $codigoEmpleadoDotacion)));
            }            
        }
        $arEmpleadoDotacionDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle();
        $arEmpleadoDotacionDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacionDetalle')->FindBy(array('codigoEmpleadoDotacionFk' => $codigoEmpleadoDotacion));        
        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoDotacion:detalle.html.twig', array(
                    'arEmpleadoDotaciones' => $arEmpleadoDotaciones,
                    'arEmpleadoDotacionDetalles' => $arEmpleadoDotacionDetalles,
                    'form' => $form->createView()
                    ));
    }
    
    public function detalleNuevoAction($codigoEmpleadoDotacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
        $arEmpleadoDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoDotacion')->find($codigoEmpleadoDotacion);
        $arDotacionElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                        $arDotacionElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request); 
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('BtnGuardar')->isClicked()) {
                if (isset($arrControles['TxtCantidad'])) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        if($arrControles['TxtCantidad'][$intIndice] > 0 ){
                            $arDotacionElemento = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                            $arDotacionElemento = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($intCodigo);
                            $arEmpleadoDotacionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacionDetalle();
                            $arEmpleadoDotacionDetalle->setEmpleadoDotacionRel($arEmpleadoDotacion);
                            
                            $arEmpleadoDotacionDetalle->setDotacionElementoRel($arDotacionElemento);
                            $intCantidad = $arrControles['TxtCantidad'][$intIndice];
                            $arEmpleadoDotacionDetalle->setCantidadAsignada($intCantidad);
                            $arEmpleadoDotacionDetalle->setCantidadDevuelta(0);
                            $intLote = $arrControles['TxtLote'][$intIndice];
                            $intSerie = $arrControles['TxtSerie'][$intIndice];
                            $arEmpleadoDotacionDetalle->setSerie($intSerie);
                            $arEmpleadoDotacionDetalle->setLote($intLote);
                            $em->persist($arEmpleadoDotacionDetalle);                                
                        }                        
                        $intIndice++;
                    }
                }
                $em->flush();                                        
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoDotacion:detallenuevo.html.twig', array(
            'arEmpleadoDotacion' => $arEmpleadoDotacion,
            'arDotacionElementos' => $arDotacionElementos,
            'form' => $form->createView()));
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
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Fecha')
                            ->setCellValue('C1', 'Centro Centro')
                            ->setCellValue('D1', 'Identificacion')
                            ->setCellValue('E1', 'Empleado')
                            ->setCellValue('F1', 'Numero Interno Referencia');
                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arEmpleadoDotaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoDotacion();
                $arEmpleadoDotaciones = $query->getResult();
                
                foreach ($arEmpleadoDotaciones as $arEmpleadoDotacion) {
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEmpleadoDotacion->getCodigoEmpleadoDotacionPk())
                            ->setCellValue('B' . $i, $arEmpleadoDotacion->getFecha()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $arEmpleadoDotacion->getEmpleadoRel()->getCentroCostoRel()->getNombre())
                            ->setCellValue('D' . $i, $arEmpleadoDotacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arEmpleadoDotacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arEmpleadoDotacion->getCodigoInternoReferencia());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('EmpleadoDotacion');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="EmpleadoDotacion.xlsx"');
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
