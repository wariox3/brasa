<?php

namespace Brasa\TransporteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TransporteBundle\Form\Type\TteClienteType;



class BaseClienteController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arClientes = new \Brasa\TransporteBundle\Entity\TteCliente();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoCliente) {
                    $arCliente = new \Brasa\TransporteBundle\Entity\TteCliente();
                    $arCliente = $em->getRepository('BrasaTransporteBundle:TteCliente')->find($codigoCliente);
                    $em->remove($arCliente);
                    $em->flush();
                }
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }
        $arClientes = new \Brasa\TransporteBundle\Entity\TteCliente();
        $query = $em->getRepository('BrasaTransporteBundle:TteCliente')->findAll();
        $arClientes = $paginator->paginate($query, $this->get('request')->query->get('page', 1),100);

        return $this->render('BrasaTransporteBundle:Base/Clientes:lista.html.twig', array(
                    'arClientes' => $arClientes,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arCliente = new \Brasa\TransporteBundle\Entity\TteCliente();
        if ($codigoCliente != 0)
        {
            $arCliente = $em->getRepository('BrasaTransporteBundle:TteCliente')->find($codigoCliente);
        }    
        $form = $this->createForm(new TteClienteType(), $arCliente);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arCliente = $form->getData();
            $em->persist($arCliente);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tte_base_clientes_lista'));
        }
        return $this->render('BrasaTransporteBundle:Base/Clientes:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
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
                    ->setCellValue('B1', 'TIPO IDENTIFICACIÓN')
                    ->setCellValue('C1', 'NÚMERO IDENTIFICACIÓN')
                    ->setCellValue('D1', 'DIGITO VERIFICACIÓN')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'RAZÓN SOCIAL')
                    ->setCellValue('G1', 'CIUDAD')
                    ->setCellValue('H1', 'DIRECCIÓN')
                    ->setCellValue('I1', 'TELÉFONO')
                    ->setCellValue('J1', 'CELULAR')
                    ->setCellValue('K1', 'FAX')
                    ->setCellValue('L1', 'EMAIL');

        $i = 2;
        $arTerceros = $em->getRepository('BrasaTransporteBundle:CtbTercero')->findAll();

        foreach ($arTerceros as $arTerceros) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTerceros->getCodigoTerceroPk())
                    ->setCellValue('B' . $i, $arTerceros->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('C' . $i, $arTerceros->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arTerceros->getDigitoVerificacion())
                    ->setCellValue('E' . $i, $arTerceros->getNombreCorto())
                    ->setCellValue('F' . $i, $arTerceros->getRazonSocial())
                    ->setCellValue('G' . $i, $arTerceros->getCiudadRel()->getNombre())
                    ->setCellValue('H' . $i, $arTerceros->getDireccion())
                    ->setCellValue('I' . $i, $arTerceros->getTelefono())
                    ->setCellValue('J' . $i, $arTerceros->getCelular())
                    ->setCellValue('K' . $i, $arTerceros->getFax())
                    ->setCellValue('L' . $i, $arTerceros->getEmail());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Terceros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Terceros.xlsx"');
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
