<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ConsultasServiciosDetallesController extends Controller
{
    var $strListaDql = "";
    var $codigoServicio = "";
    var $codigoCliente = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if ($form->isValid()) {    
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if($arCliente) {
                    $this->codigoCliente = $arCliente->getCodigoClientePk();
                }
            }            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        $arServiciosDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Consultas/Servicio:detalle.html.twig', array(
            'arServiciosDetalles' => $arServiciosDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalle')->listaConsultaDql(
                $this->codigoServicio, 
                $this->codigoCliente);
    }

    private function filtrar ($form) {                
        $this->codigoServicio = $form->get('TxtCodigo')->getData();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->codigoServicio))                        
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
        for($col = 'A'; $col !== 'Y'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }     
        for($col = 'Y'; $col !== 'Y'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'CLIENTE')
                    ->setCellValue('C1', 'SECTOR')             
                    ->setCellValue('D1', 'PUESTO')
                    ->setCellValue('E1', 'SERVICIO')
                    ->setCellValue('F1', 'MODALIDAD')
                    ->setCellValue('G1', 'PERIODO')
                    ->setCellValue('H1', 'PLANTILLA')
                    ->setCellValue('I1', 'DESDE')
                    ->setCellValue('J1', 'HASTA')
                    ->setCellValue('K1', 'CANT')
                    ->setCellValue('L1', 'CANT.R')
                    ->setCellValue('M1', 'LU')
                    ->setCellValue('N1', 'MA')
                    ->setCellValue('O1', 'MI')
                    ->setCellValue('P1', 'JU')
                    ->setCellValue('Q1', 'VI')
                    ->setCellValue('R1', 'SA')
                    ->setCellValue('S1', 'DO')
                    ->setCellValue('T1', 'FE')
                    ->setCellValue('U1', 'H')
                    ->setCellValue('V1', 'H.D')
                    ->setCellValue('W1', 'H.N')
                    ->setCellValue('X1', 'DIAS');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arServiciosDetalles = new \Brasa\TurnoBundle\Entity\TurServicioDetalle();
        $arServiciosDetalles = $query->getResult();

        foreach ($arServiciosDetalles as $arServicioDetalle) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicioDetalle->getCodigoServicioDetallePk())
                    ->setCellValue('B' . $i, $arServicioDetalle->getServicioRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('C' . $i, $arServicioDetalle->getServicioRel()->getSectorRel()->getNombre())                    
                    ->setCellValue('E' . $i, $arServicioDetalle->getConceptoServicioRel()->getNombre())
                    ->setCellValue('F' . $i, $arServicioDetalle->getModalidadServicioRel()->getNombre())
                    ->setCellValue('G' . $i, $arServicioDetalle->getPeriodoRel()->getNombre())
                    ->setCellValue('I' . $i, $arServicioDetalle->getDiaDesde())
                    ->setCellValue('J' . $i, $arServicioDetalle->getDiaHasta())
                    ->setCellValue('K' . $i, $arServicioDetalle->getCantidad())
                    ->setCellValue('L' . $i, $arServicioDetalle->getCantidadRecurso())
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getLunes()))
                    ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getMartes()))
                    ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getMiercoles()))
                    ->setCellValue('P' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getJueves()))
                    ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getViernes()))
                    ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getSabado()))
                    ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getDomingo()))
                    ->setCellValue('T' . $i, $objFunciones->devuelveBoolean($arServicioDetalle->getFestivo()))
                    ->setCellValue('U' . $i, $arServicioDetalle->getHoras())
                    ->setCellValue('V' . $i, $arServicioDetalle->getHorasDiurnas())
                    ->setCellValue('W' . $i, $arServicioDetalle->getHorasNocturnas())
                    ->setCellValue('X' . $i, $arServicioDetalle->getDias());
            if($arServicioDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arServicioDetalle->getPuestoRel()->getNombre());
            }
            if($arServicioDetalle->getPlantillaRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('H' . $i, $arServicioDetalle->getPlantillaRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ServiciosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ServiciosDetalles.xlsx"');
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