<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenDirectorioType;
use Symfony\Component\HttpFoundation\Response;

class DirectorioController extends Controller
{
    public function listaAction($codigoDirectorioPadre = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnEliminarDirectorio', 'submit', array('label'  => 'Eliminar directorio'))
            ->add('BtnEliminarArchivo', 'submit', array('label'  => 'Eliminar archivo'))
            ->getForm(); 
        $form->handleRequest($request);
        $arDirectorios = new \Brasa\GeneralBundle\Entity\GenDirectorio();
        if($form->isValid()) {
            if($form->get('BtnEliminarDirectorio')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarDirectorio');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDirectorio) {
                        $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
                        $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorio);
                        $arDirectorioArchivo = $em->getRepository('BrasaGeneralBundle:GenArchivo')->findBy(array('codigoDirectorioFk' => $codigoDirectorio));
                        if (count($arDirectorioArchivo) == 0){
                            $em->remove($arDirectorio);
                            $em->flush();
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_gen_utilidad_gestorarchivo', array('codigoDirectorioPadre' => $codigoDirectorioPadre)));
                }
            }
            if($form->get('BtnEliminarArchivo')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarArchivo');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoArchivo) {
                        $arArchivo = new \Brasa\GeneralBundle\Entity\GenArchivo();
                        $arArchivo = $em->getRepository('BrasaGeneralBundle:GenArchivo')->find($codigoArchivo);
                        $em->remove($arArchivo);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_gen_utilidad_gestorarchivo', array('codigoDirectorioPadre' => $codigoDirectorioPadre)));
                }
            }
        }
        
        $queryDirectorios = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->findBy(array('codigoDirectorioPadre' => $codigoDirectorioPadre));
        $arDirectorios = $paginator->paginate($queryDirectorios, $this->get('request')->query->get('page', 1),500);
        if ($codigoDirectorioPadre == 0){
            $codigo = null;
        }else{
            $codigo = $codigoDirectorioPadre;
        }
        $queryArchivos = $em->getRepository('BrasaGeneralBundle:GenArchivo')->findBy(array('codigoDirectorioFk' => $codigo));
        $arArchivos = $paginator->paginate($queryArchivos, $this->get('request')->query->get('page', 1),500);
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:lista.html.twig', array(
                    'arDirectorios' => $arDirectorios,
                    'codigoDirectorioPadre' => $codigoDirectorioPadre,
                    'arArchivos' => $arArchivos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoDirectorioAction($codigoDirectorio,$codigoDirectorioPadre) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
        if ($codigoDirectorio != 0)
        {
            $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorio);
        }    
        $form = $this->createForm(new GenDirectorioType(), $arDirectorio);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arDirectorio = $form->getData();
            $ruta = $arDirectorio->getNombre()."/";
            $arDirectorio->setRuta(strtolower($ruta));
            $arDirectorio->setCodigoDirectorioPadre($codigoDirectorioPadre);
            $em->persist($arDirectorio);
            $em->flush();
            //return $this->redirect($this->generateUrl('brs_gen_utilidad_gestorarchivo', array('codigoDirectorioPadre' => $codigoDirectorioPadre)));
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:nuevo.html.twig', array(
            'form' => $form->createView(),
            'codigoDirectorioPadre' => $codigoDirectorioPadre,
        ));
    }
    
    public function cargarArchivoAction($codigoDirectorioPadre) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa'); 
        $form = $this->createFormBuilder()
            ->add('attachment', 'file') 
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {                
                $objArchivo = $form['attachment']->getData();
                $arDirectorio = new \Brasa\GeneralBundle\Entity\GenDirectorio();
                $arDirectorio = $em->getRepository('BrasaGeneralBundle:GenDirectorio')->find($codigoDirectorioPadre);
                $arArchivo = new \Brasa\GeneralBundle\Entity\GenArchivo();                    
                $arArchivo->setNombre($objArchivo->getClientOriginalName());
                $arArchivo->setArchivo($objArchivo->getClientMimeType());                               
                $arArchivo->setDirectorioRel($arDirectorio);               
                                   
                $em->persist($arArchivo);
                $em->flush();
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                if ($arDirectorio == null){
                    $strDestino = $arConfiguracion->getRutaDirectorio();
                }else{
                    $strDestino = $arConfiguracion->getRutaDirectorio() . $arDirectorio->getRuta();
                }
                $strArchivo = $arArchivo->getCodigoArchivoPk() . "_" . $objArchivo->getClientOriginalName();
                $form['attachment']->getData()->move($strDestino, $strArchivo);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                
            }                                   
        }         
        return $this->render('BrasaGeneralBundle:Utilidades/Directorio:cargar.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
    public function descargarArchivoAction($codigoArchivo) {
        $em = $this->getDoctrine()->getManager();
        $arArchivo = new \Brasa\GeneralBundle\Entity\GenArchivo();
        $arArchivo = $em->getRepository('BrasaGeneralBundle:GenArchivo')->find($codigoArchivo);
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $strRuta = $arConfiguracion->getRutaDirectorio() . $arArchivo->getDirectorioRel()->getRuta(). $codigoArchivo. "_" .$arArchivo->getNombre();
        // Generate response
        $response = new Response();
        
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', $arArchivo->getArchivo());
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $arArchivo->getNombre() . '";');
        //$response->headers->set('Content-length', $arArchivo->getTamano());        
        $response->sendHeaders();
        $response->setContent(readfile($strRuta));        
              
    }   
}
