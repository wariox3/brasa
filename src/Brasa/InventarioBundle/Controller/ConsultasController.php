<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConsultasController extends Controller {

    public function kardexAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $objFunciones = new \Brasa\ExternasBundle\FuncionesZikmont\FuncionesZikmont();
        $arrControles = $request->request->All();        
        $arMovimientosDetalle = "";        

        if ($request->getMethod() == 'POST') {
            $intItem = $objFunciones->DevCodigoItem($arrControles['TxtCodigoItem']);
            $intCodigoTercero = $objFunciones->DevCodigoTercero($arrControles['terceroconsulta']);            
            $arMovimientosDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetalles($intItem, $arrControles['CboDocumentos'], $intCodigoTercero, $arrControles['TxtLote'], $arrControles['CboBodegas'], $arrControles['TxtFechaDesde'], $arrControles['TxtFechaHasta']);
        }

        $arDocumentos = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->DevDocumentos();
        $arBodegas = new \Brasa\InventarioBundle\Entity\InvBodegas();
        $arBodegas = $em->getRepository('BrasaInventarioBundle:InvBodegas')->findAll();
        return $this->render('BrasaInventarioBundle:Consultas/Inventario:kardex.html.twig', array(
                    'arMovimientosDetalle' => $arMovimientosDetalle,
                    'arDocumentos' => $arDocumentos,
                    'arBodegas' => $arBodegas,                                        
                    'arrControles' => $arrControles));
    }

    /**
     * Consulta las cantidades en existencia de un producto con ul lote determinado
     * @return vista disponibles 
     * */
    public function disponiblesAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $arrControles = $request->request->All();
        $arLotes = "";
        $arPedidos = "";
        $arOrdenes = "";
        $intCodigoProducto = "";
        $arMovimientosDetalleRemisiones = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
        if ($request->getMethod() == 'POST') {
            $intCodigoProducto = $arrControles['TxtCodigoItem'];
            $arLotes = $em->getRepository('BrasaInventarioBundle:InvLotes')->DevLotesExistencia($intCodigoProducto);
            $arPedidos = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->DevPedidosPendientes($intCodigoProducto);
            $arMovimientosDetalleRemisiones = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetallesPendientes(0, 9);
        }

        return $this->render('BrasaInventarioBundle:Consultas/Inventario:disponibles.html.twig', array(
                    'arLotes' => $arLotes,
                    'arMovimientosDetalleRemisiones' => $arMovimientosDetalleRemisiones,
                    'arPedidos' => $arPedidos,
                    'arOrdenes' => $arOrdenes,
                    'ultimo_item' => $intCodigoProducto));
    }

    public function existenciasAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objFunciones = new \Brasa\ExternasBundle\FuncionesZikmont\FuncionesZikmont();
        $arrControles = $request->request->All();
        $arLotes = new \Brasa\InventarioBundle\Entity\InvLotes();
        if ($request->getMethod() == 'POST') {
            $intItem = $objFunciones->DevCodigoItem($arrControles['TxtCodigoItem']);
            $arLotes = $em->getRepository('BrasaInventarioBundle:InvLotes')->DevLotesExistenciaFiltro($intItem, $arrControles['CboBodegas'], $arrControles['TxtLote']);            
        }
        else 
            $arLotes = $em->getRepository('BrasaInventarioBundle:InvLotes')->DevLotesExistenciaFiltro();            
        
        
        $arBodegas = new \Brasa\InventarioBundle\Entity\InvBodegas();
        $arBodegas = $em->getRepository('BrasaInventarioBundle:InvBodegas')->findAll();                
        return $this->render('BrasaInventarioBundle:Consultas/Inventario:existencias.html.twig', array(
            'arLotes' => $arLotes,                                                 
            'arBodegas' => $arBodegas,
            'arrControles' => $arrControles));
    }
    
    public function inventarioValorizadoAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arTemporalInventarioValorizado = new \Brasa\InventarioBundle\Entity\InvTemporalInventarioValorizado();        
        $intUltimoItem = "";        
        $dateDesde = "";
        $dateHasta = "";

        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            //Item
            $arrayItem = explode("-", $arrControles['TxtCodigoItem']);
            $intUltimoItem = $arrayItem[0];
            // Fecha
            $dateDesde = $arrControles['TxtFechaDesde'];
            $dateHasta = $arrControles['TxtFechaHasta'];
            
            
            $objQuery = $em->createQuery('DELETE FROM BrasaInventarioBundle:InvTemporalInventarioValorizado')->getResult();            
            
            $arItems = new \Brasa\InventarioBundle\Entity\InvItem();
            if($intUltimoItem != "")                            
                $arItems = $em->getRepository('BrasaInventarioBundle:InvItem')->findBy(array('codigoItemPk' => $intUltimoItem));
            else
                $arItems = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
            foreach ($arItems as $arItem) {
                $douCostoPromedio = 0;
                $intExistenciaAnterior = 0;
                $arMovimientosDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientosDetalles();
                $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetallesGeneranCosto($dateDesde, $dateHasta, $arItem->getCodigoItemPk()); 
                foreach ($arMovimientosDetalles as $arMovimientoDetalle) {
                    if($arMovimientoDetalle['generaCostoPromedio'] == 1)
                        $douCostoPromedio = \Brasa\InventarioBundle\Repository\InvMovimientosDetallesRepository::CacularCostoPromedio($intExistenciaAnterior, $arMovimientoDetalle['cantidadOperada'], $douCostoPromedio, $arMovimientoDetalle['costo']);                                                                            
                    $intExistenciaAnterior = $intExistenciaAnterior + $arMovimientoDetalle['cantidadOperada'];
                } 
                if($intExistenciaAnterior > 0) {
                    $arRegistroInventarioValorizado = new \Brasa\InventarioBundle\Entity\InvTemporalInventarioValorizado();
                    $arRegistroInventarioValorizado->setItemRel($arItem);  
                    $arRegistroInventarioValorizado->setCostoPromedio($douCostoPromedio);
                    $arRegistroInventarioValorizado->setSaldo($intExistenciaAnterior);
                    $arRegistroInventarioValorizado->setTotalPromedio($douCostoPromedio * $intExistenciaAnterior);
                    $em->persist($arRegistroInventarioValorizado);
                    $em->flush();
                }
            } 
            $arTemporalInventarioValorizado = $em->getRepository('BrasaInventarioBundle:InvTemporalInventarioValorizado')->findAll();
        }        
        return $this->render('BrasaInventarioBundle:Consultas/Inventario:inventarioValorizado.html.twig', array(
                    'arTemporalInventarioValorizado' => $arTemporalInventarioValorizado,
                    'ultimo_item' => $intUltimoItem,
                    'fecha_desde'=>$dateDesde,
                    'fecha_hasta'=>$dateHasta));        
        
    }    

    public function itemAction($codigoItem) {
        $em = $this->getDoctrine()->getManager();
        $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($codigoItem);
        $arLotes = new \Brasa\InventarioBundle\Entity\InvLotes();        
        $arLotes = $em->getRepository('BrasaInventarioBundle:InvLotes')->DevLotesExistencia($codigoItem);
        return $this->render('BrasaInventarioBundle:Consultas/Inventario:item.html.twig', array('arLotes' => $arLotes, 'arItem' => $arItem));
    }
    
    public function generalAnalisisGeneralAction() {
        $em = $this->getDoctrine()->getManager();
        $arCierreMesInventario = new \Brasa\InventarioBundle\Entity\InvCierresMes();
        $arCierreMesInventario = $em->getRepository('BrasaInventarioBundle:InvCierresMes')->findAll();
        return $this->render('BrasaFrontEndBundle:Consultas/General:analisisGeneral.html.twig', array('arCierreMesInventario' => $arCierreMesInventario));
    }
    
    public function comercialesPresupuestosAction() {
        $em = $this->getDoctrine()->getManager();
        return $this->render('BrasaFrontEndBundle:Consultas/Comerciales:presupuestos.html.twig');
    }    

    /*
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */

    public function movimientosPendientesAction($codigoDocumentoTipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arMovimientos = new \Brasa\InventarioBundle\Entity\InvMovimientos();
        $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimientos')->findBy(array('codigoDocumentoTipoFk' => $codigoDocumentoTipo, 'estadoAutorizado' => 1, 'estadoCerrado' => 0));
        if ($request->getMethod() == 'POST') {

        }

        return $this->render('BrasaFrontEndBundle:Movimientos:movimientosPendientes.html.twig', array('arMovimientos' => $arMovimientos));
    }

    /**
     * Genera un listado de los detalles pendientes de un movimiento
     * @param integer $codigoMovimiento El codigo del movimiento a consultar
     * @return Render Movimientos:movimientosPendientesDetalles
     */
    public function movimientosPendientesDetallesAction($codigoMovimiento, $codigoDocumentoTipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arDocumentosTipos = new \Brasa\InventarioBundle\Entity\InvDocumentosTipos();        
        $arDocumentosTipos = $em->getRepository('BrasaInventarioBundle:InvDocumentosTipos')->findAll();
        $arDocumentos = new \Brasa\InventarioBundle\Entity\InvDocumentos();
        $arDocumentos = $em->getRepository('BrasaInventarioBundle:InvDocumentos')->findAll();

        $arMovimientosDetalle = $em->getRepository('BrasaInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetallesPendientes(0, $codigoDocumentoTipo);

        if ($request->getMethod() == 'POST') {

        }

        return $this->render('BrasaInventarioBundle:Consultas/Inventario:movimientosPendientesDetalles.html.twig', array(
                    'arMovimientosDetalle' => $arMovimientosDetalle,
                    'arDocumentos' => $arDocumentos,
                    'arDocumentosTipos' => $arDocumentosTipos,
                    'intCodigoDocumentoTipo' => $codigoDocumentoTipo,
                    'intCodigoDocumento' => 0));
    }    
    
}

