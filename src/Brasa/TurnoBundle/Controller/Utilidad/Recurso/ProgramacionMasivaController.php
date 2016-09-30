<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Recurso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;


class ProgramacionMasivaController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/recurso/programacion/masiva/{anio}/{mes}/{codigoRecurso}", name="brs_tur_utilidad_recurso_programacion_masiva")
     */    
    public function detalleAction($anio, $mes, $codigoRecurso) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        $form = $this->formularioDetalleEditar();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGuardar')->isClicked()) {                
                    $arrControles = $request->request->All();
                    $this->actualizarDetalle($arrControles);                    
                    //$em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($codigoProgramacion);
                    //echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                
                    echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";                                
            }
        }
        $strAnioMes = $anio."/".$mes;
        $arrDiaSemana = array();
        for($i = 1; $i <= 31; $i++) {
            $strFecha = $strAnioMes . '/' . $i;
            $dateFecha = date_create($strFecha);
            $diaSemana = $this->devuelveDiaSemanaEspaniol($dateFecha);
            $arrDiaSemana[$i] = array('dia' => $i, 'diaSemana' => $diaSemana);
        }        
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array ('anio' => $anio, 'mes' => $mes, 'codigoRecursoFk' => $codigoRecurso));                            
        return $this->render('BrasaTurnoBundle:Utilidades/Recurso:detalle.html.twig', array(
                    'arProgramacionDetalle' => $arProgramacionDetalle,
                    'arRecurso' => $arRecurso,
                    'arrDiaSemana' => $arrDiaSemana,
                    'form' => $form->createView(),                    
                    ));
    }
    
    private function formularioDetalleEditar() {
        $form = $this->createFormBuilder(array(), array('csrf_protection' => false))                    
                    ->add('BtnGuardar', 'submit', array('label' => 'Guardar'))
                    ->getForm();
        return $form;
    }      

    private function devuelveDiaSemanaEspaniol ($dateFecha) {
        $strDia = "";
        switch ($dateFecha->format('N')) {
            case 1:
                $strDia = "l";
                break;
            case 2:
                $strDia = "m";
                break;
            case 3:
                $strDia = "i";
                break;
            case 4:
                $strDia = "j";
                break;
            case 5:
                $strDia = "v";
                break;
            case 6:
                $strDia = "s";
                break;
            case 7:
                $strDia = "d";
                break;
        }

        return $strDia;
    }    
    
    private function actualizarDetalle ($arrControles) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $validarHoras = $arConfiguracion->getValidarHorasProgramacion();        
        $intIndice = 0;
        $boolTurnosSobrepasados = false;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {            
            $horasDiurnas = 0;
            $horasNocturnas = 0;
            $horasDiurnasProgramacion = 0;
            $horasNocturnasProgramacion = 0;            
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);
            $arPedidoDetalle = new \Brasa\TurnoBundle\Entity\TurPedidoDetalle();
            $arPedidoDetalle = $em->getRepository('BrasaTurnoBundle:TurPedidoDetalle')->find($arProgramacionDetalle->getCodigoPedidoDetalleFk());            
            $horasDiurnas = $arPedidoDetalle->getHorasDiurnasProgramadas() - $arProgramacionDetalle->getHorasDiurnas();
            $horasNocturnas = $arPedidoDetalle->getHorasNocturnasProgramadas() - $arProgramacionDetalle->getHorasNocturnas();
            $horasDiurnasContratadas = $arPedidoDetalle->getHorasDiurnas();
            $horasNocturnasContratadas = $arPedidoDetalle->getHorasNocturnas();                                         
            
            if($arrControles['TxtDia01D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia01D'.$intCodigo]);                    
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia1($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia1($arrTurno['turno']);
                    } else {                        
                        $arProgramacionDetalle->setDia1(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia1(null);
            }
            if($arrControles['TxtDia02D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia02D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia2($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia2($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia2(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia2(null);
            }
            if($arrControles['TxtDia03D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia03D'.$intCodigo]);                    
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia3($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia3($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia3(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia3(null);
            }
            if($arrControles['TxtDia04D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia04D'.$intCodigo]);                    
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia4($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia4($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia4(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }
            } else {
                $arProgramacionDetalle->setDia4(null);
            }
            if($arrControles['TxtDia05D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia05D'.$intCodigo]);   
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia5($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia5($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia5(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia5(null);
            }
            if($arrControles['TxtDia06D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia06D'.$intCodigo]); 
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia6($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia6($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia6(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia6(null);
            }
            if($arrControles['TxtDia07D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia07D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia7($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia7($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia7(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia7(null);
            }
            if($arrControles['TxtDia08D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia08D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia8($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia8($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia8(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia8(null);
            }
            if($arrControles['TxtDia09D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia09D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia9($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia9($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia9(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia9(null);
            }
            if($arrControles['TxtDia10D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia10D'.$intCodigo]);   
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia10($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia10($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia10(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia10(null);
            }
            if($arrControles['TxtDia11D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia11D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia11($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia11($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia11(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia11(null);
            }
            if($arrControles['TxtDia12D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia12D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia12($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia12($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia12(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia12(null);
            }
            if($arrControles['TxtDia13D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia13D'.$intCodigo]); 
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia13($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia13($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia13(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia13(null);
            }
            if($arrControles['TxtDia14D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia14D'.$intCodigo]);    
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia14($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia14($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia14(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia14(null);
            }
            if($arrControles['TxtDia15D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia15D'.$intCodigo]);    
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia15($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia15($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia15(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia15(null);
            }                            
            
            if($arrControles['TxtDia16D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia16D'.$intCodigo]);    
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia16($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia16($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia16(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia16(null);
            }
            if($arrControles['TxtDia17D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia17D'.$intCodigo]); 
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia17($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia17($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia17(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia17(null);
            }
            if($arrControles['TxtDia18D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia18D'.$intCodigo]); 
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia18($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia18($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia18(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    

            } else {
                $arProgramacionDetalle->setDia18(null);
            }
            if($arrControles['TxtDia19D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia19D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia19($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia19($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia19(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia19(null);
            }
            if($arrControles['TxtDia20D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia20D'.$intCodigo]); 
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia20($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia20($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia20(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia20(null);
            }
            if($arrControles['TxtDia21D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia21D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia21($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia21($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia21(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia21(null);
            }
            if($arrControles['TxtDia22D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia22D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia22($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia22($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia22(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia22(null);
            }
            if($arrControles['TxtDia23D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia23D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia23($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia23($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia23(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia23(null);
            }
            if($arrControles['TxtDia24D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia24D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia24($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia24($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia24(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia24(null);
            }
            if($arrControles['TxtDia25D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia25D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia25($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia25($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia25(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia25(null);
            }
            if($arrControles['TxtDia26D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia26D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia26($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia26($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia26(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia26(null);
            }
            if($arrControles['TxtDia27D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia27D'.$intCodigo]);  
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia27($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia27($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia27(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia27(null);
            }
            if($arrControles['TxtDia28D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia28D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia28($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia28($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia28(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia28(null);
            }
            if($arrControles['TxtDia29D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia29D'.$intCodigo]); 
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia29($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia29($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia29(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia29(null);
            }
            if($arrControles['TxtDia30D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia30D'.$intCodigo]);
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia30($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia30($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia30(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    

            } else {
                $arProgramacionDetalle->setDia30(null);
            }
            if($arrControles['TxtDia31D'.$intCodigo] != '') {
                $arrTurno = $this->validarTurno($arrControles['TxtDia31D'.$intCodigo]);   
                if($validarHoras == false) {
                    $arProgramacionDetalle->setDia31($arrTurno['turno']);
                    $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                    $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                } else {
                    if($horasDiurnas +  $arrTurno['horasDiurnas'] <= $horasDiurnasContratadas && $horasNocturnas +  $arrTurno['horasNocturnas'] <= $horasNocturnasContratadas) {
                        $horasDiurnas += $arrTurno['horasDiurnas'];
                        $horasNocturnas +=  $arrTurno['horasNocturnas'];
                        $horasDiurnasProgramacion += $arrTurno['horasDiurnas'];
                        $horasNocturnasProgramacion += $arrTurno['horasNocturnas'];                        
                        $arProgramacionDetalle->setDia31($arrTurno['turno']);
                    } else {
                        $arProgramacionDetalle->setDia31(null);
                        $boolTurnosSobrepasados = true;
                    }                        
                }                    
            } else {
                $arProgramacionDetalle->setDia31(null);
            }                
            
            if($validarHoras == true) {
                $arPedidoDetalle->setHorasDiurnasProgramadas($horasDiurnas);
                $arPedidoDetalle->setHorasNocturnasProgramadas($horasNocturnas);
                $arPedidoDetalle->setHorasProgramadas($horasDiurnas+$horasNocturnas);               
                $em->persist($arPedidoDetalle);
            }
            $arProgramacionDetalle->setHorasDiurnas($horasDiurnasProgramacion);
            $arProgramacionDetalle->setHorasNocturnas($horasNocturnasProgramacion);
            $arProgramacionDetalle->setHoras($horasDiurnasProgramacion+$horasNocturnasProgramacion);            
            $em->persist($arProgramacionDetalle);
        }
        $em->flush();
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);
            $em->getRepository('BrasaTurnoBundle:TurProgramacion')->liquidar($arProgramacionDetalle->getCodigoProgramacionFk());    
        }
        if($boolTurnosSobrepasados == true) {
            $objMensaje->Mensaje('error', "Algunos turnos no fueron aplicados porque sobrepasaban las horas contratadas del pedido", $this);
        }
        
    }

    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();        
        $arrTurno = array('turno' => null, 'horasDiurnas' => 0, 'horasNocturnas' => 0);
        if($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno) {
                $arrTurno['turno'] = $strTurno;
                $arrTurno['horasDiurnas'] = $arTurno->getHorasDiurnas();
                $arrTurno['horasNocturnas'] = $arTurno->getHorasNocturnas();
            }
        }

        return $arrTurno;
    } 
    
}
