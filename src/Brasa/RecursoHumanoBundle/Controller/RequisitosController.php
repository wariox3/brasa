<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRequisitoType;
use Doctrine\ORM\EntityRepository;
class RequisitosController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
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

        $arRequisitos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Requisitos:lista.html.twig', array(
            'arRequisitos' => $arRequisitos, 
            'form' => $form->createView()));
    }

    public function detalleAction($codigoRequisito) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();        
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisito = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->find($codigoRequisito);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                if ($arRequisito->getCodigoContratoTipoFk() == 1){
                    $objFormatoContrato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoContratoObraLabor();
                    $objFormatoContrato->Generar($this, $codigoRequisito);
                }
            }
        }
        $arRequisitosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
        $arRequisitosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoDetalle')->findBy(array('codigoRequisitoFk' => $codigoRequisito));
        $arRequisitosDetalles = $paginator->paginate($arRequisitosDetalles, $this->get('request')->query->get('page', 1),50);
        return $this->render('BrasaRecursoHumanoBundle:Requisitos:detalle.html.twig', array(
                        'arRequisitosDetalles' => $arRequisitosDetalles,        
                        'arRequisito' => $arRequisito,
                        'form' => $form->createView()
                    ));
    }

    public function nuevoAction($codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();                       
        $arRequisito->setFecha(new \DateTime('now'));        
        $form = $this->createForm(new RhuRequisitoType(), $arRequisito);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRequisito = $form->getData();
            $arRequisito->setEmpleadoRel($arEmpleado);                   
            $em->persist($arRequisito);
            
            $arRequisitosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto();
            $arRequisitosConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->findAll();
            foreach ($arRequisitosConceptos as $arRequisitoConcepto) {
                $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                $arRequisitoDetalle->setRequisitoRel($arRequisito);
                $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoConcepto);
                $em->persist($arRequisitoDetalle);
            }
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }
        return $this->render('BrasaRecursoHumanoBundle:Requisitos:nuevo.html.twig', array(
            'arRequisito' => $arRequisito,            
            'form' => $form->createView()));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisito')->listaDql();
    }

    private function formularioLista() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        if($controles['fechaDesdeInicia']) {
            $this->fechaDesdeInicia = $controles['fechaDesdeInicia'];
        }
        if($controles['fechaHastaInicia']) {
            $this->fechaHastaInicia = $controles['fechaHastaInicia'];
        }
        //$session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);

        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroContratoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
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
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NUMERO')
                    ->setCellValue('E1', 'CENTRO COSTOS')
                    ->setCellValue('F1', 'TIEMPO')
                    ->setCellValue('G1', 'DESDE')
                    ->setCellValue('H1', 'HASTA')
                    ->setCellValue('I1', 'SALARIO')
                    ->setCellValue('J1', 'CARGO')
                    ->setCellValue('K1', 'CARGO DESCRIPCION')
                    ->setCellValue('L1', 'CLA. RIESGO')
                    ->setCellValue('M1', 'ULT. PAGO')
                    ->setCellValue('N1', 'ULT. PAGO PRIMAS')
                    ->setCellValue('O1', 'ULT. PAGO CESANTIAS')
                    ->setCellValue('P1', 'ULT. PAGO VACACIONES');
        $i = 2;
        $query = $em->createQuery($session->get('dqlContratoLista'));
        //$arRequisitos = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
        $arRequisitos = $query->getResult();
        foreach ($arRequisitos as $arRequisito) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRequisito->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arRequisito->getContratoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arRequisito->getFecha()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arRequisito->getNumero())
                    ->setCellValue('E' . $i, $arRequisito->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $arRequisito->getTipoTiempoRel()->getNombre())
                    ->setCellValue('G' . $i, $arRequisito->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('H' . $i, $arRequisito->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('I' . $i, $arRequisito->getVrSalario())
                    ->setCellValue('J' . $i, $arRequisito->getCargoRel()->getNombre())
                    ->setCellValue('K' . $i, $arRequisito->getCargoDescripcion())
                    ->setCellValue('L' . $i, $arRequisito->getClasificacionRiesgoRel()->getNombre())
                    ->setCellValue('M' . $i, $arRequisito->getFechaUltimoPago()->Format('Y-m-d'))
                    ->setCellValue('N' . $i, $arRequisito->getFechaUltimoPagoPrimas()->Format('Y-m-d'))
                    ->setCellValue('O' . $i, $arRequisito->getFechaUltimoPagoCesantias()->Format('Y-m-d'))
                    ->setCellValue('P' . $i, $arRequisito->getFechaUltimoPagoVacaciones()->Format('Y-m-d'));
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('contratos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos.xlsx"');
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
