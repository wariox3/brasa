<?php

namespace Brasa\RecursoHumanoBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

/**
 * RhuCertificadoIngreso controller.
 *
 */
class CertificadoIngresoController extends Controller
{
    public function CertificadoAction($codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $ConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $ConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $empleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $empleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $fechaActual = date('Y-m-j');
        $anioActual = date('Y');
        $fechaPrimeraAnterior = strtotime ( '-1 year' , strtotime ( $fechaActual ) ) ;
        $fechaPrimeraAnterior = date ( 'Y' , $fechaPrimeraAnterior );
        $fechaSegundaAnterior = strtotime ( '-2 year' , strtotime ( $fechaActual ) ) ;
        $fechaSegundaAnterior = date ( 'Y' , $fechaSegundaAnterior );
        $fechaTerceraAnterior = strtotime ( '-3 year' , strtotime ( $fechaActual ) ) ;
        $fechaTerceraAnterior = date ( 'Y' , $fechaTerceraAnterior );
        $formCertificado = $this->createFormBuilder()
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
        $formCertificado->handleRequest($request);
        if ($formCertificado->isValid()) {
            if($formCertificado->get('BtnGenerar')->isClicked()) {
                
                $controles = $request->request->get('form');
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
                $strRuta = "";
                if ( $floPrestacional > 0){
                    $objFormatoCertificadoIngreso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCertificadoIngreso();
                    $objFormatoCertificadoIngreso->Generar($this,$codigoEmpleado,$strFechaExpedicion,$strLugarExpedicion,$strFechaCertificado,$strAfc,$stCertifico1,$stCertifico2,$stCertifico3,$stCertifico4,$stCertifico5,$stCertifico6,$floPrestacional,$floPension,$floSalud,$datFechaInicio,$datFechaFin,$floCesantias,$douRetencion,$duoGestosRepresentacion,$douOtrosIngresos,$duoTotalIngresos,$floVacaciones,$strRuta);  
                } else {
                    $objMensaje->Mensaje("error", "Este empleado no registra información de ingresos  y retenciones para el año ". $strFechaCertificado."" , $this);                
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/CertificadoIngreso:certificado.html.twig', array(
            'ConfiguracionGeneral' => $ConfiguracionGeneral,
            'empleado' => $empleado,
            'formCertificado' => $formCertificado->createView(),
        ));
    }
    
}
