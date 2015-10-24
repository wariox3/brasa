<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDisciplinarioType;


class DisciplinarioController extends Controller
{
    var $strListaDql = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');                       
            if($form->get('BtnEliminar')->isClicked()){    
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoDisciplinario) {
                        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
                        $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
                        $em->remove($arDisciplinario);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_disciplinario_lista'));                    
                }
            }
            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();              
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }          
        
        $arDisciplinarios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Disciplinario:lista.html.twig', array('arDisciplinarios' => $arDisciplinarios, 'form' => $form->createView()));
    }   

    public function nuevoAction($codigoCentroCosto, $codigoEmpleado, $codigoDisciplinario = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        if($codigoDisciplinario != 0) {
            $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
            $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arDisciplinario->getCodigoCentroCostoFk());
        }else{
            $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arEmpleado->getCodigoCentroCostoFk());
        }         
        $form = $this->createForm(new RhuDisciplinarioType, $arDisciplinario);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arDisciplinario = $form->getData();            
            $arDisciplinario->setFecha(new \DateTime('now'));
            $arDisciplinario->setEmpleadoRel($arEmpleado);
            $arDisciplinario->setCentroCostoRel($arCentroCosto);
            $em->persist($arDisciplinario);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_disciplinario_nuevo', array('codigoCentroCosto' =>  $codigoCentroCosto, 'codigoEmpleado' => $codigoEmpleado, 'codigoDisciplinario' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $arDisciplinarios = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        return $this->render('BrasaRecursoHumanoBundle:Disciplinario:nuevo.html.twig', array(
            'arDisciplinario' => $arDisciplinario,
            'arDisciplinarios' => $arDisciplinarios,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoDisciplinario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');             
        $arCodigoTipoProceso = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arCodigoTipoProceso = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) { 
            if($form->get('BtnImprimir')->isClicked()) {
                if ($arCodigoTipoProceso->getCodigoDisciplinarioTipoFk() == 6){
                   $objFormatoDisciplinarioSuspencion = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDisciplinarioSuspension();
                   $objFormatoDisciplinarioSuspencion->Generar($this, $codigoDisciplinario); 
                }   
                if ($arCodigoTipoProceso->getCodigoDisciplinarioTipoFk() == 7) {
                    $objFormatoDisciplinarioLlamadoAtencion = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDisciplinarioLlamadoAtencion();
                    $objFormatoDisciplinarioLlamadoAtencion->Generar($this, $codigoDisciplinario);
                }   
                if ($arCodigoTipoProceso->getCodigoDisciplinarioTipoFk() == 8) {
                    $objFormatoDisciplinarioLlamadoAtencion = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDisciplinarioDescargo();
                    $objFormatoDisciplinarioLlamadoAtencion->Generar($this, $codigoDisciplinario);
                }
                if ($arCodigoTipoProceso->getCodigoDisciplinarioTipoFk() == 9) {
                    $objFormatoDisciplinarioVacaciones = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDisciplinarioVacaciones();
                    $objFormatoDisciplinarioVacaciones->Generar($this, $codigoDisciplinario);
                }
            }
 
        }                
        $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);        
        return $this->render('BrasaRecursoHumanoBundle:Disciplinario:detalle.html.twig', array(
                    'arDisciplinario' => $arDisciplinario,
                    'form' => $form->createView()
                    ));
    }                
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->listaDQL(                
                $session->get('filtroIdentificacion'),                                 
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                );  
    }   

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')                                        
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));                                    
        }
        $fechaAntigua = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->fechaAntigua();
        
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                                       
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('fechaDesde', 'date', array('label'  => 'Desde', 'data' => new \DateTime($fechaAntigua))) 
            ->add('fechaHasta', 'date', array('label'  => 'Hasta', 'data' => new \DateTime('now')))    
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();        
        return $form;
    }    
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData()->format('Y-m-d'));
        $session->set('filtroHasta', $form->get('fechaHasta')->getData()->format('Y-m-d'));
    }  
    
    private function generarExcel() {
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

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'CENTRO COSTOS')
                            ->setCellValue('D1', 'IDENTIFICACIÓN')
                            ->setCellValue('E1', 'EMPLEADO')
                            ->setCellValue('F1', 'CARGO')
                            ->setCellValue('G1', 'PROCESO')
                            ->setCellValue('H1', 'CAUSAL')
                            ->setCellValue('I1', 'DESCARGOS')
                            ->setCellValue('J1', 'FECHA SUSPENSIÓN');

                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
                $arDisciplinarios = $query->getResult();
                
                foreach ($arDisciplinarios as $arDisciplinario) {
                if ($arDisciplinario->getAsunto() == Null){
                $asunto = "NO APLICA";
                } else {
                    $asunto = $arDisciplinario->getAsunto();
                }
                if ($arDisciplinario->getDescargos() == Null){
                    $descargos = "NO APLICA";
                } else {
                    $descargos = $arDisciplinario->getDescargos();
                }
                if ($arDisciplinario->getSuspension() == Null){
                    $suspension = "NO APLICA";
                } else {
                    $suspension = $arDisciplinario->getSuspension();
                }    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDisciplinario->getCodigoDisciplinarioPk())
                            ->setCellValue('B' . $i, $arDisciplinario->getFecha()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $arDisciplinario->getCentroCostoRel()->getNombre())
                            ->setCellValue('D' . $i, $arDisciplinario->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arDisciplinario->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arDisciplinario->getEmpleadoRel()->getCargoDescripcion())
                            ->setCellValue('G' . $i, $arDisciplinario->getDisciplinarioTipoRel()->getNombre())
                            ->setCellValue('H' . $i, $asunto)
                            ->setCellValue('I' . $i, $descargos)
                            ->setCellValue('J' . $i, $suspension);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('ProcesosDisciplinarios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="ProcesosDisciplinarios.xlsx"');
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
