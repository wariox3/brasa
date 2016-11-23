<?php
namespace Brasa\InventarioBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\InventarioBundle\Form\Type\InvItemType;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;

class ItemController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    var $strNit = "";

    /**
     * @Route("/inv/base/item/", name="brs_inv_base_item")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 74, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaInventarioBundle:InvItem')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_inv_base_item'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }                        
        }
        
        $arItems = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Base/Item:lista.html.twig', array(
            'arItems' => $arItems, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/inv/base/item/nuevo/{codigoItem}", name="brs_inv_base_item_nuevo")
     */
    
    public function nuevoAction(Request $request, $codigoItem = '') {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arItem = new \Brasa\InventarioBundle\Entity\InvItem();
        if($codigoItem != '' && $codigoItem != '0') {
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($codigoItem);
        }        
        $form = $this->createForm(new InvItemType, $arItem);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arItem = $form->getData();                        
            $em->persist($arItem);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_inv_base_item_nuevo', array('codigoItem' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_inv_base_item'));
            }                                                                                                                           
        }
        return $this->render('BrasaInventarioBundle:Base/Item:nuevo.html.twig', array(
            'arItem' => $arItem,
            'form' => $form->createView()));
    }                
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvItem')->listaDQL(
                $this->strNombre,                
                $this->strCodigo,
                $this->strNit
                ); 
    }

    private function filtrar ($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNit = $form->get('TxtNit')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }
    
    private function formularioFiltro() {
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('TxtNit', 'text', array('label'  => 'Codigo','data' => $this->strNit))                                            
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
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'ESTRATO')
                    ->setCellValue('E1', 'CONTACTO')
                    ->setCellValue('F1', 'TELEFONO')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'DIRECCION')
                    ->setCellValue('I1', 'BARRIO')
                    ->setCellValue('J1', 'CIUDAD')
                    ->setCellValue('K1', 'FORMA PAGO')
                    ->setCellValue('L1', 'PLAZO PAGO')
                    ->setCellValue('M1', 'FINANCIERO')
                    ->setCellValue('N1', 'CELULAR FINANCIERO')
                    ->setCellValue('O1', 'GERENTE')
                    ->setCellValue('P1', 'CELULAR GERENTE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arItems = new \Brasa\InventarioBundle\Entity\InvItem();
                $arItems = $query->getResult();
                
        foreach ($arItems as $arItem) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arItem->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arItem->getNit())
                    ->setCellValue('C' . $i, $arItem->getNombreCorto())
                    ->setCellValue('D' . $i, $arItem->getEstrato())
                    ->setCellValue('E' . $i, $arItem->getContacto())
                    ->setCellValue('F' . $i, $arItem->getTelefonoContacto())
                    ->setCellValue('G' . $i, $arItem->getCelularContacto())
                    ->setCellValue('H' . $i, $arItem->getDireccion())
                    ->setCellValue('I' . $i, $arItem->getBarrio())
                    ->setCellValue('J' . $i, $arItem->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arItem->getFormaPagoRel()->getNombre())
                    ->setCellValue('L' . $i, $arItem->getPlazoPago())
                    ->setCellValue('M' . $i, $arItem->getFinanciero())
                    ->setCellValue('N' . $i, $arItem->getCelularFinanciero())
                    ->setCellValue('O' . $i, $arItem->getGerente())
                    ->setCellValue('P' . $i, $arItem->getCelularGerente());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Cliente');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Clientes.xlsx"');
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