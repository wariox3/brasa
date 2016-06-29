<?php
namespace Brasa\TurnoBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurClienteType;
use Brasa\TurnoBundle\Form\Type\TurClientePuestoType;
use Brasa\TurnoBundle\Form\Type\TurProyectoType;
use Brasa\TurnoBundle\Form\Type\TurGrupoFacturacionType;
use Brasa\TurnoBundle\Form\Type\TurClienteDireccionType;
class ClienteController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/tur/base/cliente/", name="brs_tur_base_cliente")
     */     
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
                $em->getRepository('BrasaTurnoBundle:TurCliente')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_cliente'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
            if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcelInterfaz();
            }            
        }
        
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/cliente/nuevo/{codigoCliente}", name="brs_tur_base_cliente_nuevo")
     */    
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
            $arClienteValidar = new \Brasa\TurnoBundle\Entity\TurCliente();
            $arClienteValidar = $em->getRepository('BrasaTurnoBundle:TurCliente')->findBy(array('nit' => $arCliente->getNit()));
            if(($codigoCliente == 0 || $codigoCliente == '') && count($arClienteValidar) > 0) {
                $objMensaje->Mensaje("error", "El cliente con ese nit ya existe", $this);
            } else {
                $arUsuario = $this->getUser();
                $arCliente->setUsuario($arUsuario->getUserName());
                $em->persist($arCliente);
                $em->flush();            
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente_nuevo', array('codigoCliente' => 0 )));
                } else {
                    return $this->redirect($this->generateUrl('brs_tur_base_cliente'));
                }                                   
            }                                                                            

        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/tur/base/cliente/detalle/{codigoCliente}", name="brs_tur_base_cliente_detalle")
     */     
    public function detalleAction($codigoCliente) {
        $em = $this->getDoctrine()->getManager(); 
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $form = $this->formularioDetalle($arCliente);
        $form->handleRequest($request);
        if($form->isValid()) {                        
            if($form->get('BtnEliminarPuesto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPuesto');
                $em->getRepository('BrasaTurnoBundle:TurPuesto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
            }    
            if($form->get('BtnEliminarProyecto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarProyecto');
                $em->getRepository('BrasaTurnoBundle:TurProyecto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
            }            
            if($form->get('BtnEliminarDireccion')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarDireccion');
                $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_cliente_detalle', array('codigoCliente' => $codigoCliente)));
            }             
        }

        $arPuestos = new \Brasa\TurnoBundle\Entity\TurPuesto();
        $arPuestos = $em->getRepository('BrasaTurnoBundle:TurPuesto')->findBy(array ('codigoClienteFk' => $codigoCliente));
        $arProyectos = new \Brasa\TurnoBundle\Entity\TurProyecto();
        $arProyectos = $em->getRepository('BrasaTurnoBundle:TurProyecto')->findBy(array ('codigoClienteFk' => $codigoCliente));        
        $arGruposFacturacion = new \Brasa\TurnoBundle\Entity\TurGrupoFacturacion();
        $arGruposFacturacion = $em->getRepository('BrasaTurnoBundle:TurGrupoFacturacion')->findBy(array ('codigoClienteFk' => $codigoCliente));                
        $arClienteDirecciones = new \Brasa\TurnoBundle\Entity\TurClienteDireccion();
        $arClienteDirecciones = $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->findBy(array ('codigoClienteFk' => $codigoCliente));        
        return $this->render('BrasaTurnoBundle:Base/Cliente:detalle.html.twig', array(
                    'arCliente' => $arCliente,
                    'arPuestos' => $arPuestos,
                    'arProyectos' => $arProyectos,
                    'arGruposFacturacion' => $arGruposFacturacion,
                    'arClienteDirecciones' => $arClienteDirecciones,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/tur/base/cliente/puesto/nuevo/{codigoCliente}/{codigoPuesto}", name="brs_tur_base_cliente_puesto_nuevo")
     */    
    public function puestoNuevoAction($codigoCliente, $codigoPuesto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arPuesto = new \Brasa\TurnoBundle\Entity\TurPuesto();
        if($codigoPuesto != '' && $codigoPuesto != '0') {
            $arPuesto = $em->getRepository('BrasaTurnoBundle:TurPuesto')->find($codigoPuesto);
        }        
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

    /**
     * @Route("/tur/base/cliente/proyecto/nuevo/{codigoCliente}/{codigoProyecto}", name="brs_tur_base_cliente_proyecto_nuevo")
     */    
    public function proyectoNuevoAction($codigoCliente, $codigoProyecto) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arProyecto = new \Brasa\TurnoBundle\Entity\TurProyecto();
        if($codigoProyecto != '' && $codigoProyecto != '0') {
            $arProyecto = $em->getRepository('BrasaTurnoBundle:TurProyecto')->find($codigoProyecto);
        }        
        $form = $this->createForm(new TurProyectoType, $arProyecto);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arProyecto = $form->getData();
            $arProyecto->setClienteRel($arCliente);
            $em->persist($arProyecto);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_cliente_proyecto_nuevo', array('codigoCliente' => $codigoCliente )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:proyectoNuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/base/cliente/grupo/facturacion/nuevo/{codigoCliente}/{codigoGrupoFacturacion}", name="brs_tur_base_cliente_grupo_facturacion_nuevo")
     */    
    public function grupoFacturacionNuevoAction($codigoCliente, $codigoGrupoFacturacion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arGrupoFacturacion = new \Brasa\TurnoBundle\Entity\TurGrupoFacturacion();
        if($codigoGrupoFacturacion != '' && $codigoGrupoFacturacion != '0') {
            $arGrupoFacturacion = $em->getRepository('BrasaTurnoBundle:TurGrupoFacturacion')->find($codigoGrupoFacturacion);
        }        
        $form = $this->createForm(new TurGrupoFacturacionType, $arGrupoFacturacion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arGrupoFacturacion = $form->getData();
            $arGrupoFacturacion->setClienteRel($arCliente);
            $em->persist($arGrupoFacturacion);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_cliente_grupo_facturacion_nuevo', array('codigoCliente' => $codigoCliente )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:grupoFacturacionNuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/base/cliente/direccion/nuevo/{codigoCliente}/{codigoDireccion}", name="brs_tur_base_cliente_direccion_nuevo")
     */    
    public function direccionNuevoAction($codigoCliente, $codigoDireccion) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->find($codigoCliente);
        $arClienteDireccion = new \Brasa\TurnoBundle\Entity\TurClienteDireccion();
        if($codigoDireccion != '' && $codigoDireccion != '0') {
            $arClienteDireccion = $em->getRepository('BrasaTurnoBundle:TurClienteDireccion')->find($codigoDireccion);
        }        
        $form = $this->createForm(new TurClienteDireccionType, $arClienteDireccion);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arClienteDireccion = $form->getData();
            $arClienteDireccion->setClienteRel($arCliente);
            $em->persist($arClienteDireccion);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_cliente_direccion_nuevo', array('codigoCliente' => $codigoCliente )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }            
        }
        return $this->render('BrasaTurnoBundle:Base/Cliente:direccionNuevo.html.twig', array(
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
            ->add('BtnInterfaz', 'submit', array('label'  => 'Interfaz',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $arrBotonEliminarPuesto = array('label' => 'Eliminar', 'disabled' => false);                
        $arrBotonEliminarProyecto = array('label' => 'Eliminar', 'disabled' => false);                
        $arrBotonEliminarDireccion = array('label' => 'Eliminar', 'disabled' => false);                
       
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)            
                    ->add('BtnEliminarPuesto', 'submit', $arrBotonEliminarPuesto)            
                    ->add('BtnEliminarProyecto', 'submit', $arrBotonEliminarProyecto)            
                    ->add('BtnEliminarDireccion', 'submit', $arrBotonEliminarDireccion)            
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
                $arClientes = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arClientes = $query->getResult();
                
        foreach ($arClientes as $arCliente) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNit())
                    ->setCellValue('C' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('D' . $i, $arCliente->getEstrato())
                    ->setCellValue('E' . $i, $arCliente->getContacto())
                    ->setCellValue('F' . $i, $arCliente->getTelefonoContacto())
                    ->setCellValue('G' . $i, $arCliente->getCelularContacto())
                    ->setCellValue('H' . $i, $arCliente->getDireccion())
                    ->setCellValue('I' . $i, $arCliente->getBarrio())
                    ->setCellValue('J' . $i, $arCliente->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arCliente->getFormaPagoRel()->getNombre())
                    ->setCellValue('L' . $i, $arCliente->getPlazoPago())
                    ->setCellValue('M' . $i, $arCliente->getFinanciero())
                    ->setCellValue('N' . $i, $arCliente->getCelularFinanciero())
                    ->setCellValue('O' . $i, $arCliente->getGerente())
                    ->setCellValue('P' . $i, $arCliente->getCelularGerente());                                    
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

    private function generarExcelInterfaz() {
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
                    ->setCellValue('A1', 'APELLIDO1')
                    ->setCellValue('B1', 'APELLIDO2')
                    ->setCellValue('C1', 'AUTORET')
                    ->setCellValue('D1', 'AVALCOD')
                    ->setCellValue('E1', 'AVALDIREC')
                    ->setCellValue('F1', 'AVALNOMBRE')
                    ->setCellValue('G1', 'AVALTEL')
                    ->setCellValue('H1', 'BANCO')
                    ->setCellValue('I1', 'CALIFCALID')
                    ->setCellValue('J1', 'CALIFTIEMP')
                    ->setCellValue('K1', 'CANAL')
                    ->setCellValue('L1', 'CDCIIU')
                    ->setCellValue('M1', 'CIIU')
                    ->setCellValue('N1', 'CIUDAD')
                    ->setCellValue('O1', 'CIUDADMX')
                    ->setCellValue('P1', 'CIUDADPRV')
                    ->setCellValue('Q1', 'CLASE')
                    ->setCellValue('R1', 'CODALTERNO')
                    ->setCellValue('S1', 'CODCTANIIF')
                    ->setCellValue('T1', 'CODDEPTO')
                    ->setCellValue('U1', 'CODDESFIN')
                    ->setCellValue('V1', 'CODDESFINP')
                    ->setCellValue('W1', 'CODIGOCTA')
                    ->setCellValue('X1', 'CODIGOCTAP')
                    ->setCellValue('Y1', 'CODPOSTAL')
                    ->setCellValue('Z1', 'CODPOSTALP')
                    ->setCellValue('AA1', 'CODPRECIO')
                    ->setCellValue('AB1', 'CODPRECIOP')
                    ->setCellValue('AC1', 'CODRETE')
                    ->setCellValue('AD1', 'CODRETEP')
                    ->setCellValue('AE1', 'CODRUTA')
                    ->setCellValue('AF1', 'COL_DEL')						                
                    ->setCellValue('AG1', 'COMENTARIO')
                    ->setCellValue('AH1', 'CONSUMIDOR')
                    ->setCellValue('AI1', 'CONTACTO')
                    ->setCellValue('AJ1', 'CONTESP')
                    ->setCellValue('AK1', 'CONTRIBUYE')
                    ->setCellValue('AL1', 'CTACTE')
                    ->setCellValue('AM1', 'CTANIIFPRV')		
                    ->setCellValue('AN1', 'CUPOCR')
                    ->setCellValue('AO1', 'CUPOCRP')
                    ->setCellValue('AP1', 'CURP')
                    ->setCellValue('AQ1', 'DELEGACION')
                    ->setCellValue('AR1', 'DESCCOMER')
                    ->setCellValue('AS1', 'DESCCOMERP')
                    ->setCellValue('AT1', 'DESCFINAN')
                    ->setCellValue('AU1', 'DESCFINANP')
                    ->setCellValue('AV1', 'DETALLE')
                    ->setCellValue('AW1', 'DIRECCION')
                    ->setCellValue('AX1', 'EMAIL')
                    ->setCellValue('AY1', 'EMAILP')
                    ->setCellValue('AZ1', 'ENTRECALLE')
                    ->setCellValue('BA1', 'ESCLIENTE')
                    ->setCellValue('BB1', 'ESDECLARA')
                    ->setCellValue('BC1', 'ESMAQUILA')
                    ->setCellValue('BD1', 'ESPERCAR')
                    ->setCellValue('BE1', 'ESPROVEE')						                
                    ->setCellValue('BF1', 'ESTADOMX')
                    ->setCellValue('BG1', 'ESRETCREE')
                    ->setCellValue('BH1', 'ESTADOMX')
                    ->setCellValue('BI1', 'ESTRATO')
                    ->setCellValue('BJ1', 'EXTERIOR')
                    ->setCellValue('BK1', 'FACELECTRO')
                    ->setCellValue('BL1', 'FECHAING')
                    ->setCellValue('BM1', 'FECING')
                    ->setCellValue('BN1', 'FECMOD')
                    ->setCellValue('BO1', 'FECNAC')
                    ->setCellValue('BP1', 'HABILITADO')
                    ->setCellValue('BQ1', 'IDADJUNTOS')
                    ->setCellValue('BR1', 'IDCANALENT')
                    ->setCellValue('BS1', 'IDENTIFICA')
                    ->setCellValue('BT1', 'INDEPENDIE')
                    ->setCellValue('BU1', 'INTCAR')
                    ->setCellValue('BV1', 'ISPROSPECT')
                    ->setCellValue('BW1', 'LOCAL')
                    ->setCellValue('BX1', 'LOCALIDAD')
                    ->setCellValue('BY1', 'MEADENDA')
                    ->setCellValue('BZ1', 'NDIAGRACIA')
                    ->setCellValue('CA1', 'NIT')
                    ->setCellValue('CB1', 'NITSUCUR')
                    ->setCellValue('CC1', 'NOMBRE')
                    ->setCellValue('CD1', 'NOMBRE1')
                    ->setCellValue('CE1', 'NOMBRE2')
                    ->setCellValue('CF1', 'NROENTREGA')
                    ->setCellValue('CG1', 'NROINTERNO')
                    ->setCellValue('CH1', 'NRORESOL')
                    ->setCellValue('CI1', 'NUMCTA')										                
                    ->setCellValue('CJ1', 'NUMCUENTA')
                    ->setCellValue('CK1', 'PAGINAWEB')
                    ->setCellValue('CL1', 'PAIS')
                    ->setCellValue('CM1', 'PAISMX')
                    ->setCellValue('CN1', 'PASSWORD')
                    ->setCellValue('CO1', 'PASSWORDIN')
                    ->setCellValue('CP1', 'PASSWORDMO')
                    ->setCellValue('CQ1', 'PASSWPROV')
                    ->setCellValue('CR1', 'PERIODOFAC')
                    ->setCellValue('CS1', 'PERSONANJ')
                    ->setCellValue('CT1', 'PLAZO')								                
                    ->setCellValue('CU1', 'PLAZOP')
                    ->setCellValue('CV1', 'PORAIU')
                    ->setCellValue('CW1', 'PORAIUP')
                    ->setCellValue('CX1', 'PRETICA')
                    ->setCellValue('CY1', 'PRETICAP')
                    ->setCellValue('CZ1', 'PRETIVA')
                    ->setCellValue('DA1', 'PRETIVAP')
                    ->setCellValue('DB1', 'PRETPERC')
                    ->setCellValue('DC1', 'PRETPERP')
                    ->setCellValue('DD1', 'REGSIMP')
                    ->setCellValue('DE1', 'REPORTDC')
                    ->setCellValue('DF1', 'RESPRETE')
                    ->setCellValue('DG1', 'RETICA')
                    ->setCellValue('DH1', 'RETICAP')
                    ->setCellValue('DI1', 'STADSINCRO')
                    ->setCellValue('DJ1', 'SUCURSAL')
                    ->setCellValue('DK1', 'TEL1')
                    ->setCellValue('DL1', 'TEL2')
                    ->setCellValue('DM1', 'TIDENTI')
                    ->setCellValue('DN1', 'TIPOCAR')
                    ->setCellValue('DO1', 'TIPOCLI')
                    ->setCellValue('DP1', 'TIPOCTA')
                    ->setCellValue('DQ1', 'TIPOCXP')
                    ->setCellValue('DR1', 'TIPOIDEN')
                    ->setCellValue('DS1', 'TIPOPER')
                    ->setCellValue('DT1', 'TIPOPRV')
                    ->setCellValue('DU1', 'VENDEDOR')
                    ->setCellValue('DV1', 'ZONA');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arClientes = new \Brasa\TurnoBundle\Entity\TurCliente();
                $arClientes = $query->getResult();
                
        foreach ($arClientes as $arCliente) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNit())
                    ->setCellValue('C' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('D' . $i, $arCliente->getEstrato())
                    ->setCellValue('E' . $i, $arCliente->getContacto())
                    ->setCellValue('F' . $i, $arCliente->getTelefonoContacto())
                    ->setCellValue('G' . $i, $arCliente->getCelularContacto())
                    ->setCellValue('H' . $i, $arCliente->getDireccion())
                    ->setCellValue('I' . $i, $arCliente->getBarrio())
                    ->setCellValue('J' . $i, $arCliente->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arCliente->getFormaPagoRel()->getNombre())
                    ->setCellValue('L' . $i, $arCliente->getPlazoPago())
                    ->setCellValue('M' . $i, $arCliente->getFinanciero())
                    ->setCellValue('N' . $i, $arCliente->getCelularFinanciero())
                    ->setCellValue('O' . $i, $arCliente->getGerente())
                    ->setCellValue('P' . $i, $arCliente->getCelularGerente());                                    
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