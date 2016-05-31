<?php

namespace Brasa\AdministracionDocumentalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
class ArchivosController extends Controller
{
    public function listaAction($codigoDocumento, $numero) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $query = $em->createQuery($em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->listaDQL($codigoDocumento, $numero));        
        $arArchivos = $paginator->paginate($query, $request->query->get('page', 1), 50);                               
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:lista.html.twig', array(
            'arArchivos' => $arArchivos,
            'codigoDocumento' => $codigoDocumento,
            'numero' => $numero,
            ));
    }  
    
    public function cargarAction($codigoDocumento, $numero) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa'); 
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('descripcion', 'text', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false)) 
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {                
                $objArchivo = $form['attachment']->getData();
                if($objArchivo->getClientOriginalExtension() == 'pdf') {
                    $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();                    
                    $arArchivo->setNombre($objArchivo->getClientOriginalName());
                    $arArchivo->setExtensionOriginal($objArchivo->getClientOriginalExtension());                
                    $arArchivo->setTamano($objArchivo->getClientSize());
                    $arArchivo->setTipo($objArchivo->getClientMimeType());
                    $arArchivo->setDocumentoRel($em->getRepository('BrasaAdministracionDocumentalBundle:AdDocumento')->find($codigoDocumento));               
                    $arArchivo->setNumero($numero);
                    $arArchivo->setDescripcion($form->get('descripcion')->getData());
                    $arArchivo->setComentarios($form->get('comentarios')->getData());
                    $arDirectorio = $em->getRepository('BrasaAdministracionDocumentalBundle:AdDirectorio')->devolverDirectorio();
                    $arArchivo->setDirectorioRel($arDirectorio);                    
                    $em->persist($arArchivo);
                    $em->flush();
                    $strDestino = $arDirectorio->getRutaPrincipal() . $arDirectorio->getNumero() . "/";
                    $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                    $form['attachment']->getData()->move($strDestino, $strArchivo);                    
                    return $this->redirect($this->generateUrl('brs_ad_archivos_lista', array('codigoDocumento' => $codigoDocumento, 'numero' => $numero)));
                } else {
                    $objMensaje->Mensaje("error", "Solo se pueden cargar arhivos pdf", $this);
                }
            }                                   
        }         
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:cargar.html.twig', array(
            'form' => $form->createView()
            ));
    } 
    
    public function descargarAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();
        $arArchivo = $em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->find($codigoArchivo);
        $strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        // Generate response
        $response = new Response();
        
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', $arArchivo->getTipo());
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getNombre() . '";');
        $response->headers->set('Content-length', $arArchivo->getTamano());        
        $response->sendHeaders();
        $response->setContent(readfile($strRuta));        
              
    }
    
    public function enviarAction($codigoDocumento, $numero,$codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa'); 
        $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();
        $arArchivo = $em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->find($codigoArchivo);
        $strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        
        $form = $this->createFormBuilder()
            ->add('asunto', 'text', array('required' => true))
            ->add('email', 'text', array('required' => true))
            ->add('mensaje', 'textarea', array('required' => true)) 
            ->add('BtnEnviar', 'submit', array('label'  => 'Enviar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnEnviar')->isClicked()) {                
                
               /* $para  = 'aranzatus21@gmail.com'; // atención a la coma
                
                $título = 'Prueba envio email soga';

                $mensaje = 'Hola';

                // Para enviar un correo HTML, debe establecerse la cabecera Content-type
                $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
                $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                // Cabeceras adicionales
                $cabeceras .= 'From: Recordatorio <analista.desarrollo@jgefectivo.com>' . "\r\n";

                // Enviarlo
                mail($para, $título, $mensaje, $cabeceras);*/
                
                $message = \Swift_Message::newInstance()
        ->setSubject('Hello Email')
        ->setFrom('analista.desarrollo@jgefectivo.com')
        ->setTo('desarrollo@jgefectivo.com')
        ->setBody('You should see me from the profiler!')
    ;

    $this->get('mailer')->send($message);
                
            }                                   
        }         
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:enviar.html.twig', array(
            'form' => $form->createView(),
            'codigoDocumento' => $codigoDocumento,
            'numero' => $numero,
            ));
              
    }
}
