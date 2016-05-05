<?php
namespace Brasa\CarteraBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ConsultaCuentaCobrarController extends Controller
{
    var $strListaDql = "";
    var $strFechaDesde = "";
    var $strFechaHasta = "";

    /**
     * @Route("/cartera/consulta/cuentacobrar/lista", name="brs_cartera_consulta_cuentacobrar_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        //$this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
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

        $arCuentasCobrar = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/CuentasCobrar:lista.html.twig', array(
            'arCuentasCobrar' => $arCuentasCobrar,
            'form' => $form->createView()));
    }

    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->listaConsultaDql(
                $session->get('filtroNumero'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroCuentaCobrarTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $arCuentaCobrarTipo = $form->get('cuentaCobrarTipoRel')->getData();
        if ($arCuentaCobrarTipo == null){
            $codigo = "";
        } else {
            $codigo = $arCuentaCobrarTipo->getCodigoCuentaCobrarTipoPk();
        }
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroCuentaCobrarTipo', $codigo);
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());

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
        $arrayPropiedades = array(
                'class' => 'BrasaCarteraBundle:CarCuentaCobrarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCuentaCobrarTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarCuentaCobrarTipo", $session->get('filtroCuentaCobrarTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))
            ->add('cuentaCobrarTipoRel', 'entity', $arrayPropiedades)
            //->add('fechaDesde', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            //->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
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
        for($col = 'A'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        for($col = 'I'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'VENCE')
                    ->setCellValue('F1', 'NIT')
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'ASESOR')
                    ->setCellValue('I1', 'VALOR')
                    ->setCellValue('J1', 'SALDO')
                    ->setCellValue('K1', 'PLAZO')
                    ->setCellValue('L1', 'ABONO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCuentasCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
        $arCuentasCobrar = $query->getResult();

        foreach ($arCuentasCobrar as $arCuentasCobrar) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCuentasCobrar->getCodigoCuentaCobrarPk())
                    ->setCellValue('B' . $i, $arCuentasCobrar->getNumeroDocumento())
                    ->setCellValue('D' . $i, $arCuentasCobrar->getFecha()->format('Y-m-d'))
                    ->setCellValue('E' . $i, $arCuentasCobrar->getFechaVence()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arCuentasCobrar->getValorOriginal())
                    ->setCellValue('J' . $i, $arCuentasCobrar->getSaldo())
                    ->setCellValue('K' . $i, $arCuentasCobrar->getPlazo())
                    ->setCellValue('L' . $i, $arCuentasCobrar->getAbono());
            if($arCuentasCobrar->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $arCuentasCobrar->getClienteRel()->getNit());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $arCuentasCobrar->getClienteRel()->getNombreCorto());
            }
            if($arCuentasCobrar->getAsesorRel()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arCuentasCobrar->getAsesorRel()->getNombre());                
            }            
            if($arCuentasCobrar->getCuentaCobrarTipoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arCuentasCobrar->getCuentaCobrarTipoRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CuentasCobrar');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CuentasCobrar.xlsx"');
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