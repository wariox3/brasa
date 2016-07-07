<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoEstudioType;


class EstudioController extends Controller
{
    var $strListaDql = "";
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
                    foreach ($arrSelecionados AS $codigoEstudio) {
                        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
                        if ($arEstudio->getEstadoAutorizado() == 0){
                            $em->remove($arEstudio);
                        }else{
                            $objMensaje->Mensaje("error", "El estudio número ".$codigoEstudio. ", no se puede eliminar, se encuentra autorizado", $this);
                        }   
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_estudio_lista'));
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

        $arEstudios = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:lista.html.twig', array('arEstudios' => $arEstudios, 'form' => $form->createView()));
    }

    public function nuevoAction($codigoEstudio = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        if($codigoEstudio != 0) {
            $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        } else {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arEstudio->setFechaEstudio(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuEmpleadoEstudioType, $arEstudio);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arEstudio = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleadopleado) > 0) {
                    $arEstudio->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        if ($codigoEstudio == 0){
                            $arEstudio->setCodigoUsuario($arUsuario->getUserName());
                        }
                        $em->persist($arEstudio);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_estudio_nuevo', array('codigoEstudio' => 0 )));
                        } else {
                            if ($codigoEstudio == 0){
                                return $this->redirect($this->generateUrl('brs_rhu_estudio_detalle', array('codigoEstudio' => $arEstudio->getCodigoEstudioPk())));
                            } else {
                                return $this->redirect($this->generateUrl('brs_rhu_estudio_lista'));
                            }
                            
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:nuevo.html.twig', array(
            'arEstudio' => $arEstudio,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoEstudio) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        $form = $this->formularioDetalle($arEstudio);
        $form->handleRequest($request);
        if($form->isValid()) {
            /*if($form->get('BtnAutorizar')->isClicked()) {            
                if($arEstudio->getEstadoAutorizado() == 0) {
                    $arEstudio->setEstadoAutorizado(1);
                    $em->persist($arEstudio);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_estudio_detalle', array('codigoEstudio' => $codigoEstudio)));                                                
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arEstudio->getEstadoAutorizado() == 1) {
                    $arEstudio->setEstadoAutorizado(0);
                    $em->persist($arEstudio);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_estudio_detalle', array('codigoEstudio' => $codigoEstudio)));                                                
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arEstudio->getEstadoAutorizado() == 1) {
                    $objFormatoEstudio = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEstudio();
                    $objFormatoEstudio->Generar($this, $codigoEstudio);
                }
            }*/
        }
        $arEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEstudio);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Estudios:detalle.html.twig', array(
                    'arEstudio' => $arEstudio,
                    'form' => $form->createView()
                    ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->listaMovimientoDQL(
                $session->get('filtroIdentificacion'),
                $session->get('filtroNombre'),
                $session->get('filtroEstudio'),
                $session->get('filtroEstado'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta')
                );
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesEstudio = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroEstudio')) {
            $arrayPropiedadesEstudio['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo", $session->get('filtroEstudio'));
        }
        $arrayPropiedadesEstado = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstudioEstado',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroEstado')) {
            $arrayPropiedadesEstado['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEstudioEstado", $session->get('filtroEstado'));
        }

        $form = $this->createFormBuilder()
            ->add('empleadoEstudioTipoRel', 'entity', $arrayPropiedadesEstudio)
            ->add('estudioEstadoRel', 'entity', $arrayPropiedadesEstado)    
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))    
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
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEstudio', $controles['empleadoEstudioTipoRel']);
        $session->set('filtroEstado', $controles['estudioEstadoRel']);     
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function formularioDetalle($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);               
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)                                            
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
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'CENTRO COSTOS')
                            ->setCellValue('D1', 'IDENTIFICACIÓN')
                            ->setCellValue('E1', 'EMPLEADO')
                            ->setCellValue('F1', 'CARGO')
                            ->setCellValue('G1', 'DEPARTAMENTO EMPRESA')
                            ->setCellValue('H1', 'JEFE PERMISO')
                            ->setCellValue('I1', 'TIPO PERMISO')
                            ->setCellValue('J1', 'MOTIVO')
                            ->setCellValue('K1', 'HORA SALIDA')
                            ->setCellValue('L1', 'HORA LLEGADA')
                            ->setCellValue('M1', 'HORAS')
                            ->setCellValue('N1', 'AFECTA HORARIO')
                            ->setCellValue('O1', 'AUTORIZADO')
                            ->setCellValue('P1', 'OBSERVACIONES');

                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                $arEstudios = $query->getResult();

                foreach ($arEstudios as $arEstudios) {
                    $centroCosto = "";
                    if ($arEstudios->getCodigoCentroCostoFk() != null){
                        $centroCosto = $arEstudios->getCentroCostoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arEstudios->getCodigoEstudioPk())
                            ->setCellValue('B' . $i, $arEstudios->getFechaEstudio()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $centroCosto)
                            ->setCellValue('D' . $i, $arEstudios->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arEstudios->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arEstudios->getCargoRel()->getNombre())
                            ->setCellValue('G' . $i, $arEstudios->getDepartamentoEmpresaRel()->getNombre())
                            ->setCellValue('H' . $i, $arEstudios->getJefeAutoriza())
                            ->setCellValue('I' . $i, $arEstudios->getEstudioTipoRel()->getNombre())
                            ->setCellValue('J' . $i, $arEstudios->getMotivo())
                            ->setCellValue('K' . $i, $arEstudios->getHoraSalida()->format('H:i'))
                            ->setCellValue('L' . $i, $arEstudios->getHoraLlegada()->format('H:i'))
                            ->setCellValue('M' . $i, $arEstudios->getHorasEstudio())
                            ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arEstudios->getAfectaHorario()))
                            ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arEstudios->getEstadoAutorizado()))
                            ->setCellValue('P' . $i, $arEstudios->getObservaciones());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Estudios');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Estudios.xlsx"');
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
