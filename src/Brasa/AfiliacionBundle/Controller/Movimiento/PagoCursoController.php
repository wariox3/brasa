<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiPagoCursoType;
class PagoCursoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/pago/curso", name="brs_afi_movimiento_pago_curso")
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
                $codigoPagoCurso = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->generar($codigoPagoCurso);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso'));
            }
            
            if($request->request->get('OpDeshacer')) {            
                $codigoPagoCurso = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM afi_pago_curso_detalle WHERE codigo_pago_curso_fk = " . $codigoPagoCurso;           
                $em->getConnection()->executeQuery($strSql);                 
                $arPagoCurso = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();
                $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);                
                $arPagoCurso->setEstadoGenerado(0);
                $em->persist($arPagoCurso);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso'));
            }            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arPagoCursos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/PagoCurso:lista.html.twig', array(
            'arPagoCursos' => $arPagoCursos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/pago/curso/nuevo/{codigoPagoCurso}", name="brs_afi_movimiento_pago_curso_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoPagoCurso = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arPagoCurso = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();
        if($codigoPagoCurso != '' && $codigoPagoCurso != '0') {
            $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);
        } else{
            $arPagoCurso->setFecha(new \DateTime('now'));            
        }       
        $form = $this->createForm(new AfiPagoCursoType, $arPagoCurso);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPagoCurso = $form->getData();                          
            $arUsuario = $this->getUser(); 
            $arPagoCurso->setUsuario($arUsuario->getUserName());
            $em->persist($arPagoCurso);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso_nuevo', array('codigoPagoCurso' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso_detalle', array('codigoPagoCurso' => $arPagoCurso->getCodigoPagoCursoPk())));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/PagoCurso:nuevo.html.twig', array(
            'arPagoCurso' => $arPagoCurso,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/movimiento/pago/curso/detalle/{codigoPagoCurso}", name="brs_afi_movimiento_pago_curso_detalle")
     */    
    public function detalleAction(Request $request, $codigoPagoCurso = '') {
        $em = $this->getDoctrine()->getManager();  
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $arPagoCurso = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();
        $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);
        $form = $this->formularioDetalle($arPagoCurso);
        $form->handleRequest($request);        
        if ($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {      
                $arrControles = $request->request->All();                
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->autorizar($codigoPagoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso_detalle', array('codigoPagoCurso' => $codigoPagoCurso)));
            }            
            if($form->get('BtnDesAutorizar')->isClicked()) {                            
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->desAutorizar($codigoPagoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso_detalle', array('codigoPagoCurso' => $codigoPagoCurso)));
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->imprimir($codigoPagoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {                    
                    $objPagoCurso = new \Brasa\AfiliacionBundle\Formatos\PagoCurso();
                    $objPagoCurso->Generar($this, $codigoPagoCurso);                    
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso_detalle', array('codigoPagoCurso' => $codigoPagoCurso)));
            }                        
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPagoCursoDetalle')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->liquidar($codigoPagoCurso);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_pago_curso_detalle', array('codigoPagoCurso' => $codigoPagoCurso)));
            }            
        }
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCursoDetalle')->listaDQL($codigoPagoCurso); 
        $arPagoCursoDetalles = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/PagoCurso:detalle.html.twig', array(
            'arPagoCurso' => $arPagoCurso,            
            'arPagoCursoDetalles' => $arPagoCursoDetalles,
            'form' => $form->createView()));
    }                

    /**
     * @Route("/afi/movimiento/pago/curso/detalle/curso/nuevo/{codigoPagoCurso}", name="brs_afi_movimiento_pago_curso_detalle_curso_nuevo")
     */    
    public function detalleCursoNuevoAction(Request $request, $codigoPagoCurso = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $arPagoCurso = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();
        $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                      
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCursoDetalle) {
                    $arCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
                    $arCursoDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->find($codigoCursoDetalle);
                    $arPagoCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle();
                    $arPagoCursoDetalle->setPagoCursoRel($arPagoCurso);          
                    $arPagoCursoDetalle->setCursoDetalleRel($arCursoDetalle);
                    $arPagoCursoDetalle->setCosto($arCursoDetalle->getCosto());
                    $em->persist($arPagoCursoDetalle);   
                    $arCursoDetalle->setEstadoPagado(1);
                    $em->persist($arCursoDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->liquidar($codigoPagoCurso);
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlCursosPendientes = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->pendientePagoDql($arPagoCurso->getCodigoProveedorFk());
        $arCursosDetalles = $paginator->paginate($em->createQuery($dqlCursosPendientes), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/PagoCurso:detalleCursoNuevo.html.twig', array(
            'arPagoCurso' => $arPagoCurso, 
            'arCursosDetalles' => $arCursosDetalles, 
            'form' => $form->createView()));
    }    
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->listaDQL(
                $session->get('filtroPagoCursoNombre')   
                ); 
    }      

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroPagoCursoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroPagoCursoNombre')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);      
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;                        
            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            }            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)
                    ->add('BtnAnular', 'submit', $arrBotonAnular)                                    
                    ->add('BtnDetalleEliminar', 'submit', $arrBotonDetalleEliminar)                    
                    
                    ->getForm();
        return $form;
    }        

    private function formularioDetalleNuevo() {        
        $form = $this->createFormBuilder()                 
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))                        
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arPagoCursos = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();
        $arPagoCursos = $query->getResult();
                
        foreach ($arPagoCursos as $arPagoCurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPagoCurso->getCodigoPagoCursoPk())
                    ->setCellValue('B' . $i, $arPagoCurso->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('PagoCurso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagoCursos.xlsx"');
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

    private function actualizarDetalle($arrControles, $codigoPagoCurso) {
        $em = $this->getDoctrine()->getManager();        
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arPagoCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle;
                $arPagoCursoDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCursoDetalle')->find($intCodigo);
                $arPagoCursoDetalle->setPrecio($arrControles['TxtPrecio'.$intCodigo]);                             
                $em->persist($arPagoCursoDetalle);
            }
            $em->flush();                
            $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->liquidar($codigoPagoCurso);            
        }        
    }            

}