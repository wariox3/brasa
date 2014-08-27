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
        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
        $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($codigoMovimientoDetalle);        
        $arLotes = new \Brasa\InventarioBundle\Entity\InvLotes();        
        $arLotes = $em->getRepository('BrasaInventarioBundle:InvLotes')->DevLotesExistencia($arMovimientoDetalle->getCodigoItemFk());   
        if ($request->getMethod() == 'POST') {                        
            
        }                                                       
        return $this->render('BrasaInventarioBundle:Movimientos:agregarLote.html.twig', array('arLotes' => $arLotes,'intCodigoMovimientoDetalle'=>$codigoMovimientoDetalle));                                        
    }    
    
    public function AsignarLoteAction($codigoMovimientoDetalle) {                
        $request = $this->getRequest();                   
        $em = $this->getDoctrine()->getEntityManager();
        $arMovimientoDetalle = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
        $arMovimientoDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->find($codigoMovimientoDetalle);        
        $arLotes = new \Brasa\InventarioBundle\Entity\InvLotes();        
        $arLotes = $em->getRepository('BrasaInventarioBundle:InvLotes')->DevLotesExistencia($arMovimientoDetalle->getCodigoItemFk());   
        if ($request->getMethod() == 'POST') {                        
            
        }                                                       
        return $this->render('BrasaInventarioBundle:Movimientos:movimientosAgregarLote.html.twig', array('arLotes' => $arLotes));                                        
    }    
    
  
    
}
