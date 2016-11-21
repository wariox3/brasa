<?php
namespace Brasa\InventarioBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class MovimientoController extends Controller
{
    var $strListaDql = "";    

    /**
     * @Route("/inv/movimiento/movimiento/ingreso", name="brs_inv_movimiento_movimiento_ingreso")
     */    
    public function ingresoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {                        
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaInventarioBundle:InvDocumento')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_ingreso'));  
                
                
            }
            /*if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }*/
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
            /*if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcelInterfaz();
            }*/            
        }

        $arDocumentos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:ingreso.html.twig', array(
            'arDocumentos' => $arDocumentos,
            'form' => $form->createView()));
    } 
        
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaInventarioBundle:InvDocumento')->listaDql();
    }      

    private function filtrar ($form) { 
        
    }    
    
    private function formularioFiltro() {                        
        $form = $this->createFormBuilder()
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))    
            ->getForm();
        return $form;
    }

}