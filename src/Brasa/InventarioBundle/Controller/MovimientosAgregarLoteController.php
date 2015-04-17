<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class MovimientosAgregarLoteController extends Controller
{   
    /*
     * Lista los lotes
     */
    public function listaAction($codigoMovimientoDetalle) {                
        $request = $this->getRequest();                   
        $em = $this->getDoctrine()->getEntityManager();
        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
        $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->find($codigoMovimientoDetalle);        
        $arLotes = new \Brasa\InventarioBundle\Entity\InvLote();        
        $arLotes = $em->getRepository('BrasaInventarioBundle:InvLote')->DevLotesExistencia($arMovimientoDetalle->getCodigoItemFk());   
        if ($request->getMethod() == 'POST') {                        
            
        }                                                       
        return $this->render('BrasaInventarioBundle:Movimientos:agregarLote.html.twig', array('arLotes' => $arLotes,'intCodigoMovimientoDetalle'=>$codigoMovimientoDetalle));                                        
    }    
    
    public function AsignarLoteAction($codigoMovimientoDetalle) {                
        $request = $this->getRequest();                   
        $em = $this->getDoctrine()->getEntityManager();
        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
        $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->find($codigoMovimientoDetalle);        
        $arLotes = new \Brasa\InventarioBundle\Entity\InvLote();        
        $arLotes = $em->getRepository('BrasaInventarioBundle:InvLote')->DevLotesExistencia($arMovimientoDetalle->getCodigoItemFk());   
        if ($request->getMethod() == 'POST') {                        
            
        }                                                       
        return $this->render('BrasaInventarioBundle:Movimientos:movimientosAgregarLote.html.twig', array('arLotes' => $arLotes));                                        
    }    
    
  
    
}
