<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UtilidadesCargarAdicionalesPagoController extends Controller
{
    public function cargarAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                                                                        
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $form['attachment']->getData()->move("/var/www/temporal", "carga.txt");            
                $fp = fopen("/var/www/temporal/carga.txt", "r");
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $arrayDetalle = explode(";", $linea); 
                        if($arrayDetalle[0] != "") {
                            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrayDetalle[0]));                                                                
                            if(count($arEmpleado) > 0) {
                                //Recargo nocturno festivo compensado
                                $arPagoAdicionalSubtipo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo();
                                $arPagoAdicionalSubtipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalSubtipo')->find(3);                                                                
                                $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                                $arPagoAdicional->setPagoConceptoRel($arPagoAdicionalSubtipo->getPagoConceptoRel());
                                $arPagoAdicional->setPagoAdicionalTipoRel($arPagoAdicionalSubtipo->getPagoAdicionalTipoRel());
                                $arPagoAdicional->setPagoAdicionalSubtipoRel($arPagoAdicionalSubtipo);
                                $arPagoAdicional->setEmpleadoRel($arEmpleado);
                                $arPagoAdicional->setCentroCostoRel($arProgramacionPago->getCentroCostoRel());
                                $intHoras = $arrayDetalle[1];
                                $arPagoAdicional->setCantidad($intHoras);
                                $em->persist($arPagoAdicional);                                
                            }
                        }
                    }
                }
                fclose($fp);                
                $em->flush();                
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }                                   
        }         
        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPago:cargarAdicionalesPago.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
}
