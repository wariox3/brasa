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
        $intIndice = 0;
        foreach ($arrControles['LblCodigo'] as $intCodigo) {
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($intCodigo);         
            if($arProgramacionDetalle->getProgramacionRel()->getEstadoAutorizado() ==  0) {
                if($arrControles['TxtDia01D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia01D'.$intCodigo]);
                    $arProgramacionDetalle->setDia1($strTurno);
                } else {
                    $arProgramacionDetalle->setDia1(null);
                }
                if($arrControles['TxtDia02D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia02D'.$intCodigo]);
                    $arProgramacionDetalle->setDia2($strTurno);
                } else {
                    $arProgramacionDetalle->setDia2(null);
                }
                if($arrControles['TxtDia03D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia03D'.$intCodigo]);
                    $arProgramacionDetalle->setDia3($strTurno);
                } else {
                    $arProgramacionDetalle->setDia3(null);
                }
                if($arrControles['TxtDia04D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia04D'.$intCodigo]);
                    $arProgramacionDetalle->setDia4($strTurno);
                } else {
                    $arProgramacionDetalle->setDia4(null);
                }
                if($arrControles['TxtDia05D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia05D'.$intCodigo]);
                    $arProgramacionDetalle->setDia5($strTurno);
                } else {
                    $arProgramacionDetalle->setDia5(null);
                }
                if($arrControles['TxtDia06D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia06D'.$intCodigo]);
                    $arProgramacionDetalle->setDia6($strTurno);
                } else {
                    $arProgramacionDetalle->setDia6(null);
                }
                if($arrControles['TxtDia07D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia07D'.$intCodigo]);
                    $arProgramacionDetalle->setDia7($strTurno);
                } else {
                    $arProgramacionDetalle->setDia7(null);
                }
                if($arrControles['TxtDia08D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia08D'.$intCodigo]);
                    $arProgramacionDetalle->setDia8($strTurno);
                } else {
                    $arProgramacionDetalle->setDia8(null);
                }
                if($arrControles['TxtDia09D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia09D'.$intCodigo]);
                    $arProgramacionDetalle->setDia9($strTurno);
                } else {
                    $arProgramacionDetalle->setDia9(null);
                }
                if($arrControles['TxtDia10D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia10D'.$intCodigo]);
                    $arProgramacionDetalle->setDia10($strTurno);
                } else {
                    $arProgramacionDetalle->setDia10(null);
                }
                if($arrControles['TxtDia11D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia11D'.$intCodigo]);
                    $arProgramacionDetalle->setDia11($strTurno);
                } else {
                    $arProgramacionDetalle->setDia11(null);
                }
                if($arrControles['TxtDia12D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia12D'.$intCodigo]);
                    $arProgramacionDetalle->setDia12($strTurno);
                } else {
                    $arProgramacionDetalle->setDia12(null);
                }
                if($arrControles['TxtDia13D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia13D'.$intCodigo]);
                    $arProgramacionDetalle->setDia13($strTurno);
                } else {
                    $arProgramacionDetalle->setDia13(null);
                }
                if($arrControles['TxtDia14D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia14D'.$intCodigo]);
                    $arProgramacionDetalle->setDia14($strTurno);
                } else {
                    $arProgramacionDetalle->setDia14(null);
                }
                if($arrControles['TxtDia15D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia15D'.$intCodigo]);
                    $arProgramacionDetalle->setDia15($strTurno);
                } else {
                    $arProgramacionDetalle->setDia15(null);
                }
                if($arrControles['TxtDia16D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia16D'.$intCodigo]);
                    $arProgramacionDetalle->setDia16($strTurno);
                } else {
                    $arProgramacionDetalle->setDia16(null);
                }
                if($arrControles['TxtDia17D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia17D'.$intCodigo]);
                    $arProgramacionDetalle->setDia17($strTurno);
                } else {
                    $arProgramacionDetalle->setDia17(null);
                }
                if($arrControles['TxtDia18D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia18D'.$intCodigo]);
                    $arProgramacionDetalle->setDia18($strTurno);
                } else {
                    $arProgramacionDetalle->setDia18(null);
                }
                if($arrControles['TxtDia19D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia19D'.$intCodigo]);
                    $arProgramacionDetalle->setDia19($strTurno);
                } else {
                    $arProgramacionDetalle->setDia19(null);
                }
                if($arrControles['TxtDia20D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia20D'.$intCodigo]);
                    $arProgramacionDetalle->setDia20($strTurno);
                } else {
                    $arProgramacionDetalle->setDia20(null);
                }
                if($arrControles['TxtDia21D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia21D'.$intCodigo]);
                    $arProgramacionDetalle->setDia21($strTurno);
                } else {
                    $arProgramacionDetalle->setDia21(null);
                }
                if($arrControles['TxtDia22D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia22D'.$intCodigo]);
                    $arProgramacionDetalle->setDia22($strTurno);
                } else {
                    $arProgramacionDetalle->setDia22(null);
                }
                if($arrControles['TxtDia23D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia23D'.$intCodigo]);
                    $arProgramacionDetalle->setDia23($strTurno);
                } else {
                    $arProgramacionDetalle->setDia23(null);
                }
                if($arrControles['TxtDia24D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia24D'.$intCodigo]);
                    $arProgramacionDetalle->setDia24($strTurno);
                } else {
                    $arProgramacionDetalle->setDia24(null);
                }
                if($arrControles['TxtDia25D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia25D'.$intCodigo]);
                    $arProgramacionDetalle->setDia25($strTurno);
                } else {
                    $arProgramacionDetalle->setDia25(null);
                }
                if($arrControles['TxtDia26D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia26D'.$intCodigo]);
                    $arProgramacionDetalle->setDia26($strTurno);
                } else {
                    $arProgramacionDetalle->setDia26(null);
                }
                if($arrControles['TxtDia27D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia27D'.$intCodigo]);
                    $arProgramacionDetalle->setDia27($strTurno);
                } else {
                    $arProgramacionDetalle->setDia27(null);
                }
                if($arrControles['TxtDia28D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia28D'.$intCodigo]);
                    $arProgramacionDetalle->setDia28($strTurno);
                } else {
                    $arProgramacionDetalle->setDia28(null);
                }
                if($arrControles['TxtDia29D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia29D'.$intCodigo]);
                    $arProgramacionDetalle->setDia29($strTurno);
                } else {
                    $arProgramacionDetalle->setDia29(null);
                }
                if($arrControles['TxtDia30D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia30D'.$intCodigo]);
                    $arProgramacionDetalle->setDia30($strTurno);
                } else {
                    $arProgramacionDetalle->setDia30(null);
                }
                if($arrControles['TxtDia31D'.$intCodigo] != '') {
                    $strTurno = $this->validarTurno($arrControles['TxtDia31D'.$intCodigo]);
                    $arProgramacionDetalle->setDia31($strTurno);
                } else {
                    $arProgramacionDetalle->setDia31(null);
                }
                $em->persist($arProgramacionDetalle);                
            }
        }
        $em->flush();        
    }    
    
    private function validarTurno($strTurno) {
        $em = $this->getDoctrine()->getManager();
        $strTurnoDevolver = NUll;
        if($strTurno != "") {
            $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($strTurno);
            if($arTurno) {
                $strTurnoDevolver = $strTurno;
            }
        }

        return $strTurnoDevolver;
    }    
    
}
