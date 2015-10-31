<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDesempenoType;
use Doctrine\ORM\EntityRepository;

class DesempenosController extends Controller
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

            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDesempeno) {
                        $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
                        $arDesempenoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->findBy(array('codigoDesempenoFk' => $codigoDesempeno));
                        foreach ($arDesempenoDetalles AS $arDesempenoDetalle) {
                            $em->remove($arDesempenoDetalle);
                        }
                        $em->remove($arDesempeno);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_lista'));
                }
            }
        }

        $arDesempenos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Desempenos:lista.html.twig', array(
            'arDesempenos' => $arDesempenos,
            'form' => $form->createView()));
    }
    
    public function nuevoAction($codigoDesempeno) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        if ($codigoDesempeno != 0){
            $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
        }else{
            $arDesempeno->setFecha(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuDesempenoType(), $arDesempeno);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($codigoDesempeno != 0){
                $arDesempeno = $form->getData();
                $em->persist($arDesempeno);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }else{
                $arrControles = $request->request->All();
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('numeroIdentificacion' => $arrControles['numeroIdentificacion'], 'estadoActivo' => 1));
                if (count($arEmpleado) == 0){
                    $objMensaje->Mensaje("error", "No existe el número de identificación", $this);
                } else {
                    $arEmpleadoFinal = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleadoFinal = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arEmpleado[0]);
                    if ($arEmpleadoFinal->getEstadoContratoActivo() == 0){
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato", $this);
                    }else{
                        $arDesempeno = $form->getData();
                        $arDesempeno->setEmpleadoRel($arEmpleadoFinal);
                        $arCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
                        $arCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->find($arEmpleadoFinal->getCodigoCargoFk());
                        $arDesempeno->setCargoRel($arCargo);
                        $em->persist($arDesempeno);
                        $arDesempenosConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoConcepto();
                        $arDesempenosConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoConcepto')->findAll();
                        foreach ($arDesempenosConceptos as $arDesempenoConcepto) {
                            $arDesempenoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                            $arDesempenoDetalle->setDesempenoRel($arDesempeno);
                            $arDesempenoDetalle->setDesempenoConceptoRel($arDesempenoConcepto);
                            $em->persist($arDesempenoDetalle);
                        }
                        $em->flush();
                        echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    }
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Desempenos:nuevo.html.twig', array(
            'arDesempeno' => $arDesempeno,
            'codigoDesempeno' => $codigoDesempeno,
            'form' => $form->createView()));
    }
    
    public function detalleAction($codigoDesempeno) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $arDesempeno = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempeno();
        $arDesempeno = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->find($codigoDesempeno);
        $form = $this->formularioDetalle($arDesempeno);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 0) {
                    $arDesempeno->setEstadoAutorizado(1);
                    $em->persist($arDesempeno);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 1) {
                    $arDesempeno->setEstadoAutorizado(0);
                    $em->persist($arDesempeno);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormato = new \Brasa\RecursoHumanoBundle\Formatos\FormatoDesempenos();
                $objFormato->Generar($this, $codigoDesempeno);
            }
            if($form->get('BtnCerrar')->isClicked()) {
                if($arDesempeno->getEstadoAutorizado() == 1) {
                    $arDesempeno->setEstadoCerrado(1);
                    $em->persist($arDesempeno);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
                }
            }            
            
            if($form->get('BtnEliminarDetalle')->isClicked()) {  
                $arrSeleccionados = $request->request->get('ChkSeleccionar');                                                   
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigoDesempenoDetalle) {
                        $arDesempenoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                        $arDesempenoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->find($codigoDesempenoDetalle);                        
                        $em->remove($arDesempenoDetalle);                        
                    }
                    $em->flush();                    
                } 
                return $this->redirect($this->generateUrl('brs_rhu_desempeno_detalle', array('codigoDesempeno' => $codigoDesempeno)));
            } 
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {
                    if($arrControles['TxtSiempre'.$intCodigo] != 0 && $arrControles['TxtCasiSiempre'.$intCodigo] != 0 && $arrControles['TxtAlgunasVeces'.$intCodigo] != 0 && $arrControles['TxtCasiNunca'.$intCodigo] != 0 && $arrControles['TxtNunca'.$intCodigo] != 0) {
                        $intSiempre = $arrControles['TxtSiempre'.$intCodigo];
                        $intCasiSiempre = $arrControles['TxtCasiSiempre'.$intCodigo];
                        $intAlgunasVeces = $arrControles['TxtAlgunasVeces'.$intCodigo];
                        $intCasiNunca = $arrControles['TxtCasiNunca'.$intCodigo];
                        $intNunca = $arrControles['TxtNunca'.$intCodigo];
                        $arDesempenoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
                        $arDesempenoDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->find($intCodigo);
                        $arDesempenoDetalle->setSiempre($intSiempre);
                        $arDesempenoDetalle->setCasiSiempre($intCasiSiempre);
                        $arDesempenoDetalle->setAlgunasVeces($intAlgunasVeces);
                        $arDesempenoDetalle->setCasiNunca($intCasiNunca);
                        $arDesempenoDetalle->setNunca($intNunca);
                        $em->persist($arDesempenoDetalle);
                    }
                }                
                $em->flush();
            }            
        }
        $arDesempenosDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuDesempenoDetalle();
        $arDesempenosDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempenoDetalle')->findBy(array('codigoDesempenoFk' => $codigoDesempeno));
        $arDesempenosDetalles = $paginator->paginate($arDesempenosDetalles, $this->get('request')->query->get('page', 1),100);
        return $this->render('BrasaRecursoHumanoBundle:Desempenos:detalle.html.twig', array(
                        'arDesempenosDetalles' => $arDesempenosDetalles,
                        'arDesempeno' => $arDesempeno,
                        'form' => $form->createView()
                    ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDesempeno')->listaDql();
    }
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
    }

    private function formularioLista() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
                ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($arDesempeno) {
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonCerrar = array('label' => 'Cerrar', 'disabled' => false);        
        $arrBotonActualizarDetalle = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonEliminarDetalle = array('label' => 'Eliminar', 'disabled' => false);

        if($arDesempeno->getEstadoAutorizado() == 1) {
            $arrBotonAutorizar['disabled'] = true;
            $arrBotonEliminarDetalle['disabled'] = true;
            $arrBotonActualizarDetalle['disabled'] = true;
            if($arDesempeno->getEstadoCerrado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonCerrar['disabled'] = true;
            }
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
            $arrBotonCerrar['disabled'] = true;
        }
        $form = $this->createFormBuilder()
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnCerrar', 'submit', $arrBotonCerrar)
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)
                    ->add('BtnEliminarDetalle', 'submit', $arrBotonEliminarDetalle)
                    ->add('BtnActualizarDetalle', 'submit', $arrBotonActualizarDetalle)
                    ->getForm();
        return $form;


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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CARGO');
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arDesempenos = $query->getResult();
        foreach ($arDesempenos as $arDesempeno) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDesempeno->getCodigoDesempenoPk())
                    ->setCellValue('B' . $i, $arDesempeno->getFecha()->format('Y-m-d'))
                    ->setCellValue('C' . $i, $arDesempeno->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arDesempeno->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arDesempeno->getCargoRel()->getNombre());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Desempenos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Desempenos.xlsx"');
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
