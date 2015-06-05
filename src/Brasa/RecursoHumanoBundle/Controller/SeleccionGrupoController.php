<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSeleccionGrupoType;


class SeleccionGrupoController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre',))
            ->add('estadoAbierto', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'))) 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))            
            ->getForm();
        $form->handleRequest($request); 
        
        if ($form->isValid()) {            
            if ($form->get('BtnEliminar')->isClicked()) {    
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoSeleccionGrupo) {
                        if($em->getRepository('')->devuelveNumeroSelecciones()) {
                            
                        }
                        $arSeleccionGrupo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccionGrupo);                     
                        $em->remove($arSeleccionGrupo);
                    }
                    $em->flush();
                }
            }
        }
        $arGrupos = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuSeleccionGrupo c";
        $query = $em->createQuery($dql);        
        $arGrupos = $paginator->paginate($query, $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:SeleccionGrupo:lista.html.twig', array(
            'arGrupos' => $arGrupos,
            'form' => $form->createView()
            ));     
    } 
    
    public function nuevoAction($codigoSeleccionGrupo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arGrupo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
        if($codigoSeleccionGrupo != 0) {
            $arGrupo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccionGrupo);
        }
        $form = $this->createForm(new RhuSeleccionGrupoType, $arGrupo);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arGrupo = $form->getData();
            $arGrupo->setFecha(new \DateTime('now'));
            $em->persist($arGrupo);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_selecciongrupo_nuevo', array('codigoSeleccionGrupo' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_selecciongrupo_lista'));
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:SeleccionGrupo:nuevo.html.twig', array(
            'arGrupo' => $arGrupo,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoSeleccionGrupo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');             
        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnAprobar', 'submit', array('label'  => 'Aprobar',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPago();
                $objFormatoPago->Generar($this, $codigoPago);
            }
            if($form->get('BtnReliquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->liquidar($codigoPago);
                return $this->redirect($this->generateUrl('brs_rhu_pagos_detalle', array('codigoPago' => $codigoPago)));
            }
        }        
        
        $dql   = "SELECT c FROM BrasaRecursoHumanoBundle:RhuSeleccion c where c.codigoSeleccionGrupoFk = $codigoSeleccionGrupo";
        $query = $em->createQuery($dql);        
        $arSeleccion = $query->getResult();
        $arGrupo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
        $arGrupo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($codigoSeleccionGrupo);
        return $this->render('BrasaRecursoHumanoBundle:SeleccionGrupo:detalle.html.twig', array(
                    'arSeleccion' => $arSeleccion,
                    'arGrupo' => $arGrupo,
                    'form' => $form->createView()
                    ));
    }
           
}
