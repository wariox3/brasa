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
    var $estadoAnulado = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->estadoTerminado = 0;
        $this->estadoAnulado = 0;
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
            if($request->request->get('OpCerrar')) {
                $codigo = $request->request->get('OpCerrar');
                $arTarea = new \Brasa\GeneralBundle\Entity\GenTarea();
                $arTarea = $em->getRepository('BrasaGeneralBundle:GenTarea')->find($codigo);
                if($arTarea->getEstadoTerminado() == 0) {
                    $arUsuario = $this->getUser();
                    $arTarea->setEstadoTerminado(1);                    
                    $arTarea->setFechaTermina(new \DateTime('now'));
                    $arTarea->setUsuarioTerminaFk($arUsuario->getUserName());                     
                    $em->persist($arTarea);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_gen_mov_tarea_lista'));
            }
            if($request->request->get('OpAnular')) {
                $codigo = $request->request->get('OpAnular');
                $arTarea = new \Brasa\GeneralBundle\Entity\GenTarea();
                $arTarea = $em->getRepository('BrasaGeneralBundle:GenTarea')->find($codigo);
                if($arTarea->getEstadoTerminado() == 0) {
                    $arUsuario = $this->getUser();
                    $arTarea->setEstadoAnulado(1);                    
                    $arTarea->setFechaAnula(new \DateTime('now'));
                    $arTarea->setUsuarioAnulaFk($arUsuario->getUserName());                     
                    $em->persist($arTarea);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_gen_mov_tarea_lista'));
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
                $this->estadoTerminado,
                $this->estadoAnulado
        );
    }    

    private function filtrar ($form) {                
        $this->estadoTerminado = $form->get('estadoTerminado')->getData();
        $this->estadoAnulado = $form->get('estadoAnulado')->getData();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()
            ->add('estadoTerminado', 'choice', array('choices'   => array('0' => 'SIN TERMINAR', '1' => 'TERMINADA', '2' => 'TODOS'), 'data' => $this->estadoTerminado))                
            ->add('estadoAnulado', 'choice', array('choices'   => array('0' => 'SIN ANULAR', '2' => 'TODOS', '1' => 'ANULADAS'), 'data' => $this->estadoAnulado))                            
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
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'HORA')
                    ->setCellValue('D1', 'ASUNTO')
                    ->setCellValue('E1', 'COMENTARIOS')
                    ->setCellValue('F1', 'USUARIO')
                    ->setCellValue('G1', 'F.TERMINA')
                    ->setCellValue('H1', 'U.TERMINA')
                    ->setCellValue('I1', 'TERMINADO')
                    ->setCellValue('J1', 'F.ANULA')
                    ->setCellValue('K1', 'U.ANULA')
                    ->setCellValue('L1', 'ANULADO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arTareas = new \Brasa\GeneralBundle\Entity\GenTarea();
        $arTareas = $query->getResult();

        foreach ($arTareas as $arTarea) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTarea->getCodigoTareaPk())
                    ->setCellValue('B' . $i, $arTarea->getFecha()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arTarea->getHora()->format('H:i'))
                    ->setCellValue('D' . $i, $arTarea->getAsunto())
                    ->setCellValue('E' . $i, $arTarea->getComentarios())
                    ->setCellValue('F' . $i, $arTarea->getUsuarioCreaFk())                    
                    ->setCellValue('H' . $i, $arTarea->getUsuarioTerminaFk())
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arTarea->getEstadoTerminado()))                    
                    ->setCellValue('K' . $i, $arTarea->getUsuarioAnulaFk())
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arTarea->getEstadoAnulado()));
            if($arTarea->getFechaTermina()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $arTarea->getFechaTermina()->format('Y/m/d'));
            }
            if($arTarea->getFechaAnula()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $i, $arTarea->getFechaAnula()->format('Y/m/d'));
            }            
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