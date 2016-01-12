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
                    ->setCellValue('B1', 'PUESTO')
                    ->setCellValue('C1', 'TURNO')
                    ->setCellValue('D1', 'MODALIDAD')
                    ->setCellValue('E1', 'PERIODO')
                    ->setCellValue('F1', 'PLANTILLA')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'CANT')
                    ->setCellValue('J1', 'CANT.R')
                    ->setCellValue('K1', 'LU')
                    ->setCellValue('L1', 'MA')
                    ->setCellValue('M1', 'MI')
                    ->setCellValue('N1', 'JU')
                    ->setCellValue('O1', 'VI')
                    ->setCellValue('O1', 'SA')
                    ->setCellValue('Q1', 'DO')
                    ->setCellValue('R1', 'FE')
                    ->setCellValue('S1', 'H')
                    ->setCellValue('T1', 'H.D')
                    ->setCellValue('U1', 'H.N')
                    ->setCellValue('V1', 'DIAS')
                    ->setCellValue('W1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidosDetalles = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
        $arPedidosDetalles = $query->getResult();

        foreach ($arPedidosDetalles as $arPedidoDetalle) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedidoDetalle->getCodigoPedidoDetallePk())
                    ->setCellValue('B' . $i, $arPedidoDetalle->getPuestoRel()->getNombre())
                    ->setCellValue('C' . $i, $arPedidoDetalle->getTurnoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPedidoDetalle->getModalidadServicioRel()->getNombre())
                    ->setCellValue('E' . $i, $arPedidoDetalle->getPeriodoRel()->getNombre())
                    ->setCellValue('F' . $i, $arPedidoDetalle->getPlantillaRel()->getNombre())
                    ->setCellValue('G' . $i, $arPedidoDetalle->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arPedidoDetalle->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arPedidoDetalle->getCantidad())
                    ->setCellValue('J' . $i, $arPedidoDetalle->getCantidadRecurso())
                    ->setCellValue('K' . $i, $arPedidoDetalle->getLunes())
                    ->setCellValue('L' . $i, $arPedidoDetalle->getMartes())
                    ->setCellValue('M' . $i, $arPedidoDetalle->getMiercoles())
                    ->setCellValue('N' . $i, $arPedidoDetalle->getJueves())
                    ->setCellValue('O' . $i, $arPedidoDetalle->getViernes())
                    ->setCellValue('P' . $i, $arPedidoDetalle->getSabado())
                    ->setCellValue('Q' . $i, $arPedidoDetalle->getDomingo())
                    ->setCellValue('R' . $i, $arPedidoDetalle->getFestivo())
                    ->setCellValue('S' . $i, $arPedidoDetalle->getHoras())
                    ->setCellValue('T' . $i, $arPedidoDetalle->getHorasDiurnas())
                    ->setCellValue('U' . $i, $arPedidoDetalle->getHorasNocturnas())
                    ->setCellValue('V' . $i, $arPedidoDetalle->getDias())
                    ->setCellValue('W' . $i, $arPedidoDetalle->getVrTotal());

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