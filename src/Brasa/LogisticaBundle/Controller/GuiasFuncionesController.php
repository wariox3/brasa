<?php

namespace Brasa\LogisticaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuiasFuncionesController extends Controller
{
    public function entregarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arGuias = new \Brasa\LogisticaBundle\Entity\LogGuias();
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
                                $arGuia = new \Brasa\LogisticaBundle\Entity\LogGuias();
                                $arGuia = $em->getRepository('BrasaLogisticaBundle:LogGuias')->find($codigoGuia);
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

        $arGuias = $em->getRepository('BrasaLogisticaBundle:LogGuias')->findBy($arrCriterioGuias);
        return $this->render('BrasaLogisticaBundle:Guias/Funciones:entregar.html.twig', array(
            'arGuias' => $arGuias,
            'form' => $form->createView()));
    }

    public function descargarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arGuias = new \Brasa\LogisticaBundle\Entity\LogGuias();
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
                            $arGuia = new \Brasa\LogisticaBundle\Entity\LogGuias();
                            $arGuia = $em->getRepository('BrasaLogisticaBundle:LogGuias')->find($codigoGuia);
                            $arGuia->setEstadoDescargada(1);
                            $arGuia->setFechaDescargada(date_create(date('Y-m-d H:i:s')));
                            $em->persist($arGuia);
                            $em->flush();
                        }
                    }
                    break;
            }
        }

        $arGuias = $em->getRepository('BrasaLogisticaBundle:LogGuias')->findBy($arrCriterioGuias);
        return $this->render('BrasaLogisticaBundle:Guias/Funciones:descargar.html.twig', array(
            'arGuias' => $arGuias,
            'form' => $form->createView()));
    }
}
