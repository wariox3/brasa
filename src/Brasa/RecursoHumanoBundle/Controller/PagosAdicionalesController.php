<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAdicionalPagoType;
use Symfony\Component\HttpFoundation\Request;

class PagosAdicionalesController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaTiempoSuplementarioMasivo = "";
    var $nombre = "";
    var $identificacion = "";
    var $aplicarDiaLaborado = 2;
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar($form);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista'));
                }
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getEstadoInactivo() == 1) {
                            $arPagoAdicional->setEstadoInactivo(0);
                        } else {
                            $arPagoAdicional->setEstadoInactivo(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista'));
                }
            }            
            if($form->get('BtnAplicaDiaLaborado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getAplicaDiaLaborado() == 1) {
                            $arPagoAdicional->setAplicaDiaLaborado(0);
                        } else {
                            $arPagoAdicional->setAplicaDiaLaborado(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista'));
                }
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form);
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $form = $this->formularioLista();
                $this->listar($form);
                $this->generarExcel();
            }
        }
        //$this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaDql("");        
        $arPagosAdicionales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:lista.html.twig', array(
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'form' => $form->createView()
                    ));
    }
    
    private function formularioLista() {
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
        $arrayPropiedadesConcepto = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoPagoConcepto')) {
            $arrayPropiedadesConcepto['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuPagoConcepto", $session->get('filtroCodigoPagoConcepto'));
        }
        $strNombreCorto = "";
        if($session->get('filtroNumeroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroNumeroIdentificacion')));
            if($arEmpleado) {
                $session->set('filtroNumeroIdentificacion', $arEmpleado->getNumeroIdentificacion());
                $strNombreCorto = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroNumeroIdentificacion', null);
            }          
        } else {
            $session->set('filtroNumeroIdentificacion', null);
        }       
        
        $form = $this->createFormBuilder()
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Numero Identificacion','data' => $session->get('filtroNumeroIdentificacion'), 'required' => false))
            ->add('txtNombreCorto', 'text', array('label'  => 'NombreCorto','data' => $strNombreCorto))                    
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('pagoConceptoRel', 'entity', $arrayPropiedadesConcepto)    
            ->add('BtnRetirarConcepto', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnInactivar', 'submit', array('label'  => 'Inactivar',))
            ->add('BtnAplicaDiaLaborado', 'submit', array('label'  => 'Aplicar a dia laborado',))
            ->add('aplicarDiaLaborado', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI'), 'data' => $session->get('filtroAplicarDiaLaborado')))                
            ->add('estadoInactivo', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI'), 'data' => $session->get('filtroPagoAdicionalEstadoInactivo')))                                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function listar($form) {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaAdicionalesDql(                    
            $session->get('filtroNumeroIdentificacion'),
            $session->get('filtroAplicarDiaLaborado'),        
            $session->get('filtroCodigoCentroCosto'),
            $session->get('filtroCodigoPagoConcepto'),
            $session->get('filtroPagoAdicionalEstadoInactivo'));
    }

    private function filtrarLista($form) {
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $controles = $request->request->get('form');
        $arrControles = $request->request->All();
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroNumeroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroAplicarDiaLaborado', $form->get('aplicarDiaLaborado')->getData());
        $session->set('filtroCodigoPagoConcepto', $controles['pagoConceptoRel']);
        $session->set('filtroPagoAdicionalEstadoInactivo', $form->get('estadoInactivo')->getData());
    }

    public function detalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        $form = $this->createFormBuilder()
            ->add('BtnRetirarConcepto', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnAplicaDiaLaborado', 'submit', array('label'  => 'Aplicar a dia laborado',))                
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnRetirarConcepto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        $em->remove($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }
            if($form->get('BtnConceptoPermanente')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getPermanente() == 1) {
                            $arPagoAdicional->setPermanente(0);
                        } else {
                            $arPagoAdicional->setPermanente(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }
            if($form->get('BtnAplicaDiaLaborado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoPagoAdicional) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
                        if($arPagoAdicional->getAplicaDiaLaborado() == 1) {
                            $arPagoAdicional->setAplicaDiaLaborado(0);
                        } else {
                            $arPagoAdicional->setAplicaDiaLaborado(1);
                        }
                        $em->persist($arPagoAdicional);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_detalle', array('codigoProgramacionPago' => $codigoProgramacionPago)));
                }
            }            
        }
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->listaDql($codigoProgramacionPago);        
        $arPagosAdicionales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:detalle.html.twig', array(
                    'arProgramacionPago' => $arProgramacionPago,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'form' => $form->createView()
                    ));
    }

    public function generarMasivoListaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 0));

        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);

        $arCentrosCostos = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentrosCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->findAll();


        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoLista.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    public function generarMasivoSuplementarioDetalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->get('session');
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
        //$query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL('', $arProgramacionPago->getCodigoCentroCostoFk(), 1));
        //$arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);
        //$form = $this->formularioLista();
        
        $arrayPropiedadesDepartamentoEmpresa = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoDepartamentoEmpresa')) {
            $arrayPropiedadesDepartamentoEmpresa['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa", $session->get('filtroCodigoDepartamentoEmpresa'));
        }
        $form = $this->createFormBuilder()
            ->add('departamentoEmpresaRel', 'entity', $arrayPropiedadesDepartamentoEmpresa)
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                                
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))    
            ->getForm();
        $form->handleRequest($request);
        $this->listarTiempoSuplementarioMasivo($arProgramacionPago);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            
            if($form->get('BtnGenerar')->isClicked()) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($intCodigo);
                        if(count($arEmpleado) > 0) {
                            if($arrControles['TxtRNFC'.$intCodigo] != "" && $arrControles['TxtRNFC'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(40);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtRNFC'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtRNFNC'.$intCodigo] != "" && $arrControles['TxtRNFNC'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(41);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);                                
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtRNFNC'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional(4);
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtHEFD'.$intCodigo] != "" && $arrControles['TxtHEFD'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(42);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtHEFD'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtHEFN'.$intCodigo] != "" && $arrControles['TxtHEFN'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(43);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado); 
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtHEFN'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtHEOD'.$intCodigo] != "" && $arrControles['TxtHEOD'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(44);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtHEOD'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtHEON'.$intCodigo] != "" && $arrControles['TxtHEON'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(45);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtHEON'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtDC'.$intCodigo] != "" && $arrControles['TxtDC'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(46);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtDC'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtDNC'.$intCodigo] != "" && $arrControles['TxtDNC'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(47);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtDNC'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                            if($arrControles['TxtRN'.$intCodigo] != "" && $arrControles['TxtRN'.$intCodigo] != 0) {
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find(48);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                                $intHoras = $arrControles['TxtRN'.$intCodigo];
                                $arPagoAdicional->setCantidad($intHoras);
                                $arPagoAdicional->setTipoAdicional($arPagoConcepto->getTipoAdicional());
                                $em->persist($arPagoAdicional);
                            }
                        }
                        $intIndice++;
                    }
                    $em->flush();
                    //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarListaTiempoSuplementirioMasivo($form, $request);
                $this->listarTiempoSuplementarioMasivo($arProgramacionPago);
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaTiempoSuplementarioMasivo), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoSuplementarioDetalle.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }
    
    private function listarTiempoSuplementarioMasivo($ar) {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->get('session');
        $this->strDqlListaTiempoSuplementarioMasivo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaTiempoSuplementarioMasivoDql(
                    '',
                    $ar->getCodigoCentroCostoFk(),
                    1,
                    $session->get('filtroCodigoDepartamentoEmpresa')
                    );  
    }         
    
    private function filtrarListaTiempoSuplementirioMasivo($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroCodigoDepartamentoEmpresa', $controles['departamentoEmpresaRel']);
    }
    
    public function generarMasivoValorDetalleAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');

        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL('', $codigoCentroCosto, 1));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();

        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:generarMasivoSuplementarioDetalle.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CONCEPTO')
                    ->setCellValue('C1', 'DETALLE')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CANTIDAD')
                    ->setCellValue('H1', 'VALOR')                    
                    ->setCellValue('I1', 'PERMANENTE')
                    ->setCellValue('J1', 'APLICA DIA LABORADO');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $query->getResult();
        foreach ($arPagoAdicional as $arPagoAdicional) {
            if ($arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk() == null){
                $srtCentroCosto = "";
            } else {
                $srtCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCentroCostoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arPagoAdicional->getCodigoPagoAdicionalPk())    
                ->setCellValue('B' . $i, $arPagoAdicional->getPagoConceptoRel()->getNombre())
                ->setCellValue('C' . $i, $arPagoAdicional->getDetalle())
                ->setCellValue('D' . $i, $srtCentroCosto)    
                ->setCellValue('E' . $i, $arPagoAdicional->getEmpleadoRel()->getNumeroIdentificacion())                        
                ->setCellValue('F' . $i, $arPagoAdicional->getEmpleadoRel()->getNombreCorto())                    
                ->setCellValue('G' . $i, $arPagoAdicional->getCantidad())                    
                ->setCellValue('H' . $i, $arPagoAdicional->getValor())
                ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getPermanente()))
                ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arPagoAdicional->getAplicaDiaLaborado()));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagosAdicionales');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagosAdicionales.xlsx"');
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