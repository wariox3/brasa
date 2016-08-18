<?php
namespace Brasa\CarteraBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarAnticipoType;
//use Brasa\CarteraBundle\Form\Type\CarAnticipoDetalleType;

class MovimientoAnticipoController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/cartera/movimiento/anticipo/lista", name="brs_cartera_movimiento_anticipo_listar")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {               
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarAnticipo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }

        $arAnticipos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Movimientos/Anticipo:lista.html.twig', array(
            'arAnticipos' => $arAnticipos,            
            'form' => $form->createView()));
    }

    /**
     * @Route("/cartera/movimiento/anticipo/nuevo/{codigoAnticipo}", name="brs_cartera_movimiento_anticipo_nuevo")
     */
    public function nuevoAction($codigoAnticipo) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arAnticipo = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        if($codigoAnticipo != 0) {
            $arAnticipo = $em->getRepository('BrasaCarteraBundle:CarAnticipo')->find($codigoAnticipo);
        }else{
            $arAnticipo->setFecha(new \DateTime('now'));
            $arAnticipo->setFechaPago(new \DateTime('now'));
        }
        $form = $this->createForm(new CarAnticipoType, $arAnticipo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arAnticipo = $form->getData();
            $arrControles = $request->request->All();
            $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arAnticipo->setClienteRel($arCliente);
                    $arAnticipo->setAsesorRel($arCliente->getAsesorRel());
                }
            }
            /*if ($codigoAnticipo != 0 && $em->getRepository('BrasaCarteraBundle:CarAnticipoDetalle')->numeroRegistros($codigoAnticipo) > 0) {
                if ($arAnticipo->getCodigoClienteFk() == $arCliente->getCodigoClientePk()) {
                    $arUsuario = $this->getUser();
                    $arAnticipo->setUsuario($arUsuario->getUserName());            
                    $em->persist($arAnticipo);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_nuevo', array('codigoAnticipo' => 0 )));
                    } else {
                        if ($codigoAnticipo != 0){
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));
                        } else {
                            return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $arAnticipo->getCodigoAnticipoPk())));
                        }

                    }
                } else {
                    $objMensaje->Mensaje("error", "Para modificar el cliente debe eliminar los detalles asociados a este registro", $this);
                }
            } else {*/
                $arUsuario = $this->getUser();
                $arAnticipo->setUsuario($arUsuario->getUserName());            
                $em->persist($arAnticipo);
                $em->flush();
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_nuevo', array('codigoAnticipo' => 0 )));
                } else {
                    /*if ($codigoAnticipo != 0){
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));
                    } else {
                        return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_detalle', array('codigoAnticipo' => $arAnticipo->getCodigoAnticipoPk())));
                    }*/
                    return $this->redirect($this->generateUrl('brs_cartera_movimiento_anticipo_listar'));
                }
            //}
        }
        return $this->render('BrasaCarteraBundle:Movimientos/Anticipo:nuevo.html.twig', array(
            'arAnticipo' => $arAnticipo,
            'form' => $form->createView()));
    }
  
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarAnticipo')->listaDQL(
                $session->get('filtroAnticipoNumero'), 
                $session->get('filtroCodigoCliente'),
                $session->get('filtroAnticipoEstadoImpreso'));
    }

    private function filtrar ($form) {       
        $session = $this->getRequest()->getSession();        
        $session->set('filtroAnticipoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroAnticipoEstadoImpreso', $form->get('estadoImpreso')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());   
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCotizacionNumero')))
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))
            ->add('estadoImpreso', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'IMPRESO', '0' => 'SIN IMPRIMIR'), 'data' => $session->get('filtroAnticipoEstadoImpreso')))                
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
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
        for($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'H'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'NIT')                
                    ->setCellValue('D1', 'CLIENTE')
                    ->setCellValue('E1', 'CUENTA')
                    ->setCellValue('F1', 'FECHA PAGO')
                    ->setCellValue('G1', 'TOTAL')
                    ->setCellValue('H1', 'ANULADO')
                    ->setCellValue('I1', 'AUTORIZADO')
                    ->setCellValue('J1', 'IMPRESO');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arAnticipos = new \Brasa\CarteraBundle\Entity\CarAnticipo();
        $arAnticipos = $query->getResult();

        foreach ($arAnticipos as $arAnticipo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAnticipo->getCodigoAnticipoPk())
                    ->setCellValue('B' . $i, $arAnticipo->getNumero())
                    ->setCellValue('E' . $i, $arAnticipo->getCuentaRel()->getNombre())
                    ->setCellValue('F' . $i, $arAnticipo->getFechaPago()->format('Y-m-d'))
                    ->setCellValue('G' . $i, $arAnticipo->getVrTotal())
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoAnulado()))
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoAutorizado()))
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arAnticipo->getEstadoImpreso()));
            if($arAnticipo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C' . $i, $arAnticipo->getClienteRel()->getNit());
            }
            if($arAnticipo->getClienteRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $i, $arAnticipo->getClienteRel()->getNombreCorto());
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Anticipos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Anticipos.xlsx"');
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