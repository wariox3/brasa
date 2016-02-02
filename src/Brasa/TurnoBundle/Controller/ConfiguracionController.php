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
            ->add('edadMinimaEmpleado', 'number', array('data' => 0, 'required' => true))    
            ->add('guardar', 'submit', array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
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
