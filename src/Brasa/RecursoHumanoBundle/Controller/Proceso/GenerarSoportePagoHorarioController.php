<?php
namespace Brasa\RecursoHumanoBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSoportePagoHorarioType;

class GenerarSoportePagoHorarioController extends Controller
{
    var $strListaDql = "";

    /**
     * @Route("/rhu/proceso/soporte/pago/horario", name="brs_rhu_proceso_soporte_pago_horario")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if($request->request->get('OpGenerar')) {
                $codigoSoportePagoHorario = $request->request->get('OpGenerar');
                $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->generar($codigoSoportePagoHorario);
            }
            if($request->request->get('OpDeshacer')) {
                $codigoSoportePagoHorario = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM rhu_soporte_pago_horario_detalle WHERE codigo_soporte_pago_horario_fk = " . $codigoSoportePagoHorario;           
                $em->getConnection()->executeQuery($strSql);
                
                $arSoportePagoHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();
                $arSoportePagoHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->find($codigoSoportePagoHorario);                                
                $arSoportePagoHorario->setEstadoGenerado(0);
                $em->persist($arSoportePagoHorario);
                $em->flush();                                                  
                return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));                
            }           
        }
        $arSoportesPagoHorario = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        //$arSoportesPagoHorarioDetalle = $paginator->paginate($em->createQuery($this->strListaDqlDetalle), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:lista.html.twig', array(
            'arSoportesPagosHorarios' => $arSoportesPagoHorario,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/rhu/proceso/soporte/pago/horario/nuevo/{codigoSoportePagoHorario}", name="brs_rhu_proceso_soporte_pago_horario_nuevo")
     */     
    public function nuevoAction($codigoSoportePagoHorario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arSoportePagoHorario = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();
        if($codigoSoportePagoHorario != 0) {
            $arSoportePagoHorario = $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->find($codigoSoportePagoHorario);
        }else{
            $arSoportePagoHorario->setFechaDesde(new \DateTime('now'));            
            $arSoportePagoHorario->setFechaHasta(new \DateTime('now'));  
        }
        $form = $this->createForm(new RhuSoportePagoHorarioType(), $arSoportePagoHorario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSoportePagoHorario = $form->getData();            
            $em->persist($arSoportePagoHorario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));                                                                              
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:nuevo.html.twig', array(
            'arSoportePagoHorario' => $arSoportePagoHorario,
            'form' => $form->createView()));
    }        
    
    /**
     * @Route("/rhu/proceso/soporte/pago/horario/detalle/{codigoSoportePagoHorario}", name="brs_rhu_proceso_soporte_pago_horario_detalle")
     */    
    public function detalleAction($codigoSoportePagoHorario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDetalle();
        $form->handleRequest($request);
        $this->listaDetalle($codigoSoportePagoHorario);
        if ($form->isValid()) {           
            if ($form->get('BtnExcel')->isClicked()) {
                //$this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }    
            if ($form->get('BtnGenerar')->isClicked()) {
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                $arEmpleadosPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->resumenEmpleado($dateFechaDesde->format('Y/m/d'), $dateFechaHasta->format('Y/m/d'));                
                
                foreach ($arEmpleadosPeriodo as $arEmpleadoPeriodo ) {
                   
                }                
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_proceso_soporte_pago_horario'));
            }
        }
        $arSoportesPagoHorarioDetalle = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarSoportePagoHorario:detalle.html.twig', array(
            'arSoportesPagosHorariosDetalles' => $arSoportesPagoHorarioDetalle,
            'form' => $form->createView()));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorario')->listaDql();        
    }
    
    private function listaDetalle($codigoSoportePagoHorario) {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuSoportePagoHorarioDetalle')->listaDql($codigoSoportePagoHorario);        
    }    
    
    private function formularioLista() {
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))            
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle() {
        $form = $this->createFormBuilder()
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))                        
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'DÍAS')
                    ->setCellValue('E1', 'DESCANSO')
                    ->setCellValue('F1', 'HD')
                    ->setCellValue('G1', 'HN')
                    ->setCellValue('H1', 'HFD')
                    ->setCellValue('I1', 'HFN')                
                    ->setCellValue('J1', 'HEOD')
                    ->setCellValue('K1', 'HEON')
                    ->setCellValue('L1', 'HEFD')
                    ->setCellValue('M1', 'HEFN');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arSoportesPagoHorarios = new \Brasa\RecursoHumanoBundle\Entity\RhuSoportePagoHorario();
        $arSoportesPagoHorarios = $query->getResult();

        foreach ($arSoportesPagoHorarios as $arSoportesPagoHorario) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSoportesPagoHorario->getCodigoSoportePagoHorarioPk())
                    ->setCellValue('B' . $i, $arSoportesPagoHorario->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arSoportesPagoHorario->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arSoportesPagoHorario->getDias())
                    ->setCellValue('E' . $i, $arSoportesPagoHorario->getDescanso())
                    ->setCellValue('F' . $i, $arSoportesPagoHorario->getHorasDiurnas())
                    ->setCellValue('G' . $i, $arSoportesPagoHorario->getHorasNocturnas())
                    ->setCellValue('H' . $i, $arSoportesPagoHorario->getHorasFestivasDiurnas())
                    ->setCellValue('I' . $i, $arSoportesPagoHorario->getHorasFestivasNocturnas())                    
                    ->setCellValue('J' . $i, $arSoportesPagoHorario->getHorasExtrasOrdinariasDiurnas())
                    ->setCellValue('K' . $i, $arSoportesPagoHorario->getHorasExtrasOrdinariasNocturnas())
                    ->setCellValue('L' . $i, $arSoportesPagoHorario->getHorasExtrasFestivasDiurnas())
                    ->setCellValue('M' . $i, $arSoportesPagoHorario->getHorasExtrasFestivasNocturnas());

            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('SoportePagoHorario');        
        
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SoportePagoHorario.xlsx"');
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