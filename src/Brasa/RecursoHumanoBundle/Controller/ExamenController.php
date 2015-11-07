<?php
namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenControlType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuExamenDetalleType;
class ExamenController extends Controller
{
    var $strListaDql = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->eliminarExamen($arrSeleccionados);
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
        
        $arExamenes = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:lista.html.twig', array('arExamenes' => $arExamenes, 'form' => $form->createView()));
    }

    public function nuevoAction($codigoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        if($codigoExamen != 0) {
            $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        }else{
            $arExamen->setFecha(new \DateTime('now'));            
        }        
        $form = $this->createForm(new RhuExamenType, $arExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arExamen = $form->getData();
            if($arExamen->getExamenClaseRel()->getCodigoExamenClasePk() == 1 && $codigoExamen == 0) {
                $arExamenTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
                $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findBy(array('ingreso' => 1));
                foreach ($arExamenTipos as $arExamenTipo) {                    
                    $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                    $arExamenDetalle->setExamenRel($arExamen);
                    $arExamenDetalle->setExamenTipoRel($arExamenTipo);
                    $floPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->devuelvePrecio($arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk(), $arExamenTipo->getCodigoExamenTipoPk());
                    $arExamenDetalle->setVrPrecio($floPrecio);                                        
                    $arExamenDetalle->setFechaVence(new \DateTime('now'));
                    $em->persist($arExamenDetalle);                    
                }                
            }
            $em->persist($arExamen);
            $em->flush();
            $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($arExamen->getCodigoExamenPk());
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_examen_nuevo', array('codigoExamen' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $arExamen->getCodigoExamenPk())));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:nuevo.html.twig', array(
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }
    
    public function nuevoControlAction($codigoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        if($codigoExamen != 0) {
            $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        }else{
            $arExamen->setFecha(new \DateTime('now'));            
        }        
        $form = $this->createForm(new RhuExamenControlType, $arExamen);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arExamen = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arExamen->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        $arExamen->setIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arExamen->setNombreCorto($arEmpleado->getNombreCorto());
                        $arExamen->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                        $arExamen->setCiudadRel($arEmpleado->getCiudadRel());
                        $arExamen->setEmpleadoRel($arEmpleado);
                        $arExamen->setControl(1);
                        $arExamen->setCodigoSexoFk($arEmpleado->getCodigoSexoFk());
                        $em->persist($arExamen);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_examen_nuevo_control', array('codigoExamen' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $arExamen->getCodigoExamenPk())));
                        }                        
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    } 
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }                 
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:nuevoControl.html.twig', array(
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoExamen) {
        $em = $this->getDoctrine()->getManager(); 
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $form = $this->formularioDetalle($arExamen);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arExamen->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->numeroRegistros($codigoExamen) > 0) {
                        $arExamen->setEstadoAutorizado(1);
                        $em->persist($arExamen);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));                                                                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));                                                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arExamen->getEstadoAutorizado() == 1) {
                    $arExamen->setEstadoAutorizado(0);
                    $em->persist($arExamen);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));                                                
                }
            }
            if ($form->get('BtnAprobar')->isClicked()) {                
                $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->aprobarExamen($codigoExamen);
                if($strRespuesta == ''){
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));                                                
                }else {
                  $objMensaje->Mensaje('error', $strRespuesta, $this);
                }                 
            }      
            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arExamen->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\RecursoHumanoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($this, $codigoExamen);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada", $this);
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($codigoExamen);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
            }
            if($form->get('BtnAprobarDetalle')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->aprobarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
            }
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    if($arrControles['TxtPrecio'.$intCodigo] != "" && $arrControles['TxtPrecio'.$intCodigo] != 0) {
                        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                        $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->find($intCodigo);                                        
                        $floPrecio = $arrControles['TxtPrecio'.$intCodigo];
                        $arExamenDetalle->setVrPrecio($floPrecio);
                        $em->persist($arExamenDetalle);                        
                    }
                }
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($codigoExamen);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoExamen)));
            }            
        }


        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array ('codigoExamenFk' => $codigoExamen));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:detalle.html.twig', array(
                    'arExamen' => $arExamen,
                    'arExamenDetalle' => $arExamenDetalle,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoExamen) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findAll();
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoExamenTipo) {
                        $arExamenTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->find($codigoExamenTipo);
                        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                        $arExamenDetalle->setExamenTipoRel($arExamenTipo);
                        $arExamenDetalle->setExamenRel($arExamen);
                        $arExamenDetalle->setFechaVence(new \DateTime('now'));
                        $douPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->devuelvePrecio($arExamen->getCodigoEntidadExamenFk(), $codigoExamenTipo);
                        $arExamenDetalle->setVrPrecio($douPrecio);
                        $em->persist($arExamenDetalle);
                    }
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($codigoExamen);
                }
            }
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:detalleNuevo.html.twig', array(
            'arExamenTipos' => $arExamenTipos,
            'arExamen' => $arExamen,
            'form' => $form->createView()));
    }

    public function detalleNuevoComentarioAction($codigoExamenDetalle) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
        $arExamenDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->find($codigoExamenDetalle);
        $form = $this->createForm(new RhuExamenDetalleType, $arExamenDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arExamenDetalle = $form->getData();
            $em->persist($arExamenDetalle);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Examen:detalleNuevoComentario.html.twig', array(
            'form' => $form->createView()));
    }    
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->listaDQL(
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroAprobadoExamen')
                );
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest(); 
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroAprobadoExamen', $form->get('estadoAprobado')->getData());
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
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAprobadoExamen')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonActualizarDetalle = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonAprobarDetalle = array('label' => 'Aprobar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonEliminarDetalle['disabled'] = true;
            $arrBotonActualizarDetalle['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobarDetalle['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobarDetalle['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
        }        
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnActualizarDetalle', 'submit', $arrBotonActualizarDetalle)    
                    ->add('BtnAprobarDetalle', 'submit', $arrBotonAprobarDetalle)                                
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'NOMBRES Y APELLIDOS')
                    ->setCellValue('D1', 'EDAD')
                    ->setCellValue('E1', 'SEXO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'ENTIDAD / LABORATORIO')
                    ->setCellValue('I1', 'CIUDAD')
                    ->setCellValue('J1', 'FECHA EXAMEN')
                    ->setCellValue('K1', 'AÑO EXAMEN')
                    ->setCellValue('L1', 'MES EXAMEN')
                    ->setCellValue('M1', 'DIA EXAMEN')
                    ->setCellValue('N1', 'TIPO EXAMEN')
                    ->setCellValue('O1', 'TOTAL')
                    ->setCellValue('P1', 'APROBADO')
                    ->setCellValue('Q1', 'COMENTARIOS GENERALES')
                    ->setCellValue('R1', 'EXAMEN 1')
                    ->setCellValue('S1', 'ESTADO')
                    ->setCellValue('T1', 'OBSERVACIONES')
                    ->setCellValue('U1', 'EXAMEN 2')
                    ->setCellValue('V1', 'ESTADO')
                    ->setCellValue('W1', 'OBSERVACIONES')
                    ->setCellValue('X1', 'EXAMEN 3')
                    ->setCellValue('Y1', 'ESTADO')
                    ->setCellValue('Z1', 'OBSERVACIONES')
                    ->setCellValue('AA1', 'EXAMEN 4')
                    ->setCellValue('AB1', 'ESTADO')
                    ->setCellValue('AC1', 'OBSERVACIONES')
                    ->setCellValue('AD1', 'EXAMEN 5')
                    ->setCellValue('AE1', 'ESTADO')
                    ->setCellValue('AF1', 'OBSERVACIONES')
                    ->setCellValue('AG1', 'EXAMEN 6')
                    ->setCellValue('AH1', 'ESTADO')
                    ->setCellValue('AI1', 'OBSERVACIONES');

        $i = 2;
        
        $query = $em->createQuery($this->strListaDql);
                $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
                $arExamenes = $query->getResult();
                
        foreach ($arExamenes as $arExamen) {
            $strNombreCentroCosto = "";
            if($arExamen->getCentroCostoRel()) {
                $strNombreCentroCosto = $arExamen->getCentroCostoRel()->getNombre();
            }
            $strNombreEntidad = "SIN ENTIDAD";
            if($arExamen->getEntidadExamenRel()) {
                $strNombreEntidad = $arExamen->getEntidadExamenRel()->getNombre();
            }
            if ($arExamen->getEstadoAprobado() == 1){
                $aprobado = "SI";
            } else {
                $aprobado = "NO";
            }
            //Calculo edad
            $varFechaNacimientoAnio = $arExamen->getFechaNacimiento()->format('Y');
            $varFechaNacimientoMes =  $arExamen->getFechaNacimiento()->format('m');
            $varMesActual = date('m');
            if ($varMesActual >= $varFechaNacimientoMes){
                $varEdad = date('Y') - $varFechaNacimientoAnio;
            } else {
                $varEdad = date('Y') - $varFechaNacimientoAnio -1;
            }
            //Fin calculo edad
            $arDetalleExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $arExamen->getCodigoExamenPk()));
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arExamen->getCodigoExamenPk())
                    ->setCellValue('B' . $i, $arExamen->getIdentificacion())
                    ->setCellValue('C' . $i, $arExamen->getNombreCorto())
                    ->setCellValue('D' . $i, $varEdad)
                    ->setCellValue('E' . $i, $arExamen->getCodigoSexoFk())
                    ->setCellValue('F' . $i, $arExamen->getCargoDescripcion())
                    ->setCellValue('G' . $i, $arExamen->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $strNombreEntidad)
                    ->setCellValue('I' . $i, $arExamen->getCiudadRel()->getNombre())
                    ->setCellValue('J' . $i, $arExamen->getFecha())
                    ->setCellValue('K' . $i, $arExamen->getFecha()->format('Y'))
                    ->setCellValue('L' . $i, $arExamen->getFecha()->format('m'))
                    ->setCellValue('M' . $i, $arExamen->getFecha()->format('d'))
                    ->setCellValue('N' . $i, $arExamen->getExamenClaseRel()->getNombre())
                    ->setCellValue('O' . $i, $arExamen->getVrTotal())
                    ->setCellValue('P' . $i, $aprobado)
                    ->setCellValue('Q' . $i, $arExamen->getComentarios());
                    $array = array();
                    foreach ($arDetalleExamen as $arDetalleExamen){
                        $array[] = $arDetalleExamen->getCodigoExamenTipoFk();
                        $array[] = $arDetalleExamen->getEstadoAprobado();
                        $array[] = $arDetalleExamen->getComentarios();
                    }
                    
                    
                    foreach ($array as $posicion=>$jugador){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('R' . $i, $jugador)
                            ->setCellValue('S' . $i, $jugador)
                            ->setCellValue('T' . $i, $jugador)
                            ->setCellValue('U' . $i, $jugador)
                            ->setCellValue('V' . $i, $jugador);
                    }
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Examen');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Examenes.xlsx"');
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