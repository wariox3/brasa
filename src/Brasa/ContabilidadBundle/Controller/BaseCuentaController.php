<?php

namespace Brasa\ContabilidadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\ContabilidadBundle\Form\Type\CtbCuentaType;



class BaseCuentaController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        $this->listar($form); 
        $arCuentas = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCuenta) {
                    $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
                    $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuenta);
                    $em->remove($arCuenta);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        
        //$arCuentas = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
        //$query = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->findAll();
        //$arCuentas = $paginator->paginate($query, $this->get('request')->query->get('page', 1),35);
        $arCuentas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 35);                                       
        return $this->render('BrasaContabilidadBundle:Base/Cuentas:lista.html.twig', array(
                    'arCuentas' => $arCuentas,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoCuenta) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCuenta = new \Brasa\ContabilidadBundle\Entity\CtbCuenta();
        if ($codigoCuenta != 0)
        {
            $arCuenta = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->find($codigoCuenta);
        }    
        $form = $this->createForm(new CtbCuentaType(), $arCuenta);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arCuentaPadre = $form->get('codigoCuentaPadreFk')->getData();
            $arCuenta = $form->getData();
            $arCuenta->setCodigoCuentaPadreFk($arCuentaPadre->getCodigoCuentaPk());
            if ($codigoCuenta == 0){
                $arCuenta->setNombreCuenta($arCuenta->getCodigoCuentaPk()." ".$arCuenta->getNombreCuenta());
            }else {
                $arCuenta->setNombreCuenta($arCuenta->getNombreCuenta());
            }
            
            $em->persist($arCuenta);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_ctb_base_cuentas_lista'));
        }
        return $this->render('BrasaContabilidadBundle:Base/Cuentas:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar($form) {
        $em = $this->getDoctrine()->getManager(); 
                
        $this->strDqlLista = $em->getRepository('BrasaContabilidadBundle:CtbCuenta')->listaDql(    
                $form->get('TxtCódigoCuenta')->getData(),
                $form->get('TxtNombreCuenta')->getData()                
        );  
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                                    
            ->add('TxtCódigoCuenta', 'text', array('label'  => 'Código Cuenta','data' => ""))
            ->add('TxtNombreCuenta', 'text', array('label'  => 'Nombre Cuenta','data' => ""))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                                            
            ->getForm();        
        return $form;
    }
    
    public function generarExcel(){
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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        $arComprobantes = $em->getRepository('BrasaContabilidadBundle:CtbComprobante')->findAll();

        foreach ($arComprobantes as $arComprobante) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arComprobante->getCodigoComprobantePk())
                    ->setCellValue('B' . $i, $arComprobante->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Comprobantes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Comprobantes.xlsx"');
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
