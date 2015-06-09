<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionReferenciaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionPruebaType;

class SeleccionController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnPruebasPresentadas')->isClicked()){    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->presentaPruebasSelecciones($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
            }            
            
            if($form->get('BtnReferenciasVerificadas')->isClicked()){    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->referenciasVerificadsSelecciones($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
            }
            
            if($form->get('BtnAprobar')->isClicked()){    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->estadoAprobadoSelecciones($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
            }
            
            if($form->get('BtnAbierto')->isClicked()){    
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->estadoAbiertoSelecciones($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
            }            
            
            if($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($id);
                        $totalReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroReferencias($id);    
                        if($totalReferencias == 0){
                           if ($arSelecciones->getEstadoAbierto() == 0 and $arSelecciones->getEstadoAbierto()== 0 and $arSelecciones->getReferenciasVerificadas()== 0 and $arSelecciones->getPresentaPruebas()== 0){
                                $em->remove($arSelecciones);
                                $em->flush();
                            } 
                        }                                                
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));    
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
        
        $arSelecciones = $paginator->paginate($em->createQuery($session->get('dqlSeleccionLista')), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Seleccion:lista.html.twig', array('arSelecciones' => $arSelecciones, 'form' => $form->createView()));
    }   

    public function nuevoAction($codigoSeleccion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($codigoSeleccion != 0) {
            $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        } else {
            $arSeleccion->setFechaPruebas(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuSeleccionType, $arSeleccion);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arSeleccion = $form->getData();
            $arSeleccion->setNombreCorto($arSeleccion->getNombre1() . " " . $arSeleccion->getNombre2() . " " .$arSeleccion->getApellido1() . " " . $arSeleccion->getApellido2());
            $arSeleccion->setFecha(new \DateTime('now'));
            $em->persist($arSeleccion);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_nuevo', array('codigoSeleccion' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista', array('codigoSeleccion' => $codigoSeleccion)));
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Seleccion:nuevo.html.twig', array(
            'arSeleccion' => $arSeleccion,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoSeleccion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');             
        
        $form = $this->createFormBuilder()
            ->add('BtnVerificar', 'submit', array('label'  => 'Verificar',))
            ->add('BtnEliminarReferencia', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnEliminarPrueba', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminarReferencia')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSeleccionReferencias = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
                        $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($id);
                        $em->remove($arSeleccionReferencias);
                        $em->flush();        
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                }
            }
            
            if($form->get('BtnVerificar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoSeleccionReferencia) {
                        $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($codigoSeleccionReferencia);
                        $arSeleccionReferencias->setEstadoVerificada(1);
                        $em->persist($arSeleccionReferencias);                        
                    }
                    $em->flush();   
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                }  
            } 
        }        
        
        $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));        
        $arSeleccionPruebas = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPrueba')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));                
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);        
        return $this->render('BrasaRecursoHumanoBundle:Seleccion:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arSeleccionReferencias' => $arSeleccionReferencias,
                    'arSeleccionPruebas' => $arSeleccionPruebas,
                    'form' => $form->createView()
                    ));
    }            
    
    public function agregarReferenciaAction($codigoSeleccion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionReferencia = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
        $form = $this->createForm(new RhuSeleccionReferenciaType(), $arSeleccionReferencia);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arSeleccionReferencia = $form->getData();      
            $arSeleccionReferencia->setSeleccionRel($arSeleccion);
            $em->persist($arSeleccionReferencia);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_agregar_referencia', array('codigoSeleccion' => $codigoSeleccion)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:Seleccion:agregarReferencia.html.twig', array(
            'form' => $form->createView()
            ));
    }     
    
    public function agregarPruebaAction($codigoSeleccion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionPrueba = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba();
        $form = $this->createForm(new RhuSeleccionPruebaType(), $arSeleccionPrueba);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arSeleccionPrueba = $form->getData();      
            $arSeleccionPrueba->setSeleccionRel($arSeleccion);
            $em->persist($arSeleccionPrueba);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_agregar_prueba', array('codigoSeleccion' => $codigoSeleccion)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }

        }
        return $this->render('BrasaRecursoHumanoBundle:Seleccion:agregarPrueba.html.twig', array('form' => $form->createView()));
    }         
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlSeleccionLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->listaDQL(
                $session->get('filtroNombreSeleccion'), 
                $session->get('filtroIdentificacionSeleccion'), 
                $session->get('filtroAbiertoSeleccion'), 
                $session->get('filtroAprobadoSeleccion'),
                $session->get('filtroCodigoCentroCosto')
                ));  
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
        $arCentrosCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));                                    
        }
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                           
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAprobadoSeleccion')))                           
            ->add('estadoAbierto', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAbiertoSeleccion')))                                                        
            ->add('TxtNombre', 'text', array('label'  => 'Nombre', 'data' => $session->get('filtroNombreSeleccion')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionSeleccion')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnAprobar', 'submit', array('label'  => 'Aprobar',))
            ->add('BtnAbierto', 'submit', array('label'  => 'Cerrar',))
            ->add('BtnPruebasPresentadas', 'submit', array('label'  => 'Pruebas presentadas',))
            ->add('BtnReferenciasVerificadas', 'submit', array('label'  => 'Referencias verificadas',))
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
        $session->set('filtroNombreSeleccion', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacionSeleccion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroAbiertoSeleccion', $form->get('estadoAbierto')->getData());
        $session->set('filtroAprobadoSeleccion', $form->get('estadoAprobado')->getData());
    }  
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'GRUPO')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO_COSTOS')
                    ->setCellValue('G1', 'FECHA_PRUEBAS')
                    ->setCellValue('H1', 'TELEFONO')
                    ->setCellValue('I1', 'CELULAR')
                    ->setCellValue('J1', 'PRUEBAS_PRESENTADAS')
                    ->setCellValue('K1', 'APROBADO')
                    ->setCellValue('L1', 'REFERENCIAS_VERIFICADAS')
                    ->setCellValue('M1', 'ABIERTO');

        $i = 2;
        $query = $em->createQuery($session->get('dqlSeleccionLista'));
        $arSelecciones = $query->getResult();
        foreach ($arSelecciones as $arSelecciones) {
            if ($arSelecciones->getPresentaPruebas() == 1)
            {
                $presentarP = "SI";
            }
            else
            {
                $presentarP = "NO"; 
            }
            if ($arSelecciones->getEstadoAprobado() == 1)
            {
                $aprobado = "SI";
            }
            else
            {
                $aprobado = "NO"; 
            }
            if ($arSelecciones->getReferenciasVerificadas() == 1)
            {
                $referenciasV = "SI";
            }
            else
            {
                $referenciasV = "NO"; 
            }
            if ($arSelecciones->getEstadoAbierto() == 1)
            {
                $abierto = "SI";
            }
            else
            {
                $abierto = "NO"; 
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSelecciones->getCodigoSeleccionPk())
                    ->setCellValue('B' . $i, $arSelecciones->getSeleccionTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arSelecciones->getSeleccionGrupoRel()->getNombre())
                    ->setCellValue('D' . $i, $arSelecciones->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arSelecciones->getNombreCorto())
                    ->setCellValue('F' . $i, $arSelecciones->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arSelecciones->getFechaPruebas())
                    ->setCellValue('H' . $i, $arSelecciones->getTelefono())
                    ->setCellValue('I' . $i, $arSelecciones->getCelular())
                    ->setCellValue('J' . $i, $presentarP)
                    ->setCellValue('K' . $i, $aprobado)
                    ->setCellValue('L' . $i, $referenciasV)
                    ->setCellValue('M' . $i, $abierto);
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
