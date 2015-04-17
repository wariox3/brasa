<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\MensajesBundle\GenerarMensajes;

class ProcesosController extends Controller {

    public function regenerarKardexAction() {
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        if ($request->getMethod() == 'POST') {
            $this->regenerarKardexGlobal();
            $objMensaje->Mensaje("error", "El proceso de regeneracion de kardex se a ejecutado con exito", $this);
        }
        return $this->render('BrasaInventarioBundle:Procesos/Inventario:regenerarKardex.html.twig');
    }

    public function regenerarCostosAction() {
        set_time_limit(0);
        $request = $this->getRequest();
        $objMensaje = new GenerarMensajes();
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getEntityManager();
            $arItems = new \Brasa\InventarioBundle\Entity\InvItem();
            $arItems = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
            foreach($arItems as $arItem) {
                $douCostoPromedio = 0;
                $intExistenciaAnterior = 0;
                $arMovimientosDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
                $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevMovimientosDetallesOperacion("", "", $arItem->getCodigoItemPk());
                foreach ($arMovimientosDetalles as $arMovimientosDetalle) {
                    $arMovimientoDetalleAct = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
                    $arMovimientoDetalleAct = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->find($arMovimientosDetalle['codigoDetalleMovimientoPk']);
                    //Si el documento de este movimiento es generador de costo se debe recalcular el costo promedio
                    if($arMovimientoDetalleAct->getMovimientoRel()->getDocumentoRel()->getGeneraCostoPromedio() == 1) {
                        $arMovimientoDetalleAct->setCosto($arMovimientoDetalleAct->getPrecio());
                        $douCostoPromedio = \Brasa\GeneralBundle\Repository\MovimientosDetallesRepository::CacularCostoPromedio($intExistenciaAnterior, $arMovimientoDetalleAct->getCantidad(), $douCostoPromedio, $arMovimientoDetalleAct->getCosto());
                    }
                    else
                        $arMovimientoDetalleAct->setCosto($douCostoPromedio);

                    $arMovimientoDetalleAct->setCostoPromedio($douCostoPromedio);
                    $em->persist($arMovimientoDetalleAct);
                    $em->flush();
                    $intExistenciaAnterior = $intExistenciaAnterior + $arMovimientoDetalleAct->getCantidadOperada();
                }
                $arItemAct = new \Brasa\InventarioBundle\Entity\InvItem();
                $arItemAct = $em->getRepository('BrasaInventarioBundle:InvItem')->find($arItem->getCodigoItemPk());
                $arItemAct->setCostoPromedio($douCostoPromedio);
                $em->persist($arItemAct);
                $em->flush();
            }
            $objMensaje->Mensaje("completado", "El proceso de regeneracion de costos se a ejecutado con exito", $this);
        }
        set_time_limit(0);
        return $this->render('BrasaInventarioBundle:Procesos/Inventario:regenerarCostos.html.twig');
    }

    public function cierreMesAction () {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arCierreMesInventarioProcesar = new \Brasa\InventarioBundle\Entity\InvCierreMes();
            $arCierreMesInventarioProcesar = $em->getRepository('BrasaInventarioBundle:InvCierreMes')->find($arrControles['BtnCerrar']);
            $arMovimientoDetalles = new \Brasa\InventarioBundle\Entity\InvMovimientoDetalle();
            $dateFechaDesde = date_format($arCierreMesInventarioProcesar->getFechaInicio(), 'Y-m-d');
            $dateFechaHasta = date_format($arCierreMesInventarioProcesar->getFechaFin(), 'Y-m-d');
            $douTotalVentas = 0;
            $douTotalCompras = 0;
            $arMovimientoDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevMovimientosDetallesResumen($dateFechaDesde, $dateFechaHasta, "", "", 4);
            if(count($arMovimientoDetalles) > 0)
                $douTotalVentas = $arMovimientoDetalles[0][1];

            $arMovimientoDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevMovimientosDetallesResumen($dateFechaDesde, $dateFechaHasta, "", "", 1);
            if(count($arMovimientoDetalles) > 0)
                $douTotalCompras = $arMovimientoDetalles[0][1];

            $arCierreMesInventarioProcesar->setTotalCompras($douTotalCompras);
            $arCierreMesInventarioProcesar->setTotalVentas($douTotalVentas);
            //$arCierreMesInventarioProcesar->setEstadoCerrado(1);

            //Guardar los costos de los movimientos de inventario
            $arDocumentos = new \Brasa\InventarioBundle\Entity\InvDocumento();
            $arDocumentos = $em->getRepository('BrasaInventarioBundle:InvDocumento')->findAll();
            foreach ($arDocumentos as $arDocumento) {
                $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->DevMovimientosResumenCosto($dateFechaDesde, $dateFechaHasta, $arDocumento->getCodigoDocumentoPk(), "");
                if(count($arMovimiento) > 0) {
                    $douTotal = $arMovimiento[0][1];
                    if($douTotal > 0) {
                        $arCierreMesInventarioDocumentos = new \Brasa\GeneralBundle\Entity\CierresMesInventarioDocumentos();
                        $arCierreMesInventarioDocumentos->setCierreMesInventarioRel($arCierreMesInventarioProcesar);
                        $arCierreMesInventarioDocumentos->setDocumentoRel($arDocumento);
                        $arCierreMesInventarioDocumentos->setTotalCosto($douTotal);
                        $em->persist($arCierreMesInventarioDocumentos);
                        $em->flush();
                    }
                }

            }

            //Guardar los lotes y cantidades
            //Regenera el kardex para evitar inconsistencias
            $this->regenerarKardexGlobal(date_format($arCierreMesInventarioProcesar->getFechaFin(), 'Y-m-d H:i:s'));
            $arLotes = new \Brasa\InventarioBundle\Entity\InvLote();
            $arLotes = $em->getRepository('BrasaInventarioBundle:InvLote')->DevLotesExistenciaTodos();
            foreach ($arLotes as $arLotes) {
                $arCierreMesLotes = new \Brasa\InventarioBundle\Entity\InvCierreMesLote();
                $arCierreMesLotes->setCierreMesRel($arCierreMesInventarioProcesar);
                $arCierreMesLotes->setExistencia($arLotes->getExistencia());
                $arCierreMesLotes->setItemRel($arLotes->getItemRel());
                $arCierreMesLotes->setLoteFk($arLotes->getLoteFk());
                $arCierreMesLotes->setCodigoBodegaFk($arLotes->getCodigoBodegaFk());
                $em->persist($arCierreMesLotes);
                $em->flush();
            }

            $arCierreMesInventarioProcesar->setEstadoCerrado(1);
            $em->persist($arCierreMesInventarioProcesar);
            $em->flush();
            $this->regenerarKardexGlobal();

        }
        $arCierreMesInventario = new \Brasa\InventarioBundle\Entity\InvCierreMes();
        $arCierreMesInventario = $em->getRepository('BrasaInventarioBundle:InvCierreMes')->findBy(array('annio' => 2013));
        return $this->render('BrasaInventarioBundle:Procesos/Inventario:cierreMes.html.twig', array('arCierreMesInventario' => $arCierreMesInventario));
    }

    public function regenerarKardexGlobal($dateFechaHasta = "") {
            $em = $this->getDoctrine()->getEntityManager();
            $em->getRepository('BrasaInventarioBundle:InvLote')->ReiniciarValoresLotes();
            $em->getRepository('BrasaInventarioBundle:InvItem')->ReiniciarExistencias();
            $arItemes = new \Brasa\InventarioBundle\Entity\InvItem();
            $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
            $arUltimoCierreMes = new \Brasa\InventarioBundle\Entity\InvCierreMes();
            $arUltimoCierreMes = $em->getRepository('BrasaInventarioBundle:InvCierreMes')->UltimoCierre();
            $dateUltimoCierreMes = date_format($arUltimoCierreMes[0]->getFechaFin(), 'Y-m-d H:i:s');
            foreach ($arItemes as $arItemes) {
                //Verifica si hay cierres
                if(count($dateUltimoCierreMes) > 0) {
                    //Asigna las catidades del corte que es el ultimo cierre
                    $em->getRepository('BrasaInventarioBundle:InvCierreMesLote')->AsigarDatosLotesCierreALotes($arUltimoCierreMes[0]->getCodigoCierreMesPk(), $arItemes->getCodigoItemPk());
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevMovimientosDetallesInventario($arItemes->getCodigoItemPk(), $dateUltimoCierreMes, $dateFechaHasta);
                }
                else
                    $arMovimientosDetalles = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevMovimientosDetallesInventario($arItemes->getCodigoItemPk(), "", $dateFechaHasta);

                foreach($arMovimientosDetalles as $arMovimientosDetalle) {
                    $arLoteAct = new \Brasa\InventarioBundle\Entity\InvLote();
                    $arLoteAct = $em->getRepository('BrasaInventarioBundle:InvLote')->find(array('codigoItemFk' => $arMovimientosDetalle['codigoItemFk'], 'codigoBodegaFk' => $arMovimientosDetalle['codigoBodegaFk'], 'loteFk' => $arMovimientosDetalle['loteFk']));
                    $arLoteAct->setExistencia($arLoteAct->getExistencia() + $arMovimientosDetalle['cantidadOperada']);
                    //$arLoteAct->setCantidadDisponible($arLoteAct->getCantidadDisponible() + $arMovimientosDetalle['cantidadOperada']);
                    $em->persist($arLoteAct);
                    $em->flush();
                }
            }
            $em->getRepository('BrasaInventarioBundle:InvLote')->EstablecerExistenciaItems();
    }   
    
    public function regenerarDisponiblesAction () {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $objMensaje = new GenerarMensajes();
        if ($request->getMethod() == 'POST') {            
            $em->getRepository('BrasaInventarioBundle:InvLote')->RestablecerCantidadRemisionada();
            $arItem = $em->getRepository('BrasaInventarioBundle:InvMovimientoDetalle')->DevRemisionesPendientesItemBodegaLote();
            foreach ($arItem as $arItem) {
                $arLote = new \Brasa\InventarioBundle\Entity\InvLote();
                $arLote = $em->getRepository('BrasaInventarioBundle:InvLote')->find(array('codigoItemFk' => $arItem['codigoItemFk'], 'codigoBodegaFk' => $arItem['codigoBodegaFk'], 'loteFk' => $arItem['loteFk']));                
                $arLote->setCantidadRemisionada($arItem['cantidad']);
                $em->persist($arLote);
                $em->flush();
            }
            $em->getRepository('BrasaInventarioBundle:InvLote')->EstablecerDisponiblesLotesItems();
            $objMensaje->Mensaje("completado", "El proceso de regeneracion de disponibles se a ejecutado con exito", $this);
        }
        return $this->render('BrasaInventarioBundle:Procesos/Inventario:regenerarDisponibles.html.twig');
    }
            
    public function reliquidarMovimientoAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $objMensaje = new GenerarMensajes();
        if ($request->getMethod() == 'POST') {
            if($request->request->get('TxtCodigoMovimiento') != "") {
                $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
                $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($request->request->get('TxtCodigoMovimiento'));
                if(count($arMovimiento) > 0) {
                    $em->getRepository('BrasaInventarioBundle:InvMovimiento')->Reliquidar($arMovimiento->getCodigoMovimientoPk());
                    $objMensaje->Mensaje("completado", "Movimiento reliquidado con exito", $this);                                    
                }
                else
                    $objMensaje->Mensaje("error", "El movimiento no existe", $this);                                    
            }
        }
        return $this->render('BrasaInventarioBundle:Procesos/Inventario:reliquidarMovimiento.html.twig');        
    }
    
    public function generarCuentaPagarCobrarMovimientoAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        $objMensaje = new GenerarMensajes();
        if ($request->getMethod() == 'POST') {
            if($request->request->get('TxtCodigoMovimiento') != "") {
                $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();
                $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($request->request->get('TxtCodigoMovimiento'));
                if(count($arMovimiento) > 0) {
                    $em->getRepository('BrasaInventarioBundle:InvMovimiento')->GenerarCuenta($arMovimiento->getCodigoMovimientoPk());
                    $objMensaje->Mensaje("completado", "Movimiento generado con exito", $this);                                    
                }
                else
                    $objMensaje->Mensaje("error", "El movimiento no existe", $this);                                    
            }
        }
        return $this->render('BrasaInventarioBundle:Procesos/Inventario:generarCuentaPagarCobrarMovimiento.html.twig');        
    }    
}
