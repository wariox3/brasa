<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class PagosAdicionalesAgregarController extends Controller
{
    public function tiempoAction($codigoProgramacionPago) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                
        $codigoCentroCosto = $arProgramacionPago->getCodigoCentroCostoFk();
        $arPagosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagosConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('tipoAdicional' => 4));                
        $form = $this->createFormBuilder()
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoCentroCosto) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoCentroCostoFk = :centroCosto AND e.estadoActivo = 1')
                    ->setParameter('centroCosto', $codigoCentroCosto)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
    
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                if (isset($arrControles['TxtHoras'])) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {                        
                        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intCodigo);                                                                
                        if($arrControles['TxtHoras'][$intIndice] != "" && $arrControles['TxtHoras'][$intIndice] != 0) {
                            $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                            
                            $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                            $arPagoAdicional->setEmpleadoRel($form->get('empleadoRel')->getData());                            
                            $intHoras = $arrControles['TxtHoras'][$intIndice];
                            $arPagoAdicional->setCantidad($intHoras);
                            $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                            
                            $arPagoAdicional->setTipoAdicional(4);
                            $em->persist($arPagoAdicional);                                
                        }                        
                        $intIndice++;
                    }
                }                
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarTiempo.html.twig', array(            
            'arPagosConceptos' => $arPagosConceptos,
            'arProgramacionCentroCosto' => $arProgramacionPago,
            'form' => $form->createView()));
    }
    
    public function tiempoAdicionalAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagosConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('tipoAdicional' => 4));                
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', 'submit', array('label'  => 'Guardar y nuevo',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
            if ($arrControles['txtNumeroIdentificacion'] == ""){
                $objMensaje->Mensaje("error", "Digite el número de identificación", $this);
            } else {
                if ($arEmpleado == null){
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                } else {
                        if (isset($arrControles['TxtHoras'])) {
                            $intIndice = 0;
                            foreach ($arrControles['LblCodigo'] as $intCodigo) {                        
                                $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                                $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intCodigo);                                                                
                                if($arrControles['TxtHoras'][$intIndice] != "" && $arrControles['TxtHoras'][$intIndice] != 0) {
                                    $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                            
                                    $arPagoAdicional->setEmpleadoRel($arEmpleado);                            
                                    $intHoras = $arrControles['TxtHoras'][$intIndice];
                                    $arPagoAdicional->setCantidad($intHoras);
                                    $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                            
                                    $arPagoAdicional->setTipoAdicional(4);
                                    $arPagoAdicional->setpermanente(1);
                                    $em->persist($arPagoAdicional);                                
                                }                        
                                $intIndice++;
                            }
                        }                
                        $em->flush();
                        if($form->get('BtnGuardaryNuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_tiempoadicional'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista'));
                        }
                    
                } 
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarTiempoAdicional.html.twig', array(            
            'arPagosConceptos' => $arPagosConceptos,
            'form' => $form->createView()));
    }

    public function valorAction($codigoProgramacionPago, $tipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $codigoCentroCosto = $arProgramacionPago->getCodigoCentroCostoFk();
        $intTipoAdicional = $tipo;
        $form = $this->createFormBuilder()
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoCentroCosto) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoCentroCostoFk = :centroCosto AND e.estadoActivo = 1')
                    ->setParameter('centroCosto', $codigoCentroCosto)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))                            
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('TxtValor', 'number', array('required' => true))                             
            ->add('TxtDetalle', 'text', array('required' => false))                             
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
    
        if($form->isValid()) {            
            if($form->get('BtnAgregar')->isClicked()) {                
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $boolError = FALSE;
                    $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    $arEmpleado = $form->get('empleadoRel')->getData();
                    if($arPagoConcepto->getPrestacional() == 0 && $tipo == 1) {
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
                        $floSalario = $arEmpleado->getVrSalario();
                        $floVrDia = ($floSalario / 30);
                        $floSalarioEmpleado = $floVrDia * $arProgramacionPago->getDias();
                        $floBonificacionMaxima = $floSalarioEmpleado * ($arConfiguracion->getPorcentajeBonificacionNoPrestacional() / 100);
                        $floBonificacionNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->bonificacionNoPrestacional($arEmpleado->getCodigoEmpleadoPk(), $codigoProgramacionPago);                                
                        $floBonificacion = $form->get('TxtValor')->getData();
                        $floBonificacionTotal = $floBonificacionNoPrestacional+ $floBonificacion;
                        if($floBonificacionTotal > $floBonificacionMaxima) {
                            echo "La bonificacion NO PRESTACIONAL no puede superar: " . $floBonificacionMaxima . " ya tiene bonificaciones por:" . $floBonificacionNoPrestacional;
                            $boolError = TRUE;
                        }                                                                        
                    }
                    if($boolError == FALSE) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                     
                        $arPagoAdicional->setEmpleadoRel($arEmpleado);
                        $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);                                   
                        $arPagoAdicional->setValor($form->get('TxtValor')->getData());                    
                        $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                        $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                        $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
                        $arPagoAdicional->setTipoAdicional($tipo);
                        $em->persist($arPagoAdicional);                                                        
                        $em->flush();                        
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                        
                    }
                }                                                                                                                                                       
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarValor.html.twig', array(                        
            'form' => $form->createView()));
    }
    
    public function valorAdicionalAction($tipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $intTipoAdicional = $tipo;
        $form = $this->createFormBuilder()
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('TxtValor', 'number', array('required' => true))                             
            ->add('TxtDetalle', 'text', array('required' => false))                             
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', 'submit', array('label'  => 'Guardar y nuevo',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
            if ($arrControles['txtNumeroIdentificacion'] == ""){
                $objMensaje->Mensaje("error", "Digite el número de identificación", $this);
            }else {
                if ($arEmpleado == null){
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                } else {
                    
                        if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                            $boolError = FALSE;
                             $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                             $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                            if($arPagoConcepto->getPrestacional() == 0 && $tipo == 1) {
                                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
                                $floSalario = $arEmpleado->getVrSalario();
                                $floVrDia = ($floSalario / 30);
                                $floSalarioEmpleado = $floVrDia * $arProgramacionPago->getDias();
                                $floBonificacionMaxima = $floSalarioEmpleado * ($arConfiguracion->getPorcentajeBonificacionNoPrestacional() / 100);
                                $floBonificacionNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->bonificacionNoPrestacional($arEmpleado->getCodigoEmpleadoPk(), $codigoProgramacionPago);                                
                                $floBonificacion = $form->get('TxtValor')->getData();
                                $floBonificacionTotal = $floBonificacionNoPrestacional+ $floBonificacion;
                                if($floBonificacionTotal > $floBonificacionMaxima) {
                                    echo "La bonificacion NO PRESTACIONAL no puede superar: " . $floBonificacionMaxima . " ya tiene bonificaciones por:" . $floBonificacionNoPrestacional;
                                    $boolError = TRUE;
                                }                                                                        
                            }
                            if($boolError == FALSE) {
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                     
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setValor($form->get('TxtValor')->getData());                    
                                $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                                $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                                $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
                                $arPagoAdicional->setTipoAdicional($tipo);
                                $arPagoAdicional->setpermanente(1);
                                $em->persist($arPagoAdicional);                                                        
                                $em->flush();
                            }
                            if($form->get('BtnGuardaryNuevo')->isClicked()) {
                                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo) ));
                            } else {
                                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista'));
                            }
                        }                                                                                                                                                       
                    
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarValorAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo
            ));
    }
}
