<?php
namespace Brasa\ContabilidadBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class RegistroController extends Controller
{
    var $strListaDql = "";   
    
    /**
     * @Route("/ctb/movimiento/registro", name="brs_ctb_movimiento_registro")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {             
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_ctb_movimiento_registro'));                 
                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request);
                //$form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request);
                //$form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }
        $arRegistros = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaContabilidadBundle:Movimiento/Registro:lista.html.twig', array(
            'arRegistros' => $arRegistros,            
            'form' => $form->createView()));
    }
    
    private function lista() {   
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        
        $this->strListaDql =  $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->listaDQL(
                    $session->get('filtroNumeroRegistro'),
                    $session->get('filtroCodigoComprobante'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }        
    
    private function filtrar ($form, Request $request) {
        $session = $this->get('session');        
        $controles = $request->request->get('form');
        $session->set('filtroRegistroNumero', $controles['TxtNumeroRegistro']);                
        $session->set('filtroCodigoComprobante', $controles['comprobanteRel']);
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()

            ->add('TxtNumeroRegistro', 'text', array('label'  => 'Codigo'))
            ->add('comprobanteRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                                            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }   
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for($col = 'A'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'M'; $col !== 'S'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NÚMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'AÑO')
                    ->setCellValue('F1', 'MES')
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'SECTOR')
                    ->setCellValue('I1', 'AUT')
                    ->setCellValue('J1', 'PRO')
                    ->setCellValue('K1', 'FAC')
                    ->setCellValue('L1', 'ANU')
                    ->setCellValue('M1', 'HORAS')
                    ->setCellValue('N1', 'H.DIURNAS')
                    ->setCellValue('O1', 'H.NOCTURNAS')
                    ->setCellValue('P1', 'P.MINIMO')
                    ->setCellValue('Q1', 'P.AJUSTADO')
                    ->setCellValue('R1', 'TOTAL');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
        $arRegistros = $query->getResult();

        foreach ($arRegistros as $arRegistro) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistro->getCodigoRegistroPk())
                    ->setCellValue('B' . $i, $arRegistro->getRegistroTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arRegistro->getNumero())
                    ->setCellValue('D' . $i, $arRegistro->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arRegistro->getFechaProgramacion()->format('Y'))
                    ->setCellValue('F' . $i, $arRegistro->getFechaProgramacion()->format('F'))                    
                    ->setCellValue('G' . $i, $arRegistro->getClienteRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arRegistro->getSectorRel()->getNombre())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arRegistro->getEstadoAutorizado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arRegistro->getEstadoProgramado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arRegistro->getEstadoFacturado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arRegistro->getEstadoAnulado()))
                    ->setCellValue('M' . $i, $arRegistro->getHoras())
                    ->setCellValue('N' . $i, $arRegistro->getHorasDiurnas())
                    ->setCellValue('O' . $i, $arRegistro->getHorasNocturnas())
                    ->setCellValue('P' . $i, $arRegistro->getVrTotalPrecioMinimo())
                    ->setCellValue('Q' . $i, $arRegistro->getVrTotalPrecioAjustado())
                    ->setCellValue('R' . $i, $arRegistro->getVrTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Registros');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Registros.xlsx"');
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
    
    /**
     * @Route("/ctb/movimiento/registro/eliminar", name="brs_ctb_movimiento_registro_eliminar")
     */    
    public function eliminarMasivoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();                              
        $form = $this->createFormBuilder()
            ->add('TxtNumeroRegistro', 'text', array('label'  => 'Codigo'))
            ->add('comprobanteRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                                            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))    
            ->getForm();
        $form->handleRequest($request);        
        if ($form->isValid()) {             
            if ($form->get('BtnEliminar')->isClicked()) {
                $intNumeroRegistro = $form->get('TxtNumeroRegistro')->getData();
                $arComprobante = $form->get('comprobanteRel')->getData();
                if ($arComprobante == null){
                    $codigoComprobante = "";
                } else {
                    $codigoComprobante = $arComprobante->getCodigoComprobantePk();
                }
                
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                
                $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                $arRegistros = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->listaEliminarRegistrosMasivosDql($intNumeroRegistro,$codigoComprobante,$dateFechaDesde,$dateFechaHasta);
                echo count($arRegistros);
                foreach ($arRegistros as $codigoRegistro) {
                    $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                    $arRegistro = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->find($codigoRegistro);
                    $em->remove($arRegistro);
                    $em->flush();
                }
                //$em->getRepository('BrasaContabilidadBundle:CtbRegistro')->eliminar($arrSeleccionados);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        //$arRegistros = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaContabilidadBundle:Movimiento/Registro:eliminarMasivo.html.twig', array(        
            'form' => $form->createView()));
    }
    
    
    
}