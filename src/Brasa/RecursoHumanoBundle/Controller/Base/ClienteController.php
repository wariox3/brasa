<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuClienteType;
use Symfony\Component\HttpFoundation\Request;

class ClienteController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/base/cliente", name="brs_rhu_base_cliente")
     */       
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombreCliente')))
            ->add('BtnBuscar', 'submit', array('label'  => 'Buscar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        $form->handleRequest($request);
        $this->lista();        
        if($form->isValid()) {
            if($form->get('BtnBuscar')->isClicked() || $form->get('BtnExcel')->isClicked()) {
                $session->set('dqlCliente', $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->ListaDQL(
                    $form->get('TxtNombre')->getData()
                    ));                
                $session->set('filtroNombreCliente', $form->get('TxtNombre')->getData());                
            }
            
            if($form->get('BtnPdf')->isClicked()) {
                $objFormatoClientes = new \Brasa\RecursoHumanoBundle\Formatos\FormatoClientes();
                $objFormatoClientes->Generar($this);
            }
            
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoCliente) {
                        $arCliente = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();
                        $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($codigoCliente);
                        $arContratosCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoClienteFk' =>$codigoCliente, 'estadoActivo' => 1)); 
                        $douNumeroContratoActivos = count($arContratosCliente);
                        if($arCliente->getEstadoActivo() == 1){
                            if ($douNumeroContratoActivos == 0){
                                $arCliente->setEstadoActivo(0);
                            }else {
                                echo "<script>alert('No se  puede inactivar, el centro de costo tiene contrato(s) abierto(s)');</script>";
                            }
                        } else {
                            $arCliente->setEstadoActivo(1);
                        }
                        $em->persist($arCliente);
                    }
                    $em->flush();
                }
            }
        } else {
            $session->set('dqlCliente', $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->ListaDQL(
                    $session->get('filtroNombreCliente')
                    ));                          
        }      
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $this->get('request')->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes,
            'form' => $form->createView()));
    }
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->listaDQL(
                $session->get('filtroClienteNombre')   
                ); 
    }    

    /**
     * @Route("/rhu/base/cliente/nuevo/{codigoCliente}", name="brs_rhu_base_cliente_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCliente) {        
        $em = $this->getDoctrine()->getManager();          
        $arCliente = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();
        if($codigoCliente != 0) {
            $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($codigoCliente);
        }
        
        $form = $this->createForm(new RhuClienteType(), $arCliente);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arCliente = $form->getData();
            if ($codigoCliente == 0){
                $arCliente->setUsuario($arUsuario->getUserName());
            }                
            $em->persist($arCliente);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_cliente_nuevo', array('codigoCliente' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_base_cliente'));
            }                         

        }

        return $this->render('BrasaRecursoHumanoBundle:Base/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,            
            'form' => $form->createView()));
    }
    /**
     * @Route("/rhu/base/cliente/detalle/{codigoCliente}", name="brs_rhu_base_cliente_detalle")
     */
    public function detalleAction($codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()               
            ->getForm();
        $form->handleRequest($request);
        $arSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuSede();
        $arSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSede')->findBy(array('codigoClienteFk' => $codigoCliente));
        $arSedes = $paginator->paginate($arSedes, $this->get('request')->query->get('page', 1),5);
        $arClientes = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();
        $arClientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($codigoCliente);
        return $this->render('BrasaRecursoHumanoBundle:Base/Cliente:detalle.html.twig', array(
            'arSedes' => $arSedes,        
            'arCentrosCostos' => $arClientes,
            'form' => $form->createView()
                    ));
    }    
    
    private function generarExcel() {
         $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'NOMBRE')
                            ->setCellValue('C1', 'CIUDAD')
                            ->setCellValue('D1', 'PERIODO')
                            ->setCellValue('E1', 'ABIERTO')
                            ->setCellValue('F1', 'GENERA SERV POR COBRAR')
                            ->setCellValue('G1', 'ULT PAGO')
                            ->setCellValue('H1', 'ULT PAGO PRIMA')
                            ->setCellValue('I1', 'ULT PAGO CESANTIAS');

                $i = 2;
                $arClientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->findAll();
                foreach ($arClientes as $arCliente) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                            ->setCellValue('B' . $i, $arCliente->getNombre())
                            ->setCellValue('C' . $i, $arCliente->getCiudadRel()->getNombre())
                            ->setCellValue('D' . $i, $arCliente->getPeriodoPagoRel()->getNombre())
                            ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arCliente->getPagoAbierto()))
                            ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arCliente->getGeneraServicioCobrar()))
                            ->setCellValue('G' . $i, $arCliente->getFechaUltimoPago()->format('Y-m-d'))
                            ->setCellValue('H' . $i, $arCliente->getFechaUltimoPagoPrima()->format('Y-m-d'))
                            ->setCellValue('I' . $i, $arCliente->getFechaUltimoPagoCesantias()->format('Y-m-d'));
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('ccostos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="CentrosCostos.xlsx"');
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
