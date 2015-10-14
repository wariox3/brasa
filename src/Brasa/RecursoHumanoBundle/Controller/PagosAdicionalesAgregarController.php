<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class PagosAdicionalesAgregarController extends Controller
{
    public function tiempoAction($codigoProgramacionPago) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                
        $codigoCentroCosto = $arProgramacionPago->getCodigoCentroCostoFk();
        $arPagosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
        $arPagosConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->findBy(array('tipoAdicional' => 4));                
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
                        $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($intCodigo);                                                                
                        if($arrControles['TxtHoras'][$intIndice] != "" && $arrControles['TxtHoras'][$intIndice] != 0) {
                            $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                            
                            $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                            $arPagoAdicional->setEmpleadoRel($form->get('empleadoRel')->getData());                            
                            $intHoras = $arrControles['TxtHoras'][$intIndice];
                            $arPagoAdicional->setCantidad($intHoras);
                            $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                            
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
            'arPagosConceptos' => $arPagosConceptos,
            'arProgramacionCentroCosto' => $arProgramacionPago,
            'form' => $form->createView()));
    }

    public function valorAction($codigoProgramacionPago, $tipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $codigoCentroCosto = $arProgramacionPago->getCodigoCentroCostoFk();
        $intTipoAdicional = $tipo;
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
            ->add('pagoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoConcepto',
                'query_builder' => function (EntityRepository $er) use($intTipoAdicional) {
                    return $er->createQueryBuilder('pc')
                    ->where('pc.tipoAdicional = :tipoAdicional')
                    ->setParameter('tipoAdicional', $intTipoAdicional)
                    ->orderBy('pc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('TxtValor', 'number', array('required' => true))                             
            ->add('TxtDetalle', 'text', array('required' => true))                             
            ->add('BtnAgregar', 'submit', array('label'  => 'Agregar',))
            ->getForm();
        $form->handleRequest($request);
    
        if($form->isValid()) {            
            if($form->get('BtnAgregar')->isClicked()) {                
                if($form->get('TxtValor')->getData() != "" && $form->get('TxtValor')->getData() != 0) {                    
                    $arPagoConcepto = $form->get('pagoConceptoRel')->getData();
                    //$arPagoConcepto = $arPagoConcepto->getPagoConceptoRel();
                    $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                     
                    $arPagoAdicional->setEmpleadoRel($form->get('empleadoRel')->getData());
                    $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);                                   
                    $arPagoAdicional->setValor($form->get('TxtValor')->getData());                    
                    $arPagoAdicional->setDetalle($form->get('TxtDetalle')->getData());                    
                    $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);                    
                    $em->persist($arPagoAdicional);                                                        
                    $em->flush();                        
                }                                                                                                                                       
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:PagosAdicionales:agregarValor.html.twig', array(                        
            'form' => $form->createView()));
    }    
}
