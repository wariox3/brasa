<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ConsultasPedidosDetallesController extends Controller
{
    var $strListaDql = "";
    var $codigoPedido = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {            
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
        
        $arPedidosDetalles = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Consultas/Pedido:detalle.html.twig', array(
            'arPedidosDetalles' => $arPedidosDetalles,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->listaConsultaDql();
    }

    private function filtrar ($form) {                
        $this->codigoPedido = $form->get('TxtCodigo')->getData();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->codigoPedido))                        
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NUMERO')
                    ->setCellValue('D1', 'FECHA')
                    ->setCellValue('E1', 'FH PROG')
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'SECTOR')
                    ->setCellValue('H1', 'PROGRAMADO')               
                    ->setCellValue('I1', 'PUESTO')
                    ->setCellValue('J1', 'TURNO')
                    ->setCellValue('K1', 'MODALIDAD')
                    ->setCellValue('L1', 'PERIODO')
                    ->setCellValue('M1', 'PLANTILLA')
                    ->setCellValue('N1', 'DESDE')
                    ->setCellValue('O1', 'HASTA')
                    ->setCellValue('P1', 'CANT')
                    ->setCellValue('Q1', 'CANT.R')
                    ->setCellValue('R1', 'LU')
                    ->setCellValue('S1', 'MA')
                    ->setCellValue('T1', 'MI')
                    ->setCellValue('U1', 'JU')
                    ->setCellValue('V1', 'VI')
                    ->setCellValue('W1', 'SA')
                    ->setCellValue('X1', 'DO')
                    ->setCellValue('Y1', 'FE')
                    ->setCellValue('Z1', 'H')
                    ->setCellValue('AA1', 'H.D')
                    ->setCellValue('AB1', 'H.N')
                    ->setCellValue('AC1', 'DIAS')
                    ->setCellValue('AD1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidosDetalles = $query->getResult();

        foreach ($arPedidosDetalles as $arPedidoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedidoDetalle->getCodigoPedidoDetallePk())
                    ->setCellValue('B' . $i, $arPedidoDetalle->getPedidoRel()->getPedidoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPedidoDetalle->getPedidoRel()->getNumero())
                    ->setCellValue('D' . $i, $arPedidoDetalle->getPedidoRel()->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arPedidoDetalle->getPedidoRel()->getFechaProgramacion()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arPedidoDetalle->getPedidoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arPedidoDetalle->getPedidoRel()->getSectorRel()->getNombre())
                    ->setCellValue('H' . $i, $arPedidoDetalle->getPedidoRel()->getEstadoProgramado())                    
                    ->setCellValue('J' . $i, $arPedidoDetalle->getTurnoRel()->getNombre())
                    ->setCellValue('K' . $i, $arPedidoDetalle->getModalidadServicioRel()->getNombre())
                    ->setCellValue('L' . $i, $arPedidoDetalle->getPeriodoRel()->getNombre())
                    ->setCellValue('M' . $i, $arPedidoDetalle->getPlantillaRel()->getNombre())
                    ->setCellValue('N' . $i, $arPedidoDetalle->getDiaDesde())
                    ->setCellValue('O' . $i, $arPedidoDetalle->getDiaHasta())
                    ->setCellValue('P' . $i, $arPedidoDetalle->getCantidad())
                    ->setCellValue('Q' . $i, $arPedidoDetalle->getCantidadRecurso())
                    ->setCellValue('R' . $i, $arPedidoDetalle->getLunes())
                    ->setCellValue('S' . $i, $arPedidoDetalle->getMartes())
                    ->setCellValue('T' . $i, $arPedidoDetalle->getMiercoles())
                    ->setCellValue('U' . $i, $arPedidoDetalle->getJueves())
                    ->setCellValue('V' . $i, $arPedidoDetalle->getViernes())
                    ->setCellValue('W' . $i, $arPedidoDetalle->getSabado())
                    ->setCellValue('X' . $i, $arPedidoDetalle->getDomingo())
                    ->setCellValue('Y' . $i, $arPedidoDetalle->getFestivo())
                    ->setCellValue('Z' . $i, $arPedidoDetalle->getHoras())
                    ->setCellValue('AA' . $i, $arPedidoDetalle->getHorasDiurnas())
                    ->setCellValue('AB' . $i, $arPedidoDetalle->getHorasNocturnas())
                    ->setCellValue('AC' . $i, $arPedidoDetalle->getDias())
                    ->setCellValue('AD' . $i, $arPedidoDetalle->getVrTotal());
            if($arPedidoDetalle->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('I' . $i, $arPedidoDetalle->getPuestoRel()->getNombre());
            }
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PedidosDetalles');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PedidosDetalles.xlsx"');
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