<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAccidenteTrabajoType;
use Doctrine\ORM\EntityRepository;

class AccidenteTrabajoController extends Controller
{
    var $strSqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoAccidenteTrabajo) {
                        $arAccidentesTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
                        $arAccidentesTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
                        if ($arAccidentesTrabajo->getEstadoAccidente() == 1 ) {
                            $objMensaje->Mensaje("error", "No se puede Eliminar el registro, por que ya fue cerrada!", $this);
                        }
                        else {
                            $em->remove($arAccidentesTrabajo);
                            $em->flush();
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();
            }

            /*if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }*/
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($this, $this->strSqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arAccidentesTrabajo = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:AccidentesTrabajo:lista.html.twig', array(
            'arAccidentesTrabajo' => $arAccidentesTrabajo,
            'form' => $form->createView()
            ));
    }

    public function detalleAction($codigoVacacion) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnLiquidar', 'submit', array('label'  => 'Liquidar',))            
            ->getForm();
        $form->handleRequest($request);

        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionDisfrute();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionDisfrute')->find($codigoVacacion);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleVacaciones = new \Brasa\RecursoHumanoBundle\Formatos\FormatoVacacionesDisfrutadas();
                $objFormatoDetalleVacaciones->Generar($this, $codigoVacacion);
            }
            if($form->get('BtnLiquidar')->isClicked()) {
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));
            }
            if($form->get('BtnEliminarDeduccion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoVacacionDeduccion) {
                        $arVacacionDeduccion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
                        $arVacacionDeduccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->find($codigoVacacionDeduccion);
                        $em->remove($arVacacionDeduccion);
                    }
                    $em->flush();
                }
                $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->liquidar($codigoVacacion);
                return $this->redirect($this->generateUrl('brs_rhu_vacaciones_detalle', array('codigoVacacion' => $codigoVacacion)));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:VacacionesDisfrute:detalle.html.twig', array(
                    'arVacaciones' => $arVacaciones,
                    'form' => $form->createView()
                    ));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->listaDql(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion')
                    );
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
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            //->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }

    public function nuevoAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('codigoFurat', 'number', array('required' => false))
            ->add('fechaAccidente', 'date', array('data' => new \DateTime('now')))
            ->add('tipoAccidente', 'choice', array('choices'   => array('1' => 'ACCIDENTE', '2' => 'ACCIDENTE GRAVE', '3' => 'ACCIDENTE MORTAL', '4' => 'INCIDENTE')))
            ->add('fechaEnviaInvestigacion', 'date', array('data' => new \DateTime('now')))
            ->add('fechaIncapacidadDesde', 'date', array('data' => new \DateTime('now')))                
            ->add('fechaIncapacidadHasta', 'date', array('data' => new \DateTime('now')))
            ->add('dias', 'number', array('required' => false))
            ->add('cie10', 'text', array('required' => false))
            ->add('diagnostico', 'text', array('required' => false))
            ->add('naturalezaLesion', 'text', array('required' => false))
            ->add('cuerpoAfectado', 'text', array('required' => false))
            ->add('agente', 'text', array('required' => false))
            ->add('mecanismoAccidente', 'text', array('required' => false))
            ->add('lugarAccidente', 'text', array('required' => false))                
            ->add('descripcionAccidente', 'textarea', array('required' => false))
            ->add('actoInseguro', 'textarea', array('required' => false))
            ->add('condicionInsegura', 'textarea', array('required' => false))
            ->add('factorPersonal', 'textarea', array('required' => false))
            ->add('factorTrabajo', 'textarea', array('required' => false))
            ->add('planAccion1', 'textarea', array('required' => false))
            ->add('tipoControl1', 'choice', array('choices' => array('1' => 'FUENTE', '2' => 'MEDIO', '3' => 'PERSONA')))
            ->add('fechaVerificacion1', 'date', array('data' => new \DateTime('now')))                
            ->add('areaResponsable1', 'text', array('required' => false))
            ->add('planAccion2', 'textarea', array('required' => false))
            ->add('tipoControl2', 'choice', array('choices' => array('1' => 'FUENTE', '2' => 'MEDIO', '3' => 'PERSONA')))
            ->add('fechaVerificacion2', 'date', array('data' => new \DateTime('now')))                
            ->add('areaResponsable2', 'text', array('required' => false))                
            ->add('planAccion3', 'textarea', array('required' => false))
            ->add('tipoControl3', 'choice', array('choices' => array('1' => 'FUENTE', '2' => 'MEDIO', '3' => 'PERSONA')))
            ->add('fechaVerificacion3', 'date', array('data' => new \DateTime('now')))                
            ->add('areaResponsable3', 'text', array('required' => false))
            ->add('participanteInvestigacion1', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion1', 'text', array('required' => false))
            ->add('participanteInvestigacion2', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion2', 'text', array('required' => false))                
            ->add('participanteInvestigacion3', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion3', 'text', array('required' => false))                
            ->add('representanteLegal', 'text', array('required' => false))                
            ->add('cargoRepresentanteLegal', 'text', array('required' => false))
            ->add('licencia', 'text', array('required' => false))
            ->add('fechaVerificacion', 'date', array('data' => new \DateTime('now'))) 
            ->add('reponsableVerificacion', 'text', array('required' => false))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {           
            
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('numeroIdentificacion' => $form->get('numeroIdentificacion')->getData(), 'estadoActivo' => 1));
            if (count($arEmpleado) == 0){
                $objMensaje->Mensaje("error", "No existe el número de identificación", $this);
            }else {
                $arAccidentesTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
                $arAccidentesTrabajo->setEmpleadoRel($arEmpleado[0]);
                $arEmpleadoFinal = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoFinal = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arEmpleado[0]);
                $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arEmpleadoFinal->getCentroCostoRel());
                //$arCiudad = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arEmpleadoFinal->getCiudadRel());
                $arAccidentesTrabajo->setCentroCostoRel($arCentroCosto);
                $arAccidentesTrabajo->setCiudadRel($arAccidentesTrabajo->getCiudadRel());
                $arAccidentesTrabajo->setCodigoFurat($form->get('codigoFurat')->getData());
                $arAccidentesTrabajo->setFechaAccidente($form->get('fechaAccidente')->getData());
                $arAccidentesTrabajo->setTipoAccidente($form->get('tipoAccidente')->getData());
                $arAccidentesTrabajo->setFechaEnviaInvestigacion($form->get('fechaEnviaInvestigacion')->getData());
                $arAccidentesTrabajo->setFechaIncapacidadDesde($form->get('fechaIncapacidadDesde')->getData());
                $arAccidentesTrabajo->setFechaIncapacidadHasta($form->get('fechaIncapacidadHasta')->getData());
                $arAccidentesTrabajo->setDias($form->get('dias')->getData());
                $arAccidentesTrabajo->setCie10($form->get('cie10')->getData());
                $arAccidentesTrabajo->setDiagnostico($form->get('diagnostico')->getData());
                $arAccidentesTrabajo->setNaturalezaLesion($form->get('naturalezaLesion')->getData());
                $arAccidentesTrabajo->setCuerpoAfectado($form->get('cuerpoAfectado')->getData());
                $arAccidentesTrabajo->setAgente($form->get('agente')->getData());
                $arAccidentesTrabajo->setMecanismoAccidente($form->get('mecanismoAccidente')->getData());
                $arAccidentesTrabajo->setLugarAccidente($form->get('lugarAccidente')->getData());
                $arAccidentesTrabajo->setCodigoFurat($form->get('codigoFurat')->getData());
                $arAccidentesTrabajo->setDescripcionAccidente($form->get('descripcionAccidente')->getData());
                $arAccidentesTrabajo->setActoInseguro($form->get('actoInseguro')->getData());
                $arAccidentesTrabajo->setCondicionInsegura($form->get('condicionInsegura')->getData());
                $arAccidentesTrabajo->setFactorPersonal($form->get('factorPersonal')->getData());
                $arAccidentesTrabajo->setFactorTrabajo($form->get('factorTrabajo')->getData());
                $arAccidentesTrabajo->setPlanAccion1($form->get('planAccion1')->getData());
                $arAccidentesTrabajo->setTipoControl1($form->get('tipoControl1')->getData());
                $arAccidentesTrabajo->setFechaVerificacion1($form->get('fechaVerificacion1')->getData());
                $arAccidentesTrabajo->setAreaResponsable1($form->get('areaResponsable1')->getData());
                $arAccidentesTrabajo->setPlanAccion2($form->get('planAccion2')->getData());
                $arAccidentesTrabajo->setTipoControl2($form->get('tipoControl2')->getData());
                $arAccidentesTrabajo->setFechaVerificacion2($form->get('fechaVerificacion2')->getData());
                $arAccidentesTrabajo->setAreaResponsable2($form->get('areaResponsable2')->getData());
                $arAccidentesTrabajo->setPlanAccion3($form->get('planAccion3')->getData());
                $arAccidentesTrabajo->setTipoControl3($form->get('tipoControl3')->getData());
                $arAccidentesTrabajo->setFechaVerificacion3($form->get('fechaVerificacion3')->getData());
                $arAccidentesTrabajo->setAreaResponsable3($form->get('areaResponsable3')->getData());
                $arAccidentesTrabajo->setParticipanteInvestigacion1($form->get('participanteInvestigacion1')->getData());
                $arAccidentesTrabajo->setCargoParticipanteInvestigacion1($form->get('cargoParticipanteInvestigacion1')->getData());
                $arAccidentesTrabajo->setParticipanteInvestigacion2($form->get('participanteInvestigacion2')->getData());
                $arAccidentesTrabajo->setCargoParticipanteInvestigacion2($form->get('cargoParticipanteInvestigacion2')->getData());
                $arAccidentesTrabajo->setParticipanteInvestigacion3($form->get('participanteInvestigacion3')->getData());
                $arAccidentesTrabajo->setCargoParticipanteInvestigacion3($form->get('cargoParticipanteInvestigacion3')->getData());
                $arAccidentesTrabajo->setRepresentanteLegal($form->get('representanteLegal')->getData());
                $arAccidentesTrabajo->setCargoRepresentanteLegal($form->get('cargoRepresentanteLegal')->getData());
                $arAccidentesTrabajo->setLicencia($form->get('licencia')->getData());
                $arAccidentesTrabajo->setFechaVerificacion($form->get('fechaVerificacion')->getData());
                $arAccidentesTrabajo->setResponsableVerificacion($form->get('reponsableVerificacion')->getData());
                $em->persist($arAccidentesTrabajo);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:AccidentesTrabajo:nuevo.html.twig', array(
            
            'form' => $form->createView()));
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
                            ->setCellValue('A1', 'Codigo')
                            ->setCellValue('B1', 'Centro Costo')
                            ->setCellValue('C1', 'Desde')
                            ->setCellValue('D1', 'Hasta')
                            ->setCellValue('E1', 'Identificación')
                            ->setCellValue('F1', 'Empleado')
                            ->setCellValue('G1', 'Dias')
                            ->setCellValue('H1', 'Vr Vacaciones')
                            ->setCellValue('I1', 'Pagado');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                $arVacaciones = $query->getResult();

                foreach ($arVacaciones as $arVacacion) {
                    if ($arVacacion->getEstadoPagado() == 1)
                    {
                        $Estado = "SI";
                    }
                    else
                    {
                        $Estado = "NO";
                    }

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arVacacion->getCodigoVacacionPk())
                            ->setCellValue('B' . $i, $arVacacion->getCentroCostoRel()->getNombre())
                            ->setCellValue('C' . $i, $arVacacion->getFechaDesde())
                            ->setCellValue('D' . $i, $arVacacion->getFechaHasta())
                            ->setCellValue('E' . $i, $arVacacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('F' . $i, $arVacacion->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('G' . $i, $arVacacion->getDiasVacaciones())
                            ->setCellValue('H' . $i, round($arVacacion->getVrVacacion()))
                            ->setCellValue('I' . $i, $Estado);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Vacaciones');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Vacaciones.xlsx"');
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
