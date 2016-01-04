<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurClienteType;
use Brasa\TurnoBundle\Form\Type\TurClientePuestoType;
class BaseClienteController extends Controller
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
                //$em->getRepository('BrasaTurnoBundle:TurCliente')->eliminarExamen($arrSeleccionados);
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
        return $this->render('BrasaTurnoBundle:Base/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes, 
            'form' => $form->createView()));
    }

    public function nuevoAction($codigoCliente = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if($codigoCliente != '' && $codigoCliente != '0') {
            $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        }        
        $form = $this->createForm(new TurClienteType, $arCliente);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCliente = $form->getData();
            $arrControles = $request->request->All();                        
            if($arrControles['txtNit'] != '') {
                $arTercero = new \Brasa\GeneralBundle\Entity\GenTercero();
                $arTercero = $em->getRepository('BrasaGeneralBundle:GenTercero')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arTercero) > 0) {
                    $arCliente->setTerceroRel($arTercero);
                    $em->persist($arCliente);
                    $em->flush();            

                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_tur_base_cliente_nuevo', array('codigoCliente' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_tur_base_cliente_lista'));
                    }                     
                } else {
                    $objMensaje->Mensaje("error", "El tercero no existe", $this);
                }                
            }            
                   
            
                       

        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }        

    public function detalleAction($codigoCliente) {
        $em = $this->getDoctrine()->getManager(); 
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $form = $this->formularioDetalle($arCliente);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arCliente->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurClienteDetalle')->numeroRegistros($codigoCliente) > 0) {
                        $arCliente->setEstadoAutorizado(1);
                        $em->persist($arCliente);
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));                                                                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles al examen', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));                                                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arCliente->getEstadoAutorizado() == 1) {
                    $arCliente->setEstadoAutorizado(0);
                    $em->persist($arCliente);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));                                                
                }
            }
            if ($form->get('BtnAprobar')->isClicked()) {                
                $strRespuesta = $em->getRepository('BrasaTurnoBundle:TurCliente')->aprobarExamen($codigoCliente);
                if($strRespuesta == ''){
                    return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));                                                
                }else {
                  $objMensaje->Mensaje('error', $strRespuesta, $this);
                }                 
            }      
            
            if($form->get('BtnImprimir')->isClicked()) {
                if($arCliente->getEstadoAutorizado() == 1) {
                    $objExamen = new \Brasa\TurnoBundle\Formatos\FormatoExamen();
                    $objExamen->Generar($this, $codigoCliente);
                } else {
                    $objMensaje->Mensaje("error", "No puede imprimir una orden de examen sin estar autorizada", $this);
                }
            }
            if($form->get('BtnEliminarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurClienteDetalle')->eliminarDetallesSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurCliente')->liquidar($codigoCliente);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));
            }
            if($form->get('BtnAprobarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurClienteDetalle')->aprobarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));
            }
            if($form->get('BtnCerrarDetalle')->isClicked()) {
                $em->getRepository('BrasaTurnoBundle:TurClienteDetalle')->cerrarDetallesSeleccionados($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));
            }            
            if ($form->get('BtnActualizarDetalle')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigo'] as $intCodigo) {                
                    if($arrControles['TxtPrecio'.$intCodigo] != "" && $arrControles['TxtPrecio'.$intCodigo] != 0) {
                        $arClienteDetalle = new \Brasa\TurnoBundle\Entity\TurClienteDetalle();
                        $arClienteDetalle = $em->getRepository('BrasaTurnoBundle:TurClienteDetalle')->find($intCodigo);                                        
                        $floPrecio = $arrControles['TxtPrecio'.$intCodigo];
                        $arClienteDetalle->setValidarVencimiento($arrControles['cboValidarVencimiento'.$intCodigo]);
                        $arClienteDetalle->setFechaVence(date_create($arrControles['TxtVence'.$intCodigo]));
                        $arClienteDetalle->setVrPrecio($floPrecio);
                        $em->persist($arClienteDetalle);                        
                    }
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurCliente')->liquidar($codigoCliente);
                return $this->redirect($this->generateUrl('brs_rhu_examen_detalle', array('codigoExamen' => $codigoCliente)));
            }            
        }

        $arPuestos = new \Brasa\TurnoBundle\Entity\TurPuesto();
        $arPuestos = $em->getRepository('BrasaTurnoBundle:TurPuesto')->findBy(array ('codigoClienteFk' => $codigoCliente));
        return $this->render('BrasaTurnoBundle:Base/Cliente:detalle.html.twig', array(
                    'arCliente' => $arCliente,
                    'arPuestos' => $arPuestos,
                    'form' => $form->createView()
                    ));
    }

    public function puestoNuevoAction($codigoCliente) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        
        $form = $this->createForm(new TurClientePuestoType, $arPuesto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPuesto = $form->getData();
            $arPuesto->setClienteRel($arCliente);
            $em->persist($arPuesto);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_cliente_puesto_nuevo', array('codigoCliente' => $codigoCliente )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:puestoNuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }   
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurCliente')->listaDQL(
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'NOMBRE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arClientes = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arClientes = $query->getResult();
                
        foreach ($arClientes as $arCliente) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNombreCorto());
                        
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