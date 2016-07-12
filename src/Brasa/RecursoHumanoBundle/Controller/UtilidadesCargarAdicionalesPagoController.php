<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilidadesCargarAdicionalesPagoController extends Controller
{
    public function cargarAction($codigoProgramacionPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $rutaTemporal = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $rutaTemporal = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
        $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigoProgramacionPago);                                                                        
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "archivo.xls");                
                $ruta = $rutaTemporal->getRutaTemporal(). "archivo.xls";                
                $arrCarga = array();
                $objPHPExcel = \PHPExcel_IOFactory::load($ruta);                
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle     = $worksheet->getTitle();
                    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;
                    for ($row = 2; $row <= $highestRow; ++ $row) {                        
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $concepto = $cell->getValue();
                        $cell = $worksheet->getCellByColumnAndRow(1, $row);
                        $identificacion = $cell->getValue();                       
                        $cell = $worksheet->getCellByColumnAndRow(2, $row);
                        $tipo = $cell->getValue();                                                
                        $cell = $worksheet->getCellByColumnAndRow(3, $row);
                        $valor = $cell->getValue();  
                        $cell = $worksheet->getCellByColumnAndRow(4, $row);
                        $detalle = $cell->getValue();                          
                        $arrCarga[] = array(
                            'concepto' => $concepto,
                            'identificacion' => $identificacion,
                            'tipo' => $tipo,
                            'valor' => $valor,
                            'detalle' => $detalle);
                    }
                }
                foreach ($arrCarga as $carga) {
                    //$arPagoConcepto = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto();
                    $arPagoConcepto = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoConcepto')->find($carga['concepto']);
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $carga['identificacion']));
                    if($arPagoConcepto && $arEmpleado) {
                        $arPagoAdicional = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
                        $arPagoAdicional->setProgramacionPagoRel($arProgramacionPago);
                        $arPagoAdicional->setPagoConceptoRel($arPagoConcepto);
                        $arPagoAdicional->setEmpleadoRel($arEmpleado);
                        $arPagoAdicional->setPermanente(0);
                        $arPagoAdicional->setValor($carga['valor']);
                        $arPagoAdicional->setTipoAdicional($carga['tipo']);
                        $arPagoAdicional->setDetalle($carga['detalle']);
                        $em->persist($arPagoAdicional);                        
                    }                    
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
            }                                   
        }         
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ProgramacionesPago:cargarAdicionalesPago.html.twig', array(
            'form' => $form->createView()
            ));
    }
    
}
