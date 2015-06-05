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
        $mensaje=0;
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre',))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Nombre',))
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'APROBADO', '0' => 'NO APROBADO')))                            
            ->add('estadoAbierto', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'))) 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            
            ->getForm();
        $form->handleRequest($request); 
        
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked())
            {    
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $id) {
                    $arGrupos = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionGrupo();
                    $arGrupos = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionGrupo')->find($id);
                     
                        $em->remove($arGrupos);
                        $em->flush();
                }
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
        } else {
            $arGrupo->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuSeleccionGrupoType, $arGrupo);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arGrupo = $form->getData();
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
