<?php

namespace Brasa\TurnoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;

class UtilidadProgramacionesDescargaMasivaController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {                
                    
                        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                        $strRutaGeneral = $arConfiguracion->getRutaTemporal();
                        if(!file_exists($strRutaGeneral)) {
                            mkdir($strRutaGeneral, 0777);
                        }           
                        $arProgramaciones = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                        $strDql = $em->getRepository('BrasaTurnoBundle:RhuPago')->
                        //$arPagos = new \Brasa\TurnoBundle\Entity\RhuPago();
                        //$arPagos = $em->getRepository('BrasaTurnoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacion));
                        /*$strRuta = $strRutaGeneral . "ProgramacionesTurno" . $codigoProgramacion . "/";
                        if(!file_exists($strRuta)) {
                            mkdir($strRuta, 0777);
                        }
                        foreach ($arPagos as $arPago) {                                        
                            $objFormatoPago = new \Brasa\TurnoBundle\Formatos\FormatoPago();
                            $objFormatoPago->Generar($this, $arPago->getCodigoPagoPk(), $strRuta);
                        }            
                        $strRutaZip = $strRutaGeneral . 'ProgramacionesTurno' . $codigoProgramacion . '.zip';
                        $this->comprimir($strRuta, $strRutaZip);                                                
                        $dir = opendir($strRuta);                
                        while ($current = readdir($dir)){
                            if( $current != "." && $current != "..") {
                                unlink($strRuta . $current);
                            }                    
                        } 
                        rmdir($strRuta);

                        // Generate response
                        $response = new Response();

                        // Set headers
                        $response->headers->set('Cache-Control', 'private');
                        $response->headers->set('Content-type', 'application/zip');
                        $response->headers->set('Content-Transfer-Encoding', 'binary');                
                        $response->headers->set('Content-Disposition', 'attachment; filename="ComprobantesPago' . $codigoProgramacion . '.zip";');
                        //$response->headers->set('Content-length', '');        
                        $response->sendHeaders();
                        $response->setContent(readfile($strRutaZip));    
                        unlink($strRutaZip);    
                         * 
                         */                    
                                         
                   
            }            
        }                    
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:descargaMasiva.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                        
            ->add('fechaDesde', 'date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMMMdd'))                
            ->add('fechaHasta', 'date', array('data' => new \DateTime('now'), 'format' => 'yyyyMMMMdd'))                
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))    
            ->getForm();        
        return $form;
    }                 
    
    function comprimir($ruta, $zip_salida, $handle = false, $recursivo = false, $archivo = "") {

        /* Declara el handle del objeto */
        if (!$handle) {
            $handle = new \ZipArchive();
            if ($handle->open($zip_salida, ZipArchive::CREATE) === false) {
                return false; /* Imposible crear el archivo ZIP */
            }
        }

        /* Procesa directorio */
        if (is_dir($ruta)) {
            /* Aseguramos que sea un directorio sin carácteres corruptos */
            $ruta = dirname($ruta . '/arch.ext');
            $handle->addEmptyDir($ruta); /* Agrega el directorio comprimido */            
            $dir = opendir($ruta);            
            while ($current = readdir($dir)){
                if( $current != "." && $current != "..") {
                    $this->comprimir($ruta . "/" . $current, $zip_salida, $handle, true, $current); /* Comprime el subdirectorio o archivo */
                }
            }            
            //foreach (glob($ruta . '/*') as $url) { /* Procesa cada directorio o archivo dentro de el */
                //$this->comprimir($url, $zip_salida, $handle, true); /* Comprime el subdirectorio o archivo */
            //}

            /* Procesa archivo */
        } else {
            $handle->addFile($ruta, $archivo);
        }

        /* Finaliza el ZIP si no se está ejecutando una acción recursiva en progreso */
        if (!$recursivo) {
            $handle->close();
        }

        return true; /* Retorno satisfactorio */
    }

}
