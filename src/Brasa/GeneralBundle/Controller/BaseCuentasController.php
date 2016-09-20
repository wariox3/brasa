<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenCuentaType;



class BaseCuentasController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 103, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoBanco) {
                    $arBanco = new \Brasa\GeneralBundle\Entity\GenBanco();
                    $arBanco = $em->getRepository('BrasaGeneralBundle:GenBanco')->find($codigoBanco);
                    $em->remove($arBanco);
                    $em->flush();
                }
            }            
            
        }
        $arCuentas = new \Brasa\GeneralBundle\Entity\GenCuenta();
        $query = $em->getRepository('BrasaGeneralBundle:GenCuenta')->findAll();
        $arCuentas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),50);

        return $this->render('BrasaGeneralBundle:Base/Cuentas:lista.html.twig', array(
                    'arCuentas' => $arCuentas,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoCuenta) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCuenta = new \Brasa\GeneralBundle\Entity\GenCuenta();
        if ($codigoCuenta != 0) {
            $arCuenta = $em->getRepository('BrasaGeneralBundle:GenCuenta')->find($codigoCuenta);
        }    
        $form = $this->createForm(new GenCuentaType(), $arCuenta);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arCuenta = $form->getData();
            $em->persist($arCuenta);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_gen_base_cuentas'));
        }
        return $this->render('BrasaGeneralBundle:Base/Cuentas:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
        
}
