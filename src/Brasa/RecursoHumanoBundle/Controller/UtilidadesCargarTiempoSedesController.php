<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UtilidadesCargarTiempoSedesController extends Controller
{
    public function cargarAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $form['attachment']->getData()->move("/var/www/cargas", "carga.txt");            
                $fp = fopen("/var/www/cargas/carga.txt", "r");
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $arrayDetalle = explode(";", $linea); 
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arrayDetalle[0]);
                        $arSede = $em->getRepository('BrasaRecursoHumanoBundle:RhuSede')->find($arrayDetalle[1]);
                        $arProgramacionPagoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->findOneBy(array('codigoProgramacionPagoFk' => $codigoProgramacionPago, 'codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));
                        $arProgramacionPagoDetalleSede = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede();                        
                        $arProgramacionPagoDetalleSede->setEmpleadoRel($arEmpleado);                        
                        $arProgramacionPagoDetalleSede->setSedeRel($arSede);                        
                        $arProgramacionPagoDetalleSede->setProgramacionPagoDetalleRel($arProgramacionPagoDetalle);
                        $arProgramacionPagoDetalleSede->setHorasPeriodo($arrayDetalle[2]);
                        $em->persist($arProgramacionPagoDetalleSede);
                    }
                }
                fclose($fp);                
                $em->flush();
                $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->generarProgramacionPagoDetallePorSede($codigoProgramacionPago);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }                                   
        }         
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Pago:cargarTiempoSedes.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
}
