<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuiasFuncionesController extends Controller
{
    public function entregarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuias();
        $arrCriterioGuias = array('estadoEntregada' => 0, 'estadoDespachada' => 1);

        $form = $this->createFormBuilder()
            ->add('TxtCodigoGuia', 'text', array('label'  => 'Codigo'))
            ->add('TxtNumeroGuia', 'text')
            ->add('TxtCodigoTercero', 'text')
            ->add('TxtNombreTercero', 'text')
            ->add('TxtFechaEntrega', 'datetime', array('label'  => 'Fecha Entrega:'))
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:'))
            ->add('Buscar', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $arrDatos = $form->getData();
            if($arrDatos['TxtCodigoGuia']){
                $arrCriterioGuias = array('codigoGuiaPk' => $arrDatos['TxtCodigoGuia']);
            }
            if($arrDatos['TxtNumeroGuia']){
                $arrCriterioGuias = array('numeroGuia' => $arrDatos['TxtNumeroGuia']);
            }
        }

        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            switch ($request->request->get('OpSubmit')) {
                case "OpEntregar";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            if($arrDatos['TxtFechaEntrega']) {
                                $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                                $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                                $arGuia->setEstadoEntregada(1);
                                $arGuia->setFechaEntrega($arrDatos['TxtFechaEntrega']);
                                $em->persist($arGuia);
                                $em->flush();
                            }
                        }
                    }
                    break;
            }
        }

        $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuias')->findBy($arrCriterioGuias);
        return $this->render('BrasaTransporteBundle:Guias/Funciones:entregar.html.twig', array(
            'arGuias' => $arGuias,
            'form' => $form->createView()));
    }

    public function descargarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuias();
        $arrCriterioGuias = array('estadoEntregada' => 1, 'estadoDescargada' => 0);

        $form = $this->createFormBuilder()
            ->add('TxtCodigoGuia', 'text', array('label'  => 'Codigo'))
            ->add('TxtNumeroGuia', 'text')
            ->add('TxtCodigoTercero', 'text')
            ->add('TxtNombreTercero', 'text')
            ->add('TxtFechaDesde', 'date', array('widget' => 'single_text', 'label'  => 'Desde:'))
            ->add('TxtFechaHasta', 'date', array('widget' => 'single_text', 'label'  => 'Hasta:'))
            ->add('Buscar', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $arrDatos = $form->getData();
            if($arrDatos['TxtCodigoGuia']){
                $arrCriterioGuias = array('codigoGuiaPk' => $arrDatos['TxtCodigoGuia']);
            }
            if($arrDatos['TxtNumeroGuia']){
                $arrCriterioGuias = array('numeroGuia' => $arrDatos['TxtNumeroGuia']);
            }
        }

        if ($request->getMethod() == 'POST') {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            switch ($request->request->get('OpSubmit')) {
                case "OpDescargar";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                            $arGuia->setEstadoDescargada(1);
                            $arGuia->setFechaDescargada(date_create(date('Y-m-d H:i:s')));
                            $em->persist($arGuia);
                            $em->flush();
                        }
                    }
                    break;
            }
        }

        $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuias')->findBy($arrCriterioGuias);
        return $this->render('BrasaTransporteBundle:Guias/Funciones:descargar.html.twig', array(
            'arGuias' => $arGuias,
            'form' => $form->createView()));
    }
    
    public function redespacharAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuias();        

        $form = $this->createFormBuilder()
            ->add('TxtCodigoGuia', 'text', array('label'  => 'Codigo'))
            ->add('TxtNumeroGuia', 'text')
            ->add('TxtDespacho', 'text')
            ->add('Buscar', 'submit')
            ->getForm();

        $form->handleRequest($request);
        $arrCriterioGuias = array('estadoDespachada' => 1, 'estadoAnulada' => 0);
        if ($form->isValid()) {            
            $arrDatos = $form->getData();
            if($arrDatos['TxtCodigoGuia']){
                $arrCriterioGuias = array('codigoGuiaPk' => $arrDatos['TxtCodigoGuia']);
            }
            if($arrDatos['TxtNumeroGuia']){
                $arrCriterioGuias = array('numeroGuia' => $arrDatos['TxtNumeroGuia']);
            }
            if($arrDatos['TxtDespacho']){
                $arrCriterioGuias = array('codigoDespachoFk' => $arrDatos['TxtDespacho']);
            }            
            $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuias')->findBy($arrCriterioGuias);            
        }

        if ($request->getMethod() == 'POST') {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            switch ($request->request->get('OpSubmit')) {
                case "OpRedespachar";
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                            if($arGuia->getCodigoDespachoFk() != null) {
                                if($arGuia->getDespachoRel()->getEstadoGenerado() == 1) {
                                    $arGuia->setEstadoDespachada(0);
                                    $arGuia->setDespachoRel(null);                                
                                    $em->persist($arGuia);
                                    $em->flush();  
                                    
                                    $arRedespacho = new \Brasa\TransporteBundle\Entity\TteRedespachos();
                                    $arRedespacho->setFecha(date_create(date('Y-m-d H:i:s')));                                    
                                    $arRedespacho->setGuiaRel($arGuia);
                                    $em->persist($arRedespacho);
                                    $em->flush();                                     
                                }                                
                            }
                        }
                        $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuias')->findBy($arrCriterioGuias);            
                    }
                    break;
            }
            
        }
        
        return $this->render('BrasaTransporteBundle:Guias/Funciones:redespachar.html.twig', array(
            'arGuias' => $arGuias,
            'form' => $form->createView()));
    }    
}
