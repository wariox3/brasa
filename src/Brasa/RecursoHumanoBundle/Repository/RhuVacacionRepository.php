<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RhuVacacionRepository extends EntityRepository {        
    
    public function listaVacacionesDQL($strCodigoCentroCosto = "", $strIdentificacion = "") {        
        $em = $this->getEntityManager();
        $dql   = "SELECT v, e FROM BrasaRecursoHumanoBundle:RhuVacacion v JOIN v.empleadoRel e WHERE v.codigoVacacionPk <> 0";
        
        if($strCodigoCentroCosto != "") {
            $dql .= " AND v.codigoCentroCostoFk = " . $strCodigoCentroCosto;
        }   
        if($strIdentificacion != "" ) {
            $dql .= " AND e.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        return $dql;
    }

    public function liquidar($codigoVacacion) {        
        $em = $this->getEntityManager();
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);
        $arVacacion = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->find($codigoVacacion);                 
        $intDias = 15;
        $floSalario = $arVacacion->getEmpleadoRel()->getVrSalario();        
        //Analizar cambios de salario
        $fecha = $arVacacion->getFechaHastaPeriodo()->format('Y-m-d');
        $nuevafecha = strtotime ( '-90 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
        $fechaDesdeCambioSalario = date_create_from_format('Y-m-d H:i', $nuevafecha . " 00:00");        
        $floSalarioPromedio = 0;        
        $arCambiosSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
        $arCambiosSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->cambiosSalario($arVacacion->getContratoRel()->getCodigoContratoPk(), $fechaDesdeCambioSalario->format('Y-m-d'), $arVacacion->getFechaHastaPeriodo()->format('Y-m-d'));                 
        if(count($arCambiosSalario) > 0) {
            $floPrimerSalario = $arCambiosSalario[0]->getVrSalarioAnterior();
            $intNumeroRegistros = count($arCambiosSalario) + 1;
            $floSumaSalarios = 0;
            foreach ($arCambiosSalario as $arCambioSalario) {
                $floSumaSalarios += $arCambioSalario->getVrSalarioNuevo();
            }
            $floSalarioPromedio = round((($floSumaSalarios + $floPrimerSalario) / $intNumeroRegistros));
            
        } else {
            $floSalarioPromedio = $floSalario;
        }        
        $floTotalVacacionBruto = $floSalarioPromedio / 30 * $intDias;        
        $arVacacion->setDiasVacaciones($intDias);
        $douSalud = ($floSalario * 2) / 100;
        $arVacacion->setVrSalud($douSalud);
        if ($floTotalVacacionBruto >= ($arConfiguracion->getVrSalario() * 4)){
            $douPorcentaje = $arConfiguracion->getPorcentajePensionExtra();
            $douPension = ($floSalario * $douPorcentaje) /100;
        } else {
            $douPension = ($floSalario * 2) / 100;
        }
        $arVacacion->setVrPension($douPension);                                   
        $floDeducciones = 0;
        $arVacacionDeducciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacionCredito();
        $arVacacionDeducciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacionCredito')->FindBy(array('codigoVacacionFk' => $codigoVacacion));        
        foreach ($arVacacionDeducciones as $arVacacionDeduccion) {
            $floDeducciones += $arVacacionDeduccion->getVrDeduccion();
        }
        $arVacacion->setVrDeduccion($floDeducciones);
        $arVacacion->setVrVacacionBruto($floTotalVacacionBruto);
        $floTotalVacacion = $floTotalVacacionBruto - $floDeducciones - $arVacacion->getVrPension() - $arVacacion->getVrSalud();        
        $arVacacion->setVrVacacion($floTotalVacacion);        
        $arVacacion->setDiasVacaciones(15);
        $arVacacion->setVrSalarioActual($floSalario);
        $arVacacion->setVrSalarioPromedio($floSalarioPromedio);
        $em->flush();
        
        return true;
    }     
    
}

