<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiCursoType;
class CursoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/curso", name="brs_afi_movimiento_curso")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            
            if($request->request->get('OpGenerar')) {            
                $codigoCurso = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->generar($codigoCurso);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }
            
            if($request->request->get('OpDeshacer')) {            
                $codigoCurso = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM afi_curso_detalle WHERE codigo_curso_fk = " . $codigoCurso;           
                $em->getConnection()->executeQuery($strSql);                 
                $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);                
                $arCurso->setEstadoGenerado(0);
                $em->persist($arCurso);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }
            if ($form->get('BtnAsistencia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCurso) {
                    $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                    $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
                    if($arCurso->getAsistencia() == 1) {
                        $arCurso->setAsistencia(0);
                    }  else {
                        $arCurso->setAsistencia(1);
                    }
                    $em->persist($arCurso);                    
                }                
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            } 
            if ($form->get('BtnCertificado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCurso) {
                    $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                    $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
                    if($arCurso->getCertificado() == 1) {
                        $arCurso->setCertificado(0);
                    }  else {
                        $arCurso->setCertificado(1);
                    }
                    $em->persist($arCurso);                    
                }                
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arCursos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:lista.html.twig', array(
            'arCursos' => $arCursos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/curso/nuevo/{codigoCurso}", name="brs_afi_movimiento_curso_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCurso = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        if($codigoCurso != '' && $codigoCurso != '0') {
            $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
        } else {
            $arCurso->setFechaVence(new \DateTime('now'));
            $arCurso->setFechaProgramacion(new \DateTime('now'));
        }        
        $form = $this->createForm(new AfiCursoType, $arCurso);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCurso = $form->getData();  
            $arCurso->setFecha(new \DateTime('now'));
            $em->persist($arCurso);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_nuevo', array('codigoCurso' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $arCurso->getCodigoCursoPk())));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:nuevo.html.twig', array(
            'arCurso' => $arCurso,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/movimiento/curso/detalle/{codigoCurso}", name="brs_afi_movimiento_curso_detalle")
     */    
    public function detalleAction(Request $request, $codigoCurso = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
        $form = $this->formularioDetalle($arCurso);
        $form->handleRequest($request);
        $this->listaDetalle($codigoCurso);
        if ($form->isValid()) {  
            if($form->get('BtnAutorizar')->isClicked()) {      
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoCurso);
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->autorizar($codigoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }            
            if($form->get('BtnDesAutorizar')->isClicked()) {                            
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->desAutorizar($codigoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->imprimir($codigoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    //$objFactura = new \Brasa\TurnoBundle\Formatos\FormatoFactura();
                    //$objFactura->Generar($this, $codigoFactura);                    
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }      
            if($form->get('BtnFacturar')->isClicked()) {            
                if($arCurso->getEstadoFacturado() == 0 && $arCurso->getEstadoAutorizado() == 1) {                    
                    $codigoFactura = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->facturar($codigoCurso,  $this->getUser()->getUsername(), 1);
                    if($codigoFactura != 0) {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));                                        
                    }                    
                }
            }            
            if($form->get('BtnCuentaCobro')->isClicked()) {            
                if($arCurso->getEstadoFacturado() == 0 && $arCurso->getEstadoAutorizado() == 1) {                    
                    $codigoFactura = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->facturar($codigoCurso,  $this->getUser()->getUsername(), 2);
                    if($codigoFactura != 0) {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));                                        
                    }                    
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoCurso);                                 
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }            
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->liquidar($codigoCurso);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }
        }
        
        $arCursoDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:detalle.html.twig', array(
            'arCurso' => $arCurso, 
            'arCursoDetalles' => $arCursoDetalles, 
            'form' => $form->createView()));
    }    

    /**
     * @Route("/afi/movimiento/curso/detalle/nuevo/{codigoCurso}", name="brs_afi_movimiento_curso_detalle_nuevo")
     */    
    public function detalleNuevoAction(Request $request, $codigoCurso = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                      
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCursoTipo) {
                    $arCursoTipo = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
                    $arCursoTipo = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->find($codigoCursoTipo);
                    $arCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
                    $arCursoDetalle->setCursoRel($arCurso);          
                    $arCursoDetalle->setCursoTipoRel($arCursoTipo);
                    $costo = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->costoCursoEntidadEntrenamiento($arCurso->getCodigoEntidadEntrenamientoFk(), $codigoCursoTipo);
                    $arCursoDetalle->setCosto($costo);
                    $arCursoDetalle->setPrecio($arCursoTipo->getPrecio());
                    $em->persist($arCursoDetalle);                    
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlCursosTipos = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->listaDql();
        $arCursoTipos = $paginator->paginate($em->createQuery($dqlCursosTipos), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:detalleNuevo.html.twig', array(
            'arCurso' => $arCurso, 
            'arCursoTipos' => $arCursoTipos, 
            'form' => $form->createView()));
    }    
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->listaDQL(
                $session->get('filtroCursoNombre')   
                ); 
    }
    
    private function listaDetalle($codigoCurso) {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->listaDQL(
                $codigoCurso   
                ); 
    }    

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroCursoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroCursoNombre')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnAsistencia', 'submit', array('label'  => 'Asistencia',))            
            ->add('BtnCertificado', 'submit', array('label'  => 'Certificado',))                            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }            
    
    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);      
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);    
        $arrBotonFacturar = array('label' => 'Facturar', 'disabled' => true);        
        $arrBotonCuentaCobro = array('label' => 'Cuenta cobro', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;            
            $arrBotonDetalleActualizar['disabled'] = true;

            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            } else {
                if($ar->getEstadoFacturado() == 0) {
                    if($ar->getNumero() > 0) {
                        $arrBotonFacturar['disabled'] = false;
                        $arrBotonCuentaCobro['disabled'] = false;
                    }                    
                }                
            }            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnFacturar', 'submit', $arrBotonFacturar)                
                    ->add('BtnCuentaCobro', 'submit', $arrBotonCuentaCobro)                
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)                
                    ->add('BtnDetalleActualizar', 'submit', $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;
    }    
    
    private function formularioDetalleNuevo() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()     
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoNombre')))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))            
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
        for($col = 'A'; $col !== 'P'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'O'; $col !== 'P'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'VENCE')
                    ->setCellValue('E1', 'PROGRAMADO')
                    ->setCellValue('F1', 'NIT')
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'IDENTIFICACION')
                    ->setCellValue('I1', 'EMPLEADO')
                    ->setCellValue('J1', 'FAC')
                    ->setCellValue('K1', 'AUT')
                    ->setCellValue('L1', 'ASI')
                    ->setCellValue('M1', 'CER')
                    ->setCellValue('N1', 'ANU')
                    ->setCellValue('O1', 'TOTAL');

        $i = 2;        
        $query = $em->createQuery($this->strDqlLista);
        $arCursos = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCursos = $query->getResult();
                
        foreach ($arCursos as $arCurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCurso->getCodigoCursoPk())
                    ->setCellValue('B' . $i, $arCurso->getNumero())
                    ->setCellValue('C' . $i, $arCurso->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arCurso->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arCurso->getFechaProgramacion()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arCurso->getClienteRel()->getNit())
                    ->setCellValue('G' . $i, $arCurso->getClienteRel()->getNombreCorto())
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arCurso->getEstadoFacturado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arCurso->getEstadoAutorizado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arCurso->getAsistencia()))
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arCurso->getCertificado()))
                    ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arCurso->getEstadoAnulado()))
                    ->setCellValue('O' . $i, $arCurso->getTotal());
            
            if($arCurso->getCodigoEmpleadoFk() != null) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arCurso->getEmpleadoRel()->getNumeroIdentificacion());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $arCurso->getEmpleadoRel()->getNombreCorto());
            }
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Curso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cursos.xlsx"');
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

    private function actualizarDetalle($arrControles, $codigoCurso) {
        $em = $this->getDoctrine()->getManager();        
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle;
                $arCursoDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->find($intCodigo);
                $arCursoDetalle->setPrecio($arrControles['TxtPrecio'.$intCodigo]);                             
                $arCursoDetalle->setCosto($arrControles['TxtCosto'.$intCodigo]);                             
                $em->persist($arCursoDetalle);
            }
            $em->flush();                
            $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->liquidar($codigoCurso);            
        }        
    }        
    
}