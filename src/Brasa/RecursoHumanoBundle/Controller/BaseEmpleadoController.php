<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoType;
use Doctrine\ORM\EntityRepository;

class BaseEmpleadoController extends Controller
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
            ->add('estadoActivo', 'choice', array('choices'   => array('2' => 'Todos', '1' => 'Activos', '0' => 'Inactivos')))                            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('BtnBuscar', 'submit', array('label'  => 'Buscar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnInactivar', 'submit', array('label'  => 'Activar / Inactivar',))
            ->getForm();
        $form->handleRequest($request);

        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();

        if($form->isValid()) {
            if($form->get('BtnBuscar')->isClicked()) {
                $objCentroCosto = $form->get('centroCostoRel')->getData();
                if($objCentroCosto != null) {
                    $codigoCentroCosto = $form->get('centroCostoRel')->getData()->getCodigoCentroCostoPk();
                } else {
                    $codigoCentroCosto = "";
                }
                $session->set('dqlEmpleado', $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL(
                        $form->get('TxtNombre')->getData(),
                        $codigoCentroCosto,
                        $form->get('estadoActivo')->getData(),
                        $form->get('TxtIdentificacion')->getData(),
                        ""
                        ));
                $session->set('filtroNombre', $form->get('TxtNombre')->getData());
                $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                $session->set('filtroCentroCosto', $codigoCentroCosto);
                $session->set('filtroActivos', $form->get('estadoActivo')->getData());

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

            if($form->get('BtnInactivar')->isClicked()) {
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
        } else {
           $session->set('dqlEmpleado', $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL(
                   $session->get('filtroNombre'),
                   $session->get('filtroCentroCosto'),
                   $session->get('filtroActivos'),
                   $session->get('filtroIdentificacion'),
                   ""
                   ));
        }

        $query = $em->createQuery($session->get('dqlEmpleado'));
        $arEmpleados = $paginator->paginate($query, $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:lista.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }

    public function detalleAction($codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $mensaje = 0;
        $form = $this->createFormBuilder()
            ->add('BtnRetirarContrato', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnRetirarIncapacidad', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnRetirarLicencia', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnEliminarCredito', 'submit', array('label'  => 'Eliminar',))    
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        if($form->isValid()) {
            if($form->get('BtnRetirarContrato')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarContrato');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoContrato) {
                        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                        $em->remove($arContrato);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }
            if($form->get('BtnRetirarIncapacidad')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarIncapacidad');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $em->remove($arIncapacidad);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }
            if($form->get('BtnEliminarCredito')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarCredito');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCredito) {
                        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCredito);
                        if ($arCredito->getAprobado() == 1 or $arCredito->getEstadoPagado() == 1)
                        {
                            $mensaje = "No se puede Eliminar el registro, por que el credito ya esta aprobado o cancelado!";
                        }
                        else
                        {
                            $em->remove($arCredito);
                            $em->flush();
                        }
                    }
                    //return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }  
            if($form->get('BtnRetirarLicencia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarLicencia');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoLicencia) {
                        $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                        $arLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($codigoLicencia);
                        $em->remove($arLicencia);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }            
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoHojaVida = new \Brasa\RecursoHumanoBundle\Formatos\FormatoHojaVida();
                $objFormatoHojaVida->Generar($this, $codigoEmpleado);
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:detalle.html.twig', array(
                    'arEmpleado' => $arEmpleado,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'arIncapacidades' => $arIncapacidades,
                    'arLicencias' => $arLicencias,
                    'arContratos' => $arContratos,
                    'arCreditos' => $arCreditos,
                    'mensaje' => $mensaje,
                    'form' => $form->createView()
                    ));
    }

    public function nuevoAction($codigoEmpleado, $codigoSeleccion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        if($codigoEmpleado != 0) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        } else {
            if($codigoSeleccion != 0) {
                $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                $arEmpleado->setNumeroIdentificacion($arSeleccion->getNumeroIdentificacion());
                $arEmpleado->setNombre1($arSeleccion->getNombre1());
                $arEmpleado->setNombre2($arSeleccion->getNombre2());
                $arEmpleado->setApellido1($arSeleccion->getApellido1());
                $arEmpleado->setApellido2($arSeleccion->getApellido2());
                $arEmpleado->setEstadoCivilRel($arSeleccion->getEstadoCivilRel());
                $arEmpleado->setFechaNacimiento($arSeleccion->getFechaNacimiento());
                $arEmpleado->setTelefono($arSeleccion->getTelefono());
                $arEmpleado->setCelular($arSeleccion->getCelular());
                $arEmpleado->setCorreo($arSeleccion->getCorreo());
                $arEmpleado->setDireccion($arSeleccion->getDireccion());
                $arEmpleado->setBarrio($arSeleccion->getBarrio());
                $arEmpleado->setCiudadRel($arSeleccion->getCiudadRel());                
            }
            $arEmpleado->setVrSalario(644350); //Parametrizar con configuracion salario minimo
        }
        $form = $this->createForm(new RhuEmpleadoType(), $arEmpleado);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arEmpleado = $form->getData();
            $arEmpleado->setNombreCorto($arEmpleado->getNombre1() . " " . $arEmpleado->getNombre2() . " " .$arEmpleado->getApellido1() . " " . $arEmpleado->getApellido2());            
            $em->persist($arEmpleado);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleados_nuevo', array('codigoEmpleado' => 0, 'codigoSeleccion' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleados_lista'));
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }
  
    public function enlazarAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioEnlazar();
        $form->handleRequest($request);
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($form->isValid()) {                        
            if($form->get('BtnFiltrar')->isClicked()) {
                if($form->get('TxtIdentificacion')->getData() != "") {
                    $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('numeroIdentificacion' => $form->get('TxtIdentificacion')->getData()));
                }
            }
        }                  
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:enlazar.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'form' => $form->createView()));
    }    
    
    private function formularioEnlazar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                        
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionSeleccion')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();        
        return $form;
    }        
    
}
