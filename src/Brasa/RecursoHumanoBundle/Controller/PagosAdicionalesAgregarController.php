<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class PagosAdicionalesAgregarController extends Controller
{
    //agregar tiempo suplementario desde la programacion de pago
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
            $arUsuario = $this->get('security.context')->getToken()->getUser();
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
                            $arPagoAdicional->setCodigoUsuario($arUsuario->getUserName());
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
    
    //editar tiempo suplementario desde la programacion de pago
    public function tiempoEditarAction($codigoProgramacionPago, $tipo, $codigoPagoAdicional) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        $codigoCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk();
        $codigoPagoConcepto = $arPagoAdicional->getCodigoPagoConceptoFk();
        $pagoConcepto = $arPagoAdicional->getPagoConceptoRel()->getNombre();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
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
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional,$codigoPagoConcepto,$pagoConcepto) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => $codigoPagoConcepto,
                'empty_value' => $pagoConcepto,
                ))
            ->add('TxtCantidad', 'number', array('required' => true, 'data' => $arPagoAdicional->getCantidad()))                             
            ->add('TxtDetalle', 'text', array('required' => false, 'data' => $arPagoAdicional->getDetalle()))
            //->add('aplicaDiaLaborado', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
            $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
            $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
            $arPagoAdicional->setCantidad($form->get('TxtCantidad')->getData());                    
            $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
            $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
            $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
            $arPagoAdicional->setTipoAdicional($tipo);
            $em->persist($arPagoAdicional);                                                        
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                        
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:editarTiempo.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo
            ));
    }
    
    //agregar tiempo suplementario desde movimientos y solo permanente
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
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
            if ($arrControles['form_txtNumeroIdentificacion'] == ""){
                $objMensaje->Mensaje("error", "Digite el número de identificación", $this);
            } else {
                if ($arEmpleado == null){
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                } else {
                    if ($arEmpleado->getCodigoContratoActivoFk() != null){
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                    }else {
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                    }
                    if (!$arContrato){
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato ", $this);
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
                                    $arPagoAdicional->setCodigoUsuario($arUsuario->getUserName());
                                    $em->persist($arPagoAdicional);                                
                                }                        
                                $intIndice++;
                            }
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
    
    //editar tiempo suplementario desde movimientos y solo permanente
    public function tiempoAdicionalEditarAction($tipo, $codigoPagoAdicional) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        $codigoCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk();
        $codigoPagoConcepto = $arPagoAdicional->getCodigoPagoConceptoFk();
        $pagoConcepto = $arPagoAdicional->getPagoConceptoRel()->getNombre();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
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
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional,$codigoPagoConcepto,$pagoConcepto) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => $codigoPagoConcepto,
                'empty_value' => $pagoConcepto,
                ))
            ->add('TxtCantidad', 'number', array('required' => true, 'data' => $arPagoAdicional->getCantidad()))                             
            ->add('TxtDetalle', 'text', array('required' => false, 'data' => $arPagoAdicional->getDetalle()))
            ->add('aplicaDiaLaborado', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', 'submit', array('label'  => 'Guardar y nuevo',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
           // $arrControles = $request->request->All();
            $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
            $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
            //$arPagoAdicional->setEmpleadoRel($arEmpleado);
            $arPagoAdicional->setCantidad($form->get('TxtCantidad')->getData());                    
            $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
            $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
            $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
            $arPagoAdicional->setTipoAdicional($tipo);
            $arPagoAdicional->setAplicaDiaLaborado($form->get('aplicaDiaLaborado')->getData());
            $em->persist($arPagoAdicional);                                                        
            $em->flush();
            if($form->get('BtnGuardaryNuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_tiempoadicional'));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista'));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:editarTiempoAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo
            ));
    }
    
    //agregar adicionales al pago desde la programacion de pago
    public function valorAction($codigoProgramacionPago, $tipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $codigoCentroCosto = $arProgramacionPago->getCodigoCentroCostoFk();
        $intTipoAdicional = $tipo;
        $form = $this->createFormBuilder()
            /*->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoCentroCosto) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoCentroCostoFk = :centroCosto AND e.estadoActivo = 1')
                    ->setParameter('centroCosto', $codigoCentroCosto)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true)) */                           
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
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {                
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $boolError = FALSE;
                    $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    $identificacion = $arrControles['form_TxtIdentificacion'];
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $identificacion));
                    //$arEmpleado = $form->get('empleadoRel')->getData();
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
                        $arPagoAdicional->setCodigoUsuario($arUsuario->getUserName());
                        $em->persist($arPagoAdicional);                                                        
                        $em->flush();                        
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                        
                    }
                }                                                                                                                                                       
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarValor.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $intTipoAdicional));
    }
    
    //editar adicionales al pago desde la programacion de pago
    public function valorEditarAction($codigoProgramacionPago, $tipo, $codigoPagoAdicional) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        $codigoEmpleado = $arPagoAdicional->getCodigoEmpleadoFk();
        $codigoPagoConcepto = $arPagoAdicional->getCodigoPagoConceptoFk();
        $pagoConcepto = $arPagoAdicional->getPagoConceptoRel()->getNombre();
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $codigoCentroCosto = $arProgramacionPago->getCodigoCentroCostoFk();
        $intTipoAdicional = $tipo;
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPagoAdicional->getCodigoEmpleadoFk());
        $form = $this->createFormBuilder()
            /*->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoEmpleado) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoEmpleadoPk = :empleado')
                    ->setParameter('empleado', $codigoEmpleado)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'disabled' => 'disabled',            
                'required' => true))*/                            
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional,$codigoPagoConcepto,$pagoConcepto) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => $codigoPagoConcepto,
                'empty_value' => $pagoConcepto,
                ))
                            
            ->add('TxtValor', 'number', array('required' => true, 'data' => $arPagoAdicional->getValor()))                             
            ->add('TxtDetalle', 'text', array('required' => false, 'data' => $arPagoAdicional->getDetalle()))                             
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
        /*$arrControles = $request->request->All();
        $identificacion = $arrControles['form_TxtIdentificacion'];
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $identificacion));*/
        if($form->isValid()) {            
            if($form->get('BtnAgregar')->isClicked()) {                
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $boolError = FALSE;
                    $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    //$arEmpleado = $form->get('empleadoRel')->getData();
                    //$arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPagoAdicional->getCodigoEmpleadoFk());                             
                    $arrControles = $request->request->All();
                    $identificacion = $arrControles['form_TxtIdentificacion'];
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $identificacion));
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
                        //$arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPagoAdicional->getCodigoEmpleadoFk());                             
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
            'form' => $form->createView(),
            'tipo' => $intTipoAdicional,
            'arEmpleado' => $arEmpleado));
    }
    
    //agregar adicionales al pago desde movimientos y solo permanente
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
            ->add('aplicarDiaLaborado', 'choice', array('choices' => array('0' => 'NO', '1' => 'SI')))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', 'submit', array('label'  => 'Guardar y nuevo',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
            if ($arrControles['form_txtNumeroIdentificacion'] == ""){
                $objMensaje->Mensaje("error", "Digite el número de identificación", $this);
            }else {
                if ($arEmpleado == null){
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                } else {
                    if ($arEmpleado->getCodigoContratoActivoFk() != null){
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                    }else {                       
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                    }
                    if ($arContrato == null){
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato", $this);
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
                                $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arEmpleado->getCodigoCentroCostoFk());        
                                $strPeriodoPago = $arCentroCosto->getPeriodoPagoRel()->getNombre();
                                if ($strPeriodoPago == "MENSUAL"){
                                    $intDias = 30;
                                }
                                if ($strPeriodoPago == "QUINCENAL"){
                                    $intDias = 15;
                                }
                                if ($strPeriodoPago == "CATORCENAL"){
                                    $intDias = 14;
                                }
                                if ($strPeriodoPago == "DECADAL"){
                                    $intDias = 10;
                                }
                                if ($strPeriodoPago == "SEMANAL"){
                                    $intDias = 7;
                                }
                                $floSalarioEmpleado = $floVrDia * $intDias;
                                $floBonificacionMaxima = $floSalarioEmpleado * ($arConfiguracion->getPorcentajeBonificacionNoPrestacional() / 100);
                                $floBonificacionNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->bonificacionNoPrestacional($arEmpleado->getCodigoEmpleadoPk(), 0);                                
                                $floBonificacion = $form->get('TxtValor')->getData();
                                $floBonificacionTotal = $floBonificacionNoPrestacional + $floBonificacion;
                                if($floBonificacionTotal > $floBonificacionMaxima) {
                                    //echo "La bonificacion NO PRESTACIONAL no puede superar: " . $floBonificacionMaxima . " ya tiene bonificaciones por:" . $floBonificacionNoPrestacional;
                                    $objMensaje->Mensaje("error", "La bonificacion NO PRESTACIONAL no puede superar: " . $floBonificacionMaxima . " ya tiene bonificaciones por:" . $floBonificacionNoPrestacional, $this);
                                    return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo) ));
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
                                $arPagoAdicional->setAplicaDiaLaborado($form->get('aplicarDiaLaborado')->getData());
                                $arPagoAdicional->setCodigoUsuario($arUsuario->getUserName());
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
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:agregarValorAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo
            ));
    }
    
    //editar adicionales al pago desde movimientos y solo permanente
    public function valorAdicionalEditarAction($tipo, $codigoPagoAdicional) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagoAdicional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->find($codigoPagoAdicional);
        if ($arPagoAdicional->getAplicaDiaLaborado() == 1){
            $intAplicaDiaLaborado = "SI";
        } else {
            $intAplicaDiaLaborado = "NO";
        }
        $codigoEmpleado = $arPagoAdicional->getCodigoEmpleadoFk();
        $codigoCentroCosto = $arPagoAdicional->getEmpleadoRel()->getCodigoCentroCostoFk();
        $codigoPagoConcepto = $arPagoAdicional->getCodigoPagoConceptoFk();
        $pagoConcepto = $arPagoAdicional->getPagoConceptoRel()->getNombre();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        //$arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $intTipoAdicional = $tipo;
        $aplicaDiaLaborado = $arPagoAdicional->getAplicaDiaLaborado();
        if ($aplicaDiaLaborado == false ){
            $aplicaDiaLaborado = 0;
        } else {
            $aplicaDiaLaborado = 1;
        }
        $form = $this->createFormBuilder() 
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoEmpleado) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoEmpleadoPk = :empleado')
                    ->setParameter('empleado', $codigoEmpleado)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))    
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional,$codigoPagoConcepto,$pagoConcepto) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => $codigoPagoConcepto,
                'empty_value' => $pagoConcepto,
                ))
            ->add('TxtValor', 'number', array('required' => true, 'data' => $arPagoAdicional->getValor()))                             
            ->add('TxtDetalle', 'text', array('required' => false, 'data' => $arPagoAdicional->getDetalle()))
            ->add('aplicarDiaLaborado', 'choice', array('choices' => array($aplicaDiaLaborado => $intAplicaDiaLaborado, '0' => 'NO', '1' => 'SI')))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->add('BtnGuardaryNuevo', 'submit', array('label'  => 'Guardar y nuevo',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrControles = $request->request->All();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arPagoAdicional->getCodigoEmpleadoFk());        
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $boolError = FALSE;
                     $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                     $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    if($arPagoConcepto->getPrestacional() == 0 && $tipo == 1) {
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);        
                        $floSalario = $arEmpleado->getVrSalario();
                        $floVrDia = ($floSalario / 30);
                        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);        
                        $strPeriodoPago = $arCentroCosto->getPeriodoPagoRel()->getNombre();
                        if ($strPeriodoPago == "MENSUAL"){
                            $intDias = 30;
                        }
                        if ($strPeriodoPago == "QUINCENAL"){
                            $intDias = 15;
                        }
                        if ($strPeriodoPago == "CATORCENAL"){
                            $intDias = 14;
                        }
                        if ($strPeriodoPago == "DECADAL"){
                            $intDias = 10;
                        }
                        if ($strPeriodoPago == "SEMANAL"){
                            $intDias = 7;
                        }
                        $floSalarioEmpleado = $floVrDia * $intDias;
                        $floBonificacionMaxima = $floSalarioEmpleado * ($arConfiguracion->getPorcentajeBonificacionNoPrestacional() / 100);
                        $floBonificacionNoPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->bonificacionNoPrestacional($arPagoAdicional->getCodigoEmpleadoFk(), 0);                                
                        $floBonificacion = $form->get('TxtValor')->getData();
                        $floBonificacionTotal = $floBonificacionNoPrestacional+ $floBonificacion;
                        if($floBonificacionTotal > $floBonificacionMaxima) {
                            echo "La bonificacion NO PRESTACIONAL no puede superar: " . $floBonificacionMaxima . " ya tiene bonificaciones por:" . $floBonificacionNoPrestacional;
                            $boolError = TRUE;
                        }                                                                        
                    }
                    if($boolError == FALSE) {
                        $arPagoAdicional->setEmpleadoRel($arEmpleado);
                        $arPagoAdicional->setValor($form->get('TxtValor')->getData());                    
                        $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                        $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                        $arPagoAdicional->setPrestacional($arPagoConcepto->getPrestacional());
                        $arPagoAdicional->setTipoAdicional($tipo);
                        $arPagoAdicional->setPermanente(1);
                        $arPagoAdicional->setAplicaDiaLaborado($form->get('aplicarDiaLaborado')->getData());
                        $em->persist($arPagoAdicional);                                                        
                        $em->flush();
                        if($form->get('BtnGuardaryNuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_valoradicional', array('tipo' => $tipo) ));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_lista'));
                        }
                    } 
                    
                }                                                                                                                                                                          
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/PagosAdicionales:editarAdicional.html.twig', array(                        
            'form' => $form->createView(),
            'tipo' => $tipo
            ));
    }
    
    
}
