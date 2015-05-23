<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuIncapacidadType;

class IncapacidadesController extends Controller
{

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_value' => "Todos",
                'mapped' => false,
                'data' => '',

            ))            
            ->add('TxtNumero', 'number', array('data' => $session->get('filtroNumeroIncapacidad')))                            
            ->add('TxtNumeroEps', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroNumeroEps')))                                                        
            ->add('estadoTranscripcion', 'choice', array('choices'   => array('2' => 'Todos', '1' => 'SI', '0' => 'NO')))                                        
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        $form->handleRequest($request);

        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();

        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $objCentroCosto = $form->get('centroCostoRel')->getData();
                if($objCentroCosto != null) {
                    $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
                } else {
                    $codigoCentroCosto = "";
                }                
                $session->set('filtroNumeroIncapacidad', $form->get('TxtIdentificacion')->getData());
                $session->set('filtroNumeroEps', $form->get('TxtIdentificacion')->getData());
                $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                $session->set('filtroCentroCosto', $codigoCentroCosto);
                $session->set('filtroEstadoTranscripcion', $form->get('estadoTranscripcion')->getData());

            }

            if($form->get('BtnExcel')->isClicked()) {
                $objPHPExcel = new \PHPExcel();
                // Set document properties
                $objPHPExcel->getProperties()->setCreator("JG Efectivos")
                    ->setLastModifiedBy("JG Efectivos")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Identificacion')
                            ->setCellValue('C1', 'Nombre');

                $i = 2;
                $query = $em->createQuery($session->get('dqlEmpleado'));
                $arEmpleados = $query->getResult();
                foreach ($arEmpleados as $arEmpleado) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                            ->setCellValue('B' . $i, $arEmpleado->getNumeroIdentificacion())
                            ->setCellValue('C' . $i, $arEmpleado->getNombreCorto());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Empleados');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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

            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleado) {
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
                        if($arEmpleado->getEstadoActivo() == 1) {
                            $arEmpleado->setEstadoActivo(0);
                        } else {
                            $arEmpleado->setEstadoActivo(1);
                        }
                        $em->persist($arEmpleado);
                    }
                    $em->flush();
                }
            }
        }
        $session->set('dqlIncapacidad', $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->ListaDQL(                   
                $session->get('filtroNumeroIncapacidad'),
                $session->get('filtroCentroCosto'),
                $session->get('filtroEstadoTranscripcion'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroNumeroEps')
                ));        
        $query = $em->createQuery($session->get('dqlIncapacidad'));
        $arIncapacidades = $paginator->paginate($query, $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Incapacidades:lista.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView()
            ));
    }    
    
    public function nuevoAction($codigoCentroCosto, $codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        if($codigoEmpleado != 0) {            
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        }
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();       
        $arIncapacidad->setFechaDesde(new \DateTime('now'));
        $arIncapacidad->setFechaHasta(new \DateTime('now'));    
        $arIncapacidad->setCentroCostoRel($arCentroCosto);
        $form = $this->createForm(new RhuIncapacidadType(), $arIncapacidad); 
                    
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arIncapacidad = $form->getData();                          
            $intDias = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
            $intDias = $intDias->format('%a');
            $intDias = $intDias + 1;
            if($arEmpleado->getCodigoTipoTiempoFk() == 2) {
                $arIncapacidad->setCantidad($intDias * 4);
                $arIncapacidad->setCantidadPendiente($intDias * 4);                
            } else {
                $arIncapacidad->setCantidad($intDias * 8);
                $arIncapacidad->setCantidadPendiente($intDias * 8);
            }

            if($codigoEmpleado != 0) { 
                $arIncapacidad->setEmpleadoRel($arEmpleado);                
            }
            $em->persist($arIncapacidad);
            $em->flush();                        
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_incapacidad', array('codigoCentroCosto' => $codigoCentroCosto)));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }    
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Incapacidades:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }
}
