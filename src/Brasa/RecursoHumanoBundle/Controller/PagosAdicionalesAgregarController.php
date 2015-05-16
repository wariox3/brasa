<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class PagosAdicionalesAgregarController extends Controller
{
    public function tiempoAction($codigoCentroCosto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arPagosAdicionalesSubtipos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo();
        $arPagosAdicionalesSubtipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalSubtipo')->findBy(array('codigoPagoAdicionalTipoFk' => 3));                
        $form = $this->createFormBuilder()
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoCentroCosto) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoCentroCostoFk = :centroCosto AND e.estadoActivo = 1')
                    ->setParameter('centroCosto', $codigoCentroCosto)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
    
        if($form->isValid()) {
            $arrControles = $request->request->All();
            if($form->get('BtnAgregar')->isClicked()) {
                if (isset($arrControles['TxtHoras'])) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        $arPagoAdicionalSubtipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo();
                        $arPagoAdicionalSubtipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalSubtipo')->find($intCodigo);                                                                
                        if($arrControles['TxtHoras'][$intIndice] != "" && $arrControles['TxtHoras'][$intIndice] != 0) {
                            $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                            //$arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                            $arPagoAdicional->setEmpleadoRel($form->get('empleadoRel')->getData());
                            $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                    
                            $intHoras = $arrControles['TxtHoras'][$intIndice];
                            $arPagoAdicional->setCantidad($intHoras);
                            $arPagoAdicional->setPagoConceptoRel($arPagoAdicionalSubtipo->getPagoConceptoRel());
                            $arPagoAdicional->setPagoAdicionalTipoRel($arPagoAdicionalSubtipo->getPagoAdicionalTipoRel());                                    
                            $arPagoAdicional->setPagoAdicionalSubtipoRel($arPagoAdicionalSubtipo);
                            $em->persist($arPagoAdicional);                                
                        }                        
                        $intIndice++;
                    }
                }                
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:agregarTiempo.html.twig', array(            
            'arPagosAdicionalesSubtipos' => $arPagosAdicionalesSubtipos,
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()));
    }

    public function valorAction($codigoCentroCosto, $tipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);        
        $arPagoAdicionalTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalTipo();
        $arPagoAdicionalTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalTipo')->find($tipo);        
        $intCodigoPagoAdicionalTipo = $tipo;
        $form = $this->createFormBuilder()
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleado',
                'query_builder' => function (EntityRepository $er) use($codigoCentroCosto) {
                    return $er->createQueryBuilder('e')
                    ->where('e.codigoCentroCostoFk = :centroCosto AND e.estadoActivo = 1')
                    ->setParameter('centroCosto', $codigoCentroCosto)
                    ->orderBy('e.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))                            
            ->add('subtipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoAdicionalSubtipo',
                'query_builder' => function (EntityRepository $er) use($intCodigoPagoAdicionalTipo) {
                    return $er->createQueryBuilder('t')
                    ->where('t.codigoPagoAdicionalTipoFk = :codigoPagoAdicionalTipo')
                    ->setParameter('codigoPagoAdicionalTipo', $intCodigoPagoAdicionalTipo)
                    ->orderBy('t.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('TxtValor', 'number', array('required' => true))                             
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
    
        if($form->isValid()) {            
            if($form->get('BtnAgregar')->isClicked()) {                
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $arPagoAdicionalSubtipo = $form->get('subtipoRel')->getData();
                    $arPagoConcepto = $arPagoAdicionalSubtipo->getPagoConceptoRel();
                    $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                        
                    $arPagoAdicional->setEmpleadoRel($form->get('empleadoRel')->getData());
                    $arPagoAdicional->setCentroCostoRel($arCentroCosto);                                                            
                    $arPagoAdicional->setValor($form->get('TxtValor')->getData());
                    $arPagoAdicional->setPagoAdicionalTipoRel($arPagoAdicionalTipo);                                    
                    $arPagoAdicional->setPagoAdicionalSubtipoRel($form->get('subtipoRel')->getData());
                    $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                    $em->persist($arPagoAdicional);                                                        
                    $em->flush();                        
                }                                                                                                                                       
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:agregarValor.html.twig', array(            
            'arCentroCosto' => $arCentroCosto,
            'arPagoAdicionalTipo' => $arPagoAdicionalTipo,
            'form' => $form->createView()));
    }    
}
