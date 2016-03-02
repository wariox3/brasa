<?php

namespace Brasa\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Brasa\SeguridadBundle\Form\Type\UserType;

class SegUsuariosController extends Controller
{
    var $strDqlLista = "";
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arUsuarios = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaSeguridadBundle:Usuarios:lista.html.twig', array(
            'form' => $form->createView(),
            'arUsuarios' => $arUsuarios
            ));
    }

    public function nuevoAction($codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        //El error es que se debe colocar el eslas entes de Brasa solo con eso toma la clase
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        if($codigoUsuario != 0) {
            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
        }
        $form = $this->createForm(new UserType(), $arUsuario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $form->getData();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
            if($codigoUsuario == 0) {
                $arUsuario->setPassword($password);
            }

            $em->persist($arUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_seg_admin_usuario_lista'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function detalleAction($codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnEliminarEspecial', 'submit', array('label' => 'Eliminar'))
            ->add('BtnEliminarDocumento', 'submit', array('label' => 'Eliminar'))    
            ->add('BtnActualizar', 'submit', array('label' => 'Actualizar'))    
            ->getForm();
        $form->handleRequest($request);
        
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
        if($form->isValid()) {
            if($form->get('BtnEliminarEspecial')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoEspecial');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioPermisoEspecialPk) {
                        $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                        $arUsuarioPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->find($codigoUsuarioPermisoEspecialPk);
                        $em->remove($arUsuarioPermisoEspecial);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if($form->get('BtnEliminarDocumento')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoDocumento');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPermisoDocumentoPk) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($codigoPermisoDocumentoPk);
                        $em->remove($arPermisoDocumento);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if($form->get('BtnActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                if (isset($arrControles['LblCodigoGuiaDocumento'])){
                    foreach ($arrControles['LblCodigoGuiaDocumento'] as $intCodigo) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($intCodigo);
                        if(isset($arrControles['ChkSeleccionarIngreso'.$intCodigo])) {
                            $arPermisoDocumento->setIngreso(1);
                        } else {
                            $arPermisoDocumento->setIngreso(0);
                        }
                        if(isset($arrControles['ChkSeleccionarNuevo'.$intCodigo])) {
                            $arPermisoDocumento->setNuevo(1);
                        } else {
                            $arPermisoDocumento->setNuevo(0);
                        }
                        if(isset($arrControles['ChkSeleccionarEditar'.$intCodigo])) {
                            $arPermisoDocumento->setEditar(1);
                        } else {
                            $arPermisoDocumento->setEditar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarEliminar'.$intCodigo])) {
                            $arPermisoDocumento->setEliminar(1);
                        } else {
                            $arPermisoDocumento->setEliminar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarAutorizar'.$intCodigo])) {
                            $arPermisoDocumento->setAutorizar(1);
                        } else {
                            $arPermisoDocumento->setAutorizar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarDesautorizar'.$intCodigo])) {
                            $arPermisoDocumento->setDesautorizar(1);
                        } else {
                            $arPermisoDocumento->setDesautorizar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarAprobar'.$intCodigo])) {
                            $arPermisoDocumento->setAprobar(1);
                        } else {
                            $arPermisoDocumento->setAprobar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarDesaprobar'.$intCodigo])) {
                            $arPermisoDocumento->setDesaprobar(1);
                        } else {
                            $arPermisoDocumento->setDesaprobar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarAnular'.$intCodigo])) {
                            $arPermisoDocumento->setAnular(1);
                        } else {
                            $arPermisoDocumento->setAnular(0);
                        }
                        if(isset($arrControles['ChkSeleccionarDesanular'.$intCodigo])) {
                            $arPermisoDocumento->setDesanular(1);
                        } else {
                            $arPermisoDocumento->setDesanular(0);
                        }
                        if(isset($arrControles['ChkSeleccionarImprimir'.$intCodigo])) {
                            $arPermisoDocumento->setImprimir(1);
                        } else {
                            $arPermisoDocumento->setImprimir(0);
                        }
                        $em->persist($arPermisoDocumento);     
                    }
                }
                $em->flush();
            }
        }
        $arPermisosDocumentos = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        $arPermisosDocumentos = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arPermisosEspeciales = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        return $this->render('BrasaSeguridadBundle:Usuarios:detalle.html.twig', array(
                    'arPermisosDocumentos' => $arPermisosDocumentos,
                    'arPermisosEspeciales' => $arPermisosEspeciales,
                    'arUsuario' => $arUsuario,
                    'form' => $form->createView()
                    ));
    }
    
    public function detalleNuevoPermisoEspecialAction($codigoUsuario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('permisoEspecialRel', 'entity', array(
                'class' => 'BrasaSeguridadBundle:SegPermisoEspecial',
                'property' => 'nombre',
                'data' => "",
                'required' => true,
            ))
            ->add('guardar', 'submit', array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
            $arPermisoEspecial = $form->get('permisoEspecialRel')->getData();
            $registros = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findOneBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoPermisoEspecialFk' => $arPermisoEspecial->getCodigoPermisoEspecialPk()));
            if ($registros == null){
                $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                $arUsuarioPermisoEspecial->setPermisoEspecialRel($form->get('permisoEspecialRel')->getData());
                $arUsuarioPermisoEspecial->setUsuarioRel($arUsuario);
                $arUsuarioPermisoEspecial->setPermitir(1);
                $em->persist($arUsuarioPermisoEspecial);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                    $objMensaje->Mensaje("error", "Ya existe el permiso para este usuario", $this);
            }
            
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoPermisoEspecial.html.twig', array(
            'form' => $form->createView()));
    }
    
    public function detalleNuevoPermisoDocumentoAction($codigoUsuario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('documentoRel', 'entity', array(
                'class' => 'BrasaSeguridadBundle:SegDocumento',
                'property' => 'nombre',
                'data' => "",
                'required' => true,))
            ->add('ingreso', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('nuevo', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('editar', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('eliminar', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('autorizar', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('desautorizar', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('aprobar', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('desaprobar', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('anular', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('desanular', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))    
            ->add('imprimir', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))    
            ->add('guardar', 'submit', array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
            $arPermisoDocumentos = $form->get('documentoRel')->getData();
            $registros = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findOneBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoDocumentoFk' => $arPermisoDocumentos->getCodigoDocumentoPk()));
            if ($registros == null){
                $arUsuarioPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                $arUsuarioPermisoDocumento->setDocumentoRel($form->get('documentoRel')->getData());
                $arUsuarioPermisoDocumento->setUsuarioRel($arUsuario);
                $em->persist($arUsuarioPermisoDocumento);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            } else {
                    $objMensaje->Mensaje("error", "Ya existe el permiso para este usuario", $this);
            }
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoPermisoDocumento.html.twig', array(
            'form' => $form->createView()));
    }

    public function recuperarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->formularioRecuperar();
        $form->handleRequest($request);
        if ($form->isValid()) {            
            return $this->redirect($this->generateUrl('login'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:recuperar.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function cambiarClaveAction($codigoUsuario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $formUsuario = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_seg_admin_usuario_cambiar_clave', array('codigoUsuario' => $codigoUsuario)))
            ->add('password', 'text')                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formUsuario->handleRequest($request);        
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);        
        
        if ($formUsuario->isValid()) {            
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($formUsuario->get('password')->getData(), $arUsuario->getSalt());
            $arUsuario->setPassword($password);   
            $em->persist($arUsuario);
            $em->flush();                        
            return $this->redirect($this->generateUrl('brs_seg_admin_usuario_lista'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:cambiarClave.html.twig', array(
            'arUsuario' => $arUsuario,
            'formUsuario' => $formUsuario->createView()
        ));
    }       
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaSeguridadBundle:User')->listaDql();
    }

    private function formularioRecuperar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('username', 'text', array('label'  => 'Numero', 'data' => ""))            
            ->add('BtnRecuperar', 'submit', array('label'  => 'Recuperar'))
            ->getForm();
        return $form;
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => ""))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

}