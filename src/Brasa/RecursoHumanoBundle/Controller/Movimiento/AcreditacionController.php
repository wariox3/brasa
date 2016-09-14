<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAcreditacionType;

class AcreditacionController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/movimiento/acreditacion/", name="brs_rhu_movimiento_acreditacion")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 12, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }            
            if($form->get('BtnExcelInforme')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarInformeExcel();
            }
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoAcreditacion) {
                        $arAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                        $arAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($codigoAcreditacion);
                        $em->remove($arAcreditacion);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion'));
                }
            }
            
        }
        $arAcreditaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:lista.html.twig', array(
            'arAcreditaciones' => $arAcreditaciones,
            'form' => $form->createView()
            ));
    }    

    /**
     * @Route("/rhu/movimiento/acreditacion/nuevo/{codigoAcreditacion}", name="brs_rhu_movimiento_acreditacion_nuevo")
     */    
    public function nuevoAction($codigoAcreditacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();       
        if($codigoAcreditacion != 0) {
            $arAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($codigoAcreditacion);
        } else {            
            $arAcreditacion->setFecha(new \DateTime('now'));
            $arAcreditacion->setFechaInicio(new \DateTime('now'));
            $arAcreditacion->setFechaEstado(new \DateTime('now'));
            $arAcreditacion->setFechaEstadoInvalido(new \DateTime('now'));
            $arAcreditacion->setFechaTerminacion(new \DateTime('now'));
            $arAcreditacion->setFechaVencimiento(new \DateTime('now'));
        }        

        $form = $this->createForm(new RhuAcreditacionType(), $arAcreditacion);                     
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arAcreditacion = $form->getData();                          
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));                
                if(count($arEmpleado) > 0) {                                            
                    $arAcreditacion->setEmpleadoRel($arEmpleado);                    
                    if($codigoAcreditacion == 0) {
                        $arAcreditacion->setCodigoUsuario($arUsuario->getUserName());                                           
                    }
                    $em->persist($arAcreditacion);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {                                                        
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion_nuevo', array('codigoAcreditacion' => 0)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion'));
                    }                                                                                                                             
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);                                    
                }
            }            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:nuevo.html.twig', array(
            'arAcreditacion' => $arAcreditacion,
            'form' => $form->createView()));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()       
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcelInforme', 'submit', array('label'  => 'Informe'))                
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->listaDQL(                   
                );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        //$session->set('filtroAcreditacionNumero', $form->get('TxtNumero')->getData());
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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NUMERO')                    
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'NOMBRE')                    
                    ->setCellValue('F1', 'FECHA');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);        
        $arAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
        $arAcreditaciones = $query->getResult();
        foreach ($arAcreditaciones as $arAcreditacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAcreditacion->getCodigoAcreditacionPk())
                    ->setCellValue('B' . $i, "")
                    ->setCellValue('C' . $i, $arAcreditacion->getNumeroRegistro())                                        
                    ->setCellValue('D' . $i, $arAcreditacion->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('E' . $i, $arAcreditacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arAcreditacion->getFecha()->format('Y-m-d'));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Acreditaciones');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Acreditaciones.xlsx"');
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
    
    private function generarInformeExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $nombreArchivo = "";
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
                for($col = 'A'; $col !== 'Y'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
                }                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Nit')
                            ->setCellValue('B1', 'RazonSocial')
                            ->setCellValue('C1', 'TipoDocumento')
                            ->setCellValue('D1', 'NoDocumento')
                            ->setCellValue('E1', 'Nombre1')
                            ->setCellValue('F1', 'Nombre2')
                            ->setCellValue('G1', 'Apellido1')
                            ->setCellValue('H1', 'Apellido2')
                            ->setCellValue('I1', 'FechaNacimiento')
                            ->setCellValue('J1', 'Genero')
                            ->setCellValue('K1', 'Cargo')
                            ->setCellValue('L1', 'FechaVinculacion')
                            ->setCellValue('M1', 'CodigoCurso')
                            ->setCellValue('N1', 'NitEscuela')
                            ->setCellValue('O1', 'Nro')
                            ->setCellValue('P1', 'TipoEstablecimiento')
                            ->setCellValue('Q1', 'TelefonoR')
                            ->setCellValue('R1', 'DireccionR')
                            ->setCellValue('S1', 'DireccionP')
                            ->setCellValue('T1', 'Departamento')
                            ->setCellValue('U1', 'Ciudad')
                            ->setCellValue('V1', 'EducacionBM')
                            ->setCellValue('W1', 'EducacionSuperior')
                            ->setCellValue('X1', 'Discapacidad');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                $arAcreditaciones = $query->getResult();
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                foreach ($arAcreditaciones as $arAcreditacion) {
                    
                    //tipo identificacion
                    $tipoIdentificacion = 1;
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 13){
                        $tipoIdentificacion = 1;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 12){
                        $tipoIdentificacion = 1;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 21){
                        $tipoIdentificacion = 3;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 22){
                        $tipoIdentificacion = 3;
                    }
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoTipoIdentificacionFk() == 41){
                        $tipoIdentificacion = 6;
                    }
                    //
                    $sexo = "";
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoSexoFk() == "M"){
                        $sexo = 1;
                    } else {
                        $sexo = 2;
                    }
                    $cargo = "";
                    if ($arAcreditacion->getCodigoAcreditacionTipoFk() != null){
                        if ($arAcreditacion->getAcreditacionTipoRel()->getCargo() == "VIGILANTE"){
                            $cargo = 1;
                        }
                        if ($arAcreditacion->getAcreditacionTipoRel()->getCargo() == "ESCOLTA"){
                            $cargo = 2;
                        }
                        if ($arAcreditacion->getAcreditacionTipoRel()->getCargo() == "TRIPULANTE"){
                            $cargo = 3;
                        }
                        if ($arAcreditacion->getAcreditacionTipoRel()->getCargo() == "SUPERVISOR"){
                            $cargo = 4;
                        }
                        if ($arAcreditacion->getAcreditacionTipoRel()->getCargo() == "OPERADOR DE MEDIOS TECNOLOGICOS"){
                            $cargo = 5;
                        }
                        if ($arAcreditacion->getAcreditacionTipoRel()->getCargo() == "MANEJADOR CANINO"){
                            $cargo = 6;
                        }
                        if ($arAcreditacion->getAcreditacionTipoRel()->getCargo() == "DIRECTIVO"){
                            $cargo = 7;
                        }
                    } else {
                        $cargo = "";
                    }    
                    //CONTRATO
                    $codigoContrato = "";
                    if ($arAcreditacion->getEmpleadoRel()->getCodigoContratoActivoFk() != null){
                        $codigoContrato = $arAcreditacion->getEmpleadoRel()->getCodigoContratoActivoFk();
                        
                    } else {
                        if ($arAcreditacion->getEmpleadoRel()->getCodigoContratoUltimoFk() != null){
                            $codigoContrato = $arAcreditacion->getEmpleadoRel()->getCodigoContratoUltimoFk();
                        } else {
                            $codigoContrato = 0;
                        }                        
                    }
                    
                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                    $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);                    
                    
                    $ciudadLabora = "";
                    if ($arContrato != null){
                        if ($arContrato->getCodigoCiudadLaboraFk() != null){
                            $ciudadLabora = $arContrato->getCiudadLaboraRel()->getNombre();
                        }
                        $ciudadLabora = explode(" ", $ciudadLabora);
                        $ciudadLabora = $ciudadLabora[0];

                        if ($ciudadLabora == ""){
                            $departamentoCiudadLabora = "";
                        } else {
                            $departamentoCiudadLabora = $arContrato->getCiudadLaboraRel()->getDepartamentoRel()->getNombre();
                        }
                    $contratoFechaDesde = $arContrato->getFechaDesde()->format('d/m/Y');    
                    } else {
                        $ciudadLabora = "";
                        $departamentoCiudadLabora = "";
                        $contratoFechaDesde = "";
                    }
                    $academia = "";
                    $telefono = "";  
                    $tipoAcreditacion = "";
                    $nivelEstudio = "";
                    $gradoBachiller = "Ninguna";
                    $superior = "Ninguna";                   
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arConfiguracion->getNitEmpresa().$arConfiguracion->getDigitoVerificacionEmpresa())
                            ->setCellValue('B' . $i, strtoupper($arConfiguracion->getNombreEmpresa()))
                            ->setCellValue('C' . $i, $tipoIdentificacion)
                            ->setCellValue('D' . $i, $arAcreditacion->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getNombre1()))
                            ->setCellValue('F' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getNombre2()))
                            ->setCellValue('G' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getApellido1()))
                            ->setCellValue('H' . $i, strtoupper($arAcreditacion->getEmpleadoRel()->getApellido2()))
                            ->setCellValue('I' . $i, $arAcreditacion->getEmpleadoRel()->getFechaNacimiento()->format('d/m/Y'))
                            ->setCellValue('J' . $i, $sexo)
                            ->setCellValue('K' . $i, $cargo)
                            ->setCellValue('L' . $i, $contratoFechaDesde)
                            ->setCellValue('M' . $i, $tipoAcreditacion)
                            ->setCellValue('N' . $i, $academia)
                            ->setCellValue('O' . $i, $arAcreditacion->getNumeroAcreditacion())
                            ->setCellValue('P' . $i, "Principal")
                            ->setCellValue('Q' . $i, $telefono)
                            ->setCellValue('R' . $i, $arAcreditacion->getEmpleadoRel()->getDireccion())
                            ->setCellValue('S' . $i, $arAcreditacion->getEmpleadoRel()->getDireccion())//FALTA LA DIRECCION DEL PUESTO
                            ->setCellValue('T' . $i, $departamentoCiudadLabora)
                            ->setCellValue('U' . $i, $ciudadLabora)
                            ->setCellValue('V' . $i, $gradoBachiller)
                            ->setCellValue('W' . $i, ucfirst($superior))
                            ->setCellValue('X' . $i, "Ninguna");
                    $i++;
                }
                
                $nombreArchivo = "APO".$arConfiguracion->getNitEmpresa()."".date('Y-m-d');
                $objPHPExcel->getActiveSheet()->setTitle('EstudiosInforme');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="'.$nombreArchivo.'.xlsx"');
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
