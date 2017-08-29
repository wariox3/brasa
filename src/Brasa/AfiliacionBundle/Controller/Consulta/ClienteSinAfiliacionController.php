<?php

namespace Brasa\AfiliacionBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;

class ClienteSinAfiliacionController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/afi/consulta/cliente/sin/afiliaciones", name="brs_afi_consulta_clientes_sin_afiliaciones")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
//        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 103)) {
//            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
//        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }

        $arContratos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 70);
        $arFechas = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->fechaMayor();
        return $this->render('BrasaAfiliacionBundle:Consulta/Contrato:clienteSinAfiliacion.html.twig', array(
                    'arContratos' => $arContratos,
                    'arFechas' => $arFechas,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $codigoCliente = "";
        $arAfiCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
        if ($arAfiCliente) {
            $codigoCliente = $arAfiCliente->getCodigoClientePk();
        }
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaConsultaClienteSinAfiliacionesDql(
                $codigoCliente);
    }

//
    private function filtrar($form) {
        $session = new session;
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $this->lista();
    }

//    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $form = $this->createFormBuilder()
                ->add('TxtNit', textType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', textType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

//
//    private function generarExcel() {
//        ob_clean();
//        set_time_limit(0);
//        ini_set("memory_limit", -1);
//        $em = $this->getDoctrine()->getManager();
//        $session = new session;
//        $objPHPExcel = new \PHPExcel();
//        // Set document properties
//        $objPHPExcel->getProperties()->setCreator("EMPRESA")
//            ->setLastModifiedBy("EMPRESA")
//            ->setTitle("Office 2007 XLSX Test Document")
//            ->setSubject("Office 2007 XLSX Test Document")
//            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
//            ->setKeywords("office 2007 openxml php")
//            ->setCategory("Test result file");
//        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
//        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
//        for($col = 'A'; $col !== 'J'; $col++) {
//            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
//        }
//        for($col = 'K'; $col !== 'N'; $col++) {
//                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
//                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
//        }
//        $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A1', 'CODIGO')
//                    ->setCellValue('B1', 'NUMERO')
//                    ->setCellValue('C1', 'FECHA PAGO')
//                    ->setCellValue('D1', 'NIT')
//                    ->setCellValue('E1', 'CLIENTE')
//                    ->setCellValue('F1', 'ASESOR')
//                    ->setCellValue('G1', 'DOCUMENTO')
//                    ->setCellValue('H1', 'EMPLEADO')
//                    ->setCellValue('I1', 'CONTRATO')
//                    ->setCellValue('J1', 'CUENTA')
//                    ->setCellValue('K1', 'AFILIACION')
//                    ->setCellValue('L1', 'ADMINISTRACION')
//                    ->setCellValue('M1', 'PAGO')
//                    ->setCellValue('N1', 'TIPO');
//        $i = 2;
//                        
//        $arDetalles = $this->strDqlLista;
//        
//        foreach ($arDetalles as $arDetalle) {
//
//            if ($arDetalle['tipo'] == 1){
//                $tipo = "REINGRESO";
//            } else {
//                $tipo = "NUEVO";
//            }
//            $objPHPExcel->setActiveSheetIndex(0)
//                    ->setCellValue('A' . $i, $arDetalle['codigo'])
//                    ->setCellValue('B' . $i, $arDetalle['numero'])
//                    ->setCellValue('C' . $i, $arDetalle['fechaPago'])
//                    ->setCellValue('D' . $i, $arDetalle['nit'])
//                    ->setCellValue('E' . $i, $arDetalle['cliente'])
//                    ->setCellValue('F' . $i, $arDetalle['asesor'])
//                    ->setCellValue('G' . $i, $arDetalle['ccEmpleado'])
//                    ->setCellValue('H' . $i, $arDetalle['empleado'])
//                    ->setCellValue('I' . $i, $arDetalle['contrato'])
//                    ->setCellValue('J' . $i, $arDetalle['cuenta'])
//                    ->setCellValue('K' . $i, $arDetalle['afiliacion'])
//                    ->setCellValue('L' . $i, $arDetalle['administracion'])
//                    ->setCellValue('M' . $i, $arDetalle['pago'])
//                    ->setCellValue('N' . $i, $tipo)                    ;
//            $i++;
//        }
//        
//        $objPHPExcel->getActiveSheet()->setTitle('PagoAfiliacionesAnticipos');
//        $objPHPExcel->setActiveSheetIndex(0);
//        // Redirect output to a client’s web browser (Excel2007)
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="PagoAfiliacionesAnticipos.xlsx"');
//        header('Cache-Control: max-age=0');
//        // If you're serving to IE 9, then the following may be needed
//        header('Cache-Control: max-age=1');
//        // If you're serving to IE over SSL, then the following may be needed
//        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
//        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header ('Pragma: public'); // HTTP/1.0
//        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
//        $objWriter->save('php://output');
//        exit;
//    }
}
