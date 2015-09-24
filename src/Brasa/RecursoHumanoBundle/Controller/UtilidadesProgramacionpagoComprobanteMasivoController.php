<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class UtilidadesProgramacionpagoComprobanteMasivoController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
            if($form->get('BtnGenerar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoProgramacionPago) {
                        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                        if ($arProgramacionPago->getArchivoExportadoBanco() == 0)    
                        {
                            $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                            $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago, 'archivoExportadoBanco' => 0));
                            foreach ($arPagos AS $arPago){
                                $arPagoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                                $arPagoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($arPago->getCodigoPagoPk()); 
                                $arPagoExportar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExportar();
                                $arPagoExportar->setNumeroIdentificacion($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                                $arPagoExportar->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                                $arPagoExportar->setCuenta($arPago->getEmpleadoRel()->getCuenta());
                                $arPagoExportar->setVrPago($arPago->getVrNeto());
                                $arPagoExportar->setSoporte($arPago->getCodigoProgramacionPagoFk());
                                $arPagoExportar->setTipo($arPago->getCodigoPagoTipoFk());
                                $arPagoExportar->setCentroCosto($arPago->getCentroCostoRel()->getNombre());
                                $arPagoExportar->setDetalle($arPago->getFechaDesde()->format('Y-m-d') . " Hasta " . $arPago->getFechaHasta()->format('Y-m-d'));
                                $arPagoActualizar->setArchivoExportadoBanco(1);                                
                                $em->persist($arPagoExportar);
                                $em->persist($arPagoActualizar);
                            }
                           $arProgramacionPago->setArchivoExportadoBanco(1) ;
                           $em->persist($arProgramacionPago);
                           $em->flush();
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();  
            }
            
        }            
        $arProgramacionPagoArchivo = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/ProgramacionesPago:comprobanteMasivo.html.twig', array(            
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $form = $this->createFormBuilder()
            ->add('BtnGenerar', 'submit', array('label'  => 'Exportar banco',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnGenerar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPago) {
                        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
                        $arPagoExportar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExportar();
                        $arPagoExportar->setNumeroIdentificacion($arPago->getEmpleadoRel()->getNumeroIdentificacion());
                        $arPagoExportar->setNombreCorto($arPago->getEmpleadoRel()->getNombreCorto());
                        $arPagoExportar->setCuenta($arPago->getEmpleadoRel()->getCuenta());
                        $arPagoExportar->setVrPago($arPago->getVrNeto());
                        $arPagoExportar->setSoporte($arPago->getCodigoProgramacionPagoFk());
                        $arPagoExportar->setTipo($arPago->getCodigoPagoTipoFk());
                        $arPagoExportar->setCentroCosto($arPago->getCentroCostoRel()->getNombre());
                        $arPagoExportar->setDetalle($arPago->getFechaDesde()->format('Y-m-d') . " Hasta " . $arPago->getFechaHasta()->format('Y-m-d'));
                        $em->persist($arPagoExportar);
                        $arPago->setArchivoExportadoBanco(1);
                        $em->persist($arPago);
                        
                   }
                   $em->flush();
                }    
            }
        }        
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaPagosDQL($codigoProgramacionPago));
        $arPagos = $paginator->paginate($query, $request->query->get('page', 1), 500);        
        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/PagoBanco/ProgramacionesPagoBanco:detalle.html.twig', array(
                'arPagos' => $arPagos,
                'arProgramacionPago' => $arProgramacionPago,
                'form' => $form->createView()));
    }            
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        

        $form = $this->createFormBuilder()                        
            ->add('numero','text')                
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))    
            ->getForm();        
        return $form;
    }      

    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQLArchivo(                    
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroCodigoPagoTipo'),
                    $session->get('filtroEstadoGenerado'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroCodigoPagoTipo', $controles['pagoTipoRel']);
        $session->set('filtroEstadoGenerado', $form->get('estadoGenerado')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
}
