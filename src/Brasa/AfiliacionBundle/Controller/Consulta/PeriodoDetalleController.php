<?php
namespace Brasa\AfiliacionBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class PeriodoDetalleController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/consulta/periodo/detalle", name="brs_afi_consulta_periodo_detalle")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
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
                $this->generarExcel();
            }
        }
        
        $arPeriodoDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 60);
        return $this->render('BrasaAfiliacionBundle:Consulta/Periodo:detalle.html.twig', array(
            'arPeriodoDetalles' => $arPeriodoDetalles, 
            'form' => $form->createView()));
    }
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoDetalle')->listaConsultaDql(
                $session->get('filtroCursoNumero'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroPeriodoEstadoFacturado'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta')
                ); 
    }       

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();                        
        $session->set('filtroPeriodoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroPeriodoEstadoFacturado', $form->get('estadoFacturado')->getData());                  
        $session->set('filtroNit', $form->get('TxtNit')->getData()); 
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
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
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCursoNumero')))
            ->add('estadoFacturado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'FACTURADO', '0' => 'SIN FACTURAR'), 'data' => $session->get('filtroPeriodoEstadoFacturado')))                
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }            

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for($col = 'A'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'I'; $col !== 'R'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        } 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'COD')
                    ->setCellValue('B1', 'CLIENTE')
                    ->setCellValue('C1', 'TELEFONO')
                    ->setCellValue('D1', 'EMAIL')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'IDENTIFICACION')
                    ->setCellValue('H1', 'NOMBRE')
                    ->setCellValue('I1', 'ING')
                    ->setCellValue('J1', 'DIAS')
                    ->setCellValue('K1', 'SALARIO')
                    ->setCellValue('L1', 'PENSION')
                    ->setCellValue('M1', 'SALUD')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'RIESGO')
                    ->setCellValue('P1', 'ADMON')
                    ->setCellValue('Q1', 'TOTAL');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arPeriodoDetalles = new \Brasa\AfiliacionBundle\Entity\AfiPeriodoDetalle();
        $arPeriodoDetalles = $query->getResult();
                
        foreach ($arPeriodoDetalles as $arPeriodoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoDetalle->getCodigoPeriodoDetallePk())
                    ->setCellValue('B' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getTelefono())
                    ->setCellValue('D' . $i, $arPeriodoDetalle->getPeriodoRel()->getClienteRel()->getEmail())
                    ->setCellValue('E' . $i, $arPeriodoDetalle->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arPeriodoDetalle->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('H' . $i, $arPeriodoDetalle->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arPeriodoDetalle->getIngreso()))
                    ->setCellValue('J' . $i, $arPeriodoDetalle->getDias())
                    ->setCellValue('K' . $i, $arPeriodoDetalle->getSalario())
                    ->setCellValue('L' . $i, $arPeriodoDetalle->getContratoRel()->getEntidadPensionRel()->getNombre())
                    ->setCellValue('M' . $i, $arPeriodoDetalle->getContratoRel()->getEntidadSaludRel()->getNombre())
                    ->setCellValue('N' . $i, $arPeriodoDetalle->getContratoRel()->getEntidadCajaRel()->getNombre())
                    ->setCellValue('O' . $i, $arPeriodoDetalle->getContratoRel()->getClasificacionRiesgoRel()->getNombre())
                    ->setCellValue('P' . $i, $arPeriodoDetalle->getAdministracion())
                    ->setCellValue('Q' . $i, $arPeriodoDetalle->getTotal());                                   
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

    

}