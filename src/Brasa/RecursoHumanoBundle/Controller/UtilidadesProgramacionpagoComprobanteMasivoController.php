<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;

class UtilidadesProgramacionpagoComprobanteMasivoController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strRutaGeneral = $arConfiguracion->getRutaTemporal();
                if(!file_exists($strRutaGeneral)) {
                    mkdir($strRutaGeneral, 0777);
                }                
                $codigoProgramacionPago = 2522;                
                $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
                $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago));
                $strRuta = $strRutaGeneral . "CompropantesPago" . $codigoProgramacionPago . "/";
                if(!file_exists($strRuta)) {
                    mkdir($strRuta, 0777);
                }
                foreach ($arPagos as $arPago) {                                        
                    $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPago();
                    $objFormatoPago->Generar($this, $arPago->getCodigoPagoPk(), $strRuta);
                }            
                $strRutaZip = $strRutaGeneral . 'ComprobantesPago' . $codigoProgramacionPago . '.zip';
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
                $response->headers->set('Content-type', 'application/pdf');
                $response->headers->set('Content-Disposition', 'attachment; filename="ComprobantesPago' . $codigoProgramacionPago . '.zip";');
                $response->headers->set('Content-length', '');        
                $response->sendHeaders();
                $response->setContent(readfile($strRutaZip));    
                unlink($strRutaZip);    
            }            
        }            
        $arProgramacionPagoArchivo = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/ProgramacionesPago:comprobanteMasivo.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        

        $form = $this->createFormBuilder()                        
            ->add('numero','text', array('required'  => false))                
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))    
            ->getForm();        
        return $form;
    }      

    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQLArchivo(                    
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroCodigoPagoTipo'),
                    $session->get('filtroEstadoGenerado'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );  
    }            
    
    function comprimir($ruta, $zip_salida, $handle = false, $recursivo = false) {

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
            foreach (glob($ruta . '/*') as $url) { /* Procesa cada directorio o archivo dentro de el */
                $this->comprimir($url, $zip_salida, $handle, true); /* Comprime el subdirectorio o archivo */
            }

            /* Procesa archivo */
        } else {
            $handle->addFile($ruta);
        }

        /* Finaliza el ZIP si no se está ejecutando una acción recursiva en progreso */
        if (!$recursivo) {
            $handle->close();
        }

        return true; /* Retorno satisfactorio */
    }

}
