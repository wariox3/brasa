<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuIncapacidadType;

class IncapacidadesController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            
            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoIncapacidades = new \Brasa\RecursoHumanoBundle\Formatos\FormatoIncapacidad();
                $objFormatoIncapacidades->Generar($this, $this->strSqlLista);
            }

            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $em->remove($arIncapacidad);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_incapacidades_lista'));
                }
            }
            
        }
        $arIncapacidades = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Incapacidades:lista.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView()
            ));
    }    
    
    public function nuevoAction($codigoIncapacidad = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();       
        if($codigoIncapacidad != 0) {
            $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
        } else {
            $arIncapacidad->setFecha(new \DateTime('now'));
            $arIncapacidad->setFechaDesde(new \DateTime('now'));
            $arIncapacidad->setFechaHasta(new \DateTime('now'));                
        }        

        $form = $this->createForm(new RhuIncapacidadType(), $arIncapacidad);                     
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arIncapacidad = $form->getData();                          
            $arrControles = $request->request->All();
            if($arrControles['txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));                
                if(count($arEmpleado) > 0) {
                    $arIncapacidad->setEmpleadoRel($arEmpleado);
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);                        
                    if($arIncapacidad->getFechaDesde() <= $arIncapacidad->getFechaHasta()) {
                        if($em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arIncapacidad->getCodigoIncapacidadPk())) {                    
                            if($em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(),"")) {
                                if($arIncapacidad->getFechaDesde() >= $arEmpleado->getFechaContrato()) {
                                    $intDias = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
                                    $intDias = $intDias->format('%a');
                                    $intDias = $intDias + 1;
                                    $arIncapacidad->setCantidad($intDias);                                                                                                                                    
                                    $arIncapacidad->setEntidadSaludRel($arEmpleado->getEntidadSaludRel());
                                    $floVrIncapacidad = 0;
                                    $douVrDia = $arEmpleado->getVrSalario() / 30;
                                    $douVrDiaSalarioMinimo = $arConfiguracion->getVrSalario() / 30;
                                    $douPorcentajePago = $arIncapacidad->getIncapacidadTipoRel()->getPagoConceptoRel()->getPorPorcentaje();
                                    $arIncapacidad->setPorcentajePago($douPorcentajePago);
                                    if($arIncapacidad->getIncapacidadTipoRel()->getCodigoIncapacidadTipoPk() == 1) {
                                        if($arEmpleado->getVrSalario() <= $arConfiguracion->getVrSalario()) {
                                            $floVrIncapacidad = $intDias * $douVrDia;                    
                                        }
                                        if($arEmpleado->getVrSalario() > $arConfiguracion->getVrSalario() && $arEmpleado->getVrSalario() <= $arConfiguracion->getVrSalario() * 1.5) {
                                            $floVrIncapacidad = $intDias * $douVrDiaSalarioMinimo;                    
                                        }
                                        if($arEmpleado->getVrSalario() > ($arConfiguracion->getVrSalario() * 1.5)) {
                                            $floVrIncapacidad = $intDias * $douVrDia;
                                            $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago)/100;                    
                                        }
                                    } else {
                                        $floVrIncapacidad = $intDias * $douVrDia;
                                        $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago)/100;                
                                    }     
                                    $arIncapacidad->setVrIncapacidad($floVrIncapacidad);
                                    $arIncapacidad->setVrSaldo($floVrIncapacidad);
                                    $arIncapacidad->setCentroCostoRel($arEmpleado->getCentroCostoRel());                                                    
                                    $em->persist($arIncapacidad);
                                    $em->flush();

                                    if($form->get('guardarnuevo')->isClicked()) {                                                        
                                        return $this->redirect($this->generateUrl('brs_rhu_incapacidades_nuevo', array('codigoIncapacidad' => 0)));                                        
                                    } else {
                                        return $this->redirect($this->generateUrl('brs_rhu_incapacidades_lista'));
                                    }                             
                                } else {                                    
                                    $objMensaje->Mensaje("error", "No puede ingresar novedades antes de la fecha de inicio del contrato", $this);
                                }                  
                            } else {
                                $objMensaje->Mensaje("error", "Existe una licencia en este periodo de fechas", $this);
                            }                                                           
                        } else {
                            $objMensaje->Mensaje("error", "Existe otra incapaciad del empleado en esta fecha", $this);
                        }               
                    } else {
                        $objMensaje->Mensaje("error", "La fecha desde debe ser inferior o igual a la fecha hasta de la incapacidad", $this);
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);                                    
                }
            }            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Incapacidades:nuevo.html.twig', array(
            'arIncapacidad' => $arIncapacidad,
            'form' => $form->createView()));
    }

    private function formularioLista() {
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
            ->add('TxtNumero', 'number', array('data' => $session->get('filtroNumeroIncapacidad')))                            
            ->add('TxtNumeroEps', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIncapacidadNumeroEps')))                                                        
            ->add('estadoTranscripcion', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroIncapacidadEstadoTranscripcion')))                                        
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaDQL(                   
                $session->get('filtroIncapacidadNumero'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroIncapacidadEstadoTranscripcion'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroIncapacidadNumeroEps')
                );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroIncapacidadNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroIncapacidadNumeroEps', $form->get('TxtNumeroEps')->getData());                
        $session->set('filtroIncapacidadEstadoTranscripcion', $form->get('estadoTranscripcion')->getData());                        
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NÚMERO INCAPACIDAD')
                    ->setCellValue('C1', 'NÚMERO EPS')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'DÍAS');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);        
        $arIncapacidades = $query->getResult();
        foreach ($arIncapacidades as $arIncapacidad) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidad->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidad->getNumero())
                    ->setCellValue('C' . $i, $arIncapacidad->getNumeroEps())
                    ->setCellValue('D' . $i, $arIncapacidad->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('E' . $i, $arIncapacidad->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arIncapacidad->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arIncapacidad->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arIncapacidad->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arIncapacidad->getCantidad());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Incapacidades');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Incapacidades.xlsx"');
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
