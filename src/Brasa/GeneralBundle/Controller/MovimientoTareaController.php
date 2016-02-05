<?php
namespace Brasa\GeneralBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\GeneralBundle\Form\Type\GenTareaType;

class MovimientoTareaController extends Controller
{
    var $strListaDql = "";    
    var $estadoTerminado = "";

    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();        
        if ($form->isValid()) { 
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaGeneralBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if($arCliente) {
                    $this->codigoCliente = $arCliente->getCodigoClientePk();
                }
            }            
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaGeneralBundle:GenTarea')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_pedido_lista'));                 
                
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

        $arTareas = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaGeneralBundle:Movimientos/Tarea:lista.html.twig', array(
            'arTareas' => $arTareas,            
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoTarea) {
        $request = $this->getRequest();        
        $em = $this->getDoctrine()->getManager();
        $arTarea = new \Brasa\GeneralBundle\Entity\GenTarea();
        if($codigoTarea != 0) {
            $arTarea = $em->getRepository('BrasaGeneralBundle:GenTarea')->find($codigoTarea);
        }else{
            $arTarea->setFecha(new \DateTime('now'));                       
        }
        $form = $this->createForm(new GenTareaType, $arTarea);
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arUsuario = $this->getUser();
            $arTarea = $form->getData();  
            $arTarea->setUsuarioCreaFk($arUsuario->getUserName());           
            $em->persist($arTarea);          
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_gen_mov_tarea_nuevo', array('codigoTarea' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_gen_mov_tarea_lista'));
            }                                                                       
        }
        return $this->render('BrasaGeneralBundle:Movimientos/Tarea:nuevo.html.twig', array(
            'arTarea' => $arTarea,
            'form' => $form->createView()));
    }  
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaGeneralBundle:GenTarea')->listaDQL(
        );
    }    

    private function filtrar ($form) {                
        $this->numeroTarea = $form->get('TxtNumero')->getData();
        $this->estadoAutorizado = $form->get('estadoAutorizado')->getData();
        $this->estadoProgramado = $form->get('estadoProgramado')->getData();
        $this->estadoFacturado = $form->get('estadoFacturado')->getData();
        $this->estadoAnulado = $form->get('estadoAnulado')->getData();
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $this->fechaDesde = $dateFechaDesde->format('Y/m/d');
        $this->fechaHasta = $dateFechaHasta->format('Y/m/d');   
        $this->filtrarFecha = $form->get('filtrarFecha')->getData();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()
            ->add('estadoTerminado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'TERMINADA', '0' => 'SIN TERMINAR'), 'data' => $this->estadoTerminado))                
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
        $arTareas = new \Brasa\GeneralBundle\Entity\GenTarea();
        $arTareas = $query->getResult();

        foreach ($arTareas as $arTarea) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTarea->getCodigoTareaPk())
                    ->setCellValue('B' . $i, $arTarea->getTareaTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arTarea->getNumero())
                    ->setCellValue('D' . $i, $arTarea->getFecha()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arTarea->getFechaProgramacion()->format('Y'))
                    ->setCellValue('F' . $i, $arTarea->getFechaProgramacion()->format('F'))                    
                    ->setCellValue('G' . $i, $arTarea->getClienteRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arTarea->getSectorRel()->getNombre())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arTarea->getEstadoAutorizado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arTarea->getEstadoProgramado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arTarea->getEstadoFacturado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arTarea->getEstadoAnulado()))
                    ->setCellValue('M' . $i, $arTarea->getHoras())
                    ->setCellValue('N' . $i, $arTarea->getHorasDiurnas())
                    ->setCellValue('O' . $i, $arTarea->getHorasNocturnas())
                    ->setCellValue('P' . $i, $arTarea->getVrTotalPrecioMinimo())
                    ->setCellValue('Q' . $i, $arTarea->getVrTotalPrecioAjustado())
                    ->setCellValue('R' . $i, $arTarea->getVrTotal());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Tareas');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Tareas.xlsx"');
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