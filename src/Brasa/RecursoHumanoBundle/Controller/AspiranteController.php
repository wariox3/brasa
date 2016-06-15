<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAspiranteType;


class AspiranteController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arAspirantes = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arAspirantes) > 0) {
                    foreach ($arAspirantes AS $id) {
                        $arAspirantes = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
                        $arAspirantes = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($id);
                        if ($arAspirantes->getEstadoCerrado() == 0 and $arAspirantes->getEstadoAutorizado()== 0){
                             $em->remove($arAspirantes);
                             $em->flush();
                        } else {
                            $objMensaje->Mensaje("error", "No se puede eliminar esta aprobado o autorizado", $this);
                        }     
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_aspirante_lista'));
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

        $arAspirantes = $paginator->paginate($em->createQuery($session->get('dqlAspiranteLista')), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:lista.html.twig', array('arAspirantes' => $arAspirantes, 'form' => $form->createView()));
    }

    public function nuevoAction($codigoAspirante) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        if($codigoAspirante != 0) {
            $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
        } 
        $form = $this->createForm(new RhuAspiranteType, $arAspirante);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arAspirante = $form->getData();
            $arAspirante->setNombreCorto($arAspirante->getNombre1() . " " . $arAspirante->getNombre2() . " " .$arAspirante->getApellido1() . " " . $arAspirante->getApellido2());
            $arAspirante->setFecha(new \DateTime('now'));
            if($codigoAspirante == 0) {
                $arAspirante->setCodigoUsuario($arUsuario->getUserName());
            }
            $em->persist($arAspirante);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_aspirante_nuevo', array('codigoAspirante' => 0)));
            } else {
                if ($codigoAspirante == 0){
                    return $this->redirect($this->generateUrl('brs_rhu_aspirante_detalle', array('codigoAspirante' => $arAspirante->getCodigoAspirantePk())));
                }else {
                    return $this->redirect($this->generateUrl('brs_rhu_aspirante_lista'));
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:nuevo.html.twig', array(
            'arAspirante' => $arAspirante,
            'form' => $form->createView()));
    }

    public function detalleAction($codigoAspirante) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
        $form = $this->formularioDetalle($arAspirante);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrAspiranteados = $request->request->get('ChkAspirante');
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arAspirante->getEstadoAutorizado() == 0) {
                    $arAspirante->setEstadoAutorizado(1);
                    $em->persist($arAspirante);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_aspirante_detalle', array('codigoAspirante' => $codigoAspirante)));   
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arAspirante->getEstadoAutorizado() == 1) {
                    $arAspirante->setEstadoAutorizado(0);
                    $em->persist($arAspirante);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_aspirante_detalle', array('codigoAspirante' => $codigoAspirante)));
                }
            }

            if($form->get('BtnAprobar')->isClicked()){
                if($arAspirante->getEstadoAutorizado() == 1) {
                    $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->estadoAprobadoAspirantes($codigoAspirante);
                    if ($strRespuesta == ''){
                        return $this->redirect($this->generateUrl('brs_rhu_aspirante_detalle', array('codigoAspirante' => $codigoAspirante)));
                    }else{
                        $objMensaje->Mensaje('error', $strRespuesta, $this);
                    }
                }    
            }

            if($form->get('BtnCerrar')->isClicked()){
                $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->cerrarAspirante($codigoAspirante);
                return $this->redirect($this->generateUrl('brs_rhu_aspitante_detalle', array('codigoAspirante' => $codigoAspirante)));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:detalle.html.twig', array(
                    'arAspirante' => $arAspirante,
                    'form' => $form->createView()
                    ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlAspiranteLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->listaDQL(
                $session->get('filtroNombreAspirante'),
                $session->get('filtroIdentificacionAspirante'),
                $session->get('filtroAbiertoAspirante'),
                $session->get('filtroAprobadoAspirante'),
                $session->get('filtroCodigoCentroCosto')
                ));
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
            ->add('estadoAprobado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAprobadoAspirante')))
            ->add('estadoCerrado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroAbiertoAspirante')))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre', 'data' => $session->get('filtroNombreAspirante')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionAspirante')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 0) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;
        } else {
            $arrBotonAutorizar['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonAprobar['disabled'] = true;
        }
        if ($ar->getEstadoCerrado() == 1){
            $arrBotonCerrar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnAprobar', 'submit', $arrBotonAprobar)
                    ->add('BtnCerrar', 'submit', $arrBotonCerrar)
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroNombreAspirante', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacionAspirante', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroAbiertoAspirante', $form->get('estadoCerrado')->getData());
        $session->set('filtroAprobadoAspirante', $form->get('estadoAprobado')->getData());
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'CENTRO COSTO')
                    ->setCellValue('D1', 'CARGO')
                    ->setCellValue('E1', 'CIUDAD')
                    ->setCellValue('F1', 'TIPO IDENTIFICACION')
                    ->setCellValue('G1', 'IDENTIFICACION')
                    ->setCellValue('H1', 'CIUDAD NACIMIENTO')
                    ->setCellValue('I1', 'FECHA NACIMIENTO')
                    ->setCellValue('J1', 'CIUDAD EXPEDICION')
                    ->setCellValue('K1', 'RH')
                    ->setCellValue('L1', 'NOMBRE')
                    ->setCellValue('M1', 'TELEFONO')
                    ->setCellValue('N1', 'CELULAR')
                    ->setCellValue('O1', 'DIRECCION')
                    ->setCellValue('P1', 'BARRIO')
                    ->setCellValue('Q1', 'ESTADO CIVIL')
                    ->setCellValue('R1', 'SEXO')
                    ->setCellValue('S1', 'CORREO')
                    ->setCellValue('T1', 'DISPONIBILIDAD')
                    ->setCellValue('U1', 'APROBADO')
                    ->setCellValue('V1', 'CERRADO');

        $i = 2;
        $query = $em->createQuery($session->get('dqlAspiranteLista'));
        $arAspirantes = $query->getResult();
        foreach ($arAspirantes as $arAspirantes) {
            
            if ($arAspirantes->getCodigoCentroCostoFk() == null)
            {
                $centroCosto = "";
            }
            else
            {
                $centroCosto = $arAspirantes->getCentroCostoRel()->getNombre();
            }
            $cargo = "";
            if ($arAspirantes->getCodigoCargoFk() <> null){
                $cargo = $arAspirantes->getCargoRel()->getNombre();
            }
            $ciudad = "";
            if ($arAspirantes->getCodigoCiudadFk() <> null){
                $ciudad = $arAspirantes->getCiudadRel()->getNombre();
            }
            $ciudadNacimiento = "";
            if ($arAspirantes->getCodigoCiudadNacimientoFk() <> null){
                $ciudadNacimiento = $arAspirantes->getCiudadNacimientoRel()->getNombre();
            }
            $ciudadExpedicion = "";
            if ($arAspirantes->getCodigoCiudadNacimientoFk() <> null){
                $ciudadExpedicion = $arAspirantes->getCiudadExpedicionRel()->getNombre();
            }
            $estadoCivil = "";
            if ($arAspirantes->getCodigoEstadoCivilFk() <> null){
                $estadoCivil = $arAspirantes->getEstadoCivilRel()->getNombre();
            }
            $sexo = "";
            if ($arAspirantes->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            } else {
                $sexo = "FEMENINO";
            }
            $disponibilidad = "";
            if ($arAspirantes->getCodigoDisponibilidadFk() == "1"){
                $disponibilidad = "TIEMPO COMPLETO";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "2"){
                $disponibilidad = "MEDIO TIEMPO";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "3"){
                $disponibilidad = "POR HORAS";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "4"){
                $disponibilidad = "DESDE CASA";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "5"){
                $disponibilidad = "PRACTICAS";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "0"){
                $disponibilidad = "NO APLICA";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAspirantes->getCodigoAspirantePk())
                    ->setCellValue('B' . $i, $arAspirantes->getFecha()->format('Y-m-d'))
                    ->setCellValue('C' . $i, $centroCosto)
                    ->setCellValue('D' . $i, $cargo)
                    ->setCellValue('E' . $i, $ciudad)
                    ->setCellValue('F' . $i, $arAspirantes->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('G' . $i, $arAspirantes->getNumeroIdentificacion())
                    ->setCellValue('H' . $i, $ciudadNacimiento)
                    ->setCellValue('I' . $i, $arAspirantes->getFechaNacimiento()->format('Y-m-d'))
                    ->setCellValue('J' . $i, $ciudadExpedicion)
                    ->setCellValue('K' . $i, $arAspirantes->getRhRel()->getTipo())
                    ->setCellValue('L' . $i, $arAspirantes->getNombreCorto())
                    ->setCellValue('M' . $i, $arAspirantes->getTelefono())
                    ->setCellValue('N' . $i, $arAspirantes->getCelular())
                    ->setCellValue('O' . $i, $arAspirantes->getDireccion())
                    ->setCellValue('P' . $i, $arAspirantes->getBarrio())
                    ->setCellValue('Q' . $i, $estadoCivil)
                    ->setCellValue('R' . $i, $sexo)
                    ->setCellValue('S' . $i, $arAspirantes->getCorreo())
                    ->setCellValue('T' . $i, $disponibilidad)
                    ->setCellValue('U' . $i, $objFunciones->devuelveBoolean($arAspirantes->getEstadoAprobado()))
                    ->setCellValue('V' . $i, $objFunciones->devuelveBoolean($arAspirantes->getEstadoCerrado()));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Aspirantes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Aspirantes.xlsx"');
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
