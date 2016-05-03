<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiFacturaType;
class FacturaController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/factura", name="brs_afi_movimiento_factura")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            
            if($request->request->get('OpGenerar')) {            
                $codigoFactura = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->generar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura'));
            }
            
            if($request->request->get('OpDeshacer')) {            
                $codigoFactura = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM afi_factura_detalle WHERE codigo_factura_fk = " . $codigoFactura;           
                $em->getConnection()->executeQuery($strSql);                 
                $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
                $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);                
                $arFactura->setEstadoGenerado(0);
                $em->persist($arFactura);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura'));
            }            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arFacturas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:lista.html.twig', array(
            'arFacturas' => $arFacturas, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/factura/nuevo/{codigoFactura}", name="brs_afi_movimiento_factura_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        if($codigoFactura != '' && $codigoFactura != '0') {
            $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        } else{
            $arFactura->setFecha(new \DateTime('now'));
            $arFactura->setFechaVence(new \DateTime('now'));
        }       
        $form = $this->createForm(new AfiFacturaType, $arFactura);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arFactura = $form->getData();  
            $dateFechaVence = $objFunciones->sumarDiasFecha($arFactura->getClienteRel()->getPlazoPago(), $arFactura->getFecha());
            $arFactura->setFechaVence($dateFechaVence); 
            $arUsuario = $this->getUser(); 
            $arFactura->setUsuario($arUsuario->getUserName());
            $em->persist($arFactura);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_nuevo', array('codigoFactura' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $arFactura->getCodigoFacturaPk())));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:nuevo.html.twig', array(
            'arFactura' => $arFactura,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/movimiento/factura/detalle/{codigoFactura}", name="brs_afi_movimiento_factura_detalle")
     */    
    public function detalleAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();  
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $form = $this->formularioDetalle($arFactura);
        $form->handleRequest($request);        
        if ($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {      
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->autorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }            
            if($form->get('BtnDesAutorizar')->isClicked()) {                            
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->desAutorizar($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->imprimir($codigoFactura);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    //$objFactura = new \Brasa\TurnoBundle\Formatos\FormatoFactura();
                    //$objFactura->Generar($this, $codigoFactura);                    
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }             
            if($form->get('BtnDetalleActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoFactura);                                 
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }            
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->eliminar($arrSeleccionados);
                //$em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            if ($form->get('BtnDetalleCursoEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));
            }
            
        }
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->listaDQL($codigoFactura); 
        $arFacturaDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalleCurso')->listaDQL($codigoFactura); 
        $arFacturaDetalleCursos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:detalle.html.twig', array(
            'arFactura' => $arFactura,
            'arFacturaDetalles' => $arFacturaDetalles, 
            'arFacturaDetalleCursos' => $arFacturaDetalleCursos,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/afi/movimiento/factura/detalle/nuevo/{codigoFactura}", name="brs_afi_movimiento_factura_detalle_nuevo")
     */    
    public function detalleNuevoAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                      
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoServicio) {
                    $arServicio = new \Brasa\AfiliacionBundle\Entity\AfiServicio();
                    $arServicio = $em->getRepository('BrasaAfiliacionBundle:AfiServicio')->find($codigoServicio);
                    $arFacturaDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle();
                    $arFacturaDetalle->setFacturaRel($arFactura);          
                    $arFacturaDetalle->setServicioRel($arServicio);
                    $arFacturaDetalle->setCurso($arServicio->getPendiente());
                    $em->persist($arFacturaDetalle);                    
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlServiciosPendientes = $em->getRepository('BrasaAfiliacionBundle:AfiServicio')->pendienteDql($arFactura->getCodigoClienteFk());
        $arServicios = $paginator->paginate($em->createQuery($dqlServiciosPendientes), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:detalleNuevo.html.twig', array(
            'arFactura' => $arFactura, 
            'arServicios' => $arServicios, 
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/movimiento/factura/detalle/curso/nuevo/{codigoFactura}", name="brs_afi_movimiento_factura_detalle_curso_nuevo")
     */    
    public function detalleCursoNuevoAction(Request $request, $codigoFactura = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->find($codigoFactura);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                      
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCurso) {
                    $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                    $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
                    $arFacturaDetalleCurso = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();
                    $arFacturaDetalleCurso->setFacturaRel($arFactura);          
                    $arFacturaDetalleCurso->setCursoRel($arCurso);
                    $arFacturaDetalleCurso->setPrecio($arCurso->getTotal());
                    $em->persist($arFacturaDetalleCurso);   
                    $arCurso->setEstadoFacturado(1);
                    $em->persist($arCurso);
                }
                $em->flush();
                $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlCursosPendientes = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->pendienteDql($arFactura->getCodigoClienteFk());
        $arCursos = $paginator->paginate($em->createQuery($dqlCursosPendientes), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Factura:detalleCursoNuevo.html.twig', array(
            'arFactura' => $arFactura, 
            'arCursos' => $arCursos, 
            'form' => $form->createView()));
    }    
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->listaDQL(
                $session->get('filtroFacturaNombre')   
                ); 
    }      

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroFacturaNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroFacturaNombre')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);      
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonDetalleCursoEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;            
            $arrBotonDetalleActualizar['disabled'] = true;

            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            }            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)                
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)                    
                    ->add('BtnDetalleCursoEliminar', 'submit', $arrBotonDetalleEliminar)                
                    ->getForm();
        return $form;
    }        

    private function formularioDetalleNuevo() {        
        $form = $this->createFormBuilder()                 
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))                        
            ->getForm();
        return $form;
    }             
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arFacturas = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFacturas = $query->getResult();
                
        foreach ($arFacturas as $arFactura) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFactura->getCodigoFacturaPk())
                    ->setCellValue('B' . $i, $arFactura->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Factura');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Facturas.xlsx"');
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

    private function actualizarDetalle($arrControles, $codigoFactura) {
        $em = $this->getDoctrine()->getManager();        
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arFacturaDetalle = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalle;
                $arFacturaDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->find($intCodigo);
                $arFacturaDetalle->setPrecio($arrControles['TxtPrecio'.$intCodigo]);                             
                $em->persist($arFacturaDetalle);
            }
            $em->flush();                
            $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);            
        }        
    }            

}