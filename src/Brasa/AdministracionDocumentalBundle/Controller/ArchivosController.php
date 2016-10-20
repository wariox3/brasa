<?php

namespace Brasa\AdministracionDocumentalBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
class ArchivosController extends Controller
{
    
    /**
     * @Route("/ad/archivos/lista/{codigoDocumento}/{numero}", name="brs_ad_archivos_lista")
     */     
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
        
    /**
     * @Route("/ad/archivos/cargar/{codigoDocumento}/{numero}", name="brs_ad_archivos_cargar")
     */    
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
                    if ($objArchivo->getClientSize()){
                        $em->persist($arArchivo);
                        $em->flush();
                        $strDestino = $arDirectorio->getRutaPrincipal() . $arDirectorio->getNumero() . "/";
                        $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                        $form['attachment']->getData()->move($strDestino, $strArchivo);                    
                        return $this->redirect($this->generateUrl('brs_ad_archivos_lista', array('codigoDocumento' => $codigoDocumento, 'numero' => $numero)));
                    } else {
                        $objMensaje->Mensaje('error', "El archivo tiene un tamaño mayor al permitido", $this);
                    }    
                } else {
                    $objMensaje->Mensaje("error", "Solo se pueden cargar arhivos pdf", $this);
                }
            }                                   
        }         
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:cargar.html.twig', array(
            'form' => $form->createView()
            ));
    } 
    
    /**
     * @Route("/ad/archivos/descargar/{codigoArchivo}", name="brs_ad_archivos_descargar")
     */    
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
    
    /**
     * @Route("/ad/archivos/eliminar/{codigoArchivo}", name="brs_ad_archivos_eliminar")
     */    
    public function EliminarAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();
        $arArchivo = $em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->find($codigoArchivo);
        $em->remove($arArchivo);
        $em->flush();
        //$rutadirectorio = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        $strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero() . "/" . $codigoArchivo . "_" . $arArchivo->getNombre();
        unlink($strRuta);
        return $this->redirect($this->generateUrl('brs_ad_archivos_lista', array('codigoDocumento' => $arArchivo->getCodigoDocumentoFk(), 'numero' =>$arArchivo->getNumero())));      
    }
    
    /**
     * @Route("/ad/archivos/enviar/{codigoDocumento}/{numero}/{codigoArchivo}", name="brs_ad_archivos_enviar")
     */    
    public function enviarAction($codigoDocumento, $numero,$codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();  
        $arArchivo = new \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo();
        $arArchivo = $em->getRepository('BrasaAdministracionDocumentalBundle:AdArchivo')->find($codigoArchivo);
        $strRuta = $arArchivo->getDirectorioRel()->getRutaPrincipal() . $arArchivo->getDirectorioRel()->getNumero()  ."/" . $arArchivo->getCodigoArchivoPk() . "_" . $arArchivo->getNombre();
        
        $form = $this->createFormBuilder()
            ->add('asunto', 'text', array('required' => true))
            ->add('email', 'text', array('required' => true))
            ->add('mensaje', 'textarea', array('required' => true)) 
            ->add('BtnEnviar', 'submit', array('label'  => 'Enviar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnEnviar')->isClicked()) {
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);                                
                $strMail = $form->get('email')->getData();
                $strAsunto = $form->get('asunto')->getData();                  
                $strMensaje = $form->get('mensaje')->getData();               
                //$correo = $arPago->getEmpleadoRel()->getCorreo();
                $correoNomina = $arConfiguracion->getCorreoNomina();
                if($strMail) {
                    //$rutaArchivo = $ruta."Pago".$arPago->getCodigoPagoPk().".pdf";
                    $strMensaje = "Se adjunta comprobante de pago (sogaApp)";                
                    $message = \Swift_Message::newInstance()
                        ->setSubject($strAsunto)
                        ->setFrom($correoNomina, "SogaApp" )
                        ->setTo(strtolower($strMail))
                        ->setBody($strMensaje,'text/html')                            
                        ->attach(\Swift_Attachment::fromPath($strRuta));                
                    $this->get('mailer')->send($message);                                 
                } 
                echo "Mensaje enviado con exito al correo ".$strMail. " - ".$correoNomina;
                //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                                                                                                
            }
            
        }         
        return $this->render('BrasaAdministracionDocumentalBundle:Archivos:enviar.html.twig', array(
            'form' => $form->createView(),
            'codigoDocumento' => $codigoDocumento,
            'numero' => $numero,
            ));
              
    }
}
