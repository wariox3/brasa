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
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 90)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
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
            ->add('codigoConceptoHorasDescansoFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasDescansoFk(), 'required' => false)) 
            ->add('codigoConceptoHorasDiurnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasNocturnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasNocturnasFk(), 'required' => false))                 
            ->add('codigoConceptoHorasFestivasDiurnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasFestivasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasFestivasNocturnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasFestivasNocturnasFk(), 'required' => false))                                 
            ->add('codigoConceptoHorasExtrasOrdinariasDiurnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasOrdinariasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasExtrasOrdinariasNocturnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasOrdinariasNocturnasFk(), 'required' => false))                                                 
            ->add('codigoConceptoHorasExtrasFestivasDiurnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasFestivasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasExtrasFestivasNocturnasFk', 'number', array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasFestivasNocturnasFk(), 'required' => false))                                                                 
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
            $arConfiguracion->setCodigoConceptoHorasDescansoFk($controles['codigoConceptoHorasDescansoFk']);
            $arConfiguracion->setCodigoConceptoHorasDiurnasFk($controles['codigoConceptoHorasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasNocturnasFk($controles['codigoConceptoHorasNocturnasFk']);
            $arConfiguracion->setCodigoConceptoHorasFestivasDiurnasFk($controles['codigoConceptoHorasFestivasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasFestivasNocturnasFk($controles['codigoConceptoHorasFestivasNocturnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasOrdinariasDiurnasFk($controles['codigoConceptoHorasExtrasOrdinariasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasOrdinariasNocturnasFk($controles['codigoConceptoHorasExtrasOrdinariasNocturnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasFestivasDiurnasFk($controles['codigoConceptoHorasExtrasFestivasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasFestivasNocturnasFk($controles['codigoConceptoHorasExtrasFestivasNocturnasFk']);
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
