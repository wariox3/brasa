<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ValidacionController extends Controller
{
    public function turnoAction($codigoProgramacionDetalle, $dia, $codigoTurno) {   
        $em = $this->getDoctrine()->getManager();
        $strRespuesta = 0;
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle = $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->find($codigoProgramacionDetalle);                
        $strAnio = $arProgramacionDetalle->getAnio();
        $strMes = $arProgramacionDetalle->getMes();
        $codigoRecurso = $arProgramacionDetalle->getCodigoRecursoFk();
        if($codigoRecurso) {
            $strSql = "SELECT dia_$dia
                        FROM tur_programacion_detalle                    
                        WHERE
                        dia_$dia = '$codigoTurno' AND anio = $strAnio AND mes = $strMes AND codigo_recurso_fk = $codigoRecurso"; 
            $connection = $em->getConnection();
            $statement = $connection->prepare($strSql);        
            $statement->execute();
            $results = $statement->fetchAll();
            if(count($results) > 0) {
                $strRespuesta = 1;
            }            
        }         
        return new \Symfony\Component\HttpFoundation\Response((string) $strRespuesta);
    } 


}