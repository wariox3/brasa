<?php
namespace Brasa\TurnoBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class ActualizarHoraPedidoController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/proceso/actualizar/hora/pedido", name="brs_tur_proceso_actualizar_hora_pedido")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();                                        
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $mensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $anio = $form->get('anio')->getData();
            $mes = $form->get('mes')->getData();
            $fecha = date_create($anio . "/" . $mes . "/01");            
            $strUltimoDiaMes = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1)); 
            $dateFechaHasta = date_create($anio . "/" . $mes . "/" . $strUltimoDiaMes); 
            $dateFechaDesde = $fecha;            
            
            if ($form->get('BtnActualizar')->isClicked()) {                 
                $dql = $em->getRepository('BrasaTurnoBundle:TurPedido')->listaDql('','','','','',0,$dateFechaDesde->format('Y/m/d'),$dateFechaHasta->format('Y/m/d'));
                $query = $em->createQuery($dql);
                $arPedidos = $query->getResult();
                foreach ($arPedidos as $arPedido) {
                    $em->getRepository('BrasaTurnoBundle:TurPedido')->actualizarHorasProgramadas($arPedido->getCodigoPedidoPk());
                }
            }                           
        }
                
        return $this->render('BrasaTurnoBundle:Procesos/ActualizarFechaPedido:lista.html.twig', array(        
            'form' => $form->createView()));
    }           
    
    private function formularioLista() {  
        $fecha = new \DateTime('now');
        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');
        $form = $this->createFormBuilder()
            ->add('mes', 'choice', array(
                'choices'  => array(
                    '01' => 'Enero','02' => 'Febrero','03' => 'Marzo','04' => 'Abril','05' => 'Mayo','06' => 'Junio','07' => 'Julio',
                    '08' => 'Agosto','09' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre',
                ),
                'data' => $mes,
            ))   
            ->add('anio', 'choice', array(
                'choices'  => array(
                    $anio -1 => $anio -1, $anio => $anio, $anio +1 =>$anio+1
                ),
                'data' => $anio,
            ))                
            ->add('BtnActualizar', 'submit', array('label'  => 'Actualizar'))            
            ->getForm();
        return $form;
    }        
    
}