<?php

namespace Brasa\ContabilidadBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class RegistrosController extends Controller
{
    var $strDqlLista = "";
    var $strNumero = "";
    var $strNumeroReferencia = "";
    var $strComprobante = "";
    var $strDesde = "";
    var $strHasta = "";
    
    /**
     * @Route("/ctb/consultas/registros/", name="brs_ctb_consultas_registros")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 35)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request);
                $this->listar();
                $this->generarExcel();
            }            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request);
                $this->listar();
            }

        }
        $arRegistros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 100);
        return $this->render('BrasaContabilidadBundle:Consulta/Registro:lista.html.twig', array(
            'arRegistros' => $arRegistros,
            'form' => $form->createView()
            ));
    }    
    
    private function listar() {        
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->listaDql(
                    $session->get('filtroCodigoComprobante'),
                    $session->get('filtroNumero'),
                    $session->get('filtroNumeroReferencia'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();                
        $form = $this->createFormBuilder()
            ->add('TxtComprobante', 'text', array('label'  => 'Comprobante'))
            ->add('TxtNumero', 'text', array('label'  => 'Numero'))
            ->add('TxtNumeroReferencia', 'text', array('label'  => 'Numero referencia'))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }    

    private function filtrar($form, Request $request) {
        $session = $this->get('session');  
        $controles = $request->request->get('form');
        $session->set('filtroCodigoComprobante', $controles['TxtComprobante']);                
        $session->set('filtroNumero', $controles['TxtNumero']);
        $session->set('filtroNumeroReferencia', $controles['TxtNumeroReferencia']);
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }   

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
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
        for($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'L'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'REFERENCIA')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'COMPROBANTE')
                    ->setCellValue('F1', 'CUENTA')
                    ->setCellValue('G1', 'NIT')
                    ->setCellValue('H1', 'TERCERO')
                    ->setCellValue('I1', 'DEBITO')
                    ->setCellValue('J1', 'CREDITO')
                    ->setCellValue('K1', 'BASE')
                    ->setCellValue('L1', 'C.COSTO')
                    ->setCellValue('M1', 'DETALLE');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
        $arRegistros = $query->getResult();
        foreach ($arRegistros as $arRegistro) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistro->getCodigoRegistroPk())
                    ->setCellValue('B' . $i, $arRegistro->getNumero())
                    ->setCellValue('C' . $i, $arRegistro->getNumeroReferencia())
                    ->setCellValue('D' . $i, $arRegistro->getFecha()->Format('Y-m-d'))
                    ->setCellValue('E' . $i, $arRegistro->getCodigoComprobanteFk())
                    ->setCellValue('F' . $i, $arRegistro->getCodigoCuentaFk())                    
                    ->setCellValue('I' . $i, $arRegistro->getDebito())
                    ->setCellValue('J' . $i, $arRegistro->getCredito())
                    ->setCellValue('K' . $i, $arRegistro->getBase())
                    ->setCellValue('L' . $i, $arRegistro->getCodigoCentroCostoFk())
                    ->setCellValue('M' . $i, $arRegistro->getDescripcionContable());            
            if($arRegistro->getTerceroRel()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $arRegistro->getTerceroRel()->getNumeroIdentificacion());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arRegistro->getTerceroRel()->getNombreCorto());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('registros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RegistrosContables.xlsx"');
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
