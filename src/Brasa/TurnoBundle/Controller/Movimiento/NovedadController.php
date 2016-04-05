<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurNovedadType;

class NovedadController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/movimiento/novedad", name="brs_tur_movimiento_novedad")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();                
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {              
            if ($form->get('BtnAplicar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {                                
                        $em->getRepository('BrasaTurnoBundle:TurNovedad')->aplicar($codigo);                                                            
                    }            
                }                
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->aplicar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad'));                
            }            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad'));                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }

        $arNovedades = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Novedad:lista.html.twig', array(
            'arNovedades' => $arNovedades,            
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/tur/movimiento/novedad/nuevo/{codigoNovedad}", name="brs_tur_movimiento_novedad_nuevo")
     */
    public function nuevoAction(Request $request, $codigoNovedad) {        
        $em = $this->getDoctrine()->getManager();        
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();
        if($codigoNovedad != 0) {
            $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);
        }else{
            $arNovedad->setFechaDesde(new \DateTime('now'));
            $arNovedad->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(new TurNovedadType, $arNovedad);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arNovedad = $form->getData();
            $arrControles = $request->request->All();
            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
            if($arrControles['txtCodigoRecurso'] != '') {                
                $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['txtCodigoRecurso']);                
                if(count($arRecurso) > 0) {
                    $arNovedad->setRecursoRel($arRecurso);
                }
            }
            $arRecursoReemplazo = new \Brasa\TurnoBundle\Entity\TurRecurso();
            if($arrControles['txtCodigoRecursoReemplazo'] != '') {                
                $arRecursoReemplazo = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['txtCodigoRecursoReemplazo']);                
                if(count($arRecursoReemplazo) > 0) {
                    $arNovedad->setRecursoReemplazoRel($arRecursoReemplazo);
                }
            }            
            $arUsuario = $this->getUser();
            $arNovedad->setUsuario($arUsuario->getUserName());            
            $em->persist($arNovedad);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad_nuevo', array('codigoNovedad' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad'));
            }                                                                               
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Novedad:nuevo.html.twig', array(
            'arNovedad' => $arNovedad,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoNovedad) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();
        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);
        $form = $this->formularioDetalle($arNovedad);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {  
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoNovedad);                
                if($arNovedad->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->numeroRegistros($codigoNovedad) > 0) {
                        $arNovedad->setEstadoAutorizado(1);
                        $em->persist($arNovedad);
                        $em->flush();                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles a la novedad', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arNovedad->getEstadoAutorizado() == 1) {
                    $arNovedad->setEstadoAutorizado(0);
                    $em->persist($arNovedad);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));                
                }
            }   
            if($form->get('BtnAprobar')->isClicked()) {            
                if($arNovedad->getEstadoAutorizado() == 1) {
                    $arNovedad->setEstadoAprobado(1);
                    $em->persist($arNovedad);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));                
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoNovedad);                
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }  
            if($form->get('BtnOtroActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigoNovedadOtro'] as $intCodigo) {
                    $arNovedadOtro = new \Brasa\TurnoBundle\Entity\TurNovedadOtro();
                    $arNovedadOtro = $em->getRepository('BrasaTurnoBundle:TurNovedadOtro')->find($intCodigo);
                    $arNovedadDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);                                                          
                    $em->persist($arNovedadDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }
            if($form->get('BtnOtroEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }   
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurNovedad')->imprimir($codigoNovedad);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    $objNovedad = new \Brasa\TurnoBundle\Formatos\FormatoNovedad();
                    $objNovedad->Generar($this, $codigoNovedad);                   
                }                
            }                        
        }

        $arNovedadDetalle = new \Brasa\TurnoBundle\Entity\TurNovedadDetalle();
        $arNovedadDetalle = $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->findBy(array ('codigoNovedadFk' => $codigoNovedad));
        $arNovedadOtros = new \Brasa\TurnoBundle\Entity\TurNovedadOtro();
        $arNovedadOtros = $em->getRepository('BrasaTurnoBundle:TurNovedadOtro')->findBy(array ('codigoNovedadFk' => $codigoNovedad));        
        return $this->render('BrasaTurnoBundle:Movimientos/Novedad:detalle.html.twig', array(
                    'arNovedad' => $arNovedad,
                    'arNovedadDetalle' => $arNovedadDetalle,
                    'arNovedadOtros' => $arNovedadOtros,
                    'form' => $form->createView()
                    ));
    }       
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurNovedad')->listaDQL(
                $session->get('filtroCodigoRecurso'));
    }

    private function filtrar ($form) {       
        $session = $this->getRequest()->getSession();        
        $session->set('filtroNovedadNumero', $form->get('TxtNumero')->getData());
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroNovedadNumero')))
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('estadoAutorizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroNovedadEstadoAutorizado')))                
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnAplicar', 'submit', array('label'  => 'Aplicar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonOtroActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonOtroEliminar = array('label' => 'Eliminar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = false;            
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonOtroEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonOtroActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = true;            
        } 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                 
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)                 
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->add('BtnOtroActualizar', 'submit', $arrBotonOtroActualizar)
                    ->add('BtnOtroEliminar', 'submit', $arrBotonOtroEliminar)
                    ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();        
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'H'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'VENCE')                
                    ->setCellValue('E1', 'CLIENTE')
                    ->setCellValue('F1', 'PROSPECTO')
                    ->setCellValue('G1', 'SECTOR')
                    ->setCellValue('H1', 'HORAS')
                    ->setCellValue('I1', 'H.DIURNAS')
                    ->setCellValue('J1', 'H.NOCTURNAS')
                    ->setCellValue('K1', 'P.MINIMO')
                    ->setCellValue('L1', 'P.AJUSTADO')
                    ->setCellValue('M1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arNovedades = new \Brasa\TurnoBundle\Entity\TurNovedad();
        $arNovedades = $query->getResult();

        foreach ($arNovedades as $arNovedad) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNovedad->getCodigoNovedadPk())
                    ->setCellValue('B' . $i, $arNovedad->getNumero())
                    ->setCellValue('C' . $i, $arNovedad->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arNovedad->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arNovedad->getSectorRel()->getNombre())
                    ->setCellValue('H' . $i, $arNovedad->getHoras())
                    ->setCellValue('I' . $i, $arNovedad->getHorasDiurnas())
                    ->setCellValue('J' . $i, $arNovedad->getHorasNocturnas())
                    ->setCellValue('K' . $i, $arNovedad->getVrTotalPrecioMinimo())
                    ->setCellValue('L' . $i, $arNovedad->getVrTotalPrecioAjustado())
                    ->setCellValue('M' . $i, $arNovedad->getVrTotal());
            if($arNovedad->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E' . $i, $arNovedad->getClienteRel()->getNombreCorto());
            }
            if($arNovedad->getProspectoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F' . $i, $arNovedad->getProspectoRel()->getNombreCorto());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Novedades');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Novedades.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

    private function actualizarDetalle($arrControles, $codigoNovedad) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arNovedadDetalle = new \Brasa\TurnoBundle\Entity\TurNovedadDetalle();
                $arNovedadDetalle = $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->find($intCodigo);
                $arNovedadDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);
                if($arrControles['TxtValorAjustado'.$intCodigo] != '') {
                    $arNovedadDetalle->setVrPrecioAjustado($arrControles['TxtValorAjustado'.$intCodigo]);                
                }                     
                if(isset($arrControles['chkLunes'.$intCodigo])) {
                    $arNovedadDetalle->setLunes(1);
                } else {
                    $arNovedadDetalle->setLunes(0);
                }
                if(isset($arrControles['chkMartes'.$intCodigo])) {
                    $arNovedadDetalle->setMartes(1);
                } else {
                    $arNovedadDetalle->setMartes(0);
                }
                if(isset($arrControles['chkMiercoles'.$intCodigo])) {
                    $arNovedadDetalle->setMiercoles(1);
                } else {
                    $arNovedadDetalle->setMiercoles(0);
                }
                if(isset($arrControles['chkJueves'.$intCodigo])) {
                    $arNovedadDetalle->setJueves(1);
                } else {
                    $arNovedadDetalle->setJueves(0);
                }
                if(isset($arrControles['chkViernes'.$intCodigo])) {
                    $arNovedadDetalle->setViernes(1);
                } else {
                    $arNovedadDetalle->setViernes(0);
                }
                if(isset($arrControles['chkSabado'.$intCodigo])) {
                    $arNovedadDetalle->setSabado(1);
                } else {
                    $arNovedadDetalle->setSabado(0);
                }
                if(isset($arrControles['chkDomingo'.$intCodigo])) {
                    $arNovedadDetalle->setDomingo(1);
                } else {
                    $arNovedadDetalle->setDomingo(0);
                }
                if(isset($arrControles['chkFestivo'.$intCodigo])) {
                    $arNovedadDetalle->setFestivo(1);
                } else {
                    $arNovedadDetalle->setFestivo(0);
                }                    
                $em->persist($arNovedadDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);                   
        }
    }
    
}