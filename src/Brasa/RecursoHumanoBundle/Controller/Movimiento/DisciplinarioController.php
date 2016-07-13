<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDisciplinarioType;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDisciplinarioDescargoType;

class DisciplinarioController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/rhu/movimiento/disciplinario/", name="brs_rhu_movimiento_disciplinario")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
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
                        if ($arDisciplinario->getEstadoAutorizado() == 0){
                            $em->remove($arDisciplinario);
                        }else{
                            $objMensaje->Mensaje("error", "El proceso número ".$codigoDisciplinario. ", no se puede eliminar, se encuentra autorizado", $this);
                        }   
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario'));
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
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:lista.html.twig', array('arDisciplinarios' => $arDisciplinarios, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/disciplinario/nuevo/{codigoDisciplinario}", name="brs_rhu_movimiento_disciplinario_nuevo")
     */    
    public function nuevoAction($codigoDisciplinario = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        if($codigoDisciplinario != 0) {
            $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        } else {
            $arDisciplinario->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuDisciplinarioType, $arDisciplinario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arDisciplinario = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arDisciplinario->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        $arDisciplinario->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                        $arDisciplinario->setCargoRel($arEmpleado->getCargoRel());
                        if($codigoDisciplinario == 0) {
                            $arDisciplinario->setCodigoUsuario($arUsuario->getUserName());
                        }
                        $em->persist($arDisciplinario);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_nuevo', array('codigoDisciplinario' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario'));
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:nuevo.html.twig', array(
            'arDisciplinario' => $arDisciplinario,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimiento/disciplinario/detalle/{codigoDisciplinario}", name="brs_rhu_movimiento_disciplinario_detalle")
     */    
    public function detalleAction($codigoDisciplinario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arProcesoDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arProcesoDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        $form = $this->formularioDetalle($arProcesoDisciplinario);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arProcesoDisciplinario->getEstadoAutorizado() == 0) {
                    $arProcesoDisciplinario->setEstadoAutorizado(1);
                    $em->persist($arProcesoDisciplinario);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $codigoDisciplinario)));                                                
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arProcesoDisciplinario->getEstadoAutorizado() == 1) {
                    $arProcesoDisciplinario->setEstadoAutorizado(0);
                    $em->persist($arProcesoDisciplinario);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $codigoDisciplinario)));                                                
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arProcesoDisciplinario->getEstadoAutorizado() == 1) {
                    $codigoProcesoDisciplinarioTipo = $arProcesoDisciplinario->getCodigoDisciplinarioTipoFk();
                    $codigoProcesoDisciplinario = $arProcesoDisciplinario->getCodigoDisciplinarioPk();
                    $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoProcesoDisciplinario();
                    $objFormatoCarta->Generar($this, $codigoProcesoDisciplinarioTipo, $codigoProcesoDisciplinario);
                }    
            }
            if($form->get('BtnCerrar')->isClicked()) {
                if($arProcesoDisciplinario->getEstadoAutorizado() == 1) {
                    if($arProcesoDisciplinario->getEstado() == 0) {
                        $arProcesoDisciplinario->setEstado(1);
                        $em->persist($arProcesoDisciplinario);
                    } else {
                        $arProcesoDisciplinario->setEstado(0);
                        $em->persist($arProcesoDisciplinario);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_disciplinario_detalle', array('codigoDisciplinario' => $codigoDisciplinario)));                                                
                } else {
                    $objMensaje = "Debe estar autorizado";
                }    
            }

        }
        $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        $arDescargos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioDescargo')->findBy(array('codigoDisciplinarioFk' => $codigoDisciplinario));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:detalle.html.twig', array(
                    'arDisciplinario' => $arDisciplinario,
                    'arDescargos' => $arDescargos,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/movimiento/disciplinario/descargo/nuevo/{codigoDisciplinario}/{codigoDisciplinarioDescargo}", name="brs_rhu_movimiento_disciplinario_descargo_nuevo")
     */     
    public function nuevoDescargoAction($codigoDisciplinario, $codigoDisciplinarioDescargo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDisciplinario = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinario = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->find($codigoDisciplinario);
        $arDisciplinarioDescargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioDescargo();
        if($codigoDisciplinarioDescargo != 0) {
            $arDisciplinarioDescargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinarioDescargo')->find($codigoDisciplinarioDescargo);
        }
        $form = $this->createForm(new RhuDisciplinarioDescargoType(), $arDisciplinarioDescargo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arDisciplinarioDescargo = $form->getData();
            $arDisciplinarioDescargo->setDisciplinarioRel($arDisciplinario);
            $em->persist($arDisciplinarioDescargo);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Disciplinario:nuevoDescargo.html.twig', array(
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

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
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
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar / abrir', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);               
        $arrBotonEliminarDescargo = array('label' => 'Eliminar descargo', 'disabled' => false);               
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;
        }
        
        $form = $this->createFormBuilder()    
            ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
            ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
            ->add('BtnCerrar', 'submit', $arrBotonCerrar)
            ->add('BtnImprimir', 'submit', $arrBotonImprimir)                                            
            ->add('BtnEliminarDescargo', 'submit', $arrBotonEliminarDescargo)                                            
            ->getForm();  
        return $form;
    }

    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
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
                for($col = 'A'; $col !== 'AR'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'CENTRO COSTOS')
                            ->setCellValue('C1', 'IDENTIFICACIÓN')
                            ->setCellValue('D1', 'EMPLEADO')
                            ->setCellValue('E1', 'CARGO')
                            ->setCellValue('F1', 'PUESTO')
                            ->setCellValue('G1', 'OPERACION')
                            ->setCellValue('H1', 'ZONA')
                            ->setCellValue('I1', 'PROCESO')
                            ->setCellValue('J1', 'CAUSAL O MOTIVO')
                            ->setCellValue('K1', 'DESCARGOS')
                            ->setCellValue('L1', 'FECHA DEL INCIDENTE')
                            ->setCellValue('M1', 'FECHA PROCESO INICIO')
                            ->setCellValue('N1', 'FECHA PROCESO HASTA')
                            ->setCellValue('O1', 'DÍAS SANCIÓN')
                            ->setCellValue('P1', 'FECHA INGRESO TRABAJO')
                            ->setCellValue('Q1', 'REENTRENAMIENTO')
                            ->setCellValue('R1', 'AUTORIZADO')
                            ->setCellValue('S1', 'ABIERTO')
                            ->setCellValue('T1', 'OBSERVACIONES');

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
                if ($arDisciplinario->getFechaIncidente() == Null){
                    $fechaIncidente = "NO APLICA";
                } else {
                    $fechaIncidente = $arDisciplinario->getFechaIncidente();
                }
                if ($arDisciplinario->getFechaAplicaProceso() == Null){
                    $fechaProceso = "NO APLICA";
                } else {
                    $fechaProceso = $arDisciplinario->getFechaAplicaProceso();
                }
                if ($arDisciplinario->getFechaAplicaHastaProceso() == Null){
                    $fechaProcesoHasta = "NO APLICA";
                } else {
                    $fechaProcesoHasta = $arDisciplinario->getFechaAplicaHastaProceso();
                }
                if ($arDisciplinario->getFechaIngresoTrabajo() == Null){
                    $fechaIngresoTrabajo = "NO APLICA";
                } else {
                    $fechaIngresoTrabajo = $arDisciplinario->getFechaIngresoTrabajo();
                }
                if ($arDisciplinario->getEstadoAutorizado() == 1){
                    $autorizado = "SI";
                } else {
                    $autorizado = "NO";
                }
                if ($arDisciplinario->getReentrenamiento() == 1){
                    $reentrenamiento = "SI";
                } else {
                    $reentrenamiento = "NO";
                }
                if ($arDisciplinario->getEstado() == 1){
                    $estado = "NO";
                } else {
                    $estado = "SI";
                }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDisciplinario->getCodigoDisciplinarioPk())
                            ->setCellValue('B' . $i, $arDisciplinario->getCentroCostoRel()->getNombre())
                            ->setCellValue('C' . $i, $arDisciplinario->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('D' . $i, $arDisciplinario->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('E' . $i, $arDisciplinario->getEmpleadoRel()->getCargoDescripcion())
                            ->setCellValue('F' . $i, $arDisciplinario->getPuesto())
                            ->setCellValue('G' . $i, $arDisciplinario->getZona())
                            ->setCellValue('H' . $i, $arDisciplinario->getOperacion())
                            ->setCellValue('I' . $i, $arDisciplinario->getDisciplinarioTipoRel()->getNombre())
                            ->setCellValue('J' . $i, $asunto)
                            ->setCellValue('K' . $i, $descargos)
                            ->setCellValue('L' . $i, $fechaIncidente)
                            ->setCellValue('M' . $i, $fechaProceso)
                            ->setCellValue('N' . $i, $fechaProcesoHasta)
                            ->setCellValue('O' . $i, $arDisciplinario->getDiasSuspencion())
                            ->setCellValue('P' . $i, $fechaIngresoTrabajo)
                            ->setCellValue('Q' . $i, $reentrenamiento)
                            ->setCellValue('R' . $i, $autorizado)
                            ->setCellValue('S' . $i, $estado)
                            ->setCellValue('T' . $i, $arDisciplinario->getComentarios());
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
