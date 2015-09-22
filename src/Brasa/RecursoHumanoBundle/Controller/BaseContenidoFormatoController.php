<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContenidoFormatoType;

/**
 * RhuContenidoFormato controller.
 *
 */
class BaseContenidoFormatoController extends Controller
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
                foreach ($arrSeleccionados AS $codigoContenidoFormato) {
                    $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
                    $arContenidoFormato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($codigoContenidoFormato);
                    $em->remove($arContenidoFormato);
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_contenido_formato_lista'));
            }                        
        }
        $arContenidoFormatos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Base/ContenidoFormato:listar.html.twig', array(
                    'arContenidoFormatos' => $arContenidoFormatos,
                    'form'=> $form->createView()
        ));
    }
    
    public function nuevoAction($codigoContenidoFormato) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arContenidoFormato = new \Brasa\RecursoHumanoBundle\Entity\RhuContenidoFormato();
        if ($codigoContenidoFormato != 0) {
            $arContenidoFormato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->find($codigoContenidoFormato);
        }    
        $form = $this->createForm(new RhuContenidoFormatoType(), $arContenidoFormato);
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arContenidoFormato = $form->getData();
            $em->persist($arContenidoFormato);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_contenido_formato_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ContenidoFormato:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContenidoFormato')->listaDql();         
    }
    
}
