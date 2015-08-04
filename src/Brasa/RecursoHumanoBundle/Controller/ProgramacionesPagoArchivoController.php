<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ProgramacionesPagoArchivoController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }                          
        }            
        $arProgramacionPagoArchivo = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);                               
        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPagoArchivo:lista.html.twig', array(
            'arProgramacionPagoArchivo' => $arProgramacionPagoArchivo,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $mensajefechaTransmision = 0;
        $paginator  = $this->get('knp_paginator');
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);        
        $arConfiguracionGeneral = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracionGeneral();
        $arConfiguracionGeneral = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracionGeneral')->find(1);        
        $form = $this->createFormBuilder()
            ->add('BtnGenerarTxt', 'submit', array('label'  => 'Generar archivo',))
            ->add('descripcion', 'text', array('data'  => '225PAGO NOMI '),array('required' => true))
            ->add('cuenta', 'text', array('data'  => '42073078473'),array('required' => true))
            ->add('tipoCuenta', 'choice', array('choices' => array('D' => 'CORRIENTE', 'S' => 'AHORRO')))
            ->add('fechaTransmision', 'text', array('required' => true))
            ->add('secuencia', 'choice', array('choices' => array('A' => 'A', 'B' => 'B','C' => 'C', 'D' => 'D','E' => 'E', 'F' => 'F','G' => 'G', 'H' => 'H','I' => 'I', 'J' => 'J','K' => 'K', 'L' => 'L','M' => 'M', 'N' => 'N','O' => 'O', 'P' => 'P','Q' => 'Q', 'R' => 'R','S' => 'S', 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z'),))
            ->add('fechaAplicacion', 'text', array('required' => true))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $controles = $request->request->get('form');                        
            if($form->get('BtnGenerarTxt')->isClicked()) {                
                if ($controles['fechaTransmision'] == ""){
                    $mensajefechaTransmision = "Se requiere la fecha de transmisión";
                } else {
                  if ($controles['fechaAplicacion'] == ""){
                    $mensajefechaTransmision = "Se requiere la fecha de aplicación";
                  }else {  
                    
                $strNombreArchivo = "PagoNomina" . date('YmdHis') . ".txt";
                $ar = fopen($strNombreArchivo,"a") or die("Problemas en la creacion del archivo plano");
                // Encabezado
                $strNitEmpresa = $arConfiguracionGeneral->getNit();
                $strNombreEmpresa = $arConfiguracionGeneral->getEmpresa();
                $strTipoPagoSecuencia = $controles['descripcion'];
                $strFechaCreacion = $controles['fechaTransmision'];
                $strSecuencia = $controles['secuencia'];
                $strFechaAplicacion = $controles['fechaAplicacion'];
                $strCuenta = $controles['cuenta'];
                $strTipoCuenta = $controles['tipoCuenta'];
                $strNumeroRegistros = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->totalResgistrosProgramacionPago($codigoProgramacionPago);
                $strNumeroRegistros = $this->RellenarNr($strNumeroRegistros, "0", 6, "I");
                $strValorTotal = round($arProgramacionPago->getVrNeto());
                $strValorTotal = $this->RellenarNr($strValorTotal, "0", 24, "I");
                //Fin encabezado
                fputs($ar, "1" . $strNitEmpresa . $strNombreEmpresa . $strTipoPagoSecuencia .
                      $strFechaCreacion . $strSecuencia . $strFechaAplicacion . $strNumeroRegistros . 
                      $strValorTotal . $strCuenta . $strTipoCuenta . "\r\n");
                //Inicio cuerpo
                $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->listaDQLDetalleArchivo($codigoProgramacionPago);
                foreach ($arEmpleados AS $arEmpleado) {
                        fputs($ar, "6" . $this->RellenarNr($arEmpleado->getEmpleadoRel()->getNumeroIdentificacion(), "0", 15, "I"));
                        $duoNombreCorto = substr($arEmpleado->getEmpleadoRel()->getNombreCorto(), 0, 18);
                        fputs($ar, $this->RellenarNr($duoNombreCorto,"0", 18, "I"));
                        fputs($ar, "005600078");
                        fputs($ar, $this->RellenarNr($arEmpleado->getEmpleadoRel()->getCuenta(), "0", 17, "I"));
                        fputs($ar, "S37");
                        $duoValorNetoPagar = round($arEmpleado->getvrNetoPagar());
                        fputs($ar, ($this->RellenarNr($duoValorNetoPagar, "0", 10, "I")));
                        fputs($ar, " ");
                        fputs($ar, "\r\n");
                    }
                //Fin cuerpo
                fclose($ar);
                $strArchivo = $strNombreArchivo;
                header('Content-Description: File Transfer');
                      header('Content-Type: text/csv; charset=ISO-8859-15');
                      header('Content-Disposition: attachment; filename='.basename($strArchivo));
                      header('Expires: 0');
                      header('Cache-Control: must-revalidate');
                      header('Pragma: public');
                      header('Content-Length: ' . filesize($strArchivo));
                      readfile($strArchivo);
                      
                $arProgramacionPagoGenerado = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPagoGenerado = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);
                $arProgramacionPagoGenerado->setArchivoExportado(1);
                $em->persist($arProgramacionPagoGenerado);
                $em->flush();
                exit;      
                }    
                }
            }
            
        }
        
        $query = $em->createQuery($em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPagoDetalle')->listaDQL($codigoProgramacionPago));
        $arProgramacionPagoDetalles = $paginator->paginate($query, $request->query->get('page', 1), 500);        
        return $this->render('BrasaRecursoHumanoBundle:ProgramacionesPagoArchivo:detalle.html.twig', array(
                'arProgramacionPagoDetalles' => $arProgramacionPagoDetalles,
                'arProgramacionPago' => $arProgramacionPago,
                'mensajefechaTransmision' => $mensajefechaTransmision,
                'form' => $form->createView() 
                ));
    }            
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')                                        
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));                                    
        }
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                           
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                                             
            ->getForm();        
        return $form;
    }      

    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->listaDQLArchivo(                    
                    $session->get('filtroCodigoCentroCosto')
                    );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);        
    }
    
    //Rellenar numeros
    private function RellenarNr($Nro, $Str, $NroCr, $strPosicion) {
                     $Longitud = strlen($Nro);
                     $Nc = $NroCr - $Longitud;
                     for ($i = 0; $i < $Nc; $i++) {
                         if($strPosicion == "I") {
                             $Nro = $Str . $Nro;
                         } else {
                             $Nro = $Nro . $Str;
                         }
                     }
                     return (string) $Nro;
                 }
    
}
