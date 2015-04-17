<?php

namespace Brasa\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\MensajesBundle\GenerarMensajes;

class ItemsController extends Controller {

    /**
     * consulta todos los item de la base de datos
     * y lo muestra en un listado
     * @return type 
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();                
        $arMarcas = $em->getRepository('BrasaInventarioBundle:InvMarca')->findAll();
        $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
        if ($request->getMethod() == 'POST') {
            switch ($request->request->get('OpSubmit')) {
                case "OpBuscar";
                    if ($request->request->get('TxtCodigoItem') != "" && is_numeric($request->request->get('TxtCodigoItem'))) {
                        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findBy(array('codigoItemPk' => $request->request->get('TxtCodigoItem')));
                    } elseif ($request->request->get('TxtDescripcionItem') != "") {
                        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));
                    }
                    elseif($request->request->get('TxtCodigoBarras') != "") {
                        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarCodigoBarras($request->request->get('TxtCodigoBarras'));
                    }
                    // Todos los productos
                    else {
                        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
                    }
                break;
                
                case "OpExportar";                    
                   $objPHPExcel = new \PHPExcel();
                   // Set properties
                   $objPHPExcel->getProperties()->setCreator("Brasa");                   
                   $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
                   $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
                   $objPHPExcel->getProperties()->setDescription("Lista de items.");

                   // Add some data
                   $objPHPExcel->setActiveSheetIndex(0);
                   $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
                   $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
                   $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
                   $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

                   // Rename sheet
                   $objPHPExcel->getActiveSheet()->setTitle('Simple');

                   // Save Excel 2007 file
                   $strArchivo = "/opt/lampp/htdocs/prueba.xlsx";
                   $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                   $objWriter->save($strArchivo); 
                break;            
            }                                                                
        }
        // Todos los productos
        else {
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        }

        return $this->render('BrasaInventarioBundle:Base/Items:lista.html.twig', array('arItems' => $arItem, 'ultimo_item' => $request->request->get('TxtCodigoItem'), 'ultima_descripcion' => $request->request->get('TxtDescripcionItem'), 'arMarcas' => $arMarcas,'codigo_barras'=>$request->request->get('TxtCodigoBarras')));
    }    

    /**
     * Alamacena un nuevo tercero
     * @return type 
     */
    public function nuevoAction($codigoItemPk = null) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        
        if ($request->getMethod() == 'POST') {
            if (($request->request->get('TxtCodigoItem')))
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($request->request->get('TxtCodigoItem'));
            else            
                $arItem = new \Brasa\InventarioBundle\Entity\InvItem();

            $arItem->setDescripcion($request->request->get('TxtDescripcion'));
            $arItem->setCodigoEAN($request->request->get('TxtCodigoEAN'));
            $arItem->setCodigoBarras($request->request->get('TxtCodigoBarra'));
            $arItem->setPorcentajeIva($request->request->get('TxtPorcentajeIva'));
            $arItem->setCuentaVentas($request->request->get('TxtCuentaIngreso'));
            $arItem->setCuentaDevolucionVentas($request->request->get('TxtCuentaDevolucionVentas'));
            $arItem->setCuentaCompras($request->request->get('TxtCuentaCompras'));
            $arItem->setCuentaDevolucionCompras($request->request->get('TxtCuentaDevolucionCompras'));
            $arItem->setCuentaCosto($request->request->get('TxtCuentaCosto'));
            $arItem->setCuentaInventario($request->request->get('TxtCuentaInventario'));
            $arItem->setVrCostoPredeterminado($request->request->get('TxtCostoPredeterminado'));
            $arItem->setVrPrecioPredeterminado($request->request->get('TxtPrecioPredeterminado'));
                                    

            if ($request->request->get('CboMarcas') != "") {
                $arMarca = new \Brasa\InventarioBundle\Entity\InvMarca();
                $arMarca = $em->getRepository('BrasaInventarioBundle:InvMarca')->find($request->request->get('CboMarcas'));
                $arItem->setMarcaRel($arMarca);
            }
            
            if ($request->request->get('CboUnidadesMedida') != "") {
                $arUnidadMedida = new \Brasa\InventarioBundle\Entity\InvUnidadMedida();
                $arUnidadMedida = $em->getRepository('BrasaInventarioBundle:InvUnidadMedida')->find($request->request->get('CboUnidadesMedida'));
                $arItem->setUnidadMedidaRel($arUnidadMedida);
            }            
            
            if(($request->request->get('ChkMateriaPrima') == 'on') && $request->request->get('ChkMateriaPrima'))
                $arItem->setMateriaPrima(1);
            else
                $arItem->setMateriaPrima(0);
            
            $em->persist($arItem);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_inv_base_items_lista'));
        }
        $arItem = null;
        if ($codigoItemPk != null && $codigoItemPk != "" && $codigoItemPk != 0)        
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($codigoItemPk);        
        $arMarcas = $em->getRepository('BrasaInventarioBundle:InvMarca')->findAll();
        $arUnidadesMedida = $em->getRepository('BrasaInventarioBundle:InvUnidadMedida')->findAll();
        return $this->render('BrasaInventarioBundle:Base/Items:nuevo.html.twig', array(
            'arItem' => $arItem,
            'arMarcas'=>$arMarcas,
            'arUnidadesMedida'=>$arUnidadesMedida));

    }       
    
    public function detalleAction($codigoItemPk = null) {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();        
        $arItem = new \Brasa\InventarioBundle\Entity\InvItem(); 
        $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($codigoItemPk);        
        $arItemMaterialFabricacion = new \Brasa\InventarioBundle\Entity\InvItemMaterialFabricacion();
        
        if ($request->getMethod() == 'POST') { 
            $objFunciones = new \Brasa\ExternasBundle\FuncionesZikmont\FuncionesZikmont();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {                       
                case "OpEliminar"; 
                    if(count($arrSeleccionados) > 0) {
                        foreach ($arrSeleccionados as $codigoDocumentoHijoPk) {
                            $arDocumentoControl = new \Brasa\InventarioBundle\Entity\InvDocumentoControl();
                            $arDocumentoControl = $em->getRepository('BrasaInventarioBundle:InvDocumentoControl')->find(array('codigoDocumentoPadrePk' => $codigoDocumentoPk, 'codigoDocumentoHijoPk' => $codigoDocumentoHijoPk));
                            $em->remove($arDocumentoControl);
                            $em->flush();
                        }                         
                    }                   
                    break;                     
                case "OpAgregar"; 
                    $intCodigoItem = $objFunciones->DevCodigoItem($request->request->get('TxtCodigoItem'));
                    $arItemHijo = new \Brasa\InventarioBundle\Entity\InvItem(); 
                    $arItemHijo = $em->getRepository('BrasaInventarioBundle:InvItem')->find($intCodigoItem);
                    if(count($arItemHijo) > 0) {
                        $arItemMaterialFabricacionNuevo = new \Brasa\InventarioBundle\Entity\InvItemMaterialFabricacion();
                        $arItemMaterialFabricacionNuevo->setItemPadreRel($arItem);
                        $arItemMaterialFabricacionNuevo->setItemRel($arItemHijo);
                        $arItemMaterialFabricacionNuevo->setCantidad($request->request->get('TxtCantidad'));
                        $em->persist($arItemMaterialFabricacionNuevo);
                        $em->flush();                                            
                    }

                    break;
            }            
        }        
        
        $arItemMaterialFabricacion = $em->getRepository('BrasaInventarioBundle:InvItemMaterialFabricacion')->findBy(array('codigoItemPadreFk' => $codigoItemPk));
        return $this->render('BrasaInventarioBundle:Maestros/Items:detalle.html.twig', array(
            'arItem' => $arItem,        
            'arItemMaterialFabricacion' => $arItemMaterialFabricacion));        
    }        

}
