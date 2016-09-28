<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class UtilidadesProyeccionController extends Controller
{
    var $strDqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 80)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->generarExcel();
            }
            if($form->get('BtnGenerar')->isClicked()) {                                 
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                if($fechaDesde != null && $fechaHasta != null) {
                    $strSql = "DELETE FROM rhu_proyeccion WHERE 1";
                    $em->getConnection()->executeQuery($strSql); 
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $douAuxilioTransporte = $arConfiguracion->getVrAuxilioTransporte();
                    //$douAuxilioTransporte = 74000;
                    $intDias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->diasPrestaciones($fechaDesde, $fechaHasta);
                    $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $centroCosto = $form->get('centroCostoRel')->getData();
                    if ($centroCosto == ""){
                        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('indefinido' => 1));
                    }else{
                        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('indefinido' => 1, 'codigoCentroCostoFk' => $centroCosto));
                    }
                    
                    foreach($arContratos as $arContrato) {
                        $floSalarioPromedio = $arContrato->getVrSalarioPago();
                        $floIbc = ($arContrato->getVrSalarioPago() / 30) * $intDias;
                        $douBasePrestaciones = ($floIbc / $intDias) * 30;
                        $douBasePrestacionesTotal = $douBasePrestaciones + $douAuxilioTransporte;
                        $douCesantias = ($douBasePrestacionesTotal * $intDias) / 360;          
                        $floPorcentajeIntereses = (($intDias * 12) / 360)/100;   
                        $douInteresesCesantias = $douCesantias * $floPorcentajeIntereses;
                        $douPrima = ($douBasePrestacionesTotal * $intDias) / 360;                         
                        $douVacaciones = ($floSalarioPromedio * $intDias) / 720;                        
                        $arProyeccion = new \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion();
                        $arProyeccion->setContratoRel($arContrato);
                        $arProyeccion->setEmpleadoRel($arContrato->getEmpleadoRel());
                        $arProyeccion->setVrSalario($arContrato->getVrSalario());
                        $arProyeccion->setVrCesantias($douCesantias);                        
                        $arProyeccion->setVrInteresesCesantias($douInteresesCesantias);
                        $arProyeccion->setVrPrimas($douPrima);
                        $arProyeccion->setVrVacaciones($douVacaciones);
                        $arProyeccion->setDias($intDias);
                        $arProyeccion->setFechaDesde($fechaDesde);
                        $arProyeccion->setFechaHasta($fechaHasta);
                        $em->persist($arProyeccion);
                    }                
                    $em->flush();                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_utilidades_proyeccion'));           
            }            
            
            /*if($form->get('BtnPDF')->isClicked()) {
                $this->filtrarLista($form);
                $this->listarCostosGeneral();
                $objReporteCostos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCostos();
                $objReporteCostos->Generar($this, $this->strDqlLista);
            }*/            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

        }
        $arProyecciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Proyeccion:lista.html.twig', array(
            'arProyecciones' => $arProyecciones,
            'form' => $form->createView()
            ));
    }        
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProyeccion')->listaDql(                                        
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
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroDesde') != "") {
            $strFechaDesde = $session->get('filtroDesde');
        }
        if($session->get('filtroHasta') != "") {
            $strFechaHasta = $session->get('filtroHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }        

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroHasta', $dateFechaHasta->format('Y/m/d'));
    }

    private function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CONTRATO')
                    ->setCellValue('E1', 'CENTRO COSTO')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA')
                    ->setCellValue('H1', 'SALARIO')
                    ->setCellValue('I1', 'DÍAS')
                    ->setCellValue('J1', 'VACACIONES')
                    ->setCellValue('K1', 'PRIMAS')
                    ->setCellValue('L1', 'CESANTIAS')
                    ->setCellValue('M1', 'INTERESES CESANTIAS');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arProyecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuProyeccion();
        $arProyecciones = $query->getResult();
        foreach ($arProyecciones as $arProyeccion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProyeccion->getCodigoProyeccionPk())
                    ->setCellValue('B' . $i, $arProyeccion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arProyeccion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arProyeccion->getCodigoContratoFk())
                    ->setCellValue('E' . $i, $arProyeccion->getContratoRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $arProyeccion->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arProyeccion->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('H' . $i, $arProyeccion->getVrSalario())
                    ->setCellValue('I' . $i, $arProyeccion->getDias())
                    ->setCellValue('J' . $i, $arProyeccion->getVrVacaciones())
                    ->setCellValue('K' . $i, $arProyeccion->getVrPrimas())
                    ->setCellValue('L' . $i, $arProyeccion->getVrCesantias())
                    ->setCellValue('M' . $i, $arProyeccion->getVrInteresesCesantias());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Proyeccion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Proyeccion.xlsx"');
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
