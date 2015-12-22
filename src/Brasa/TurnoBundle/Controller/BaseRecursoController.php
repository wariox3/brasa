<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurRecursoType;
class BaseRecursoController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                //$em->getRepository('BrasaTurnoBundle:TurRecurso')->eliminarExamen($arrSeleccionados);
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arRecursos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Recurso:lista.html.twig', array(
            'arRecursos' => $arRecursos, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoRecurso = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        if($codigoRecurso != '' && $codigoRecurso != '0') {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        }        
        $form = $this->createForm(new TurRecursoType, $arRecurso);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arRecurso = $form->getData(); 
            if($arrControles['txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arRecurso->setEmpleadoRel($arEmpleado);
                    $em->persist($arRecurso);
                    $em->flush();            

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_base_recurso_nuevo', array('codigoRecurso' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_base_recurso_lista'));
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
                       

        }
        return $this->render('BrasaTurnoBundle:Base/Recurso:nuevo.html.twig', array(
            'arRecurso' => $arRecurso,
            'form' => $form->createView()));
    }        

    public function detalleAction($codigoRecurso) {
        $em = $this->getDoctrine()->getManager(); 
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        $form = $this->formularioDetalle($arRecurso);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arRecurso->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->numeroRegistros($codigoRecurso) > 0) {
                        $arRecurso->setEstadoAutorizado(1);
                        $em->persist($arRecurso);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));                                                                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));                                                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arRecurso->getEstadoAutorizado() == 1) {
                    $arRecurso->setEstadoAutorizado(0);
                    $em->persist($arRecurso);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));                                                
                }
            }
            if ($form->get('BtnAprobar')->isClicked()) {                
                $strRespuesta = $em->getRepository('BrasaTurnoBundle:TurRecurso')->aprobarExamen($codigoRecurso);
                if($strRespuesta == ''){
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));                                                
                }else {
                  $objMensaje->Mensaje('error', $strRespuesta, $this);
                }                 
            }      
            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arRecurso->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\TurnoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($this, $codigoRecurso);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada", $this);
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurRecurso')->liquidar($codigoRecurso);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));
            }
            if($form->get('BtnAprobarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->aprobarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));
            }
            if($form->get('BtnCerrarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->cerrarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));
            }            
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    if($arrControles['TxtPrecio'.$intCodigo] != "" && $arrControles['TxtPrecio'.$intCodigo] != 0) {
                        $arRecursoDetalle = new \Brasa\TurnoBundle\Entity\TurRecursoDetalle();
                        $arRecursoDetalle = $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->find($intCodigo);                                        
                        $floPrecio = $arrControles['TxtPrecio'.$intCodigo];
                        $arRecursoDetalle->setValidarVencimiento($arrControles['cboValidarVencimiento'.$intCodigo]);
                        $arRecursoDetalle->setFechaVence(date_create($arrControles['TxtVence'.$intCodigo]));
                        $arRecursoDetalle->setVrPrecio($floPrecio);
                        $em->persist($arRecursoDetalle);                        
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurRecurso')->liquidar($codigoRecurso);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoRecurso)));
            }            
        }

        $arRecursoDetalle = new \Brasa\TurnoBundle\Entity\TurRecursoDetalle();
        $arRecursoDetalle = $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->findBy(array ('codigoProgramacionFk' => $codigoRecurso));
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalle.html.twig', array(
                    'arRecurso' => $arRecurso,
                    'arRecursoDetalle' => $arRecursoDetalle,
                    'form' => $form->createView()
                    ));
    }

    public function detalleNuevoAction($codigoRecurso, $codigoRecursoDetalle = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        $arRecursoDetalle = new \Brasa\TurnoBundle\Entity\TurRecursoDetalle();
        if($codigoRecursoDetalle != 0) {
            $arRecursoDetalle = $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->find($codigoRecursoDetalle);
        }       
        $form = $this->createForm(new TurRecursoDetalleType, $arRecursoDetalle);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRecursoDetalle = $form->getData();            
            $arRecursoDetalle->setProgramacionRel($arRecurso);
            $em->persist($arRecursoDetalle);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_programacion_detalle_nuevo', array('codigoProgramacion' => $codigoRecurso, 'codigoProgramacionDetalle' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Programacion:detalleNuevo.html.twig', array(
            'arRecurso' => $arRecurso,
            'form' => $form->createView()));
    }   
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurRecurso')->listaDQL(
                $this->strNombre,                
                $this->strCodigo   
                ); 
    }

    private function filtrar ($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }
    
    private function formularioFiltro() {
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);        
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {
            $arrBotonDetalleActualizar['disabled'] = true;
        }        
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)    
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)            
                    ->getForm();  
        return $form;
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos = $query->getResult();
                
        foreach ($arRecursos as $arRecurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecurso->getCodigoRecursoPk())
                    ->setCellValue('B' . $i, $arRecurso->getNombreCorto());
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Recurso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Recursos.xlsx"');
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