<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurCentroCostoType;
class BaseCentroCostoController extends Controller
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
                $em->getRepository('BrasaTurnoBundle:TurCentroCosto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_centrocosto_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arCentroCostos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/CentroCosto:lista.html.twig', array(
            'arCentrosCostos' => $arCentroCostos, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoCentroCosto = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCentroCosto = new \Brasa\TurnoBundle\Entity\TurCentroCosto();
        if($codigoCentroCosto != '' && $codigoCentroCosto != '0') {
            $arCentroCosto = $em->getRepository('BrasaTurnoBundle:TurCentroCosto')->find($codigoCentroCosto);
        }        
        $form = $this->createForm(new TurCentroCostoType, $arCentroCosto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCentroCosto = $form->getData();            
            $em->persist($arCentroCosto);
            $em->flush();            

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_centro_costo_nuevo', array('codigoCentroCosto' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_centro_costo_lista'));
            }                                                                                          
        }
        return $this->render('BrasaTurnoBundle:Base/CentroCosto:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'form' => $form->createView()));
    }        
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurCentroCosto')->listaDQL(
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
        $arrBotonEliminarPuesto = array('label' => 'Eliminar', 'disabled' => false);                
       
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnEliminarPuesto', 'submit', $arrBotonEliminarPuesto)            
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
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'ESTRATO')
                    ->setCellValue('E1', 'CONTACTO')
                    ->setCellValue('F1', 'TELEFONO')
                    ->setCellValue('G1', 'CELULAR');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arCentroCostos = new \Brasa\TurnoBundle\Entity\TurCentroCosto();
                $arCentroCostos = $query->getResult();
                
        foreach ($arCentroCostos as $arCentroCosto) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCentroCosto->getCodigoCentroCostoPk())
                    ->setCellValue('B' . $i, $arCentroCosto->getNit())
                    ->setCellValue('C' . $i, $arCentroCosto->getNombreCorto())
                    ->setCellValue('D' . $i, $arCentroCosto->getEstrato())
                    ->setCellValue('E' . $i, $arCentroCosto->getContacto())
                    ->setCellValue('F' . $i, $arCentroCosto->getTelefonoContacto())
                    ->setCellValue('G' . $i, $arCentroCosto->getCelularContacto());
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('CentroCosto');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CentroCostos.xlsx"');
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