<?php
namespace Brasa\CarteraBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarCuentaCobrarType;
//use Brasa\CarteraBundle\Form\Type\CarCuentaCobrarDetalleType;

class MovimientoCuentaCobrarController extends Controller
{
    var $strListaDql = "";
    var $numero = "";
    var $codigoCliente = "";
    var $estadoAutorizado = "";
    
    /**
     * @Route("/cartera/movimiento/cuentacobrar/lista", name="brs_cartera_movimiento_cuentacobrar_listar")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_cuentacobrar_listar'));                
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

        $arCuentaCobrars = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Movimientos/CuentaCobrar:lista.html.twig', array(
            'arCuentaCobrars' => $arCuentaCobrars,            
            'form' => $form->createView()));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->listaDQL(
                $session->get('filtroCuentaCobrarNumero'), 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroCuentaCobrarEstadoImpreso'));
    }

    private function filtrar ($form) {       
        $session = $this->getRequest()->getSession();        
        $session->set('filtroCuentaCobrarNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroCuentaCobrarEstadoImpreso', $form->get('estadoImpreso')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());   
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCotizacionNumero')))
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('estadoImpreso', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'IMPRESO', '0' => 'SIN IMPRIMIR'), 'data' => $session->get('filtroCuentaCobrarEstadoImpreso')))                
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
                    ->setCellValue('C1', 'NIT')                
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'CUENTA')
                    ->setCellValue('F1', 'TIPO RECIBO')
                    ->setCellValue('G1', 'FECHA PAGO')
                    ->setCellValue('H1', 'TOTAL')
                    ->setCellValue('I1', 'ANULADO')
                    ->setCellValue('J1', 'AUTORIZADO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCuentaCobrars = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentaCobrars = $query->getResult();

        foreach ($arCuentaCobrars as $arCuentaCobrar) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuentaCobrar->getCodigoCuentaCobrarPk())
                    ->setCellValue('B' . $i, $arCuentaCobrar->getNumero())
                    ->setCellValue('E' . $i, $arCuentaCobrar->getCuentaRel()->getNombre())
                    ->setCellValue('F' . $i, $arCuentaCobrar->getCuentaCobrarTipoRel()->getNombre())
                    ->setCellValue('G' . $i, $arCuentaCobrar->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arCuentaCobrar->getVrTotal())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arCuentaCobrar->getEstadoAnulado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arCuentaCobrar->getEstadoAutorizado()));
            if($arCuentaCobrar->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arCuentaCobrar->getClienteRel()->getNit());
            }
            if($arCuentaCobrar->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arCuentaCobrar->getClienteRel()->getNombreCorto());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CuentaCobrars');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CuentaCobrars.xlsx"');
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