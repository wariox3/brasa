<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UtilidadesPagosPagarController extends Controller
{
    public function pagarAction () {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');        
        $session = $this->getRequest()->getSession(); 
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')                                        
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "Todos",    
                'data' => ""
            );            
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));                                    
        }
        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                            
            ->add('fechaHasta', 'date', array('label'  => 'Hasta', 'data' => new \DateTime('now')))                                                        
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))                                                    
            ->add('BtnPagar', 'submit', array('label'  => 'Pagar',))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnDeshacer', 'submit', array('label'  => 'Des-hacer',))                
            ->getForm();
        $form->handleRequest($request);                
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnFiltrar')->isClicked()) {
                $controles = $request->request->get('form');
                $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
                $session->set('dqlProgramacionPago', $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaGenerarPagoDQL(
                    "", $form->get('fechaHasta')->getData()->format('Y-m-d'), $session->get('filtroCodigoCentroCosto') 
                    ));                 
            }
            if($form->get('BtnPagar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');                
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->pagarSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_pagar'));                
            }            
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->eliminar($codigoProgramacionPago);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_pagar'));
                }
            }            
            if($form->get('BtnDeshacer')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->deshacer($codigoProgramacionPago);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_pagar'));
                }
            }            
            //Esta opcion queda pendiente.
            /*if($form->get('BtnLiquidar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPagar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->generarPagoDetalleSede($codigoProgramacionPago);
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_utilidades_pagos_generar_pago'));
                }
            } 
             * 
             */           
        }    
        $arProgramacionPagoPendientes = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPagoPendientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->findBy(array('estadoGenerado' => 1, 'estadoPagado' => 0, 'estadoAnulado' => 0));
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:pagar.html.twig', array(            
            'arProgramacionPagoPendientes' => $arProgramacionPagoPendientes,
            'form' => $form->createView()
            ));
    }          
}
