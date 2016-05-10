<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\DoctrineBundle\ConnectionFactory;
class DefaultController extends Controller
{           
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        // Chart
        $series = array(
            array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8))
        );

        $ob = new \Ob\HighchartsBundle\Highcharts\Highchart();
        $ob->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text'  => "Horizontal axis title"));
        $ob->yAxis->title(array('text'  => "Vertical axis title"));
        $ob->series($series);       
        $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        if($arConfiguracion->getInhabilitado() == 1) {           
            return $this->redirect($this->generateUrl('logout'));
        }
        return $this->render('BrasaGeneralBundle:Default:index.html.twig', array(
            'chart' => $ob
        ));
    }
    
    public function menuAction()
    {
        //$arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        //$arUsuario = $this->get('security.context')->getToken()->getUser();
        //$strUsuario = $arUsuario->getNombreCorto();
        //$destinatario = $this->contenedor->getParameter('contact_email');
        //$obj = new \Brasa\GeneralBundle\MisClases\CambiarBD();
        //$obj->setUpAppConnection($this);
        //\Brasa\GeneralBundle\MisClases\CambiarBD::setUpAppConnection();
        
        return $this->render('BrasaGeneralBundle:plantillas:menu.html.twig');
    }                  
    
}
