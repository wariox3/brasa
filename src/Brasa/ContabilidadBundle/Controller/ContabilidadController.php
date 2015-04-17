<?php

namespace Brasa\ContabilidadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContabilidadController extends Controller {



    public function resumenAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->DevMovimientosFacturacionResumidoAnnioMes();
        return $this->render('BrasaFrontEndBundle:Contabilidad/Consultas:resumen.html.twig', array('arMovimientos' => $arMovimientos));
    }

    public function balancePruebaAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $arMovimientosContables = new \Brasa\FrontEndBundle\Entity\MovimientosContables();

        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $strCuenta = $arrControles['TxtCodigoCuenta'];
            $intNroMovimiento = $arrControles['TxtNumeroMovimiento'];
            $arMovimientosContables = $em->getRepository('BrasaFrontEndBundle:MovimientosContables')->DevMovimientosContablesFiltro($intNroMovimiento, $strCuenta);
        } else {
            $arMovimientosContables = $em->getRepository('BrasaFrontEndBundle:MovimientosContables')->DevMovimientosContablesFiltro();
        }
        return $this->render('BrasaFrontEndBundle:Contabilidad/Informes:balancePrueba.html.twig', array('arMovimientosContables' => $arMovimientosContables));
    }

    public function certificadosRetencionesAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $arControlRetencionesFuente = new \Brasa\FrontEndBundle\Entity\ControlRetencionesFuente();
        $arControlRetencionesFuente = $em->getRepository('BrasaFrontEndBundle:ControlRetencionesFuente')->findBy(array('estadoRecibida' => 0), array('estadoRecibida' => 'ASC'));
        if ($request->getMethod() == 'POST') {
            
        }

        return $this->render('BrasaFrontEndBundle:Contabilidad/Control:certificadosRetenciones.html.twig', array('arControlRetencionesFuente' => $arControlRetencionesFuente));
    }

    public function listadoCuentasAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $arCuentas = new \Brasa\ContabilidadBundle\Entity\CtbCuentaContable();
        $arCuentas = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->findAll();

        $html = " <table  class='table table-striped table-bordered table-condensed'> ";

        $html .= "<div class='accordion' id='accordion2'>
                      <div class='accordion-group'>";
        $i = 0;
        $j = 0;
        foreach ($arCuentas as $Cuentas) {
            $strNombre = $Cuentas->getCodigoCuentaPk() . " - " . $Cuentas->getNombreCuenta();
            
            // Grupo
            if (strlen($Cuentas->getCodigoCuentaPk()) == 1) {

                $i++;

                if ($j != 0)
                    $html.= "</div></div>";

                $html.= "<div class='accordion-heading'>
                            <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapseOne$i'>
                               $strNombre
                            </a>
                        </div>";

                $Cuenta = $Cuentas->getCodigoCuentaPk();
                $j = 0;
                
            /// Sub Grupos    
            } elseif (strlen($Cuentas->getCodigoCuentaPk()) == 2) {
                if (strlen($Cuenta) == 1) {
                    $html.= "<div id='collapseOne$i' class='accordion-body collapse'>
                                <div class='accordion-inner'>";
                }
                
                if (($j % 2) == 1)
                    $html.= " <p><a id = 'consultakardex' href = '#'>$strNombre</a></p>";
                else
                    $html.= " <p style='background: #f9f9f9'><a href = ''>$strNombre</a></p>";

                $j++;

                $Cuenta = $Cuentas->getCodigoCuentaPk();
            }
        }
        $html .= "  </div>
                 </div>";

        $html .= "</table>";

        return $this->render('BrasaFrontEndBundle:Contabilidad/Cuentas:listadoCuentas.html.twig', array('Cuentas' => $html));
    }
    
    
    /**
     * Busca un tercero por nombre o nit segun sea escrito en un textbox
     * @return boolean|\Symfony\Component\HttpFoundation\Response 
     */
    public function buscarCuentasAction() {
        //try {
            $em = $this->getDoctrine()->getEntityManager();

            $strCuenta = $_GET["q"];

            $arCuentas = new \Brasa\ContabilidadBundle\Entity\CtbCuentaContable();

            if ($this->getRequest()->isXmlHttpRequest())
                $arCuentas = $em->getRepository('BrasaContabilidadBundle:CtbCuentaContable')->BuscarDescripcionCuenta($strCuenta);
            
            if(count($arCuentas) > 0) {
                foreach ($arCuentas as $key => $value) 
                    $array[] = $value->getCodigoCuentaPk()."-".trim($value->getNombreCuenta());                
            }
            else                        
                $array[] = "";
            
            /*if(isset($array) == false)
                return false;            
            
            if (!is_array($array)) 
                return false;            
            */
            
            $construct = "";
            
            foreach ($array as $value) {

                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = $value;
                }

                $construct .= $value . "\n";
            }

            $construct = str_replace('Array', '', $construct);

            return new \Symfony\Component\HttpFoundation\Response((string) $construct);

        //} catch (Exception $e) {            
        //}
    }

    /**
     * Busca un banco por nombre o codigo segun sea escrito en un textbox
     * @return boolean|\Symfony\Component\HttpFoundation\Response 
     */
    public function buscarBancosAction() {
            $em = $this->getDoctrine()->getEntityManager();
            $strBanco = $_GET["q"];
            $arBancos = new \Brasa\ContabilidadBundle\Entity\CtbBanco();
            if ($this->getRequest()->isXmlHttpRequest())
                $arBancos = $em->getRepository('BrasaContabilidadBundle:CtbBanco')->BuscarDescripcionBanco($strBanco);
            
            if(count($arBancos) > 0) {
                foreach ($arBancos as $key => $value) 
                    $array[] = $value->getCodigoBancoPk()."-".trim($value->getNombre());                
            }
            else                        
                $array[] = "";
            
            $construct = "";
            
            foreach ($array as $value) {

                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = $value;
                }

                $construct .= $value . "\n";
            }

            $construct = str_replace('Array', '', $construct);

            return new \Symfony\Component\HttpFoundation\Response((string) $construct);
    }    
    
    /**
     * Busca un impuesto por nombre o codigo sea escrito en un textbox
     * @return boolean|\Symfony\Component\HttpFoundation\Response 
     */
    public function buscarImpuestosAction() {
        //try {
            $em = $this->getDoctrine()->getEntityManager();

            $strImpuesto = $_GET["q"];

            $arImpuestos = new \Brasa\ContabilidadBundle\Entity\CtbImpuesto();

            if ($this->getRequest()->isXmlHttpRequest())
                $arImpuestos = $em->getRepository('BrasaContabilidadBundle:CtbImpuesto')->BuscarDescripcionImpuesto($strImpuesto);
            
            if(count($arImpuestos) > 0) {
                foreach ($arImpuestos as $key => $value) 
                    $array[] = $value->getCodigoImpuestoPk()."-".trim($value->getNombreImpuesto());                
            }
            else                        
                $array[] = "";           
            
            $construct = "";
            
            foreach ($array as $value) {

                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = $value;
                }

                $construct .= $value . "\n";
            }

            $construct = str_replace('Array', '', $construct);

            return new \Symfony\Component\HttpFoundation\Response((string) $construct);

        //} catch (Exception $e) {            
        //}
    }    
    
}
