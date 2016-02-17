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
    var $codigoCentroCosto = "";
    var $strNumeroIdentificacion = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnActivarInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados as $codigo) {
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigo);
                        if($arRecurso->getEstadoActivo() == 1) {
                            $arRecurso->setEstadoActivo(0);
                        } else {
                            $arRecurso->setEstadoActivo(1);
                        }
                        $em->persist($arRecurso);
                        $em->flush();
                    }
                }                
                return $this->redirect($this->generateUrl('brs_tur_base_recurso_lista'));
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

    public function nuevoAction($codigoRecurso = '', $codigoEmpleado = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        if($codigoRecurso != '' && $codigoRecurso != '0') {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        } else {
            $arRecurso->setEstadoActivo(1);
            if($codigoEmpleado != '' && $codigoEmpleado != '0') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);                
                if($arEmpleado) {
                    $arRecursoValidar = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));                 
                    if(!$arRecursoValidar) {
                        $arRecurso->setEmpleadoRel($arEmpleado);
                        $arRecurso->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arRecurso->setNombreCorto($arEmpleado->getNombreCorto());
                        $arRecurso->setTelefono($arEmpleado->getTelefono());
                        $arRecurso->setCelular($arEmpleado->getCelular());
                        $arRecurso->setDireccion($arEmpleado->getDireccion());
                        $arRecurso->setCorreo($arEmpleado->getCorreo());
                        $arRecurso->setFechaNacimiento($arEmpleado->getFechaNacimiento());
                    }else {
                        $objMensaje->Mensaje("error", "El recurso " . $arEmpleado->getNombreCorto() . " ya existe", $this);
                    }                    
                } 
            }
        }        
        $form = $this->createForm(new TurRecursoType, $arRecurso);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('guardar')->isClicked()) {
                $arrControles = $request->request->All();
                $arRecurso = $form->getData(); 
                if($arrControles['txtNumeroIdentificacion'] != '') {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                    if(count($arEmpleado) > 0) {
                        if($arRecurso->getCodigoTurnoFijoNominaFk()) {
                            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
                            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($arRecurso->getCodigoTurnoFijoNominaFk());
                            if($arTurno) {
                                $arRecurso->setEmpleadoRel($arEmpleado);
                                $em->persist($arRecurso);
                                $em->flush();            

                                if($form->get('guardarnuevo')->isClicked()) {
                                    return $this->redirect($this->generateUrl('brs_tur_base_recurso_nuevo', array('codigoRecurso' => 0, 'codigoEmpleado' => 0 )));
                                } else {
                                    return $this->redirect($this->generateUrl('brs_tur_base_recurso_lista'));
                                }                                 
                            } else {
                                $objMensaje->Mensaje("error", "Asigno un turno fijo de nomina pero ese codigo no existe", $this);
                            }
                        }                   
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no existe", $this);
                    }
                }                
            } 
            if($form->get('BtnActualizar')->isClicked()) {
                if($codigoRecurso != '' && $codigoRecurso != '0') {
                    $arEmpleado = $arRecurso->getEmpleadoRel();                                                            
                    $arRecurso->setTelefono($arEmpleado->getTelefono());
                    $arRecurso->setCelular($arEmpleado->getCelular());
                    $arRecurso->setDireccion($arEmpleado->getDireccion());
                    $arRecurso->setCorreo($arEmpleado->getCorreo());
                    $arRecurso->setFechaNacimiento($arEmpleado->getFechaNacimiento());                    
                    $em->persist($arRecurso);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_base_recurso_nuevo', array('codigoRecurso' => $codigoRecurso, 'codigoEmpleado' => 0 )));
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
            if($form->get('BtnImprimir')->isClicked()) {
            }            
        }

        //$arRecursoDetalle = new \Brasa\TurnoBundle\Entity\TurRecursoDetalle();
        //$arRecursoDetalle = $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->findBy(array ('codigoProgramacionFk' => $codigoRecurso));
        return $this->render('BrasaTurnoBundle:Base/Recurso:detalle.html.twig', array(
                    'arRecurso' => $arRecurso,                    
                    'form' => $form->createView()
                    ));
    }    
    
    public function enlazarAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioEnlazar();
        $form->handleRequest($request);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {     
                $arCentroCosto = $form->get('centroCostoRel')->getData();
                $codigoCentroCosto = "";
                if($arCentroCosto) {
                    $codigoCentroCosto = $arCentroCosto->getCodigoCentroCostoPk();
                }
                $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaDQL(
                        $form->get('TxtNombreCorto')->getData(), 
                        $codigoCentroCosto, 1,
                        $form->get('TxtIdentificacion')->getData(), "", 1));
                $arEmpleados = $query->getResult();                
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Recurso:enlazar.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()));
    }    
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurRecurso')->listaDQL(
                $this->strNombre,                
                $this->strCodigo,
                $this->codigoCentroCosto,
                $this->strNumeroIdentificacion                
                ); 
    }

    private function filtrar ($form) {
        $arCentroCosto = $form->get('centroCostoRel')->getData();
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strNumeroIdentificacion = $form->get('TxtNumeroIdentificacion')->getData();
        if($arCentroCosto) {
            $this->codigoCentroCosto = $arCentroCosto->getCodigoCentroCostoPk();
        }
        $this->lista();
    }
    
    private function formularioFiltro() {
        $arrayPropiedadesCentroCosto = array(
                'class' => 'BrasaTurnoBundle:TurCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($this->codigoCentroCosto != "") {
            $arrayPropiedadesCentroCosto['data'] = $em->getReference("BrasaTurnoBundle:TurCentroCosto", $this->codigoCentroCosto);
        }        
        $form = $this->createFormBuilder()            
            ->add('centroCostoRel', 'entity', $arrayPropiedadesCentroCosto)
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                  
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'NumeroIdentificacion','data' => $this->strNumeroIdentificacion))                            
            ->add('BtnActivarInactivar', 'submit', array('label'  => 'Activar / Inactivar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)                       
                    ->getForm();  
        return $form;
    }

    private function formularioEnlazar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))                
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionSeleccion')))
            ->add('TxtNombreCorto', 'text', array('label'  => 'Nombre'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'TIPO')
                    ->setCellValue('E1', 'CENTRO COSTOS')
                    ->setCellValue('F1', 'ACTIVO')
                    ->setCellValue('G1', 'TURNO FIJO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos = $query->getResult();
                
        foreach ($arRecursos as $arRecurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecurso->getCodigoRecursoPk())
                    ->setCellValue('B' . $i, $arRecurso->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arRecurso->getNombreCorto())
                    ->setCellValue('D' . $i, $arRecurso->getRecursoTipoRel()->getNombre())
                    ->setCellValue('E' . $i, $arRecurso->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arRecurso->getEstadoActivo())) 
                    ->setCellValue('G' . $i, $arRecurso->getCodigoTurnoFijoNominaFk());
                        
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