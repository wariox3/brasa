<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuVacacionType;
use Doctrine\ORM\EntityRepository;

class VacacionesController extends Controller
{    
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $mensaje = 0;
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoVacacion) {
                        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
                        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
                        if ($arVacaciones->getEstadoPagado() == 1 ) {
                            $mensaje = "No se puede Eliminar el registro, por que ya fue pagada!";
                        }
                        else {    
                            $em->remove($arVacaciones);
                            $em->flush();
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
        $arVacaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);                        
        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:lista.html.twig', array(
            'arVacaciones' => $arVacaciones,
            'mensaje' => $mensaje,
            'form' => $form->createView()
            ));
    } 
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacion')->listaVacacionesDQL(
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
    
    public function nuevoAction($codigoCentroCosto, $codigoEmpleado, $codigoVacacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        if($codigoEmpleado != 0) {            
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        } 
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();       
        if($codigoVacacion != 0) {
            $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);
        } else {
            $arVacacion->setFecha(new \DateTime('now'));
            $arVacacion->setFechaDesde(new \DateTime('now'));
            $arVacacion->setFechaHasta(new \DateTime('now'));    
            $arVacacion->setCentroCostoRel($arCentroCosto);            
        }
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $form = $this->createForm(new RhuVacacionType(), $arVacacion);     
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arVacacion = $form->getData();                          
            $intDias = $arVacacion->getFechaDesde()->diff($arVacacion->getFechaHasta());
            $intDias = $intDias->format('%a');
            $intDias = $intDias + 1;
            $floSalario = $arEmpleado->getVrSalario();
            $floIbc = $floSalario / 30 * $intDias;
            $arVacacion->setVrIbc($floIbc);
            $arVacacion->setDiasVacaciones($intDias);
            $douSalud = ($floIbc * 4) /100;
            $arVacacion->setVrSalud($douSalud);
            if ($floSalario >= ($arConfiguracion->getVrSalario() * 4)){
                $douPorcentaje = $arConfiguracion->getPorcentajePensionExtra();
                $douPension = ($floIbc * $douPorcentaje) /100;
            }
            else {
                $douPension = ($floIbc * 4) /100;
            }
            $arVacacion->setVrPension($douPension);
            $douVacacion = $floIbc - $douPension - $douSalud;
            $arVacacion->setVrVacacion($douVacacion);
            if($codigoEmpleado != 0) { 
                $arVacacion->setEmpleadoRel($arEmpleado); 
            }
            $em->persist($arVacacion);
            $em->flush();                        
            
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                
            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Base/Vacaciones:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoVacacion) {
        /*$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()    
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        $codigoCreditoFk = $codigoCreditoPk;
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->find($codigoCreditoPk);
        $arCreditoPago = new \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago();
        $arCreditoPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuCreditoPago')->findBy(array('codigoCreditoFk' => $codigoCreditoFk));
        if($form->isValid()) {
                      
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoDetalleCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDetalleCredito();
                $objFormatoDetalleCredito->Generar($this, $codigoCreditoFk);
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Creditos:detalle.html.twig', array(
                    'arCreditoPago' => $arCreditoPago,
                    'arCreditos' => $arCreditos,
                    'form' => $form->createView()
                    ));*/
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
                            ->setCellValue('A1', 'Codigo_Credito')
                            ->setCellValue('B1', 'Tipo_Credito')
                            ->setCellValue('C1', 'Fecha_Credito')
                            ->setCellValue('D1', 'Centro_Costo')
                            ->setCellValue('E1', 'Empleado')
                            ->setCellValue('F1', 'Valor_Credito')
                            ->setCellValue('G1', 'Valor_Cuota')
                            ->setCellValue('H1', 'Valor_Seguro')
                            ->setCellValue('I1', 'Cuotas')
                            ->setCellValue('J1', 'Cuota_Actual')
                            ->setCellValue('K1', 'Pagado')
                            ->setCellValue('L1', 'Aprobado')
                            ->setCellValue('M1', 'Suspendido');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
                $arCreditos = $query->getResult();
                
                foreach ($arCreditos as $arCredito) {
                    if ($arCredito->getEstadoPagado() == 1)
                    {
                        $Estado = "SI";
                    }
                    else
                    {
                        $Estado = "NO"; 
                    }
                    if ($arCredito->getAprobado() == 1)
                    {
                        $Aprobado = "SI";
                    }
                    else
                    {
                        $Aprobado = "NO"; 
                    }
                    if ($arCredito->getEstadoSuspendido() == 1)
                    {
                        $Suspendido = "SI";
                    }
                    else
                    {
                        $Suspendido = "NO"; 
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCredito->getCodigoCreditoPk())
                            ->setCellValue('B' . $i, $arCredito->getCreditoTipoRel()->getNombre())
                            ->setCellValue('C' . $i, $arCredito->getFecha())
                            ->setCellValue('D' . $i, $arCredito->getEmpleadoRel()->getCentroCostoRel()->getNombre())
                            ->setCellValue('E' . $i, $arCredito->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arCredito->getVrPagar())
                            ->setCellValue('G' . $i, $arCredito->getVrCuota())
                            ->setCellValue('H' . $i, $arCredito->getSeguro())
                            ->setCellValue('I' . $i, $arCredito->getNumeroCuotas())
                            ->setCellValue('J' . $i, $arCredito->getNumeroCuotaActual())
                            ->setCellValue('K' . $i, $Estado)
                            ->setCellValue('L' . $i, $Aprobado)
                            ->setCellValue('M' . $i, $Suspendido);
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Creditos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Creditos.xlsx"');
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
