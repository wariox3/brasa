<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiCursoRepository extends EntityRepository {  
    
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiCurso c WHERE c.codigoCursoPk <> 0";
        $dql .= " ORDER BY c.codigoCursoPk DESC";
        return $dql;
    }            
    
    public function pendienteDql($codigoCliente) {        
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiCurso c WHERE c.estadoFacturado = 0 AND c.estadoAnulado = 0 AND c.codigoClienteFk = " . $codigoCliente;
        $dql .= " ORDER BY c.codigoCursoPk DESC";
        return $dql;
    }                    
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }        
    
    public function liquidar($codigoCurso) {        
        $em = $this->getEntityManager();        
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();        
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);                 
        $floSubTotal = 0;        
        $arCursosDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();        
        $arCursosDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->findBy(array('codigoCursoFk' => $codigoCurso));                 
        foreach ($arCursosDetalle as $arCursoDetalle) {
            $floSubTotal +=  $arCursoDetalle->getPrecio();
        }                           
        $arCurso->setTotal($floSubTotal);
        $em->persist($arCurso);
        $em->flush();
        return true;
    }
    
    public function autorizar($codigoCurso) {
        $em = $this->getEntityManager();                
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);            
        $strResultado = "";        
        if($arCurso->getEstadoAutorizado() == 0) {
            if($em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->numeroRegistros($codigoCurso) > 0) {                            
                if($strResultado == "") {
                    $arCurso->setEstadoAutorizado(1);
                    $em->persist($arCurso);
                    $em->flush();                              
                }
              
            } else {
                $strResultado = "Debe adicionar detalles";
            }            
        } else {
            $strResultado = "Ya esta autorizado";
        }        
        return $strResultado;
    } 
    
    public function desAutorizar($codigoCurso) {
        $em = $this->getEntityManager();                
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);            
        $strResultado = "";        
        if($arCurso->getEstadoAutorizado() == 1 && $arCurso->getEstadoAnulado() == 0 && $arCurso->getNumero() == 0) {                                            
            $arCurso->setEstadoAutorizado(0);
            $em->persist($arCurso);
            $em->flush();                                                        
        } else {
            $strResultado = "El curso debe estas autorizado y no puede estar anulada o impresa";
        }        
        return $strResultado;
    }    
    
    public function imprimir($codigoCurso) {
        $em = $this->getEntityManager();        
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();        
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);        
        if($arCurso->getEstadoAutorizado() == 1) {
            if($arCurso->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(1);
                $arCurso->setNumero($intNumero);
                /*$arServicio = new \Brasa\AfiliacionBundle\Entity\AfiServicio();
                $arServicio->setClienteRel($arCurso->getClienteRel());
                $arServicio->setCurso($arCurso->getTotal());
                $arServicio->setTotal($arCurso->getTotal());
                $arServicio->setPendiente($arCurso->getTotal());
                $em->persist($arServicio);                
                 * 
                 */
            }   
            $em->persist($arCurso);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar el curso para imprimir";
        }
        return $strResultado;
    }    
}