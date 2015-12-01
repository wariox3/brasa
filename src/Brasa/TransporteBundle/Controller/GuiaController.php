<?php
namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TteGuiaType;
class GuiaController extends Controller
{
    var $strListaDql = "";
    var $codigoGuia = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTransporteBundle:TteGuia')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_programacion_lista'));                                 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arGuias = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTransporteBundle:Movimientos/Guia:lista.html.twig', array(
            'arGuias' => $arGuias, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoGuia) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
        if($codigoGuia != 0) {
            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
        }else{
            $arGuia->setFechaAnuncio(new \DateTime('now'));            
            $arGuia->setFechaGuia(new \DateTime('now'));            
        }        
        $form = $this->createForm(new TteGuiaType, $arGuia);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arGuia = $form->getData();                       
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
                $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arTercero) > 0) {
                    $arGuia->setTerceroRel($arTercero);
                    $em->persist($arGuia);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tte_recogida_nuevo', array('codigoGuia' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tte_recogida_detalle', array('codigoGuia' => $arGuia->getCodigoGuiaPk())));
                    }                      
                } else {
                    $objMensaje->Mensaje("error", "El tercero no existe", $this);
                }                             
            }            
            
        }
        return $this->render('BrasaTransporteBundle:Movimientos/Guia:nuevo.html.twig', array(
            'arGuia' => $arGuia,
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTransporteBundle:TteGuia')->listaDql($this->codigoGuia);
    }

    private function filtrar ($form) {        
        $this->codigoGuia = $form->get('TxtCodigo')->getData();
    }
    
    private function formularioFiltro() {        
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $session->get('filtroIdentificacion')))            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();        
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
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arGuias = new \Brasa\TransporteBundle\Entity\TteGuia();
        $arGuias = $query->getResult();

        foreach ($arGuias as $arGuia) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arGuia->getCodigoGuiaPk())
                    ->setCellValue('B' . $i, $arGuia->getTerceroRel()->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Guiaes');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Guiaes.xlsx"');
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