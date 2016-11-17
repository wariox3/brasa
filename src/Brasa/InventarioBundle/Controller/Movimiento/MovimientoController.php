<?php
namespace Brasa\InventarioBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class MovimientoController extends Controller
{
    var $strListaDql = "";    

    /**
     * @Route("/inv/movimiento/movimiento/ingreso", name="brs_inv_movimiento_movimiento_ingreso")
     */    
    public function ingresoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {                        
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaInventarioBundle:TurFactura')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura'));  

                /*set_time_limit(0);
                ini_set("memory_limit", -1);
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $arFacturas = new \Brasa\InventarioBundle\Entity\TurFactura();
                $arFacturas = $em->getRepository('BrasaInventarioBundle:TurFactura')->findAll();
                foreach ($arFacturas as $arFactura) {                    
                    $arFacturaAct = new \Brasa\InventarioBundle\Entity\TurFactura();        
                    $arFacturaAct = $em->getRepository('BrasaInventarioBundle:TurFactura')->find($arFactura->getCodigoFacturaPk());                     
                    
                    /*$porRetencionFuente = $arFactura->getFacturaServicioRel()->getPorRetencionFuente();
                    $porBaseRetencionFuente = $arFactura->getFacturaServicioRel()->getPorBaseRetencionFuente();
                    $baseRetencionFuente = ($arFacturaAct->getVrSubtotal() * $porBaseRetencionFuente) / 100;
                    $retencionFuente = 0;
                    if($baseRetencionFuente >= $arConfiguracion->getBaseRetencionFuente()) {
                        $retencionFuente = ($baseRetencionFuente * $porRetencionFuente ) / 100;
                    }               

                    $totalNeto = $arFacturaAct->getVrSubtotal() + $arFacturaAct->getVrIva() - $arFacturaAct->getVrRetencionFuente();                    
                    //$arFacturaAct->setVrBaseRetencionFuente($baseRetencionFuente);
                    //$arFacturaAct->setVrRetencionFuente($retencionFuente);
                    $arFacturaAct->setVrTotalNeto($totalNeto);
                    $em->persist($arFacturaAct);    
                    //echo "hola";
                }
                $em->flush();                                  
                return $this->redirect($this->generateUrl('brs_tur_movimiento_factura'));  
                */
                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
            if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcelInterfaz();
            }            
        }

        $arDocumentos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:ingreso.html.twig', array(
            'arDocumentos' => $arDocumentos,
            'form' => $form->createView()));
    } 
    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaInventarioBundle:InvDocumento')->listaDql();
    }      

    private function filtrar ($form) { 
        
    }    
    
    private function formularioFiltro() {                        
        $form = $this->createFormBuilder()
            ->getForm();
        return $form;
    }

}