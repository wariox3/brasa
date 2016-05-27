<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiNovedadType;
class NovedadController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/novedad", name="brs_afi_movimiento_novedad")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                               
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_novedad'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arNovedades = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Novedad:lista.html.twig', array(
            'arNovedades' => $arNovedades, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/novedad/nuevo/{codigoNovedad}", name="brs_afi_movimiento_novedad_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoNovedad = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arNovedad = new \Brasa\AfiliacionBundle\Entity\AfiNovedad();
        if($codigoNovedad != '' && $codigoNovedad != '0') {
            $arNovedad = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->find($codigoNovedad);
        } else {
            $fecha = new \DateTime('now');
            $arNovedad->setFechaDesde($fecha);
            $arNovedad->setFechaHasta($fecha);
        }        
        $form = $this->createForm(new AfiNovedadType, $arNovedad);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arNovedad = $form->getData();
            $arrControles = $request->request->All();
            if($arrControles['txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
                $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));                                        
                if(count($arEmpleado) > 0) {
                    $arNovedad->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivo()) {
                        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
                        $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($arEmpleado->getCodigoContratoActivo());                                                                
                        $arNovedad->setContratoRel($arContrato);
                    }                    
                }   
            }
            $em->persist($arNovedad);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_novedad_nuevo', array('codigoNovedad' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_movimiento_novedad'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Novedad:nuevo.html.twig', array(
            'arNovedad' => $arNovedad,
            'form' => $form->createView()));
    }        
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->listaDQL(
                  
                ); 
    }   
    
    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroNovedadNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNovedadNombre')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
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
        for($col = 'A'; $col !== 'C'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        /*for($col = 'AI'; $col !== 'AK'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }*/         
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'TIPO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arNovedades = new \Brasa\AfiliacionBundle\Entity\AfiNovedad();
        $arNovedades = $query->getResult();
                
        foreach ($arNovedades as $arNovedad) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNovedad->getCodigoNovedadPk())
                    ->setCellValue('B' . $i, $arNovedad->getNovedadTipoRel()->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Novedad');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Novedads.xlsx"');
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