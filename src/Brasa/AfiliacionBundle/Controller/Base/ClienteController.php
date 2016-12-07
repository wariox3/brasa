<?php
namespace Brasa\AfiliacionBundle\Controller\Base;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiClienteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClienteController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/cliente", name="brs_afi_base_cliente")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_cliente'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/cliente/nuevo/{codigoCliente}", name="brs_afi_base_cliente_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCliente = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
        if($codigoCliente != '' && $codigoCliente != '0') {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigoCliente);
        }        
        $form = $this->createForm(new AfiClienteType, $arCliente);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCliente = $form->getData();                        
            $em->persist($arCliente);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_cliente_nuevo', array('codigoCliente' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_cliente'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/base/cliente/detalle/{codigoCliente}", name="brs_afi_base_cliente_detalle")
     */    
    public function detalleAction(Request $request, $codigoCliente = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioDetalle();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            
            if ($form->get('BtnImprimir')->isClicked()) {
               $objFormatoCliente = new \Brasa\AfiliacionBundle\Formatos\Cliente();
               $objFormatoCliente->Generar($this, $codigoCliente);
            }
        }
        $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
        $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigoCliente);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaDetalleDql($codigoCliente);        
        $arContratos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Cliente:detalle.html.twig', array(
            'arCliente' => $arCliente,
            'arContratos' => $arContratos, 
            'form' => $form->createView()));
    }    
    
    private function lista() {  
        $session = new Session();        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->listaDQL(
                $session->get('filtroClienteNombre'),
                $session->get('filtroClienteCodigo'),
                $session->get('filtroClienteIndentificacion'),
                $session->get('filtroIndependiente')
                ); 
    }

    private function filtrar ($form) {        
        $session = new Session();         
        $session->set('filtroClienteNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroClienteCodigo', $form->get('TxtCodigo')->getData());
        $session->set('filtroClienteIndentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroIndependiente', $form->get('independiente')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new Session(); 
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroClienteNombre')))
            ->add('TxtIdentificacion', textType::class, array('label'  => 'Identificacion','data' => $session->get('filtroClienteIndentificacion')))
            ->add('TxtCodigo', textType::class, array('label'  => 'Codigo'))    
            ->add('independiente', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO')))                                            
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalle() {        
        $form = $this->createFormBuilder()                                    
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir',))                        
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
        $arClientes = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
        $arClientes = $query->getResult();
                
        foreach ($arClientes as $arCliente) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNit())
                    ->setCellValue('C' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('D' . $i, $arCliente->getFormaPagoRel()->getNombre())
                    ->setCellValue('E' . $i, $arCliente->getPlazoPago())
                    ->setCellValue('F' . $i, $arCliente->getDireccion())
                    ->setCellValue('G' . $i, $arCliente->getBarrio())
                    ->setCellValue('H' . $i, $arCliente->getCiudadRel()->getNombre())
                    ->setCellValue('I' . $i, $arCliente->getTelefono())
                    ->setCellValue('J' . $i, $arCliente->getCelular())
                    ->setCellValue('K' . $i, $arCliente->getFax())
                    ->setCellValue('L' . $i, $arCliente->getEmail())
                    ->setCellValue('M' . $i, $arCliente->getContacto())
                    ->setCellValue('N' . $i, $arCliente->getCelularContacto())
                    ->setCellValue('O' . $i, $arCliente->getTelefonoContacto());                                    
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