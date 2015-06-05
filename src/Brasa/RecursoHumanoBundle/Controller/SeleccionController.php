<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionReferenciaType;

class SeleccionController extends Controller
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
                'empty_value' => "TODOS",
                'mapped' => false,
                'data' => '',

            ))            
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'APROBADO', '0' => 'NO APROBADO')))                            
            ->add('estadoAbierto', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO')))                                                        
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnAprobado', 'submit', array('label'  => 'Aprobado/Des-Aprobado',))
            ->add('BtnAbierto', 'submit', array('label'  => 'Abierto/Cerrado',))
            ->add('BtnPruebasP', 'submit', array('label'  => 'Pruebas presentadas',))
            ->add('BtnReferenciasV', 'submit', array('label'  => 'Referencias Verificadas',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
            ->getForm();
        $form->handleRequest($request);

        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();

        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($id);
                            if ($arSelecciones->getEstadoAbierto() == 0 and $arSelecciones->getEstadoAbierto()== 0 and $arSelecciones->getReferenciasVerificadas()== 0 and $arSelecciones->getPresentaPruebas()== 0){
                                $em->remove($arSelecciones);
                                $em->flush();
                            }
                        return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));    
                    }
                    
                }
            }
            
            if ($form->get('BtnAbierto')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($id);
                            
                                if ($arSelecciones->getEstadoAbierto() == 0){
                                    $arSelecciones->setEstadoAbierto(1);
                                }
                                else{
                                    $arSelecciones->setEstadoAbierto(0);
                                }
                                $em->persist($arSelecciones);
                                $em->flush();  
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
                }
            }
            
            if ($form->get('BtnPruebasP')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($id);
                            
                                if ($arSelecciones->getPresentaPruebas() == 0){
                                    $arSelecciones->setPresentaPruebas(1);
                                }
                                else{
                                    $arSelecciones->setPresentaPruebas(0);
                                }
                                $em->persist($arSelecciones);
                                $em->flush();  
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
                }
            }
            
            if ($form->get('BtnReferenciasV')->isClicked()){    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($id);
                        $arSelRefencias = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
                        $arSelRefencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($id);
                        count($arSelRefencias);
                        $dql   = "SELECT count(codigo_seleccion_fk) as t FROM BrasaRecursoHumanoBundle:RhuSeleccionReferencia where estado_verificada = 1 and codigo_seleccion_fk = $id";
                        $query = $em->createQuery($dql);        
                        echo $totalrows = $query->getResult()->count();
                        if (count($arSelRefencias) > 0 and $arSelRefencias->getEstadoVerificada() == 1){
                                
                            
                                if ($arSelecciones->getReferenciasVerificadas() == 0){
                                    $arSelecciones->setReferenciasVerificadas(1);
                                }
                                else{
                                    $arSelecciones->setReferenciasVerificadas(0);
                                }
                                $em->persist($arSelecciones);
                                $em->flush();
                        }        
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
                }
            }
            
            if($form->get('BtnFiltrar')->isClicked()) {
                $objCentroCosto = $form->get('centroCostoRel')->getData();
                if($objCentroCosto != null) {
                    $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
                } else {
                    $codigoCentroCosto = "";
                }
                $session->set('dqlSeleccion', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->ListaDQL(
                        ));
                $session->set('filtroNombre', $form->get('TxtNombre')->getData());
                $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                $session->set('filtroCentroCosto', $codigoCentroCosto);                

            }

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
                            ->setCellValue('C1', 'Nombre');

                $i = 2;
                $query = $em->createQuery($session->get('dqlEmpleado'));
                $arSelecciones = $query->getResult();
                foreach ($arSelecciones as $arSeleccion) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSeleccion->getCodigoEmpleadoPk())
                            ->setCellValue('B' . $i, $arSeleccion->getNumeroIdentificacion())
                            ->setCellValue('C' . $i, $arSeleccion->getNombreCorto());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Empleados');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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
        } else {
           $session->set('dqlSeleccion', $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->ListaDQL(
                   ));
        }

        $query = $em->createQuery($session->get('dqlSeleccion'));
        $arSelecciones = $paginator->paginate($query, $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Seleccion:lista.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'form' => $form->createView()
            ));
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
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnAprobar', 'submit', array('label'  => 'Aprobar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked())
            {    
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
                    foreach ($arrSeleccionados AS $id) {
                        $arSeleccionReferencias = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
                        $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($id);
                        $arSeleccionReferencias->setEstadoVerificada(1);
                        $em->persist($arSeleccionReferencias);
                        $em->flush();   
                    }
                }  
            }
            
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPago();
                $objFormatoPago->Generar($this, $codigoPago);
            }
            
        }        
        $arSeleccionReferencias = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
        $arSeleccionReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);        
        return $this->render('BrasaRecursoHumanoBundle:Seleccion:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arSeleccionReferencias' => $arSeleccionReferencias,
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
}
