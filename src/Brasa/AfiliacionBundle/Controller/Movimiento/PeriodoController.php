<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class PeriodoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/periodo", name="brs_afi_movimiento_periodo")
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
                $codigoPeriodo = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->generar($codigoPeriodo);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }
            
            if($request->request->get('OpDeshacer')) {            
                $codigoPeriodo = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM afi_periodo_detalle WHERE codigo_periodo_fk = " . $codigoPeriodo;           
                $em->getConnection()->executeQuery($strSql);                 
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);                
                $arPeriodo->setEstadoGenerado(0);
                $em->persist($arPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arPeriodos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:lista.html.twig', array(
            'arPeriodos' => $arPeriodos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/periodo/nuevo/{codigoPeriodo}", name="brs_afi_movimiento_periodo_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoPeriodo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        if($codigoPeriodo != '' && $codigoPeriodo != '0') {
            $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
        }        
        $form = $this->createForm(new AfiPeriodoType, $arPeriodo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPeriodo = $form->getData();                        
            $em->persist($arPeriodo);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo_nuevo', array('codigoPeriodo' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:nuevo.html.twig', array(
            'arPeriodo' => $arPeriodo,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/movimiento/periodo/detalle/{codigoPeriodo}", name="brs_afi_movimiento_periodo_detalle")
     */    
    public function detalleAction(Request $request, $codigoPeriodo = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        $this->listaDetalle($codigoPeriodo);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            
            if($request->request->get('OpGenerar')) {            
                $codigoPeriodo = $request->request->get('OpGenerar');
                $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->find($codigoPeriodo);
                $arPeriodo->setEstadoGenerado(1);
                $em->persist($arPeriodo);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_periodo'));
            }                            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_factura_concepto'));
            }
            if ($form->get('BtnExcel')->isClicked()) {
                //$this->filtrar($form);
                $this->listaDetalle($codigoPeriodo);
                $this->generarDetalleExcel();
            }
        }
        
        $arPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Periodo:detalle.html.twig', array(
            'arPeriodoDetalles' => $arPeriodoDetalles, 
            'form' => $form->createView()));
    }    
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->listaDQL(
                $session->get('filtroPeriodoNombre')   
                ); 
    }
    
    private function listaDetalle($codigoPeriodo) {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->listaDQL(
                $codigoPeriodo   
                ); 
    }    

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroPeriodoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroPeriodoNombre')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalle() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()                        
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))            
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
        $arPeriodos = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
        $arPeriodos = $query->getResult();
                
        foreach ($arPeriodos as $arPeriodo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodo->getCodigoPeriodoPk())
                    ->setCellValue('B' . $i, $arPeriodo->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Periodo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Periodos.xlsx"');
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

    private function generarDetalleExcel() {
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
                    ->setCellValue('A1', 'COD')
                    ->setCellValue('B1', 'CLIENTE')
                    ->setCellValue('C1', 'DESDE')
                    ->setCellValue('D1', 'HASTA');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arPeriodoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = $query->getResult();
                
        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                    ->setCellValue('B' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arPeriodoDetalle->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arPeriodoDetalle->getFechaHasta()->format('Y/m/d'));                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('PeriodoDetalle');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PeriodoDetalles.xlsx"');
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

}