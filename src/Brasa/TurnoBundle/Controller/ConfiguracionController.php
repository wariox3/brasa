<?php

namespace Brasa\TurnoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

/**
 * TurConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller
{
    public function configuracionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arConsecutivo = new \Brasa\TurnoBundle\Entity\TurConsecutivo();
        $arConsecutivo = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->findAll();

        $formConfiguracion = $this->createFormBuilder()             
            ->add('informacionLegalFactura', 'textarea', array('data' => $arConfiguracion->getInformacionLegalFactura(), 'required' => false)) 
            ->add('informacionPagoFactura', 'textarea', array('data' => $arConfiguracion->getInformacionPagoFactura(), 'required' => false)) 
            ->add('informacionContactoFactura', 'textarea', array('data' => $arConfiguracion->getInformacionContactoFactura(), 'required' => false)) 
            ->add('informacionResolucionDianFactura', 'textarea', array('data' => $arConfiguracion->getInformacionResolucionDianFactura(), 'required' => false)) 
            ->add('informacionResolucionSupervigilanciaFactura', 'textarea', array('data' => $arConfiguracion->getInformacionResolucionSupervigilanciaFactura(), 'required' => false)) 
            ->add('guardar', 'submit', array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
            $controles = $request->request->get('form');
            $strInformacionLegalFactura = $controles['informacionLegalFactura'];
            $strInformacionPagoFactura = $controles['informacionPagoFactura'];
            $strInformacionContactoFactura = $controles['informacionContactoFactura'];
            $strInformacionResolucionDianFactura = $controles['informacionResolucionDianFactura'];
            $strInformacionResolucionSupervigilanciaFactura = $controles['informacionResolucionSupervigilanciaFactura'];
            $arConfiguracion->setInformacionLegalFactura($strInformacionLegalFactura);
            $arConfiguracion->setInformacionPagoFactura($strInformacionPagoFactura);
            $arConfiguracion->setInformacionContactoFactura($strInformacionContactoFactura);
            $arConfiguracion->setInformacionResolucionDianFactura($strInformacionResolucionDianFactura);
            $arConfiguracion->setInformacionResolucionSupervigilanciaFactura($strInformacionResolucionSupervigilanciaFactura);
            $arrControles = $request->request->All();
            $intIndiceConsecutivo = 0;
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arConsecutivo = new \Brasa\TurnoBundle\Entity\TurConsecutivo();
                $arConsecutivo = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->find($intCodigo);
                if(count($arConsecutivo) > 0) {                                            
                    $intConsecutivo = $arrControles['TxtConsecutivo'.$intCodigo];
                    $arConsecutivo->setConsecutivo($intConsecutivo);
                    $em->persist($arConsecutivo);
                }
                $intIndiceConsecutivo++;
            }
                    
            $em->persist($arConfiguracion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tur_configuracion', array('codigoConfiguracionPk' => 1)));
        }
        return $this->render('BrasaTurnoBundle:Configuracion:Configuracion.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
            'arConsecutivo' => $arConsecutivo
        ));
    }
    
}
