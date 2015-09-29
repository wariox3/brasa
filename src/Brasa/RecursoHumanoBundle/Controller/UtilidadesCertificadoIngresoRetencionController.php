<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;

class UtilidadesCertificadoIngresoRetencionController extends Controller
{
    var $strDqlLista = "";
    
    public function CertificadoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();  
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');        
        $formCertificado = $this->formularioLista();
        $formCertificado->handleRequest($request);
        if($formCertificado->isValid()) {
            if($formCertificado->get('BtnGenerar')->isClicked()) {
                $controles = $request->request->get('form');
                if ($formCertificado->get('txtIdentificacion')->getData() == "" && $formCertificado->get('centroCostoRel')->getData() == ""){
                    $objMensaje->Mensaje("error", "Por favor ingresar el número de identificación o el código del centro de costo para generar el certificado de ingresos y retenciones!", $this);
                } else {
                    if ($formCertificado->get('txtIdentificacion')->getData() != ""){
                        $empleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $empleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $formCertificado->get('txtIdentificacion')->getData()));
                        if (count($empleado) == 0){
                            $objMensaje->Mensaje("error", "No existe el empleado con el número de identificación ".$controles['txtIdentificacion']."", $this);
                        } else {
                            $codigoEmpleado = $empleado->getCodigoEmpleadoPk();
                            $strFechaExpedicion = $formCertificado->get('fechaExpedicion')->getData();
                            $strLugarExpedicion = $controles['LugarExpedicion'];
                            $strFechaCertificado = $controles['fechaCertificado'];
                            $strAfc = $controles['afc'];
                            $stCertifico1 = $controles['certifico1'];
                            $stCertifico2 = $controles['certifico2'];
                            $stCertifico3 = $controles['certifico3'];
                            $stCertifico4 = $controles['certifico4'];
                            $stCertifico5 = $controles['certifico5'];
                            $stCertifico6 = $controles['certifico6'];
                            $datFechaCertificadoInicio = $strFechaCertificado."-01-01";
                            $datFechaCertificadoFin = $strFechaCertificado."-12-30";
                            $arrayCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosFechaCertificadoIngreso($codigoEmpleado,$datFechaCertificadoInicio, $datFechaCertificadoFin );
                            $floPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelvePrestacionalEmpleadoFecha($codigoEmpleado, $strFechaCertificado);
                            $floPrestacional = (float)$floPrestacional;
                            $floPension = (float)$arrayCostos[0]['Pension'];
                            $floSalud = (float)$arrayCostos[0]['Salud'];
                            $datFechaInicio = $arrayCostos[0]['fechaInicio'];
                            $datFechaFin = $arrayCostos[0]['fechaFin'];
                            $arrayCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelveCesantiasFecha($codigoEmpleado,$datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floCesantias = (float)$arrayCesantias[0]['Cesantias'];
                            $arrayVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->devuelveVacacionesFecha($codigoEmpleado,$datFechaCertificadoInicio, $datFechaCertificadoFin);
                            $floVacaciones = (float)$arrayVacaciones[0]['Vacaciones'];
                            $douRetencion = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveRetencionFuenteEmpleadoFecha($codigoEmpleado, $strFechaCertificado);
                            $douRetencion = (float)$douRetencion;
                            $duoGestosRepresentacion = 0;
                            $douOtrosIngresos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveOtrosIngresosEmpleadoFecha($codigoEmpleado, $strFechaCertificado);
                            $douOtrosIngresos = (float)$douOtrosIngresos;
                            $duoTotalIngresos = $floCesantias + $duoGestosRepresentacion + $douOtrosIngresos + $floPrestacional + $floVacaciones;
                            if ( $floPrestacional > 0){
                                $objFormatoCertificadoIngreso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCertificadoIngreso();
                                $objFormatoCertificadoIngreso->Generar($this,$codigoEmpleado,$strFechaExpedicion,$strLugarExpedicion,$strFechaCertificado,$strAfc,$stCertifico1,$stCertifico2,$stCertifico3,$stCertifico4,$stCertifico5,$stCertifico6,$floPrestacional,$floPension,$floSalud,$datFechaInicio,$datFechaFin,$floCesantias,$douRetencion,$duoGestosRepresentacion,$douOtrosIngresos,$duoTotalIngresos,"");  
                            } else {
                                $objMensaje->Mensaje("error", "Este empleado no registra información de ingresos  y retenciones para el año ". $strFechaCertificado."" , $this);                
                            }
                        }    
                    } else {
                        
                        $empleadosCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->createQueryBuilder('c')
                            ->where('c.codigoCentroCostoFk = :centroCosto')
                            ->andWhere('c.fechaDesde LIKE :fechaDesde')
                            ->andWhere('c.fechaHasta LIKE :fechaHasta')
                            ->setParameter('centroCosto', $controles['centroCostoRel'])
                            ->setParameter('fechaDesde', '%'.$controles['fechaCertificado'].'%')
                            ->setParameter('fechaHasta', '%'.$controles['fechaCertificado'].'%')
                            ->getQuery()
                            ->getResult();
                            $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                                    $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                                    /*$strRutaGeneral = $arConfiguracion->getRutaTemporal();
                                    if(!file_exists($strRutaGeneral)) {
                                        mkdir($strRutaGeneral, 0777);
                                    }
                                    /*$strRuta = $strRutaGeneral . "CertificadoIngresoRetencion" . $empleadoCentroCosto->getCentroCostoRel()->getNombre() . $strFechaCertificado ."/";
                                    if(!file_exists($strRuta)) {
                                    mkdir($strRuta, 0777);
                                    }*/
                                    $strRutaGeneral = "C:\wamp\www\prueba";
                                    $strRuta = "";
                            foreach ($empleadosCentroCosto as $empleadoCentroCosto) {
                                $codigoEmpleado = $empleadoCentroCosto->getCodigoEmpleadoFk();
                                $strFechaExpedicion = $formCertificado->get('fechaExpedicion')->getData();
                                $strLugarExpedicion = $controles['LugarExpedicion'];
                                $strFechaCertificado = $controles['fechaCertificado'];
                                $strAfc = $controles['afc'];
                                $stCertifico1 = $controles['certifico1'];
                                $stCertifico2 = $controles['certifico2'];
                                $stCertifico3 = $controles['certifico3'];
                                $stCertifico4 = $controles['certifico4'];
                                $stCertifico5 = $controles['certifico5'];
                                $stCertifico6 = $controles['certifico6'];
                                $datFechaCertificadoInicio = $strFechaCertificado."-01-01";
                                $datFechaCertificadoFin = $strFechaCertificado."-12-30";
                                $arrayCostos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->devuelveCostosFechaCertificadoIngreso($codigoEmpleado,$datFechaCertificadoInicio, $datFechaCertificadoFin );
                                $floPrestacional = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelvePrestacionalEmpleadoFecha($codigoEmpleado, $strFechaCertificado);
                                $floPrestacional = (float)$floPrestacional;
                                $floPension = (float)$arrayCostos[0]['Pension'];
                                $floSalud = (float)$arrayCostos[0]['Salud'];
                                $datFechaInicio = $arrayCostos[0]['fechaInicio'];
                                $datFechaFin = $arrayCostos[0]['fechaFin'];
                                $arrayCesantias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLiquidacion')->devuelveCesantiasFecha($codigoEmpleado,$datFechaCertificadoInicio, $datFechaCertificadoFin);
                                $floCesantias = (float)$arrayCesantias[0]['Cesantias'];
                                $arrayVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->devuelveVacacionesFecha($codigoEmpleado,$datFechaCertificadoInicio, $datFechaCertificadoFin);
                                $floVacaciones = (float)$arrayVacaciones[0]['Vacaciones'];
                                $douRetencion = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveRetencionFuenteEmpleadoFecha($codigoEmpleado, $strFechaCertificado);
                                $douRetencion = (float)$douRetencion;
                                $duoGestosRepresentacion = 0;
                                $douOtrosIngresos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->devuelveOtrosIngresosEmpleadoFecha($codigoEmpleado, $strFechaCertificado);
                                $douOtrosIngresos = (float)$douOtrosIngresos;
                                $duoTotalIngresos = $floCesantias + $duoGestosRepresentacion + $douOtrosIngresos + $floPrestacional + $floVacaciones;
                                if ( $floPrestacional > 0){
                                    
                                    $objFormatoCertificadoIngreso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCertificadoIngreso();
                                    $objFormatoCertificadoIngreso->Generar($this,$codigoEmpleado,$strFechaExpedicion,$strLugarExpedicion,$strFechaCertificado,$strAfc,$stCertifico1,$stCertifico2,$stCertifico3,$stCertifico4,$stCertifico5,$stCertifico6,$floPrestacional,$floPension,$floSalud,$datFechaInicio,$datFechaFin,$floCesantias,$douRetencion,$duoGestosRepresentacion,$douOtrosIngresos,$duoTotalIngresos,$strRuta);  
                                    $strRutaZip = $strRutaGeneral . 'CertficadoIngresoRetencion' .$empleadoCentroCosto->getCentroCostoRel()->getNombre() . $strFechaCertificado . '.zip';                     
                                }
                                
                            }
                            $this->comprimir($strRuta, $strRutaZip);                                                
                                    $dir = opendir($strRuta);                
                                    while ($current = readdir($dir)){
                                        if( $current != "." && $current != "..") {
                                            unlink($strRuta . $current);
                                        }                    
                                    } 
                                    rmdir($strRuta);

                                    // Generate response
                                    $response = new Response();

                                    // Set headers
                                    $response->headers->set('Cache-Control', 'private');
                                    $response->headers->set('Content-type', 'application/zip');
                                    $response->headers->set('Content-Transfer-Encoding', 'binary');                
                                    $response->headers->set('Content-Disposition', 'attachment; filename="CertificadosIngresosretenciones' . $empleadoCentroCosto->getCentroCostoRel()->getNombre() . $strFechaCertificado . '.zip";');
                                    //$response->headers->set('Content-length', '');        
                                    $response->sendHeaders();
                                    $response->setContent(readfile($strRutaZip));    
                                    unlink($strRutaZip);
                    }
                    
                }    
            }
        }                    
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/CertificadoIngresoRetencion:Certificado.html.twig', array(            
            'formCertificado' => $formCertificado->createView()));
    }              
    
    private function formularioLista() {
        
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $ConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $ConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $fechaActual = date('Y-m-j');
        $anioActual = date('Y');
        $fechaPrimeraAnterior = strtotime ( '-1 year' , strtotime ( $fechaActual ) ) ;
        $fechaPrimeraAnterior = date ( 'Y' , $fechaPrimeraAnterior );
        $fechaSegundaAnterior = strtotime ( '-2 year' , strtotime ( $fechaActual ) ) ;
        $fechaSegundaAnterior = date ( 'Y' , $fechaSegundaAnterior );
        $fechaTerceraAnterior = strtotime ( '-3 year' , strtotime ( $fechaActual ) ) ;
        $fechaTerceraAnterior = date ( 'Y' , $fechaTerceraAnterior );
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
        $formCertificado = $this->createFormBuilder()                        
            ->add('txtIdentificacion', 'text', array('data' => '', 'required' => false))
            ->add('centroCostoRel', 'entity', $arrayPropiedades)    
            ->add('fechaCertificado', 'choice', array('choices' => array($anioActual = date('Y') => $anioActual = date('Y'),$fechaPrimeraAnterior => $fechaPrimeraAnterior, $fechaSegundaAnterior => $fechaSegundaAnterior, $fechaTerceraAnterior => $fechaTerceraAnterior),))    
            ->add('fechaExpedicion','date', array('data' => new \ DateTime('now')))
            ->add('LugarExpedicion', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('afc', 'number', array('data' => '0', 'required' => false))
            ->add('certifico1', 'text', array('data' => '1. Mi patrimonio bruto era igual o inferior a 4.500 UVT ($123.683.000)', 'required' => true))                
            ->add('certifico2', 'text', array('data' => '2. No fui responsable del impuesto sobre las ventas', 'required' => true))                
            ->add('certifico3', 'text', array('data' => '3. Mis ingresos totales fueron iguales o inferiores a 1.400 UVT ($38.479.000)', 'required' => true))
            ->add('certifico4', 'text', array('data' => '4. Mis consumos mediante tarjeta de crédito no excedieron la suma de 2.800 UVT ($76.958.000)', 'required' => true))
            ->add('certifico5', 'text', array('data' => '5. Quen el total de mis compras y consumos no superaron la suma de 2.800 UVT ($76.958.000)', 'required' => true))                
            ->add('certifico6', 'text', array('data' => '6. Que el valor total de mis consignaciones bancarias, depósitos o inversiones financieras no excedieron la suma de 4.500 UVT ($123.683.000)', 'required' => true))                
            ->add('BtnGenerar', 'submit', array('label' => 'Generar'))    
            ->getForm();        
        return $formCertificado;
    }                 
    
    function comprimir($ruta, $zip_salida, $handle = false, $recursivo = false) {

        /* Declara el handle del objeto */
        if (!$handle) {
            $handle = new \ZipArchive();
            if ($handle->open($zip_salida, ZipArchive::CREATE) === false) {
                return false; /* Imposible crear el archivo ZIP */
            }
        }

        /* Procesa directorio */
        if (is_dir($ruta)) {
            /* Aseguramos que sea un directorio sin carácteres corruptos */
            $ruta = dirname($ruta . '/arch.ext');
            $handle->addEmptyDir($ruta); /* Agrega el directorio comprimido */
            foreach (glob($ruta . '/*') as $url) { /* Procesa cada directorio o archivo dentro de el */
                $this->comprimir($url, $zip_salida, $handle, true); /* Comprime el subdirectorio o archivo */
            }

            /* Procesa archivo */
        } else {
            $handle->addFile($ruta);
        }

        /* Finaliza el ZIP si no se está ejecutando una acción recursiva en progreso */
        if (!$recursivo) {
            $handle->close();
        }

        return true; /* Retorno satisfactorio */
    }

}
