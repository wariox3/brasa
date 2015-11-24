<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurProgramacionType;
class ProgramacionController extends Controller
{
    var $strListaDql = "";
    var $codigoProgramacion = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_programacion_lista'));                                 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arProgramaciones = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:lista.html.twig', array(
            'arProgramaciones' => $arProgramaciones, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoProgramacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        if($codigoProgramacion != 0) {
            $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        }else{
            $arProgramacion->setFecha(new \DateTime('now'));            
        }        
        $form = $this->createForm(new TurProgramacionType, $arProgramacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProgramacion = $form->getData();                       
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
                $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arTercero) > 0) {
                    $arProgramacion->setTerceroRel($arTercero);
                    $em->persist($arProgramacion);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_programacion_nuevo', array('codigoProgramacion' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $arProgramacion->getCodigoProgramacionPk())));
                    }                      
                } else {
                    $objMensaje->Mensaje("error", "El tercero no existe", $this);
                }                             
            }            
            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:nuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'form' => $form->createView()));
    }        

    public function detalleAction($codigoProgramacion) {
        $em = $this->getDoctrine()->getManager(); 
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->formularioDetalle($arProgramacion);
        $form->handleRequest($request);
        if($form->isValid()) { 
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arProgramacion->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->numeroRegistros($codigoProgramacion) > 0) {
                        $arProgramacion->setEstadoAutorizado(1);
                        $em->persist($arProgramacion);
                        $em->flush();                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles a la programacion', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }    
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $arProgramacion->setEstadoAutorizado(0);
                    $em->persist($arProgramacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
                }
            }   
            if($form->get('BtnAprobar')->isClicked()) {            
                if($arProgramacion->getEstadoAutorizado() == 1) {
                    $arProgramacion->setEstadoAprobado(1);
                    $em->persist($arProgramacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
                }
            }            
            if($form->get('BtnDetalleNuevoLibre')->isClicked()) {
                $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                $em->persist($arProgramacionDetalle);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
                    $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);                                                            
                    if($arrControles['TxtRecurso'.$intCodigo] != '') {
                        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['TxtRecurso'.$intCodigo]);
                        if($arRecurso) {
                            $arProgramacionDetalle->setRecursoRel($arRecurso);
                        }
                    }
                    
                    if($arrControles['TxtDia1'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia1($arrControles['TxtDia1'.$intCodigo]);                                                
                    } else {
                        $arPlantillaDetalle->setDia1(null);                                                
                    }
                    if($arrControles['TxtDia2'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia2($arrControles['TxtDia2'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia2(null);                                                
                    }
                    if($arrControles['TxtDia3'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia3($arrControles['TxtDia3'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia3(null);                                                
                    }
                    if($arrControles['TxtDia4'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia4($arrControles['TxtDia4'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia4(null);                                                
                    }
                    if($arrControles['TxtDia5'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia5($arrControles['TxtDia5'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia5(null);                                                
                    }                   
                    if($arrControles['TxtDia6'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia6($arrControles['TxtDia6'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia6(null);                                                
                    }
                    if($arrControles['TxtDia7'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia7($arrControles['TxtDia7'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia7(null);                                                
                    }
                    if($arrControles['TxtDia8'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia8($arrControles['TxtDia8'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia8(null);                                                
                    }
                    if($arrControles['TxtDia9'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia9($arrControles['TxtDia9'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia9(null);                                                
                    }
                    if($arrControles['TxtDia10'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia10($arrControles['TxtDia10'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia10(null);                                                
                    }
                    if($arrControles['TxtDia11'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia11($arrControles['TxtDia11'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia11(null);                                                
                    }
                    if($arrControles['TxtDia12'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia12($arrControles['TxtDia12'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia12(null);                                                
                    }
                    if($arrControles['TxtDia13'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia13($arrControles['TxtDia13'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia13(null);                                                
                    }
                    if($arrControles['TxtDia14'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia14($arrControles['TxtDia14'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia14(null);                                                
                    }
                    if($arrControles['TxtDia15'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia15($arrControles['TxtDia15'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia15(null);                                                
                    }
                    if($arrControles['TxtDia16'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia16($arrControles['TxtDia16'.$intCodigo]);                  
                    } else {
                        $arPlantillaDetalle->setDia16(null);                                                
                    }
                    if($arrControles['TxtDia17'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia17($arrControles['TxtDia17'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia17(null);                                                
                    }
                    if($arrControles['TxtDia18'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia18($arrControles['TxtDia18'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia18(null);                                                
                    }
                    if($arrControles['TxtDia19'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia19($arrControles['TxtDia19'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia19(null);                                                
                    }
                    if($arrControles['TxtDia20'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia20($arrControles['TxtDia20'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia20(null);                                                
                    }
                    if($arrControles['TxtDia21'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia21($arrControles['TxtDia21'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia21(null);                                                
                    }
                    if($arrControles['TxtDia22'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia22($arrControles['TxtDia22'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia22(null);                                                
                    }
                    if($arrControles['TxtDia23'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia23($arrControles['TxtDia23'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia23(null);                                                
                    }
                    if($arrControles['TxtDia24'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia24($arrControles['TxtDia24'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia24(null);                                                
                    }
                    if($arrControles['TxtDia25'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia25($arrControles['TxtDia25'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia25(null);                                                
                    }
                    if($arrControles['TxtDia26'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia26($arrControles['TxtDia26'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia26(null);                                                
                    }
                    if($arrControles['TxtDia27'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia27($arrControles['TxtDia27'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia27(null);                                                
                    }
                    if($arrControles['TxtDia28'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia28($arrControles['TxtDia28'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia28(null);                                                
                    }
                    if($arrControles['TxtDia29'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia29($arrControles['TxtDia29'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia29(null);                                                
                    }
                    if($arrControles['TxtDia30'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia30($arrControles['TxtDia30'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia30(null);                                                
                    }
                    if($arrControles['TxtDia31'.$intCodigo] != '') {
                        $arProgramacionDetalle->setDia31($arrControles['TxtDia31'.$intCodigo]);                 
                    } else {
                        $arPlantillaDetalle->setDia31(null);                                                
                    }                   
                    $em->persist($arProgramacionDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }     
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle', array('codigoProgramacion' => $codigoProgramacion)));
            }            
        }
        $strAnioMes = "2015/11";
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);         
        }
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array ('codigoProgramacionFk' => $codigoProgramacion));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalle.html.twig', array(
                    'arProgramacion' => $arProgramacion,
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arrDiaSemana' => $arrDiaSemana,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoProgramacion, $codigoProgramacionDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arProgramacion = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramacion = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->find($codigoProgramacion);
        $form = $this->createFormBuilder()
            ->add('plantillaRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPlantilla',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arPlantilla = $form->get('plantillaRel')->getData();                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arrControles = $request->request->All();
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {    
                        $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
                        $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($codigo); 
                        $intDiaInicial = $arPedidoDetalle->getFechaDesde()->format('j');
                        $intDiaFinal = $arPedidoDetalle->getFechaHasta()->format('j');
                        $strMesAnio = $arPedidoDetalle->getFechaHasta()->format('Y/m');
                        for($j = 1; $j <= $arPedidoDetalle->getCantidad(); $j++) {
                            if($arPlantilla) {
                                $arPlantillaDetalles = new \Brasa\TurnoBundle\Entity\TurPlantillaDetalle();
                                $arPlantillaDetalles = $em->getRepository('BrasaTurnoBundle:TurPlantillaDetalle')->findBy(array('codigoPlantillaFk' => 1));
                                foreach ($arPlantillaDetalles as $arPlantillaDetalle) {
                                    $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();                            
                                    $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                                    $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                    for($i = 1; $i < 32; $i++) {                                
                                        $boolAplica = $this->aplicaPlantilla($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle);
                                        if($i == 1 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia1($this->devuelveCodigoTurno($arPlantillaDetalle->getDia1()));                                    
                                        }                                                        
                                        if($i == 2 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia2($this->devuelveCodigoTurno($arPlantillaDetalle->getDia2()));                                    
                                        }
                                        if($i == 3 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia3($this->devuelveCodigoTurno($arPlantillaDetalle->getDia3()));                                    
                                        }
                                        if($i == 4 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia4($this->devuelveCodigoTurno($arPlantillaDetalle->getDia4()));                                    
                                        }
                                        if($i == 5 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia5($this->devuelveCodigoTurno($arPlantillaDetalle->getDia5()));                                    
                                        }
                                        if($i == 6 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia6($this->devuelveCodigoTurno($arPlantillaDetalle->getDia6()));                                    
                                        }
                                        if($i == 7 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia7($this->devuelveCodigoTurno($arPlantillaDetalle->getDia7()));                                    
                                        }
                                        if($i == 8 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia8($this->devuelveCodigoTurno($arPlantillaDetalle->getDia8()));                                    
                                        }
                                        if($i == 9 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia9($this->devuelveCodigoTurno($arPlantillaDetalle->getDia9()));                                    
                                        }
                                        if($i == 10 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia10($this->devuelveCodigoTurno($arPlantillaDetalle->getDia10()));                                    
                                        }
                                        if($i == 11 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia11($this->devuelveCodigoTurno($arPlantillaDetalle->getDia11()));                                    
                                        }
                                        if($i == 12 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia12($this->devuelveCodigoTurno($arPlantillaDetalle->getDia12()));                                    
                                        }
                                        if($i == 13 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia13($this->devuelveCodigoTurno($arPlantillaDetalle->getDia13()));                                    
                                        }
                                        if($i == 14 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia14($this->devuelveCodigoTurno($arPlantillaDetalle->getDia14()));                                    
                                        }
                                        if($i == 15 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia15($this->devuelveCodigoTurno($arPlantillaDetalle->getDia15()));                                    
                                        }
                                        if($i == 16 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia16($this->devuelveCodigoTurno($arPlantillaDetalle->getDia16()));                                    
                                        }
                                        if($i == 17 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia17($this->devuelveCodigoTurno($arPlantillaDetalle->getDia17()));                                    
                                        }
                                        if($i == 18 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia18($this->devuelveCodigoTurno($arPlantillaDetalle->getDia18()));                                    
                                        }
                                        if($i == 19 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia19($this->devuelveCodigoTurno($arPlantillaDetalle->getDia19()));                                    
                                        }
                                        if($i == 20 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia20($this->devuelveCodigoTurno($arPlantillaDetalle->getDia20()));                                    
                                        }
                                        if($i == 21 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia21($this->devuelveCodigoTurno($arPlantillaDetalle->getDia21()));                                    
                                        }
                                        if($i == 22 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia22($this->devuelveCodigoTurno($arPlantillaDetalle->getDia22()));                                    
                                        }
                                        if($i == 23 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia23($this->devuelveCodigoTurno($arPlantillaDetalle->getDia23()));                                    
                                        }
                                        if($i == 24 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia24($this->devuelveCodigoTurno($arPlantillaDetalle->getDia24()));                                    
                                        }
                                        if($i == 25 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia25($this->devuelveCodigoTurno($arPlantillaDetalle->getDia25()));                                    
                                        }
                                        if($i == 26 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia26($this->devuelveCodigoTurno($arPlantillaDetalle->getDia26()));                                    
                                        }                                
                                        if($i == 27 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia27($this->devuelveCodigoTurno($arPlantillaDetalle->getDia27()));                                    
                                        }
                                        if($i == 28 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia28($this->devuelveCodigoTurno($arPlantillaDetalle->getDia28()));                                    
                                        }
                                        if($i == 29 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia29($this->devuelveCodigoTurno($arPlantillaDetalle->getDia29()));                                    
                                        }
                                        if($i == 30 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia30($this->devuelveCodigoTurno($arPlantillaDetalle->getDia30()));                                    
                                        }
                                        if($i == 31 && $boolAplica == TRUE) {
                                            $arProgramacionDetalle->setDia31($this->devuelveCodigoTurno($arPlantillaDetalle->getDia31()));                                    
                                        }                                
                                    }                                                        
                                    $em->persist($arProgramacionDetalle);                            
                                }                                                            
                            } else {
                                if($arrControles['TxtCantidad'.$codigo] != '' && is_numeric($arrControles['TxtCantidad'.$codigo]) && $arrControles['TxtCantidad'.$codigo] != 0) {
                                    $intCantidad = $arrControles['TxtCantidad'.$codigo];
                                    for($k = 1; $k <=$intCantidad; $k++) {
                                        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();                            
                                        $arProgramacionDetalle->setProgramacionRel($arProgramacion);
                                        $arProgramacionDetalle->setPedidoDetalleRel($arPedidoDetalle);
                                        $em->persist($arProgramacionDetalle);
                                    }                                    
                                }
                            }                            
                        }                        
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
            }            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                           
        }
        $arPedidosDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->pendientesCliente($arProgramacion->getCodigoTerceroFk());
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevo.html.twig', array(
            'arProgramacion' => $arProgramacion,
            'arPedidosDetalle' => $arPedidosDetalle,
            'form' => $form->createView()));
    }   
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurProgramacion')->listaDQL($this->codigoProgramacion);
    }

    private function filtrar ($form) {        
        $this->codigoProgramacion = $form->get('TxtCodigo')->getData();
    }
    
    private function formularioFiltro() {        
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $session->get('filtroIdentificacion')))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);        
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleNuevoLibre = array('label' => 'Nuevo libre', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = false;            
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonDetalleNuevoLibre['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = true;            
        } 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                 
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)                 
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleNuevoLibre', 'submit', $arrBotonDetalleNuevoLibre)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;                
    }

    private function generarExcel() {
        ob_clean();
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
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arProgramaciones = new \Brasa\TurnoBundle\Entity\TurProgramacion();
        $arProgramaciones = $query->getResult();

        foreach ($arProgramaciones as $arProgramacion) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacion->getCodigoProgramacionPk())
                    ->setCellValue('B' . $i, $arProgramacion->getTerceroRel()->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Programaciones');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Programaciones.xlsx"');
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

    private function devuelveCodigoTurno ($strCodigo) {
        $strCodigoReal = "";
        if($strCodigo == 'A') {
            $strCodigoReal = '1';
        }
        if($strCodigo == 'B') {
            $strCodigoReal = '2';
        }
        if($strCodigo == 'D') {
            $strCodigoReal = 'D';
        }        
        return $strCodigoReal;                  
    }
    
    private function aplicaPlantilla ($i, $intDiaInicial, $intDiaFinal, $strMesAnio, $arPedidoDetalle) {
        $boolResultado = FALSE;
        if($i >= $intDiaInicial && $i <= $intDiaFinal) {
            $strFecha = $strMesAnio . '/' . $i;
            $dateNuevaFecha = date_create($strFecha);
            $diaSemana = $dateNuevaFecha->format('N');
            if($diaSemana == 1) {
                if($arPedidoDetalle->getLunes() == 1) {
                    $boolResultado = TRUE;
                }                                        
            }                                        
            if($diaSemana == 2) {
                if($arPedidoDetalle->getMartes() == 1) {
                    $boolResultado = TRUE;
                }                                        
            }
            if($diaSemana == 3) {
                if($arPedidoDetalle->getMiercoles() == 1) {
                    $boolResultado = TRUE;
                }                                        
            }
            if($diaSemana == 4) {
                if($arPedidoDetalle->getJueves() == 1) {
                    $boolResultado = TRUE;
                }                                        
            }
            if($diaSemana == 5) {
                if($arPedidoDetalle->getViernes() == 1) {
                    $boolResultado = TRUE;
                }                                        
            }
            if($diaSemana == 6) {
                if($arPedidoDetalle->getSabado() == 1) {
                    $boolResultado = TRUE;
                }                                        
            }
            if($diaSemana == 7) {
                if($arPedidoDetalle->getDomingo() == 1) {
                    $boolResultado = TRUE;
                }                                        
            }            
        }        
        return $boolResultado;
    }
    
    private function devuelveDiaSemanaEspaniol ($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;            
            case 2:
                $strDia = "m";
                break;            
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;            
        }
        
        return $strDia;
    }

}