<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ConsultasServiciosDetallesRecursosController extends Controller
{
    var $strListaDql = "";
    var $codigoServicio = "";
    
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
        $arServiciosDetallesRecursos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Consultas/Servicio:detalleRecurso.html.twig', array(
            'arServiciosDetallesRecursos' => $arServiciosDetallesRecursos,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->listaConsultaDql();
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
        for($col = 'A'; $col !== 'AE'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }  
        $objPHPExcel->setActiveSheetIndex(0)              
                    ->setCellValue('A1', 'CLIENTE')
                    ->setCellValue('B1', 'PUESTO')
                    ->setCellValue('C1', 'SERVICIO')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'POSICION')
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'SECTOR')             
                    ->setCellValue('H1', 'PUESTO')
                    ->setCellValue('I1', 'SERVICIO')
                    ->setCellValue('J1', 'MODALIDAD')
                    ->setCellValue('K1', 'PERIODO')
                    ->setCellValue('L1', 'PLANTILLA')
                    ->setCellValue('M1', 'DESDE')
                    ->setCellValue('N1', 'HASTA')
                    ->setCellValue('O1', 'CANT')
                    ->setCellValue('P1', 'CANT.R')
                    ->setCellValue('Q1', 'LU')
                    ->setCellValue('R1', 'MA')
                    ->setCellValue('S1', 'MI')
                    ->setCellValue('T1', 'JU')
                    ->setCellValue('U1', 'VI')
                    ->setCellValue('V1', 'SA')
                    ->setCellValue('W1', 'DO')
                    ->setCellValue('X1', 'FE')
                    ->setCellValue('Y1', 'H')
                    ->setCellValue('Z1', 'H.D')
                    ->setCellValue('AA1', 'H.N')
                    ->setCellValue('AB1', 'DIAS')
                    ->setCellValue('AC1', 'COSTO')
                    ->setCellValue('AD1', 'VR.MINIMO')
                    ->setCellValue('AE1', 'VR.AJUSTADO')
                    ->setCellValue('AF1', 'VALOR');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arServiciosDetallesRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
        $arServiciosDetallesRecursos = $query->getResult();

        foreach ($arServiciosDetallesRecursos as $arServicioDetalleRecurso) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicioDetalleRecurso->getCodigoServicioDetalleRecursoPk())                    
                    ->setCellValue('B' . $i, $arServicioDetalleRecurso->getCodigoRecursoFk())
                    ->setCellValue('C' . $i, $arServicioDetalleRecurso->getRecursoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arServicioDetalleRecurso->getPosicion())
                    ->setCellValue('E' . $i, $arServicioDetalleRecurso->getCodigoServicioDetalleFk())
                    ->setCellValue('F' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getServicioRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getServicioRel()->getSectorRel()->getNombre())                    
                    ->setCellValue('I' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getConceptoServicioRel()->getNombre())
                    ->setCellValue('J' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getModalidadServicioRel()->getNombre())
                    ->setCellValue('K' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPeriodoRel()->getNombre())
                    ->setCellValue('M' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getDiaDesde())
                    ->setCellValue('N' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getDiaHasta())
                    ->setCellValue('O' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getCantidad())
                    ->setCellValue('P' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getCantidadRecurso())
                    ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getLunes()))
                    ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getMartes()))
                    ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getMiercoles()))
                    ->setCellValue('T' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getJueves()))
                    ->setCellValue('U' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getViernes()))
                    ->setCellValue('V' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getSabado()))
                    ->setCellValue('W' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getDomingo()))
                    ->setCellValue('X' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getFestivo()))
                    ->setCellValue('Y' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getHoras())
                    ->setCellValue('Z' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getHorasDiurnas())
                    ->setCellValue('AA' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getHorasNocturnas())
                    ->setCellValue('AB' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getDias())
                    ->setCellValue('AC' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getVrCostoCalculado())
                    ->setCellValue('AD' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getVrTotalMinimo())
                    ->setCellValue('AE' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getVrTotalAjustado())
                    ->setCellValue('AF' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getVrTotal());
            if($arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('H' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()->getNombre());
            }
            if($arServicioDetalleRecurso->getServicioDetalleRel()->getPlantillaRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('L' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPlantillaRel()->getNombre());
            }
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('DetRecurso');     
        $objPHPExcel->createSheet(2)->setTitle('Detalle')
                    ->setCellValue('A1', 'CLIENTE')
                    ->setCellValue('B1', 'SERVICIO')
                    ->setCellValue('C1', 'PUESTO')                
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'POSICION');
        $i = 2;
        foreach ($arServiciosDetallesRecursos as $arServicioDetalleRecurso) {   
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getServicioRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('B' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getConceptoServicioRel()->getNombre())
                    ->setCellValue('D' . $i, $arServicioDetalleRecurso->getRecursoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arServicioDetalleRecurso->getPosicion());
            if($arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('C' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()->getNombre());
            }
            $i++;
        }        
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);        
 
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