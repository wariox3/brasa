<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad\ProgramacionPago;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;

class envioComprobanteCorreoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/utilidades/programacion/pago/comprobante/correo/{codigoProgramacionPago}", name="brs_rhu_utilidades_programacion_pago_comprobante_correo")
     */         
    public function listaAction($codigoProgramacionPago = "") {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 75)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnEnviar')->isClicked()) {
                $codigo = $form->get('numero')->getData();    
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $codigoFormato = $arConfiguracion->getCodigoFormatoPago();
                $ruta = 'C:\exportacion\\';
                $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();                
                $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigo));                
                foreach ($arPagos as $arPago) {
                    if($codigoFormato <= 1) {
                        $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\PagoMasivo1();
                        $objFormatoPago->Generar($this, "", $ruta, $arPago->getCodigoPagoPk(), "", "", "", "", "", "");
                    }   
                    /*if($codigoFormato == 2) {
                        $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\PagoMasivo2();
                        $objFormatoPago->Generar($this, $form->get('numero')->getData(), "", "", "", "", "", "", "", "");
                    }  
                     * 
                     */   
                    $strMensaje = "Se adjunta comprobante de pago";                
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Comprobante de pago ')
                        ->setFrom('jefedesarrollo@jgefectivo.com', "SogaApp" )
                        ->setTo(strtolower('maestradaz3@gmail.com'))
                        ->setBody($strMensaje,'text/html')                            
                        ->attach(\Swift_Attachment::fromPath($ruta."Pago".$arPago->getCodigoPagoPk().".pdf"));                
                    $this->get('mailer')->send($message);                    
                }                                                                                
            }            
        }                    
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/ProgramacionesPago:comprobanteCorreo.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {  
        $em = $this->getDoctrine()->getManager();  
        $session = $this->get('session');
               
        $form = $this->createFormBuilder()                  
            ->add('numero','text', array('required'  => false, 'data' => ""))
            ->add('BtnEnviar', 'submit', array('label'  => 'Enviar'))    
            ->getForm();        
        return $form;
    }                         

}
