<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ConsultasControlAccesosVisitantesController extends Controller
{
    var $strDqlLista = "";

    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

        }
        $arControlAccesosVisitantes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 45);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/ControlAcceso:visitante.html.twig', array(
            'arControlAccesosVisitantes' => $arControlAccesosVisitantes,
            'form' => $form->createView()
            ));
    }

    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuRegistroVisita')->listaDql(
            $session->get('filtroIdentificacion'),
            $session->get('filtroNombre'),
            $session->get('filtroDesde'),
            $session->get('filtroHasta')
            );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();

        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
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
                    ->setCellValue('A1', 'NRO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'TIPO ACCESO')
                    ->setCellValue('E1', 'DEPARTAMENTO EMPRESA')
                    ->setCellValue('F1', 'FECHA')
                    ->setCellValue('G1', 'HORA')
                    ->setCellValue('H1', 'MOTIVO')
                    ->setCellValue('I1', 'COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arRegistroVisitas = new \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita();
        $arRegistroVisitas = $query->getResult();
        $j = 1;
        foreach ($arRegistroVisitas as $arRegistroVisita) {
            if ($arRegistroVisita->getCodigoTipoAccesoFk() == 1){
                    $tipoAcceso = "ENTRADA";
                }else {
                    $tipoAcceso = "SALIDA";
                }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $j)
                ->setCellValue('B' . $i, $arRegistroVisita->getNumeroIdentificacion())
                ->setCellValue('C' . $i, $arRegistroVisita->getNombre())
                ->setCellValue('D' . $i, $tipoAcceso)
                ->setCellValue('E' . $i, $arRegistroVisita->getDepartamentoEmpresaRel()->getNombre())    
                ->setCellValue('F' . $i, $arRegistroVisita->getFecha()->Format('Y-m-d'))
                ->setCellValue('G' . $i, $arRegistroVisita->getFecha()->Format('H:i:s'))
                ->setCellValue('H' . $i, $arRegistroVisita->getMotivo())
                ->setCellValue('I' . $i, $arRegistroVisita->getComentarios())    ;
            $i++;
            $j++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ControlAccesoVisitante');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ControlAccesoVisitante.xlsx"');
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
