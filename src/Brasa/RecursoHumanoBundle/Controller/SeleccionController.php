<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionReferenciaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionPruebaType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionVisitaType;

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
            /*if($form->get('BtnPruebasPresentadas')->isClicked()){
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->presentaPruebasSelecciones($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
            }
            if($form->get('BtnReferenciasVerificadas')->isClicked()){
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->referenciasVerificadsSelecciones($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_lista'));
            }*/

            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($id);
                        $totalReferencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroReferencias($id);
                        if($totalReferencias == 0){
                           if ($arSelecciones->getEstadoCerrado() == 0 and $arSelecciones->getEstadoAutorizado()== 0 and $arSelecciones->getReferenciasVerificadas()== 0 and $arSelecciones->getPresentaPruebas()== 0){
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
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:lista.html.twig', array('arSelecciones' => $arSelecciones, 'form' => $form->createView()));
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
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $arSeleccion->getCodigoSeleccionPk())));
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:nuevo.html.twig', array(
            'arSeleccion' => $arSeleccion,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoSeleccion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $form = $this->formularioDetalle($arSeleccion);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arSeleccion->getEstadoAutorizado() == 0) {
                    $arSeleccion->setEstadoAutorizado(1);
                    $em->persist($arSeleccion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));                                                
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arSeleccion->getEstadoAutorizado() == 1) {
                    $arSeleccion->setEstadoAutorizado(0);
                    $em->persist($arSeleccion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));                                                
                }
            }
            
            if($form->get('BtnAprobar')->isClicked()){
                $aprobar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->estadoAprobadoSelecciones($codigoSeleccion);
                if ($aprobar == 1){
                    $objMensaje->Mensaje("error", "No se puede aprobar sin verificar todas las referencias", $this);
                }else{
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));                                                
                }
                
            }

            if($form->get('BtnCerrar')->isClicked()){
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->cerrarSeleccion($codigoSeleccion);
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));                                                
            }
            
            if ($form->get('BtnEliminarReferencia')->isClicked()){
                $arrSeleccionados = $request->request->get('ChkSeleccionarReferencia');
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
            if ($form->get('BtnEliminarPrueba')->isClicked()){
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSeleccionPruebas = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPrueba();
                        $arSeleccionPruebas = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPrueba')->find($id);
                        $em->remove($arSeleccionPruebas);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                }
            }
            if ($form->get('BtnEliminarVisita')->isClicked()){
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $id) {
                        $arSeleccionVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita();
                        $arSeleccionVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionVisita')->find($id);
                        $em->remove($arSeleccionVisita);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_seleccion_detalle', array('codigoSeleccion' => $codigoSeleccion)));
                }
            }
            if($form->get('BtnDetalleVerificarReferencia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarReferencia');
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
        $arSeleccionVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionVisita')->findBy(array('codigoSeleccionFk' => $codigoSeleccion));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arSeleccionReferencias' => $arSeleccionReferencias,
                    'arSeleccionPruebas' => $arSeleccionPruebas,
                    'arSeleccionVisita' => $arSeleccionVisita,
                    'form' => $form->createView()
                    ));
    }

    public function agregarReferenciaAction($codigoSeleccion, $codigoSeleccionReferencia) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionReferencia = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionReferencia();
        if($codigoSeleccionReferencia != 0) {
            $arSeleccionReferencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->find($codigoSeleccionReferencia);
        }
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

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:agregarReferencia.html.twig', array(
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
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:agregarPrueba.html.twig', array('form' => $form->createView()));
    }

    public function agregarVisitaAction($codigoSeleccion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        $arSeleccionVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionVisita();
        $form = $this->createForm(new RhuSeleccionVisitaType(), $arSeleccionVisita);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSeleccionVisita = $form->getData();
            $arSeleccionVisita->setSeleccionRel($arSeleccion);
            $em->persist($arSeleccionVisita);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_seleccion_agregar_visita', array('codigoSeleccion' => $codigoSeleccion)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }

        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Seleccion:agregarVisita.html.twig', array('form' => $form->createView()));
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
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonEliminarReferencia = array('label' => 'Eliminar referencia', 'disabled' => false);        
        $arrBotonEliminarPrueba = array('label' => 'Eliminar prueba', 'disabled' => false);
        $arrBotonEliminarVisita = array('label' => 'Eliminar visita', 'disabled' => false);
        $arrBotonDetalleVerificarReferencia = array('label' => 'Verificar', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 0) {                        
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;                                    
        } else {
            $arrBotonDetalleVerificarReferencia['disabled'] = true;
            $arrBotonAutorizar['disabled'] = true;                                 
            $arrBotonEliminarReferencia['disabled'] = true;
            $arrBotonEliminarPrueba['disabled'] = true;
            $arrBotonEliminarVisita['disabled'] = true;
        }
        /*if ($ar->getEstadoCerrado() == 1){
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;
            $arrBotonDetalleVerificarReferencia['disabled'] = true;
            $arrBotonEliminarPrueba['disabled'] = true;
            $arrBotonEliminarReferencia['disabled'] = true;
            $arrBotonEliminarVisita['disabled'] = true;
        }*/
        $form = $this->createFormBuilder()
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar) 
                    ->add('BtnCerrar', 'submit', $arrBotonCerrar) 
                    ->add('BtnDetalleVerificarReferencia', 'submit', $arrBotonDetalleVerificarReferencia) 
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnEliminarReferencia', 'submit', $arrBotonEliminarReferencia) 
                    ->add('BtnEliminarPrueba', 'submit', $arrBotonEliminarPrueba) 
                    ->add('BtnEliminarVisita', 'submit', $arrBotonEliminarVisita)                                 
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
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'GRUPO')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO_COSTOS')
                    ->setCellValue('G1', 'TELEFONO')
                    ->setCellValue('H1', 'CELULAR')
                    ->setCellValue('I1', 'PRUEBAS_PRESENTADAS')
                    ->setCellValue('J1', 'APROBADO')
                    ->setCellValue('K1', 'REFERENCIAS_VERIFICADAS')
                    ->setCellValue('L1', 'ABIERTO');

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
            if ($arSelecciones->getCodigoSeleccionGrupoFk() == null)
            {
                $seleccionGrupo = "";
            }
            else
            {
                $seleccionGrupo = $arSelecciones->getSeleccionGrupoRel()->getNombre();
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSelecciones->getCodigoSeleccionPk())
                    ->setCellValue('B' . $i, $arSelecciones->getSeleccionTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $seleccionGrupo)
                    ->setCellValue('D' . $i, $arSelecciones->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arSelecciones->getNombreCorto())
                    ->setCellValue('F' . $i, $arSelecciones->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arSelecciones->getTelefono())
                    ->setCellValue('H' . $i, $arSelecciones->getCelular())
                    ->setCellValue('I' . $i, $presentarP)
                    ->setCellValue('J' . $i, $aprobado)
                    ->setCellValue('K' . $i, $referenciasV)
                    ->setCellValue('L' . $i, $abierto);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Seleccionados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="seleccionados.xlsx"');
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
