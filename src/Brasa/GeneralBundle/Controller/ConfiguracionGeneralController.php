<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

/**
 * RhuConfiguracionGeneral controller.
 *
 */
class ConfiguracionGeneralController extends Controller
{
    public function configuracionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $formConfiguracionGeneral = $this->createFormBuilder() 
            ->add('nitEmpresa', 'text', array('data' => $arConfiguracionGeneral->getNitEmpresa(), 'required' => true))
            ->add('digitoVerificacion', 'number', array('data' => $arConfiguracionGeneral->getDigitoVerificacionEmpresa(), 'required' => true))
            ->add('nombreEmpresa', 'text', array('data' => $arConfiguracionGeneral->getNombreEmpresa(), 'required' => true))    
            ->add('sigla', 'text', array('data' => $arConfiguracionGeneral->getSigla(), 'required' => true))    
            ->add('telefonoEmpresa', 'text', array('data' => $arConfiguracionGeneral->getTelefonoEmpresa(), 'required' => true))
            ->add('direccionEmpresa', 'text', array('data' => $arConfiguracionGeneral->getDireccionEmpresa(), 'required' => true))    
            ->add('baseRetencionFuente', 'text', array('data' => $arConfiguracionGeneral->getBaseRetencionFuente(), 'required' => true))
            ->add('baseRetencionCree', 'text', array('data' => $arConfiguracionGeneral->getBaseRetencionCREE(), 'required' => true))    
            ->add('porcentajeRetencionFuente', 'text', array('data' => $arConfiguracionGeneral->getPorcentajeRetencionFuente(), 'required' => true))    
            ->add('porcentajeRetencionCree', 'text', array('data' => $arConfiguracionGeneral->getPorcentajeRetencionCREE(), 'required' => true))
            ->add('baseRetencionIvaVentas', 'text', array('data' => $arConfiguracionGeneral->getBaseRetencionIvaVentas(), 'required' => true))    
            ->add('porcentajeRetencionIvaVentas', 'text', array('data' => $arConfiguracionGeneral->getPorcentajeRetencionIvaVentas(), 'required' => true))
            ->add('fechaUltimoCierre', 'date', array('data' => $arConfiguracionGeneral->getFechaUltimoCierre(), 'required' => true))
            ->add('nitVentasMostrador', 'text', array('data' => $arConfiguracionGeneral->getNitVentasMostrador(), 'required' => true))    
            ->add('rutaTemporal', 'text', array('data' => $arConfiguracionGeneral->getRutaTemporal(), 'required' => true))    
            ->add('rutaAlmacenamiento', 'text', array('data' => $arConfiguracionGeneral->getRutaAlmacenamiento(), 'required' => true))    
            ->add('guardar', 'submit', array('label' => 'Actualizar'))            
            ->getForm();
        $formConfiguracionGeneral->handleRequest($request);
        if ($formConfiguracionGeneral->isValid()) {
            if($formConfiguracionGeneral->get('guardar')->isClicked()) {
                $controles = $request->request->get('form');                                    
                $NitEmpresa = $formConfiguracionGeneral->get('nitEmpresa')->getData();
                $NumeroDv = $controles['digitoVerificacion'];
                $NombreEmpresa = $controles['nombreEmpresa'];
                $Sigla = $controles['sigla'];
                $TelefonoEmpresa = $controles['telefonoEmpresa'];
                $DireccionEmpresa = $controles['direccionEmpresa'];
                $BaseRetencionFuente = $controles['baseRetencionFuente'];
                $BaseRetencionCree = $controles['baseRetencionCree'];
                $PorcentajeRetencionFuente = $controles['porcentajeRetencionFuente'];
                $PorcentajeRetencionCree = $controles['porcentajeRetencionCree'];
                $BaseRetencionIvaVentas = $controles['baseRetencionIvaVentas'];
                $PorcentajeRetencionIvaVentas = $controles['porcentajeRetencionIvaVentas'];
                $FechaUltimoCierre = $formConfiguracionGeneral->get('fechaUltimoCierre')->getData();
                $NitVentasMostrador = $controles['nitVentasMostrador'];
                $RutaTemporal = $controles['rutaTemporal'];
                $RutaAlmacenamiento = $controles['rutaAlmacenamiento'];
                // guardar la tarea en la base de datos
                $arConfiguracionGeneral->setNitEmpresa($NitEmpresa);
                $arConfiguracionGeneral->setDigitoVerificacionEmpresa($NumeroDv);
                $arConfiguracionGeneral->setNombreEmpresa($NombreEmpresa);
                $arConfiguracionGeneral->setSigla($Sigla);
                $arConfiguracionGeneral->setTelefonoEmpresa($TelefonoEmpresa);
                $arConfiguracionGeneral->setDireccionEmpresa($DireccionEmpresa);
                $arConfiguracionGeneral->setBaseRetencionFuente($BaseRetencionFuente);
                $arConfiguracionGeneral->setBaseRetencionCree($BaseRetencionCree);
                $arConfiguracionGeneral->setPorcentajeRetencionFuente($PorcentajeRetencionFuente);
                $arConfiguracionGeneral->setPorcentajeRetencionCREE($PorcentajeRetencionCree);
                $arConfiguracionGeneral->setBaseRetencionIvaVentas($BaseRetencionIvaVentas);
                $arConfiguracionGeneral->setPorcentajeRetencionIvaVentas($PorcentajeRetencionIvaVentas);
                $arConfiguracionGeneral->setFechaUltimoCierre($FechaUltimoCierre);
                $arConfiguracionGeneral->setNitVentasMostrador($NitVentasMostrador);
                $arConfiguracionGeneral->setRutaTemporal($RutaTemporal);
                $arConfiguracionGeneral->setRutaAlmacenamiento($RutaAlmacenamiento);
                $em->persist($arConfiguracionGeneral);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_gen_configuracion_general', array('codigoConfiguracionPk' => 1)));                
            }
        }
        return $this->render('BrasaGeneralBundle:ConfiguracionGeneral:Configuracion.html.twig', array(
            'formConfiguracionGeneral' => $formConfiguracionGeneral->createView(),
        ));
    }

    public function borrarBDAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        
        $form = $this->createFormBuilder()            
            ->add('BtnCargar', 'submit', array('label'  => 'Borrar'))
            ->getForm();
        $form->handleRequest($request);

        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                //$form['attachment']->getData()->move("/var/www/temporal", "carga.txt");                            
                //$fp = fopen("/var/www/temporal/carga.txt", "r");                
                $fp = fopen("../src/Brasa/GeneralBundle/Resources/sql/limpiarbd.txt", "r");                                
                $strSql = "";
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $strSql = $strSql . $linea;
                    }
                }
                fclose($fp);
                $em->getConnection()->executeQuery($strSql);
                echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";                
            }                                   
        }         
        return $this->render('BrasaGeneralBundle:ConfiguracionGeneral:borrarBD.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
}
