<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Programacion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;


class ImportarController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/programacion/importar", name="brs_tur_utilidad_programacion_importar")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 87)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $fecha = new \DateTime('now');
                if($periodo != 0 && $periodo != "") {
                    $arPagoAdicionalPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalPeriodo();                                    
                    $arPagoAdicionalPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicionalPeriodo')->find($periodo);            
                    $fecha = $arPagoAdicionalPeriodo->getFecha();                    
                }
                
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "archivo.xls");                
                $ruta = $rutaTemporal->getRutaTemporal(). "archivo.xls";                
                $arrCarga = array();
                $objPHPExcel = \PHPExcel_IOFactory::load($ruta);                
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle     = $worksheet->getTitle();
                    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;
                    for ($row = 2; $row <= $highestRow; ++ $row) {                        
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $concepto = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $identificacion = $cell->getValue();                       
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $tipo = $cell->getValue();                                                
                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                        $valor = $cell->getValue();  
                        $cell = $worksheet->getCellByColumnAndRow(4, $row);
                        $detalle = $cell->getValue();                          
                        $arrCarga[] = array(
                            'concepto' => $concepto,
                            'identificacion' => $identificacion,
                            'tipo' => $tipo,
                            'valor' => $valor,
                            'detalle' => $detalle);
                    }
                }
                $error = "";
                foreach ($arrCarga as $carga) {
                    $arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    if($carga['concepto']) {
                        $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($carga['concepto']);                        
                    } 
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    if($carga['identificacion']) {
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $carga['identificacion']));    
                    }                    
                    if($arPagoConcepto) {
                        if($arEmpleado) {
                            $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();                            
                            $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                            $arPagoAdicional->setEmpleadoRel($arEmpleado);
                            $arPagoAdicional->setPermanente(1);
                            $arPagoAdicional->setValor($carga['valor']);
                            $arPagoAdicional->setTipoAdicional($carga['tipo']);
                            $arPagoAdicional->setDetalle($carga['detalle']);
                            $arPagoAdicional->setModalidad(1);
                            if($periodo != 0 && $periodo != "") {
                                $arPagoAdicional->setPermanente(0);
                                $arPagoAdicional->setModalidad(2);
                                $arPagoAdicional->setCodigoPeriodoFk($periodo);
                                $arPagoAdicional->setFecha($fecha);
                            }
                            $arPagoAdicional->setFechaCreacion(new \DateTime('now'));
                            $arPagoAdicional->setFechaUltimaEdicion(new \DateTime('now'));                            
                            $arPagoAdicional->setCodigoUsuario($arUsuario->getUserName());                            
                            $em->persist($arPagoAdicional);                             
                        } else {
                            $error .= "Empleado" . $carga['identificacion'] . " no existe ";
                        }                       
                    } else {
                        $error .= "Concepto" . $carga['concepto'] . " no existe ";
                    }                    
                }
                if($error != "") {
                    //echo "Error al cargar:" . $error;
                    $objMensaje->Mensaje('error', "Error al cargar:" . $error, $this);
                } else {
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                }
                
                
            } 
        }                 
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->listaDql();
        $arProgramacionesImportar = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:importar.html.twig', array(            
            'arProgramacionesImportar' => $arProgramacionesImportar,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                        
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))   
            ->getForm();        
        return $form;
    }        

}
