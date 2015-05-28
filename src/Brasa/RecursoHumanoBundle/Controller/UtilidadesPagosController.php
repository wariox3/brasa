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
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('',0, ""));
            if($form->get('Generar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCentroCosto) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->generarPeriodoPago($codigoCentroCosto);
                    }
                }
            }
            if($form->get('BtnBuscar')->isClicked()) {
                $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL('', $form->get('ChkMostrarInactivos')->getData(), ""));
            }
        } else {
            $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->ListaDQL($form->get('TxtNombre')->getData(), 1, 0));
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
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $session = $this->getRequest()->getSession();
        
        $form = $this->createFormBuilder()
            ->add('fechaHasta', 'date', array('label'  => 'Hasta', 'data' => new \DateTime('now')))                                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))                
            ->add('BtnNovedadesVerificadas', 'submit', array('label'  => 'Novedades verificadas',))
            ->add('BtnGenerarEmpleados', 'submit', array('label'  => 'Generar empleados',))
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
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaGenerarPagoDQL());
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
                            $arProgramacionPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                            $arProgramacionPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findBy(array('codigoProgramacionPagoFk' => $arProgramacionPagoProcesar->getCodigoProgramacionPagoPk()));                            
                            foreach ($arProgramacionPagoDetalles as $arProgramacionPagoDetalle) {
                                
                                $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arPago->setEmpleadoRel($arProgramacionPagoDetalle->getEmpleadoRel());
                                $arPago->setCentroCostoRel($arCentroCosto);
                                $arPago->setFechaDesde($arProgramacionPagoProcesar->getFechaDesde());
                                $arPago->setFechaHasta($arProgramacionPagoProcesar->getFechaHasta());
                                $arPago->setVrSalario($arProgramacionPagoDetalle->getVrSalario());
                                $arPago->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                $em->persist($arPago);
                                /*if($arEmpleado->getNumeroIdentificacion() =='1056122069') {
                                    echo "Entro";
                                }*/

                                //Parametros generales                                                                
                                $intHorasLaboradas = $arProgramacionPagoDetalle->getHorasPeriodoReales();
                                $intDiasTransporte = $arProgramacionPagoDetalle->getDias();
                                $douVrDia = $arProgramacionPagoDetalle->getVrSalario() / 30;
                                $douVrHora = $douVrDia / 8;
                                $douVrSalarioMinimo = 644350; //Configurar desde configuraciones
                                $douVrHoraSalarioMinimo = ($douVrSalarioMinimo / 30) / 8;
                                $douDevengado = 0;
                                $douIngresoBaseCotizacion = 0;                                

                                //Procesar Incapacidades
                                $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                                $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));
                                foreach ($arIncapacidades as $arIncapacidad) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel());
                                    $douPagoDetalle = 0;
                                    $douIngresoBaseCotizacionIncapacidad = 0;                                    
                                    $intHorasLaboradas = $intHorasLaboradas - $arIncapacidad->getCantidad();
                                    $intHorasProcesarIncapacidad = $arIncapacidad->getCantidad();
                                    $intDiasTransporte = $intDiasTransporte - ($intHorasProcesarIncapacidad / $arProgramacionPagoDetalle->getFactorDia());

                                    
                                    if($arIncapacidad->getCodigoPagoAdicionalSubtipoFk() == 28) {
                                        if($arProgramacionPagoDetalle->getVrSalario() <= $douVrSalarioMinimo) {
                                            $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHora;
                                            $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                        }
                                        if($arProgramacionPagoDetalle->getVrSalario() > $douVrSalarioMinimo && $arProgramacionPagoDetalle->getVrSalario() <= $douVrSalarioMinimo * 1.5) {                                            
                                            $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHoraSalarioMinimo;
                                            $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                        }
                                        if($arProgramacionPagoDetalle->getVrSalario() > ($douVrSalarioMinimo * 1.5)) {
                                            $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHora;
                                            $douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPagoAdicionalSubtipoRel()->getPorcentaje())/100;
                                            $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                        }
                                    } else {
                                        $douPagoDetalle = $intHorasProcesarIncapacidad * $douVrHora;
                                        $douPagoDetalle = ($douPagoDetalle * $arIncapacidad->getPagoAdicionalSubtipoRel()->getPorcentaje())/100;
                                        $douIngresoBaseCotizacionIncapacidad = $intHorasProcesarIncapacidad * $douVrHora;
                                    }
                                    $arPagoDetalle->setNumeroHoras($intHorasProcesarIncapacidad);
                                    $arPagoDetalle->setVrHora($douVrHora);
                                    $arPagoDetalle->setVrDia($douVrDia);
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                    $em->persist($arPagoDetalle);
                                    if($arIncapacidad->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douDevengado = $douDevengado + $douPagoDetalle;
                                        $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douIngresoBaseCotizacionIncapacidad;
                                        $arPagoDetalle->setVrIngresoBaseCotizacion($douIngresoBaseCotizacionIncapacidad);
                                    }
                                }
                                

                                //Procesar Licencias
                                $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                                $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));
                                foreach ($arLicencias as $arLicencia) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel());
                                    $arPagoDetalle->setDetalle($arLicencia->getPagoAdicionalSubtipoRel()->getDetalle());

                                    $douPagoDetalle = 0;
                                    $intHorasProcesarLicencia = 0;
                                    if($arLicencia->getCantidad() <= $intHorasLaboradas) {
                                        $intHorasLaboradas = $intHorasLaboradas - $arLicencia->getCantidad();
                                        $intHorasProcesarLicencia = $arLicencia->getCantidad();
                                    }

                                    if($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douPagoDetalle = $intHorasProcesarLicencia * $douVrHora;                                                                                
                                        $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douPagoDetalle;                                        
                                        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);                                        
                                        if($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion() == 0) {
                                            $douPagoDetalle = 0;
                                        }
                                        $douDevengado = $douDevengado + $douPagoDetalle;                                                                                                                        
                                        $arPagoDetalle->setVrPago($douPagoDetalle);
                                        $arPagoDetalle->setOperacion($arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arLicencia->getPagoAdicionalSubtipoRel()->getPagoConceptoRel()->getOperacion());
                                    }
                                    $arPagoDetalle->setNumeroHoras($intHorasProcesarLicencia);
                                    $em->persist($arPagoDetalle);
                                    if($arLicencia->getAfectaTransporte() == 1){
                                        $intDiasLicenciaProcesar = intval($intHorasProcesarLicencia / $arProgramacionPagoDetalle->getFactorDia());
                                        $intDiasTransporte = $intDiasTransporte - $intDiasLicenciaProcesar;
                                    }
                                    //Actualizar cantidades licencia

                                }

                                //Procesar los conceptos de pagos adicionales
                                $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPagoProcesar->getCodigoCentroCostoFk(), 'pagoAplicado' => 0, 'codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk()));
                                foreach ($arPagosAdicionales as $arPagoAdicional) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoAdicional->getPagoConceptoRel());
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponePorcentaje() == 1) {
                                        $douVrHoraAdicional = ($douVrHora * $arPagoAdicional->getPagoAdicionalSubtipoRel()->getPorcentaje())/100;
                                        $douPagoDetalle = $douVrHoraAdicional * $arPagoAdicional->getCantidad();
                                        $arPagoDetalle->setVrHora($douVrHoraAdicional);
                                        $arPagoDetalle->setVrDia($douVrDia);
                                        $arPagoDetalle->setNumeroHoras($arPagoAdicional->getCantidad());
                                    }
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponeValor() == 1) {
                                        $douPagoDetalle = $arPagoAdicional->getValor();
                                        $arPagoDetalle->setVrDia($douVrDia);
                                    }
                                    $arPagoDetalle->setDetalle($arPagoAdicional->getDetalle());
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoAdicional->getPagoConceptoRel()->getOperacion());
                                    $arPagoDetalle->setDetalle($arPagoAdicional->getPagoAdicionalSubtipoRel()->getDetalle());
                                    $em->persist($arPagoDetalle);

                                    //Aumentar los dias y las horas ordinarias
                                    if($arPagoAdicional->getPagoConceptoRel()->getComponeSalario() == 1) {
                                        $intHorasProcesarAdicionales = $arPagoAdicional->getCantidad();
                                        $intHorasLaboradas = $intHorasLaboradas + $intHorasProcesarAdicionales;
                                        $intDiasAdicionales = intval($intHorasProcesarAdicionales / 8);
                                        $intDiasTransporte = $intDiasTransporte - $intDiasAdicionales;
                                    }
                                    if($arPagoAdicional->getPagoConceptoRel()->getPrestacional() == 1) {
                                        $douDevengado = $douDevengado + $douPagoDetalle;
                                        $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douPagoDetalle;
                                        $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);
                                    }
                                    if($arPagoAdicional->getPermanente() == 0) {
                                        $arPagoAdicionalActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                        $arPagoAdicionalActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($arPagoAdicional->getCodigoPagoAdicionalPk());
                                        $arPagoAdicionalActualizar->setPagoAplicado(1);
                                        $arPagoAdicionalActualizar->setProgramacionPagoRel($arProgramacionPagoProcesar);
                                        $em->persist($arPagoAdicionalActualizar);
                                    }
                                }

                                //Procesar creditos
                                $intConceptoCreditos = 14; //Configurar desde configuraciones
                                $arPagoConceptoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConceptoCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intConceptoCreditos);
                                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                                $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $arProgramacionPagoDetalle->getCodigoEmpleadoFk(), 'estadoPagado' => 0));
                                foreach ($arCreditos as $arCredito) {
                                    $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                    $arPagoDetalle->setPagoRel($arPago);
                                    $arPagoDetalle->setPagoConceptoRel($arPagoConceptoCredito);
                                    $douPagoDetalle = $arCredito->getVrCuota(); //Falta afectar credito
                                    $arPagoDetalle->setDetalle($arCredito->getCreditoTipoRel()->getNombre());
                                    $arPagoDetalle->setVrPago($douPagoDetalle);
                                    $arPagoDetalle->setOperacion($arPagoConceptoCredito->getOperacion());
                                    $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConceptoCredito->getOperacion());
                                    $em->persist($arPagoDetalle);
                                    $arPagoCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoCredito();
                                    //se guarda el pago en la tabla rhu_pago_credito
                                    $arPagoCredito->setcodigoCreditoFk($arCredito->getCodigoCreditoPk());
                                    $arPagoCredito->setvrCuota($douPagoDetalle);
                                    $arPagoCredito->setfechaPago(new \ DateTime("now"));
                                    $em->persist($arPagoCredito);
                                    //Actualizar el saldo del credito
                                    $nroACuotas = $arCredito->getNumeroCuotaActual();
                                    $arCredito->setNumeroCuotaActual($nroACuotas + 1);
                                    $credito =  $arCredito->getSaldo();
                                    $arCredito->setSaldo($credito - $douPagoDetalle);
                                    if ($arCredito->getSaldo() <= 0)
                                    {
                                       $arCredito->setEstadoPagado(1); 
                                    }        
                                    $em->persist($arCredito);
                                }

                                $intPagoConceptoSalario = 1; //Se debe traer de la base de datos
                                $intPagoConceptoSalud = 3; //Se debe traer de la base de datos
                                $intPagoConceptoPension = 4; //Se debe traer de la base de datos
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();

                                //Liquidar salario
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalario);
                                $douPagoDetalle = $intHorasLaboradas * $douVrHora;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setVrHora($douVrHora);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setNumeroHoras($intHorasLaboradas);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);
                                $douDevengado = $douDevengado + $douPagoDetalle;
                                $douIngresoBaseCotizacion = $douIngresoBaseCotizacion + $douPagoDetalle;
                                $arPagoDetalle->setVrIngresoBaseCotizacion($douPagoDetalle);

                                //Liquidar salud
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoSalud);
                                $douPagoDetalle = ($douIngresoBaseCotizacion * $arPagoConcepto->getPorPorcentaje())/100;;
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
                                $douPorcentaje = $arPagoConcepto->getPorPorcentaje();                                
                                if($douIngresoBaseCotizacion * $arCentroCosto->getPeriodoPagoRel()->getPeriodosMes() > $douVrSalarioMinimo * 4) {
                                    $douPorcentaje = 5; //Traer de la configuracion
                                }
                                $douPagoDetalle = ($douIngresoBaseCotizacion * $douPorcentaje)/100;
                                $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                $arPagoDetalle->setPagoRel($arPago);
                                $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                $arPagoDetalle->setPorcentajeAplicado($douPorcentaje);
                                $arPagoDetalle->setVrDia($douVrDia);
                                $arPagoDetalle->setVrPago($douPagoDetalle);
                                $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                $em->persist($arPagoDetalle);

                                //Subsidio transporte
                                if($intDiasTransporte > 0) {
                                    if($arProgramacionPagoDetalle->getEmpleadoRel()->getAuxilioTransporte() == 1) {
                                        $intPagoConceptoTransporte = 18; //Se debe traer de la base de datos
                                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intPagoConceptoTransporte);
                                        $douVrDiaTransporte = 74000 / 30;
                                        $douPagoDetalle = $douVrDiaTransporte * $intDiasTransporte;
                                        $arPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
                                        $arPagoDetalle->setPagoRel($arPago);
                                        $arPagoDetalle->setPagoConceptoRel($arPagoConcepto);
                                        $arPagoDetalle->setNumeroHoras($intDiasTransporte);
                                        $arPagoDetalle->setVrHora($douVrDiaTransporte / 8);
                                        $arPagoDetalle->setVrDia($douVrDiaTransporte);
                                        $arPagoDetalle->setVrPago($douPagoDetalle);
                                        $arPagoDetalle->setOperacion($arPagoConcepto->getOperacion());
                                        $arPagoDetalle->setVrPagoOperado($douPagoDetalle * $arPagoConcepto->getOperacion());
                                        $em->persist($arPagoDetalle);
                                    }
                                }
                            }
                            $arProgramacionPagoProcesar->setEstadoGenerado(1);
                            if($arProgramacionPagoProcesar->getNoGeneraPeriodo() == 0) {
                                $arCentroCosto->setPagoAbierto(0);
                            } else {
                                $arCentroCosto->setPagoAbierto(1);
                            }

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
                if($form->get('BtnGenerarEmpleados')->isClicked()) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPagoProcesar = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPagoProcesar = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);
                        $arProgramacionPagoProcesar->setEmpleadosGenerados(1);
                        $em->persist($arProgramacionPagoProcesar);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaGenerarPagoDQL("", $form->get('fechaHasta')->getData()->format('Y-m-d')));                    
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

        $arProgramacionPago = $paginator->paginate($query, $request->query->get('page', 1), 50);                                        
        $arProgramacionPagoPendientes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoPendientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 1, 'estadoPagado' => 0, 'estadoAnulado' => 0));
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPago.html.twig', array(
            'arProgramacionPago' => $arProgramacionPago,
            'arProgramacionPagoPendientes' => $arProgramacionPagoPendientes,
            'form' => $form->createView(),
            'frmPagar' => $frmPagar->createView()            
            ));
    }

    public function generarPagoResumenAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $form = $this->createFormBuilder()
            ->add('BtnGenerarEmpleados', 'submit', array('label'  => 'Generar empleados',))
            ->add('BtnActualizarEmpleados', 'submit', array('label'  => 'Actualizar',))
            ->add('BtnEliminarEmpleados', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerarEmpleados')->isClicked()) {                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarEmpleados($codigoProgramacionPago);        
                $arProgramacionPago->setEmpleadosGenerados(1);
                $em->persist($arProgramacionPago);
                $em->flush(); 
                return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago_resumen', array('codigoProgramacionPago' => $codigoProgramacionPago)));
            }
            
            if($form->get('BtnActualizarEmpleados')->isClicked()) {            
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigoDetalle'] as $intCodigo) {
                   if($arrControles['TxtHorasPeriodoReales'][$intIndice] != "") {
                       $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                       $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($intCodigo);
                       $arProgramacionPagoDetalle->setHorasPeriodoReales($arrControles['TxtHorasPeriodoReales'][$intIndice]);
                       $em->persist($arProgramacionPagoDetalle);
                   }
                   $intIndice++;
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago_resumen', array('codigoProgramacionPago' => $codigoProgramacionPago)));
            }
            if($form->get('BtnEliminarEmpleados')->isClicked()) {            
                $arrSeleccionados = $request->request->get('ChkSeleccionarEmpleado');
                foreach ($arrSeleccionados AS $codigo) {
                    $arProgramacionPagoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalle();
                    $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->find($codigo);
                    $em->remove($arProgramacionPagoDetalle);                                                        
                }                
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago_resumen', array('codigoProgramacionPago' => $codigoProgramacionPago)));
            }            
        }
        $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arProgramacionPago->getCodigoCentroCostoFk());
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoCentroCostoFk' => $arProgramacionPago->getCodigoCentroCostoFk(), 'pagoAplicado' => 0));
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoCentroCostoFk' => $arProgramacionPago->getCodigoCentroCostoFk()));
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoCentroCostoFk' => $arProgramacionPago->getCodigoCentroCostoFk()));
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->listaDQL($codigoProgramacionPago));
        $arProgramacionPagoDetalles = $paginator->paginate($query, $request->query->get('page', 1), 500);        
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
        }

        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:generarPagoResumen.html.twig', array(
                    'arCentroCosto' => $arCentroCosto,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'arIncapacidades' => $arIncapacidades,
                    'arLicencias' => $arLicencias,
                    'arProgramacionPagoDetalles' => $arProgramacionPagoDetalles,
                    'arProgramacionPago' => $arProgramacionPago,
                    'form' => $form->createView() 
                    ));
    }
    
}
