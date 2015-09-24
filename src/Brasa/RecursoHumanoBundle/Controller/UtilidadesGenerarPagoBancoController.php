<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class UtilidadesGenerarPagoBancoController extends Controller
{
    
    public function GenerarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPagosExportar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExportar();
        $arPagosExportar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExportar')->findAll(); 
        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $arrayPropiedadesBancos = array(
            'class' => 'BrasaGeneralBundle:GenCuenta',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')                                        
                ->orderBy('c.nombre', 'ASC');},
            'property' => 'nombre',
            'required' => true);                   
        
        $form = $this->createFormBuilder()
            ->add('BtnGenerarTxt', 'submit', array('label'  => 'Generar archivo banco',))
            ->add('descripcion', 'text', array('data'  => '225PAGO NOMI '),array('required' => true))
            ->add('cuentaRel', 'entity', $arrayPropiedadesBancos) 
            ->add('fechaTransmision', 'text', array('required' => true))
            ->add('secuencia', 'choice', array('choices' => array('A' => 'A', 'B' => 'B','C' => 'C', 'D' => 'D','E' => 'E', 'F' => 'F','G' => 'G', 'H' => 'H','I' => 'I', 'J' => 'J','K' => 'K', 'L' => 'L','M' => 'M', 'N' => 'N','O' => 'O', 'P' => 'P','Q' => 'Q', 'R' => 'R','S' => 'S', 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z'),))
            ->add('fechaAplicacion', 'text', array('required' => true))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $controles = $request->request->get('form');
            $arPagoExportar = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoExportar();
            $arPagoExportar = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExportar')->findAll(); 
            if($form->get('BtnGenerarTxt')->isClicked()) {                
                if (count($arPagoExportar) == 0){
                    $objMensaje->Mensaje("error", "No hay registros a generar", $this);
                } else {
                    if ($controles['fechaTransmision'] == ""){
                        $objMensaje->Mensaje("error", "Se requiere la fecha de transmisión", $this);
                    } else {
                        if ($controles['fechaAplicacion'] == ""){
                            $objMensaje->Mensaje("error", "Se require la fecha de aplicación", $this);
                        }else {                  
                            $strNombreArchivo = "PagoBanco" . date('YmdHis') . ".txt";
                            $strArchivo = $arConfiguracionGeneral->getRutaTemporal() . $strNombreArchivo;
                            //$strArchivo = "" . $strNombreArchivo;
                            $ar = fopen($strArchivo,"a") or die("Problemas en la creacion del archivo plano");
                            // Encabezado
                            $strNitEmpresa = $arConfiguracionGeneral->getNitEmpresa();
                            $strNombreEmpresa = $arConfiguracionGeneral->getNombreEmpresa();
                            $strTipoPagoSecuencia = $controles['descripcion'];
                            $strFechaCreacion = $controles['fechaTransmision'];
                            $strSecuencia = $controles['secuencia'];
                            $strFechaAplicacion = $controles['fechaAplicacion'];
                            $intCodigoCuenta = $controles['cuentaRel'];
                            $arCuenta = new \Brasa\GeneralBundle\Entity\GenCuenta();
                            $arCuenta = $em->getRepository('BrasaGeneralBundle:GenCuenta')->find($intCodigoCuenta);
                            $strNumeroRegistros = count($arPagoExportar);
                            $strNumeroRegistros = $this->RellenarNr($strNumeroRegistros, "0", 6, "I");
                            $strValorTotal = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExportar')->totalValorResgistrosPagoExportar();
                            $strValorTotal = $this->RellenarNr($strValorTotal, "0", 24, "I");
                            //Fin encabezado
                            fputs($ar, "1" . $strNitEmpresa . $strNombreEmpresa . $strTipoPagoSecuencia .
                                  $strFechaCreacion . $strSecuencia . $strFechaAplicacion . $strNumeroRegistros . 
                                  $strValorTotal . $arCuenta->getCuenta() . $arCuenta->getTipo() . "\r\n");
                            //Inicio cuerpo
                            foreach ($arPagoExportar AS $arPagoExportar) {
                                    fputs($ar, "6" . $this->RellenarNr($arPagoExportar->getNumeroIdentificacion(), "0", 15, "I"));
                                    $duoNombreCorto = substr($arPagoExportar->getNombreCorto(), 0, 18);
                                    fputs($ar, $this->RellenarNr($duoNombreCorto,"0", 18, "I"));
                                    fputs($ar, "005600078");
                                    fputs($ar, $this->RellenarNr($arPagoExportar->getCuenta(), "0", 17, "I"));
                                    fputs($ar, "S37");
                                    $duoValorNetoPagar = round($arPagoExportar->getVrPago());
                                    fputs($ar, ($this->RellenarNr($duoValorNetoPagar, "0", 10, "I")));
                                    fputs($ar, " ");
                                    fputs($ar, "\r\n");
                                }
                                //Fin cuerpo
                                fclose($ar);                
                                header('Content-Description: File Transfer');
                                      header('Content-Type: text/csv; charset=ISO-8859-15');
                                      header('Content-Disposition: attachment; filename='.basename($strArchivo));
                                      header('Expires: 0');
                                      header('Cache-Control: must-revalidate');
                                      header('Pragma: public');
                                      header('Content-Length: ' . filesize($strArchivo));
                                      readfile($strArchivo);
                                $arPagoExportar2 = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoExportar')->findAll();             
                                foreach ($arPagoExportar2 AS $arPagoExportar2) {
                                $em->remove($arPagoExportar2);
                                $em->flush();
                                }
                                exit;      
                        }    
                    }
                }
                
            }
        }
        
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/PagoBanco/GenerarArchivoBanco:GenerarArchivoBanco.html.twig', array(
                'form' => $form->createView(),
                'arPagosExportar' => $arPagosExportar,
                ));
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
