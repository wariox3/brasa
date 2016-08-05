<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurConceptoServicioType;
class BaseConceptoServicioController extends Controller
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
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurConceptoServicio')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_concepto_servicio_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arConceptoServicios = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/ConceptoServicio:lista.html.twig', array(
            'arConceptoServicios' => $arConceptoServicios, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoConceptoServicio) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConceptoServicio = new \Brasa\TurnoBundle\Entity\TurConceptoServicio();
        if($codigoConceptoServicio != 0) {
            $arConceptoServicio = $em->getRepository('BrasaTurnoBundle:TurConceptoServicio')->find($codigoConceptoServicio);
        }        
        $form = $this->createForm(new TurConceptoServicioType, $arConceptoServicio);
        $form->handleRequest($request);
        if ($form->isValid()) {
            //$arrControles = $request->request->All();
            $arConceptoServicio = $form->getData(); 
            $em->persist($arConceptoServicio);
            $em->flush();            

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_concepto_servicio_nuevo', array('codigoConceptoServicio' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_concepto_servicio_lista'));
            }                    
                
            
                       

        }
        return $this->render('BrasaTurnoBundle:Base/ConceptoServicio:nuevo.html.twig', array(
            'arConceptoServicio' => $arConceptoServicio,
            'form' => $form->createView()));
    }            
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurConceptoServicio')->listaDQL(
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
                    ->setCellValue('C1', 'H')
                    ->setCellValue('D1', 'HD')
                    ->setCellValue('E1', 'HN');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arConceptoServicios = new \Brasa\TurnoBundle\Entity\TurConceptoServicio();
                $arConceptoServicios = $query->getResult();
                
        foreach ($arConceptoServicios as $arConceptoServicio) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arConceptoServicio->getCodigoConceptoServicioPk())
                    ->setCellValue('B' . $i, $arConceptoServicio->getNombre())
                    ->setCellValue('C' . $i, $arConceptoServicio->getHoras())
                    ->setCellValue('D' . $i, $arConceptoServicio->getHorasDiurnas())
                    ->setCellValue('E' . $i, $arConceptoServicio->getHorasNocturnas());
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('ConceptoServicio');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConceptoServicios.xlsx"');
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