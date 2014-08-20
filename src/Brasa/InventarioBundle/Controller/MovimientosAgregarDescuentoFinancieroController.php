<?php

namespace Brasa\InventarioBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovimientosAgregarDescuentoFinancieroController extends Controller
{   
    
    public function listaAction($codigoMovimiento) {                
        $request = $this->getRequest();                   
        $em = $this->getDoctrine()->getEntityManager();
        $arDescuentosFinancieros = $em->getRepository('zikmontInventarioBundle:InvDescuentosFinancieros')->findAll();     
        $arMovimiento = new \zikmont\InventarioBundle\Entity\InvMovimientos();
        $arMovimiento = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->find($codigoMovimiento);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');            
            switch ($request->request->get('OpSubmit')) {     
                case "OpAgregar";                    
                    if (count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados AS $codigoDescuentoFinanciero) {
                            $arDescuentoFinanciero = new \zikmont\InventarioBundle\Entity\InvDescuentosFinancieros();
                            $arDescuentoFinanciero = $em->getRepository('zikmontInventarioBundle:InvDescuentosFinancieros')->find($codigoDescuentoFinanciero);     
                            $arMovimientoDescuentoFinanciero = new \zikmont\InventarioBundle\Entity\InvMovimientosDescuentosFinancieros();
                            $arMovimientoDescuentoFinanciero->setMovimientoRel($arMovimiento);
                            $arMovimientoDescuentoFinanciero->setDescuentoFinancieroRel($arDescuentoFinanciero);
                            $arMovimientoDescuentoFinanciero->setPorcentaje($arDescuentoFinanciero->getPorcentaje());
                            $arMovimientoDescuentoFinanciero->setValorTotal($arMovimiento->getTotalBruto()*$arDescuentoFinanciero->getPorcentaje()/100);
                            $arMovimientoDescuentoFinanciero->setBase($arMovimiento->getTotalBruto());
                            $em->persist($arMovimientoDescuentoFinanciero);
                            $em->flush();
                        }
                        $em->getRepository('zikmontInventarioBundle:InvMovimientos')->LiquidarRetenciones($codigoMovimiento);
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                    break;      
            }            
        }        
        return $this->render('zikmontInventarioBundle:Movimientos:agregarDescuentoFinanciero.html.twig', array(
            'arDescuentosFinancieros' => $arDescuentosFinancieros, 
            'arMovimiento' => $arMovimiento));                                        
    }               
}
