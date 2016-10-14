<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuVisitaType;


class VisitaController extends Controller
{
    var $strListaDql = "";

    /**
     * @Route("/rhu/movimiento/visita", name="brs_rhu_movimiento_visita")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 17, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoVisita) {
                        $arVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuVisita();
                        $arVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisita')->find($codigoVisita);
                        if ($arVisita->getEstadoAutorizado() == 1 || $arVisita->getEstadoCerrado() == 1){
                            $objMensaje->Mensaje("error", "La visita ". $codigoVisita ." ya fue autorizada y/o cerrada, no se pude eliminar", $this);
                        } else {                            
                            $em->remove($arVisita);
                            }
                        } 
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_visita_lista'));
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

        $arVisitas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Visita:lista.html.twig', array('arVisitas' => $arVisitas, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/visita/nuevo/{codigoVisita}", name="brs_rhu_movimiento_visita_nuevo")
     */
    public function nuevoAction($codigoVisita = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuVisita();    
        if($codigoVisita != 0) {
            $arVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisita')->find($codigoVisita);
        } else {
            $arVisita->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuVisitaType, $arVisita);         
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arVisita = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arVisita->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {                        
                        $arVisita->setFecha(new \DateTime('now'));
                        if($codigoVisita == 0) {
                            $arVisita->setCodigoUsuario($arUsuario->getUserName());                                                                                                                
                        }
                        $em->persist($arVisita);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_visita_nuevo', array('codigoVisita' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_visita_detalle', array('codigoVisita' => $arVisita->getCodigoVisitaPk() )));
                        }                        
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }                
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Visita:nuevo.html.twig', array(
            'arVisita' => $arVisita,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/visita/detalle/{codigoVisita}", name="brs_rhu_movimiento_visita_detalle")
     */
    public function detalleAction($codigoVisita) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuVisita();
        $arVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisita')->find($codigoVisita);
        $form = $this->formularioDetalle($arVisita);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arVisita->getEstadoAutorizado() == 0) {
                    $arVisitaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisitaDetalle')->findBy(array('codigoDotacionFk' => $codigoVisita));
                    if ($arVisitaDetalle != null){
                        $arVisita->setEstadoAutorizado(1);
                        $em->persist($arVisita);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $codigoVisita)));                                                
                    } else {
                        $objMensaje->Mensaje("error", "La dotación no tiene detalles, no se puede autorizar", $this);
                    }    
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arVisita->getEstadoAutorizado() == 1) {
                    $arVisita->setEstadoAutorizado(0);
                    $em->persist($arVisita);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $codigoVisita)));                                                
                }
            }            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arVisita->getEstadoAutorizado() == 1) {
                    $objFormatoDotacionDetalle = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDotacionDetalle();
                    $objFormatoDotacionDetalle->Generar($this, $codigoVisita);
                }    
            }
            
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                if($arVisita->getEstadoAutorizado() == 0) {
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoVisitaPk) {
                            $arVisitaDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuVisitaDetalle();
                            $arVisitaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisitaDetalle')->find($codigoVisitaPk);
                            if($arVisitaDetalle->getCodigoDotacionDetalleEnlaceFk()) {
                                $arVisitaDetalleEnlace = new \Brasa\RecursoHumanoBundle\Entity\RhuVisitaDetalle();
                                $arVisitaDetalleEnlace = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisitaDetalle')->find($arVisitaDetalle->getCodigoDotacionDetalleEnlaceFk());                            
                                $arVisitaDetalleEnlace->setCantidadDevuelta($arVisitaDetalleEnlace->getCantidadDevuelta() - $arVisitaDetalle->getCantidadAsignada());
                                $em->persist($arVisitaDetalleEnlace);
                            }
                            $em->remove($arVisitaDetalle);
                        }
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_dotacion_detalle', array('codigoDotacion' => $codigoVisita)));
                }
            }
            /*if($form->get('BtnCerrar')->isClicked()) {
                $arVisitaDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisita')->dotacionDevolucion($arVisitaes->getCodigoEmpleadoFk());
                $intRegistros = count($arVisitaDetalle);
                if ($intRegistros > 0){
                    $objMensaje->Mensaje("error", "No se puede cerrar, el empleado tiene devoluciones pendientes", $this);
                }else{
                    $arCerrarDotaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisita')->findBy(array('codigoEmpleadoFk' => $arVisitaes->getCodigoEmpleadoFk()));
                    foreach ($arCerrarDotaciones as $arCerrarDotacion) {
                        $arCerrarDotacion->setEstadoCerrado(1);
                        $em->persist($arCerrarDotacion);
                    }
                    $em->flush();
                }
            }*/

        }
        $arVisitaDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuVisitaDetalle();
        $arVisitaDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisitaDetalle')->FindBy(array('codigoDotacionFk' => $codigoVisita));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Dotacion:detalle.html.twig', array(
                    'arDotacion' => $arVisita,
                    'arDotacionDetalles' => $arVisitaDetalles,
                    'form' => $form->createView()
                    ));
    }


    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisita')->listaDQL(
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
    
    private function formularioDetalle($arVisita) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);        
        if($arVisita->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonEliminarDetalle['disabled'] = true;            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)                                
                    ->getForm();  
        return $form;
    }     
    
    private function generarExcel() {
        ob_clean();
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
                $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Fecha')
                            ->setCellValue('C1', 'Centro Centro')
                            ->setCellValue('D1', 'Identificación')
                            ->setCellValue('E1', 'Empleado')
                            ->setCellValue('F1', 'Número Interno Referencia');
                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arVisitaes = new \Brasa\RecursoHumanoBundle\Entity\RhuVisita();
                $arVisitaes = $query->getResult();

                foreach ($arVisitaes as $arVisita) {

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arVisita->getCodigoDotacionPk())
                            ->setCellValue('B' . $i, $arVisita->getFecha()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $arVisita->getCentroCostoRel()->getNombre())
                            ->setCellValue('D' . $i, $arVisita->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arVisita->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arVisita->getCodigoInternoReferencia());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Dotacion');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Dotacion.xlsx"');
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
