<?php
namespace Brasa\TurnoBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurOperacionType;
class OperacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/base/operacion", name="brs_tur_base_operacion")
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
                $em->getRepository('BrasaTurnoBundle:TurOperacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_operacion'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arOperaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Operacion:lista.html.twig', array(
            'arOperacions' => $arOperaciones, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/operacion/nuevo/{codigoOperacion}", name="brs_tur_base_operacion_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoOperacion = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arOperacion = new \Brasa\TurnoBundle\Entity\TurOperacion();
        if($codigoOperacion != '' && $codigoOperacion != '0') {
            $arOperacion = $em->getRepository('BrasaTurnoBundle:TurOperacion')->find($codigoOperacion);
        }        
        $form = $this->createForm(new TurOperacionType, $arOperacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arOperacion = $form->getData();                        
            $em->persist($arOperacion);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_operacion_nuevo', array('codigoOperacion' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_operacion'));
            }                                   
        }
        return $this->render('BrasaTurnoBundle:Base/Operacion:nuevo.html.twig', array(
            'arOperacion' => $arOperacion,
            'form' => $form->createView()));
    }        

    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurOperacion')->listaDQL('',
                $session->get('filtroOperacionNombre'), $session->get('filtroTurnosCodigoProyecto')   
                ); 
    }

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroOperacionNombre', $form->get('TxtNombre')->getData());
        $arProyecto = $form->get('proyectoRel')->getData();
        if($arProyecto) {
            $session->set('filtroTurnosCodigoProyecto', $arProyecto->getCodigoProyectoPk());
        } else {
            $session->set('filtroTurnosCodigoProyecto', null);
        }         
        $this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedadesProyecto = array(
                'class' => 'BrasaTurnoBundle:TurProyecto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroTurnosCodigoProyecto')) {
            $arrayPropiedadesProyecto['data'] = $em->getReference("BrasaTurnoBundle:TurProyecto", $session->get('filtroTurnosCodigoProyecto'));
        }         
        $form = $this->createFormBuilder()            
            ->add('proyectoRel', 'entity', $arrayPropiedadesProyecto)                 
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroOperacionNombre')))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'PROYECTO')    
                    ->setCellValue('C1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arOperaciones = new \Brasa\TurnoBundle\Entity\TurOperacion();
        $arOperaciones = $query->getResult();
                
        foreach ($arOperaciones as $arOperacion) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arOperacion->getCodigoOperacionPk())
                    ->setCellValue('B' . $i, $arOperacion->getProyectoRel()->getNombre())
                    ->setCellValue('C' . $i, $arOperacion->getNombre());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Operacion');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Operacions.xlsx"');
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