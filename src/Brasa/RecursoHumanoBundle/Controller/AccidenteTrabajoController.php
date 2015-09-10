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
                            return $this->redirect($this->generateUrl('brs_rhu_accidente_trabajo_lista'));
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();
            }
            if ($form->get('BtnCerrar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoAccidenteTrabajo) {
                        $arAccidentesTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
                        $arAccidentesTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
                        if ($arAccidentesTrabajo->getEstadoAccidente() == 0 ) {
                            $arAccidentesTrabajo->setEstadoAccidente(1);
                            $em->persist($arAccidentesTrabajo);
                            $em->flush();
                            return $this->redirect($this->generateUrl('brs_rhu_accidente_trabajo_lista'));
                        }
                        else {
                            return $this->redirect($this->generateUrl('brs_rhu_accidente_trabajo_lista'));
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
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

    public function detalleAction($codigoAccidenteTrabajo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
                        
            ->getForm();
        $form->handleRequest($request);

        $arAccidenteTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
        $arAccidenteTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
        if($form->isValid()) {
            
            
        }
        return $this->render('BrasaRecursoHumanoBundle:AccidentesTrabajo:detalle.html.twig', array(
                    'arAccidenteTrabajo' => $arAccidenteTrabajo,
                    'form' => $form->createView()
                    ));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->listaDql(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
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
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnCerrar', 'submit', array('label'  => 'Cerrar',))    
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
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    public function nuevoAction($codigoAccidenteTrabajo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arEntidadRiesgo = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
        $arEntidadRiesgo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($arConfiguracion->getCodigoEntidadRiesgoFk());
        $arAccidenteTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
        if ($codigoAccidenteTrabajo != 0)
        {
            $arAccidenteTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
        }
        else {
            /*$arAccidenteTrabajo->getFechaAccidente(new \DateTime('now'));
            $arAccidenteTrabajo->getFechaEnviaInvestigacion(new \DateTime('now'));
            $arAccidenteTrabajo->getFechaIncapacidadDesde(new \DateTime('now'));
            $arAccidenteTrabajo->getFechaIncapacidadHasta(new \DateTime('now'));
            $arAccidenteTrabajo->getFechaVerificacion1(new \DateTime('now'));
            $arAccidenteTrabajo->getFechaVerificacion2(new \DateTime('now'));
            $arAccidenteTrabajo->getFechaVerificacion3(new \DateTime('now'));
            $arAccidenteTrabajo->getFechaVerificacion(new \DateTime('now'));*/
        }
        $form = $this->createForm(new RhuAccidenteTrabajoType(), $arAccidenteTrabajo);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arAccidenteTrabajo = $form->getData();
            $identificacion = $request->request->get('TxtIdentificacion');
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('numeroIdentificacion' => $identificacion, 'estadoActivo' => 1));
            $arEmpleadoFinal = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleadoFinal = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arEmpleado[0]);
            if (count($arEmpleadoFinal) == 0){
                $objMensaje->Mensaje("error", "No existe el número de identificación", $this);
            }else {
                if ($arEmpleadoFinal->getCodigoCentroCostoFk() == ""){
                    $objMensaje->Mensaje("error", "El empleado no tiene contrato", $this);
                }else {
                    $arCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto();
                    $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($arEmpleadoFinal->getCentroCostoRel());

                    $arAccidenteTrabajo->setCentroCostoRel($arCentroCosto);
                    $arAccidenteTrabajo->setEmpleadoRel($arEmpleadoFinal);
                    $arAccidenteTrabajo->setEntidadRiesgoProfesionalRel($arEntidadRiesgo);
                    $em->persist($arAccidenteTrabajo);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
            
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:AccidentesTrabajo:nuevo.html.twig', array(
            'form' => $form->createView(),
            'arAccidenteTrabajo' => $arAccidenteTrabajo,
            'codigoAccidenteTrabajo' => $codigoAccidenteTrabajo,
        ));
    }

    private function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ;
                    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arAccidentesTrabajo = $query->getResult();
        foreach ($arAccidentesTrabajo as $arAccidenteTrabajo) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAccidenteTrabajo->getDias())
                    ;
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Accidentes');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="AccidentesTrabajo.xlsx"');
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
