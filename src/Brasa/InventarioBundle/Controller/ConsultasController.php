<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConsultasController extends Controller {

    public function kardexAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();        
        $objFunciones = new \zikmont\ExternasBundle\FuncionesZikmont\FuncionesZikmont();
        $arrControles = $request->request->All();        
        $arMovimientosDetalle = "";        

        if ($request->getMethod() == 'POST') {
            $intItem = $objFunciones->DevCodigoItem($arrControles['TxtCodigoItem']);
            $intCodigoTercero = $objFunciones->DevCodigoTercero($arrControles['terceroconsulta']);            
            $arMovimientosDetalle = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetalles($intItem, $arrControles['CboDocumentos'], $intCodigoTercero, $arrControles['TxtLote'], $arrControles['CboBodegas'], $arrControles['TxtFechaDesde'], $arrControles['TxtFechaHasta']);
        }

        $arDocumentos = $em->getRepository('zikmontInventarioBundle:InvDocumentos')->DevDocumentos();
        $arBodegas = new \zikmont\InventarioBundle\Entity\InvBodegas();
        $arBodegas = $em->getRepository('zikmontInventarioBundle:InvBodegas')->findAll();
        return $this->render('zikmontInventarioBundle:Consultas/Inventario:kardex.html.twig', array(
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
        $em = $this->getDoctrine()->getEntityManager();

        $arrControles = $request->request->All();
        $arLotes = "";
        $arPedidos = "";
        $arOrdenes = "";
        $intCodigoProducto = "";
        $arMovimientosDetalleRemisiones = new \zikmont\InventarioBundle\Entity\InvMovimientosDetalles();
        if ($request->getMethod() == 'POST') {
            $intCodigoProducto = $arrControles['TxtCodigoItem'];
            $arLotes = $em->getRepository('zikmontInventarioBundle:InvLotes')->DevLotesExistencia($intCodigoProducto);
            $arPedidos = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->DevPedidosPendientes($intCodigoProducto);
            $arMovimientosDetalleRemisiones = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetallesPendientes(0, 9);
        }

        return $this->render('zikmontInventarioBundle:Consultas/Inventario:disponibles.html.twig', array(
                    'arLotes' => $arLotes,
                    'arMovimientosDetalleRemisiones' => $arMovimientosDetalleRemisiones,
                    'arPedidos' => $arPedidos,
                    'arOrdenes' => $arOrdenes,
                    'ultimo_item' => $intCodigoProducto));
    }

    public function existenciasAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $objFunciones = new \zikmont\ExternasBundle\FuncionesZikmont\FuncionesZikmont();
        $arrControles = $request->request->All();
        $arLotes = new \zikmont\InventarioBundle\Entity\InvLotes();
        if ($request->getMethod() == 'POST') {
            $intItem = $objFunciones->DevCodigoItem($arrControles['TxtCodigoItem']);
            $arLotes = $em->getRepository('zikmontInventarioBundle:InvLotes')->DevLotesExistenciaFiltro($intItem, $arrControles['CboBodegas'], $arrControles['TxtLote']);            
        }
        else 
            $arLotes = $em->getRepository('zikmontInventarioBundle:InvLotes')->DevLotesExistenciaFiltro();            
        
        
        $arBodegas = new \zikmont\InventarioBundle\Entity\InvBodegas();
        $arBodegas = $em->getRepository('zikmontInventarioBundle:InvBodegas')->findAll();                
        return $this->render('zikmontInventarioBundle:Consultas/Inventario:existencias.html.twig', array(
            'arLotes' => $arLotes,                                                 
            'arBodegas' => $arBodegas,
            'arrControles' => $arrControles));
    }
    
    public function inventarioValorizadoAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager(); 
        $arTemporalInventarioValorizado = new \zikmont\InventarioBundle\Entity\InvTemporalInventarioValorizado();        
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
            
            
            $objQuery = $em->createQuery('DELETE FROM zikmontInventarioBundle:InvTemporalInventarioValorizado')->getResult();            
            
            $arItems = new \zikmont\InventarioBundle\Entity\InvItem();
            if($intUltimoItem != "")                            
                $arItems = $em->getRepository('zikmontInventarioBundle:InvItem')->findBy(array('codigoItemPk' => $intUltimoItem));
            else
                $arItems = $em->getRepository('zikmontInventarioBundle:InvItem')->findAll();
            foreach ($arItems as $arItem) {
                $douCostoPromedio = 0;
                $intExistenciaAnterior = 0;
                $arMovimientosDetalles = new \zikmont\InventarioBundle\Entity\InvMovimientosDetalles();
                $arMovimientosDetalles = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetallesGeneranCosto($dateDesde, $dateHasta, $arItem->getCodigoItemPk()); 
                foreach ($arMovimientosDetalles as $arMovimientoDetalle) {
                    if($arMovimientoDetalle['generaCostoPromedio'] == 1)
                        $douCostoPromedio = \zikmont\InventarioBundle\Repository\InvMovimientosDetallesRepository::CacularCostoPromedio($intExistenciaAnterior, $arMovimientoDetalle['cantidadOperada'], $douCostoPromedio, $arMovimientoDetalle['costo']);                                                                            
                    $intExistenciaAnterior = $intExistenciaAnterior + $arMovimientoDetalle['cantidadOperada'];
                } 
                if($intExistenciaAnterior > 0) {
                    $arRegistroInventarioValorizado = new \zikmont\InventarioBundle\Entity\InvTemporalInventarioValorizado();
                    $arRegistroInventarioValorizado->setItemRel($arItem);  
                    $arRegistroInventarioValorizado->setCostoPromedio($douCostoPromedio);
                    $arRegistroInventarioValorizado->setSaldo($intExistenciaAnterior);
                    $arRegistroInventarioValorizado->setTotalPromedio($douCostoPromedio * $intExistenciaAnterior);
                    $em->persist($arRegistroInventarioValorizado);
                    $em->flush();
                }
            } 
            $arTemporalInventarioValorizado = $em->getRepository('zikmontInventarioBundle:InvTemporalInventarioValorizado')->findAll();
        }        
        return $this->render('zikmontInventarioBundle:Consultas/Inventario:inventarioValorizado.html.twig', array(
                    'arTemporalInventarioValorizado' => $arTemporalInventarioValorizado,
                    'ultimo_item' => $intUltimoItem,
                    'fecha_desde'=>$dateDesde,
                    'fecha_hasta'=>$dateHasta));        
        
    }    

    public function itemAction($codigoItem) {
        $em = $this->getDoctrine()->getEntityManager();
        $arItem = new \zikmont\InventarioBundle\Entity\InvItem();
        $arItem = $em->getRepository('zikmontInventarioBundle:InvItem')->find($codigoItem);
        $arLotes = new \zikmont\InventarioBundle\Entity\InvLotes();        
        $arLotes = $em->getRepository('zikmontInventarioBundle:InvLotes')->DevLotesExistencia($codigoItem);
        return $this->render('zikmontInventarioBundle:Consultas/Inventario:item.html.twig', array('arLotes' => $arLotes, 'arItem' => $arItem));
    }
    
    public function generalAnalisisGeneralAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $arCierreMesInventario = new \zikmont\InventarioBundle\Entity\InvCierresMes();
        $arCierreMesInventario = $em->getRepository('zikmontInventarioBundle:InvCierresMes')->findAll();
        return $this->render('zikmontFrontEndBundle:Consultas/General:analisisGeneral.html.twig', array('arCierreMesInventario' => $arCierreMesInventario));
    }
    
    public function comercialesPresupuestosAction() {
        $em = $this->getDoctrine()->getEntityManager();
        return $this->render('zikmontFrontEndBundle:Consultas/Comerciales:presupuestos.html.twig');
    }    

    /*
     * Lista los movimientos detalle (Detalles) segun encabezado - Filtro
     */

    public function movimientosPendientesAction($codigoDocumentoTipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $arMovimientos = new \zikmont\InventarioBundle\Entity\InvMovimientos();
        $arMovimientos = $em->getRepository('zikmontInventarioBundle:InvMovimientos')->findBy(array('codigoDocumentoTipoFk' => $codigoDocumentoTipo, 'estadoAutorizado' => 1, 'estadoCerrado' => 0));
        if ($request->getMethod() == 'POST') {

        }

        return $this->render('zikmontFrontEndBundle:Movimientos:movimientosPendientes.html.twig', array('arMovimientos' => $arMovimientos));
    }

    /**
     * Genera un listado de los detalles pendientes de un movimiento
     * @param integer $codigoMovimiento El codigo del movimiento a consultar
     * @return Render Movimientos:movimientosPendientesDetalles
     */
    public function movimientosPendientesDetallesAction($codigoMovimiento, $codigoDocumentoTipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $arDocumentosTipos = new \zikmont\InventarioBundle\Entity\InvDocumentosTipos();        
        $arDocumentosTipos = $em->getRepository('zikmontInventarioBundle:InvDocumentosTipos')->findAll();
        $arDocumentos = new \zikmont\InventarioBundle\Entity\InvDocumentos();
        $arDocumentos = $em->getRepository('zikmontInventarioBundle:InvDocumentos')->findAll();

        $arMovimientosDetalle = $em->getRepository('zikmontInventarioBundle:InvMovimientosDetalles')->DevMovimientosDetallesPendientes(0, $codigoDocumentoTipo);

        if ($request->getMethod() == 'POST') {

        }

        return $this->render('zikmontInventarioBundle:Consultas/Inventario:movimientosPendientesDetalles.html.twig', array(
                    'arMovimientosDetalle' => $arMovimientosDetalle,
                    'arDocumentos' => $arDocumentos,
                    'arDocumentosTipos' => $arDocumentosTipos,
                    'intCodigoDocumentoTipo' => $codigoDocumentoTipo,
                    'intCodigoDocumento' => 0));
    }    
    
}

