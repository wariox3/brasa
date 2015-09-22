<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoTipoType;

/**
 * RhuContratoTipo controller.
 *
 */
class BaseContratoTipoController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $this->lista();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoContratoTipo) {
                    $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
                    $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($codigoContratoTipo);
                    $em->remove($arContratoTipo);
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_contratos_tipo_lista'));
            }                        
        }
        $arContratosTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoTipo:listar.html.twig', array(
                    'arContratosTipos' => $arContratosTipos,
                    'form'=> $form->createView()
        ));
    }
    
    public function nuevoAction($codigoContratoTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        if ($codigoContratoTipo != 0) {
            $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($codigoContratoTipo);
        }    
        $form = $this->createForm(new RhuContratoTipoType(), $arContratoTipo);
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arContratoTipo = $form->getData();
            $em->persist($arContratoTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_contratos_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->listaDql();         
    }
    
}
