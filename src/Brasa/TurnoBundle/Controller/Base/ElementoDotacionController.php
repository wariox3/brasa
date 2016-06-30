<?php
namespace Brasa\TurnoBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurElementoDotacionType;
class ElementoDotacionController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/base/elemento/dotacion", name="brs_tur_base_elemento_dotacion")
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
                $em->getRepository('BrasaTurnoBundle:TurElementoDotacion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_elemento_dotacion'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arElementosDotaciones = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/ElementoDotacion:lista.html.twig', array(
            'arElementosDotaciones' => $arElementosDotaciones, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/elemento/dotacion/nuevo/{codigoElementoDotacion}", name="brs_tur_base_elemento_dotacion_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoElementoDotacion = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arElementoDotacion = new \Brasa\TurnoBundle\Entity\TurElementoDotacion();
        if($codigoElementoDotacion != '' && $codigoElementoDotacion != '0') {
            $arElementoDotacion = $em->getRepository('BrasaTurnoBundle:TurElementoDotacion')->find($codigoElementoDotacion);
        }        
        $form = $this->createForm(new TurElementoDotacionType, $arElementoDotacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arElementoDotacion = $form->getData();                        
            $em->persist($arElementoDotacion);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_elemento_dotacion_nuevo', array('codigoElementoDotacion' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_elemento_dotacion'));
            }                                   
        }
        return $this->render('BrasaTurnoBundle:Base/ElementoDotacion:nuevo.html.twig', array(
            'arElementoDotacion' => $arElementoDotacion,
            'form' => $form->createView()));
    }        

    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurElementoDotacion')->listaDQL(
                $session->get('filtroElementoDotacionNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroElementoDotacionNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroElementoDotacionNombre')))
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
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'COSTO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arElementoDotacions = new \Brasa\TurnoBundle\Entity\TurElementoDotacion();
        $arElementoDotacions = $query->getResult();
                
        foreach ($arElementoDotacions as $arElementoDotacion) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arElementoDotacion->getCodigoElementoDotacionPk())
                    ->setCellValue('B' . $i, $arElementoDotacion->getNombre())
                    ->setCellValue('C' . $i, $arElementoDotacion->getCosto());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('ElementoDotacion');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ElementoDotacions.xlsx"');
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