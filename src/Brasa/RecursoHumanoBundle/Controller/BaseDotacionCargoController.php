<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionCargoType;
use Doctrine\ORM\EntityRepository;

/**
 * RhuDotacionCargo controller.
 *
 */
class BaseDotacionCargoController extends Controller
{
    var $strDqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista(); 
        $form->handleRequest($request);     
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDotacionCargo) {
                        $arDotacionCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionCargo')->find($codigoDotacionCargo);
                        $em->remove($arDotacionCargo);
                        $em->flush();
                    }
                }                
            }
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }            
        }                
        $arDotacionesCargos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionCargo:lista.html.twig', array(
                    'arDotacionesCargos' => $arDotacionesCargos,
                    'form'=> $form->createView()
           
        ));
    }
    
    public function nuevoAction($codigoDotacionCargo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDotacionCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo();
        if ($codigoDotacionCargo != 0) {
            $arDotacionCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionCargo')->find($codigoDotacionCargo);
        }    
        $form = $this->createForm(new RhuDotacionCargoType(), $arDotacionCargo);
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arDotacionCargo = $form->getData();
            $em->persist($arDotacionCargo);            
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_cargo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionCargo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function nuevoMultipleAction($codigoDotacionCargo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDotacionesElementos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
        $arDotacionesElementos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->findAll();
        $form = $this->createFormBuilder()
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'property' => 'nombre',
            ))    
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if ($form->get('BtnGuardar')->isClicked()) {
                if (isset($arrControles['TxtCantidad'])) {
                    $intIndice = 0;
                    foreach ($arrControles['LblCodigo'] as $intCodigo) {
                        if($arrControles['TxtCantidad'][$intIndice] > 0 ){
                            $arDotacionElemento = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento();
                            $arDotacionElemento = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElemento')->find($intCodigo);
                            $arDotacionCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo();
                            $arDotacionCargo->setDotacionElementoRel($arDotacionElemento);
                            $arDotacionCargo->setCargoRel($form->get('cargoRel')->getData());
                            $intCantidad = $arrControles['TxtCantidad'][$intIndice];
                            $arDotacionCargo->setCantidadAsignada($intCantidad);
                            $em->persist($arDotacionCargo);
                        }
                        $intIndice++;
                    }
                }
                $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_cargo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionCargo:nuevoMultiple.html.twig', array(
            'form' => $form->createView(),
            'arDotacionesElementos' => $arDotacionesElementos,
        ));
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCargo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCargo", $session->get('filtroCodigoCargo'));
        }
        $form = $this->createFormBuilder()
            ->add('cargoRel', 'entity', $arrayPropiedades)    
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                
            ->getForm();        
        return $form;
    }     
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionCargo')->listaDql(
        $session->get('filtroCodigoCargo'));         
    }    
    
    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCargo', $controles['cargoRel']);
    }
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CARGO')
                    ->setCellValue('C1', 'DOTACIÓN');

        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arDotacionesCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo();
        $arDotacionesCargos = $query->getResult();
        foreach ($arDotacionesCargos as $arDotacionCargo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDotacionCargo->getCodigoDotacionCargoPk())
                    ->setCellValue('B' . $i, $arDotacionCargo->getCargoRel()->getNombre())
                    ->setCellValue('C' . $i, $arDotacionCargo->getDotacionElementoRel()->getDotacion());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('DotacionesCargos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="DotacionesCargos.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }    
}
