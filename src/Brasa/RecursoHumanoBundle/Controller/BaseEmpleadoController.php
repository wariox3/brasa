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
            ->add('ChkActivo', 'checkbox', array('label'=> '', 'required'  => false,))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
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
                        $form->get('ChkActivo')->getData()));
                $session->set('filtroNombre', $form->get('TxtNombre')->getData());
                $session->set('filtroCentroCosto', $codigoCentroCosto);
                $session->set('filtroActivo', $form->get('ChkActivo')->getData());

            }

            if($form->get('BtnExcel')->isClicked()) {
                   $objPHPExcel = new \PHPExcel();
                   // Set properties
                   $objPHPExcel->getProperties()->setCreator("Brasa app");
                   $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
                   $objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
                   $objPHPExcel->getProperties()->setDescription("Lista centros costo");

                   // Add some data
                   $objPHPExcel->setActiveSheetIndex(0);
                   $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
                   $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
                   $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
                   $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

                   // Rename sheet
                   $objPHPExcel->getActiveSheet()->setTitle('Simple');

                   // Save Excel 2007 file
                   $strArchivo = "/opt/lampp/htdocs/prueba.xlsx";
                   $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
                   $objWriter->save($strArchivo);
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
                   $session->get('filtroActivo')
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
        $form = $this->createFormBuilder()
            ->add('BtnRetirarContrato', 'submit', array('label'  => 'Retirar',))
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arPagosAdicionales = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arPagosAdicionales = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
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
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoHojaVida = new \Brasa\RecursoHumanoBundle\Formatos\FormatoHojaVida();
                $objFormatoHojaVida->Generar($this, $codigoEmpleado);
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:detalle.html.twig', array(
                    'arEmpleado' => $arEmpleado,
                    'arPagosAdicionales' => $arPagosAdicionales,
                    'arIncapacidades' => $arIncapacidades,
                    'arContratos' => $arContratos,
                    'arCreditos' => $arCreditos,
                    'form' => $form->createView()
                    ));
    }

    public function nuevoAction($codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        if($codigoEmpleado != 0) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        } else {
            $arEmpleado->setVrSalario(644350); //Parametrizar con configuracion salario minimo
        }
        $form = $this->createForm(new RhuEmpleadoType(), $arEmpleado);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arEmpleado = $form->getData();
            $em->persist($arEmpleado);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleados_nuevo', array('codigoEmpleado' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleados_lista'));
            }

        }

        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }
}
