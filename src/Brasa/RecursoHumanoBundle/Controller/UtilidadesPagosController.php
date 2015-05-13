<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilidadesPagosController extends Controller
{
    public function generarPeriodoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('ChkMostrarInactivos', 'checkbox', array('label'=> '', 'required'  => false,))                 
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => ''))                       
            ->add('BtnBuscar', 'submit', array('label'  => 'Filtrar'))                                
            ->add('Actualizar', 'submit')
            ->add('Generar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('',0));
            if($form->get('Generar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {                                                
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($codigoCentroCosto);
                    }
                }
            }
            if($form->get('BtnBuscar')->isClicked()) {
                $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('', $form->get('ChkMostrarInactivos')->getData()));
            }            
        } else {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL($form->get('TxtNombre')->getData(), 0));
        }        
        $arCentroCosto = $paginator->paginate($query, $request->query->get('page', 1), 100);                                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPeriodoPago.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()
            ));
    }

    public function generarPagoAction () {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');       
        $form = $this->createFormBuilder()
            ->add('BtnNovedadesVerificadas', 'submit', array('label'  => 'Novedades verificadas',))            
            ->add('BtnNoGenerar', 'submit', array('label'  => 'No generar pago',))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar',))
            ->getForm();
        $form->handleRequest($request);
        $frmPagar = $this->createFormBuilder()
            ->add('BtnGenerarArchivoBancolombia', 'submit', array('label'  => 'Generar archivo bancolombia',))
            ->add('BtnLiquidar', 'submit', array('label'  => 'Liquidar',))
            ->add('BtnPagar', 'submit', array('label'  => 'Pagar',))
            ->add('BtnAnular', 'submit', array('label'  => 'Anular',))
            ->add('BtnDeshacer', 'submit', array('label'  => 'Des-hacer',))    
            ->getForm();
        $frmPagar->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                if($form->get('BtnGenerar')->isClicked()) {
                    $boolErrores = 0;
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                        if($arProgramacionPagoProcesar->getNovedadesVerificadas() == 1) {
                            $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'estadoActivo' => 1, 'pagadoEntidadSalud' => 0));
                            foreach ($arEmpleados as $arEmpleado) {
                                $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arPago->setEmpleadoRel($arEmpleado);
                                $arPago->setCentroCostoRel($arCentroCosto);
                                $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                                $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                                $arPago->setVrSalario($arEmpleado->getVrSalario());
                                $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                $em->persist($arPago);
                                /*if($arEmpleado->getNumeroIdentificacion() =='1038406105') {
                                    echo "Entro";
                                }*/
                                  
                                 
                                
                                //Parametros generales
                                //$intDiasLaborados = $arProgramacionPagoProcesar->getDias();
                                $intDiasLaborados = $this->devolverDiasLaborados($arEmpleado, $arProgramacionPagoProcesar, $arProgramacionPagoProcesar->getDias());

                                $douVrDia = $arEmpleado->getVrSalario() / 30;
                                $douVrHora = $douVrDia / 8;
                                $douVrSalarioMinimo = 644350; //Configurar desde configuraciones
                                $douSalarioPrestacional = 0;
                                //Procesar Incapacidades
                                $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                                $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                foreach ($arIncapacidades as $arIncapacidad) {
                                    $intConceptoIncapacidad = 0;
                                    if($arIncapacidad->getIncapacidadTipoRel()->getIncapacidadGeneral() == 1) {
                                        $intConceptoIncapacidad = 16; //Configurar desde configuraciones
                                    } else {
                                        $intConceptoIncapacidad = 17; //Configurar desde configuraciones
                                    }
                                    $arPagoConceptoIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                    $arPagoConceptoIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intConceptoIncapacidad);
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConceptoIncapacidad);
                                    $douPagoDetalle = 0;
                                    $intDiasProcesarIncapacidad = 0;
                                    if($arIncapacidad->getCantidadPendiente() <= $intDiasLaborados) {
                                        $intDiasLaborados = $intDiasLaborados - $arIncapacidad->getCantidad();
                                        $intDiasProcesarIncapacidad = $arIncapacidad->getCantidad();
                                    } else {
                                       $intDiasProcesarIncapacidad = $intDiasLaborados;
                                       $intDiasLaborados = 0; 
                                    }
                                    if($arIncapacidad->getIncapacidadTipoRel()->getIncapacidadGeneral() == 1) {
                                        if($arEmpleado->getVrSalario() <= $douVrSalarioMinimo) {
                                            $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDia;
                                        }
                                        if($arEmpleado->getVrSalario() > $douVrSalarioMinimo && $arEmpleado->getVrSalario() <= $douVrSalarioMinimo * 1.5) {
                                            $douVrDiaSalarioMinimo = $douVrSalarioMinimo / 30;
                                            $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDiaSalarioMinimo;
                                        }
                                        if($arEmpleado->getVrSalario() > ($douVrSalarioMinimo * 1.5)) {
                                            $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDia;
                                            $douPagoDetalle = ($douPagoDetalle * $arPagoConceptoIncapacidad->getPorPorcentaje())/100;
                                        }
                                    } else {
                                        $douPagoDetalle = $intDiasProcesarIncapacidad * $douVrDia;
                                        $douPagoDetalle = ($douPagoDetalle * $arPagoConceptoIncapacidad->getPorPorcentaje())/100;
                                    }
                                    $arPagoDetalle->setNumeroHoras($intDiasProcesarIncapacidad*8);
                                    $arPagoDetalle->setVrHora($douVrHora);
                                    $arPagoDetalle->setVrDia($douVrDia);                                    
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConceptoIncapacidad->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConceptoIncapacidad->getOperacion());
                                    $em->persist($arPagoDetalle);
                                    if($arPagoConceptoIncapacidad->getPrestacional() == 1) {
                                        $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;
                                    }
                                }

                                //Procesar Licencias
                                $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                                $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                foreach ($arLicencias as $arLicencia) {
                                    $intConceptoLicencia = 20;
                                    $arPagoConceptoLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                    $arPagoConceptoLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intConceptoLicencia);
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConceptoLicencia);
                                    $douPagoDetalle = 0;
                                    $intDiasProcesarLicencia = 0;
                                    if($arLicencia->getCantidad() <= $intDiasLaborados) {
                                        $intDiasLaborados = $intDiasLaborados - $arLicencia->getCantidad();
                                        $intDiasProcesarLicencia = $arLicencia->getCantidad();
                                    }
                                    $douPagoDetalle = $intDiasProcesarLicencia * $douVrDia;
                                    $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;                                    
                                    $arPagoDetalle->setNumeroHoras($intDiasProcesarLicencia * 8);                                                                                                                                                
                                    $em->persist($arPagoDetalle);
                                    //Actualizar cantidades licencia
                                    
                                }                                
                                
                                //Procesar los conceptos de pagos adicionales
                                $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'pagoAplicado' => 0, 'codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                foreach ($arPagosAdicionales as $arPagoAdicional) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoAdicional->getPagoConceptoRel());
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponePorcentaje() == 1) {
                                        $douVrHoraAdicional = ($douVrHora * $arPagoAdicional->getPagoConceptoRel()->getPorPorcentaje())/100;
                                        $douPagoDetalle = $douVrHoraAdicional * $arPagoAdicional->getCantidad();
                                        $arPagoDetalle->setVrHora($douVrHoraAdicional);
                                        $arPagoDetalle->setVrDia($douVrDia);
                                    }
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponeValor() == 1) {
                                        $douPagoDetalle = $arPagoAdicional->getValor();
                                        $arPagoDetalle->setVrDia($douVrDia);
                                    }
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $em->persist($arPagoDetalle);
                                    if($arPagoAdicional->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;
                                    }
                                    if($arPagoAdicional->getPermanente() == 0) {
                                        $arPagoAdicionalActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                        $arPagoAdicionalActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($arPagoAdicional->getCodigoPagoAdicionalPk());
                                        $arPagoAdicionalActualizar->setPagoAplicado(1);
                                        $arPagoAdicionalActualizar->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                        $em->persist($arPagoAdicionalActualizar);
                                    }
                                }

                                //Descuentos adicionales
                                $arDescuentosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();
                                $arDescuentosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuDescuentoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'descuentoAplicado' => 0, 'codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                                foreach ($arDescuentosAdicionales as $arDescuentoAdicional) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arDescuentoAdicional->getPagoConceptoRel());                                    
                                    $douPagoDetalle = $arDescuentoAdicional->getValor();
                                    $arPagoDetalle->setVrDia($douVrDia);
                                    
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arDescuentoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arDescuentoAdicional->getPagoConceptoRel()->getOperacion());
                                    $em->persist($arPagoDetalle);              
                                    if($arDescuentoAdicional->getPermanente() == 0) {
                                        $arDescuentoAdicionalActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();
                                        $arDescuentoAdicionalActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuDescuentoAdicional')->find($arDescuentoAdicional->getCodigoDescuentoAdicionalPk());
                                        $arDescuentoAdicionalActualizar->setDescuentoAplicado(1);
                                        $arDescuentoAdicionalActualizar->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                        $em->persist($arDescuentoAdicionalActualizar);
                                    }
                                }                                
                                
                                //Procesar creditos
                                $intConceptoCreditos = 14; //Configurar desde configuraciones
                                $arPagoConceptoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConceptoCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intConceptoCreditos);
                                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(), 'estadoPagado' => 0));
                                foreach ($arCreditos as $arCredito) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConceptoCredito);
                                    $douPagoDetalle = $arCredito->getVrCuota(); //Falta afectar credito
                                    $arPagoDetalle->setDetalle($arCredito->getCreditoTipoRel()->getNombre());
                                    $arPagoDetalle->setVrHora($douVrHora);
                                    $arPagoDetalle->setVrDia($douVrDia);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConceptoCredito->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConceptoCredito->getOperacion());
                                    $em->persist($arPagoDetalle);
                                }

                                $intPagoConceptoSalario = 1; //Se debe traer de la base de datos
                                $intPagoConceptoSalud = 3; //Se debe traer de la base de datos
                                $intPagoConceptoPension = 4; //Se debe traer de la base de datos
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();

                                //Liquidar salario
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalario);
                                $douPagoDetalle = $intDiasLaborados * $douVrDia;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setVrHora($douVrHora);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setNumeroHoras($intDiasLaborados * 8);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);
                                $douSalarioPrestacional = $douSalarioPrestacional + $douPagoDetalle;

                                //Liquidar salud
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalud);
                                $douPagoDetalle = ($douSalarioPrestacional * $arPagoConcepto->getPorPorcentaje())/100;;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());                                
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);

                                //Liquidar pension
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoPension);
                                $douPagoDetalle = ($douSalarioPrestacional * $arPagoConcepto->getPorPorcentaje())/100;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();                                
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setPorcentajeAplicado($arPagoConcepto->getPorPorcentaje());
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);

                                //Subsidio transporte
                                if($arEmpleado->getAuxilioTransporte() == 1) {
                                    $intPagoConceptoTransporte = 18; //Se debe traer de la base de datos
                                    $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoTransporte);
                                    $douVrDiaTransporte = 74000 / 30;
                                    $douPagoDetalle = $douVrDiaTransporte * $intDiasLaborados;
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);                                    
                                    $arPagoDetalle->setNumeroHoras($intDiasLaborados);
                                    $arPagoDetalle->setVrHora($douVrDiaTransporte/8);
                                    $arPagoDetalle->setVrDia($douVrDiaTransporte);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                    $em->persist($arPagoDetalle);
                                }
                            }
                            $arProgramacionPagoProcesar->setEstadoGenerado(1);
                            $arCentroCosto->setPagoAbierto(0);
                            $em->persist($arCentroCosto);
                            $em->persist($arProgramacionPagoProcesar);
                            $em->flush();
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->Liquidar($codigoProgramacionPago);
                            if($arProgramacionPagoProcesar->getNoGeneraPeriodo() == 0) {
                                $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk());                                
                            }
                            

                        } else {
                            $boolErrores = 1;
                        }
                    }
                    if($boolErrores == 1) {
                        $objMensaje->Mensaje("error", "Algunas programaciones no tienen la verificacion de novedades", $this);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
                if($form->get('BtnNoGenerar')->isClicked()) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setEstadoGenerado(1);
                        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                        $arCentroCosto->setPagoAbierto(0);
                        $em->persist($arProgramacionPagoProcesar);
                        $em->persist($arCentroCosto);
                        $em->flush();
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($arProgramacionPagoProcesar->getCodigoCentroCostoFk());
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
                if($form->get('BtnNovedadesVerificadas')->isClicked()) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setNovedadesVerificadas(1);
                        $em->persist($arProgramacionPagoProcesar);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
        }
        if($frmPagar->isValid()) {
            if($frmPagar->get('BtnPagar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setEstadoPagado(1);
                        $em->persist($arProgramacionPagoProcesar);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
            if($frmPagar->get('BtnLiquidar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                        foreach($arPagos as $arPago) {
                            $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->liquidar($arPago->getCodigoPagoPk());
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
            if($frmPagar->get('BtnAnular')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->Anular($codigoProgramacionPago);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
            if($frmPagar->get('BtnDeshacer')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->Deshacer($codigoProgramacionPago);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }            
        }

        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 0));
        $arProgramacionPagoPendientes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoPendientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 1, 'estadoPagado' => 0, 'estadoAnulado' => 0));
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPago.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'arProgramacionPagoPendientes' => $arProgramacionPagoPendientes,
            'form' => $form->createView(),
            'frmPagar' => $frmPagar->createView()
            ));
    }

    public function generarPagoResumenAction($codigoCentroCosto) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto, 'pagoAplicado' => 0));
        $arDescuentosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional();
        $arDescuentosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuDescuentoAdicional')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto, 'descuentoAplicado' => 0));        
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoCentroCostoFk' => $codigoCentroCosto));        
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL('', $codigoCentroCosto, 1, "", 0));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 50);                        
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
        }

        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPagoResumen.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'arDescuentosAdicionales' => $arDescuentosAdicionales,
                    'arIncapacidades' => $arIncapacidades,
                    'arLicencias' => $arLicencias,
                    'arEmpleados' => $arEmpleados
                    ));
    }

    public function devolverDiasLaborados($arEmpleadoProcesar, $arProgramacionPagoProcesar, $intDiasPeriodo) {
        $em = $this->getDoctrine()->getManager();
        $intDiasDevolver = $intDiasPeriodo;
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $arEmpleadoProcesar;
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $arProgramacionPagoProcesar;
        $dateFechaDesde =  "";
        $dateFechaHasta =  "";
        $fechaFinalizaContrato = $arEmpleado->getFechaFinalizaContrato();
        if($arEmpleado->getContratoIndefinido() == 1) {            
            $fecha = date_create(date('Y-m-d'));
            date_modify($fecha, '+365 day');
            $fechaFinalizaContrato = $fecha;
        }
        if($arEmpleado->getFechaContrato() <  $arProgramacionPago->getFechaDesde() == true) {
            $dateFechaDesde = $arProgramacionPago->getFechaDesde();
        } else {
            if($arEmpleado->getFechaContrato() > $arProgramacionPago->getFechaHasta() == true) {
                $intDiasDevolver = 0;
            } else {
                $dateFechaDesde = $arEmpleado->getFechaContrato();
            }
        }
        if($fechaFinalizaContrato >  $arProgramacionPago->getFechaHasta() == true) {
            $dateFechaHasta = $arProgramacionPago->getFechaHasta();
        } else {
            if($fechaFinalizaContrato < $arProgramacionPago->getFechaDesde() == true) {
                $intDiasDevolver = 0;
            } else {
                $dateFechaHasta = $fechaFinalizaContrato;
            }
        }
        if($dateFechaDesde != "" && $dateFechaHasta != "") {
            $intDias = $dateFechaDesde->diff($dateFechaHasta);
            $intDias = $intDias->format('%a');
            $intDiasDevolver = $intDias + 1;
        }
        if($intDiasDevolver > 0 && $arEmpleado->getTipoTiempoRel()->getFactor() != 0) {
            $intDiasDevolver = $intDiasDevolver / $arEmpleado->getTipoTiempoRel()->getFactor();
        }
        return $intDiasDevolver;
    }
}
