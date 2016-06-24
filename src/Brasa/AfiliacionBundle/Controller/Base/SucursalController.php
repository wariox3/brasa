<?php
namespace Brasa\AfiliacionBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiSucursalType;
class SucursalController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/sucursal", name="brs_afi_base_sucursal")
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
                $em->getRepository('BrasaAfiliacionBundle:AfiSucursal')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_sucursal'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arSucursales = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Sucursal:lista.html.twig', array(
            'arSucursales' => $arSucursales, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/sucursal/nuevo/{codigoSucursal}", name="brs_afi_base_sucursal_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoSucursal = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arSucursal = new \Brasa\AfiliacionBundle\Entity\AfiSucursal();
        if($codigoSucursal != '' && $codigoSucursal != '0') {
            $arSucursal = $em->getRepository('BrasaAfiliacionBundle:AfiSucursal')->find($codigoSucursal);
        }        
        $form = $this->createForm(new AfiSucursalType, $arSucursal);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arSucursal = $form->getData();                        
            $em->persist($arSucursal);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_sucursal_nuevo', array('codigoSucursal' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_sucursal'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/Sucursal:nuevo.html.twig', array(
            'arSucursal' => $arSucursal,
            'form' => $form->createView()));
    }        
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiSucursal')->listaDQL(
                $session->get('filtroSucursalNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroSucursalNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroSucursalNombre')))
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
        for($col = 'A'; $col !== 'P'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'FORMAPAGO')
                    ->setCellValue('E1', 'PLAZO')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'BARRIO')
                    ->setCellValue('H1', 'CIUDAD')
                    ->setCellValue('I1', 'TELEFONO')
                    ->setCellValue('J1', 'CELULAR')
                    ->setCellValue('K1', 'FAX')
                    ->setCellValue('L1', 'EMAIL')
                    ->setCellValue('M1', 'CONTACTO')
                    ->setCellValue('N1', 'CELCONTACTO')
                    ->setCellValue('O1', 'TELCONTACTO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arSucursales = new \Brasa\AfiliacionBundle\Entity\AfiSucursal();
        $arSucursales = $query->getResult();
                
        foreach ($arSucursales as $arSucursal) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSucursal->getCodigoSucursalPk())
                    ->setCellValue('B' . $i, $arSucursal->getNit())
                    ->setCellValue('C' . $i, $arSucursal->getNombreCorto())
                    ->setCellValue('D' . $i, $arSucursal->getFormaPagoRel()->getNombre())
                    ->setCellValue('E' . $i, $arSucursal->getPlazoPago())
                    ->setCellValue('F' . $i, $arSucursal->getDireccion())
                    ->setCellValue('G' . $i, $arSucursal->getBarrio())
                    ->setCellValue('H' . $i, $arSucursal->getCiudadRel()->getNombre())
                    ->setCellValue('I' . $i, $arSucursal->getTelefono())
                    ->setCellValue('J' . $i, $arSucursal->getCelular())
                    ->setCellValue('K' . $i, $arSucursal->getFax())
                    ->setCellValue('L' . $i, $arSucursal->getEmail())
                    ->setCellValue('M' . $i, $arSucursal->getContacto())
                    ->setCellValue('N' . $i, $arSucursal->getCelularContacto())
                    ->setCellValue('O' . $i, $arSucursal->getTelefonoContacto());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Sucursal');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Sucursals.xlsx"');
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