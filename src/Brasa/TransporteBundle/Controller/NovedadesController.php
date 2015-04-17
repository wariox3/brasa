<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NovedadesController extends Controller
{
    public function solucionarNovedadAction($codigoNovedad) {
        $em = $this->getDoctrine()->getManager();        
        $request = $this->getRequest();
        $arNovedad = new \Brasa\TransporteBundle\Entity\TteNovedad();
        $arNovedad = $em->getRepository('BrasaTransporteBundle:TteNovedad')->find($codigoNovedad);
        $arNovedad->setFechaSolucion(new \DateTime('tomorrow'));        
        $form = $this->createFormBuilder($arNovedad)
            ->add('solucion', 'textarea')
            ->add('fechaSolucion', 'datetime')
            ->add('guardar', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arNovedad = $form->getData();
            $arNovedad->setFechaRegistroSolucion(date_create(date('Y-m-d H:i:s')));
            $arNovedad->setEstadoSolucionada(1);
            $em->persist($arNovedad);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaTransporteBundle:Guias/Novedades:solucionarNovedad.html.twig', array(
            'form' => $form->createView()));
    }
}
