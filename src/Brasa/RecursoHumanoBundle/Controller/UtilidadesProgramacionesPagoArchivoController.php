<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class UtilidadesProgramacionesPagoArchivoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
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
                        
                        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                        foreach ($arPagos AS $arPagos){
                            $arPagoExportar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExportar();
                            $arPagoExportar->setNumeroIdentificacion($arPagos->getEmpleadoRel()->getNumeroIdentificacion());
                            $arPagoExportar->setNombreCorto($arPagos->getEmpleadoRel()->getNombreCorto());
                            $arPagoExportar->setCuenta($arPagos->getEmpleadoRel()->getCuenta());
                            $arPagoExportar->setVrPago($arPagos->getVrNeto());
                            $arPagoExportar->setSoporte($arPagos->getCodigoProgramacionPagoFk());
                            $arPagoExportar->setTipo($arPagos->getCodigoPagoTipoFk());
                            $em->persist($arPagoExportar);
                            $em->flush();
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();  
            }
        }            
        $arProgramacionPagoArchivo = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/GenerarArchivosPagos/ProgramacionesPagoArchivo:lista.html.twig', array(
            'arProgramacionPagoArchivo' => $arProgramacionPagoArchivo,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaPagosDQL($codigoProgramacionPago));
        $arPagos = $paginator->paginate($query, $request->query->get('page', 1), 500);        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/GenerarArchivosPagos/ProgramacionesPagoArchivo:detalle.html.twig', array(
                'arPagos' => $arPagos,
                'arProgramacionPago' => $arProgramacionPago,
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
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                           
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))    
            ->getForm();        
        return $form;
    }      

    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQLArchivo(                    
                    $session->get('filtroCodigoCentroCosto')
                    );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);        
    }
    
}
