<?php
namespace Brasa\InventarioBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\InventarioBundle\Form\Type\InvMovimientoType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use PHPExcel_Style_Border;


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
            /*if ($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcelInterfaz();
            }*/            
        }

        $arDocumentos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:ingreso.html.twig', array(
            'arDocumentos' => $arDocumentos,
            'form' => $form->createView()));
    } 
    
    /**
     * @Route("/inv/movimiento/movimiento/lista/{codigoDocumento}", name="brs_inv_movimiento_movimiento_lista")
     */    
    public function movimientoAction($codigoDocumento) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();        
        $paginator  = $this->get('knp_paginator');
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
        $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($codigoDocumento);
        $form = $this->formularioMovimiento($arDocumento);
        $form->handleRequest($request);        
        if ($form->isValid()) {                                                
                        
        }
        $arMovimientos = new \Brasa\InventarioBundle\Entity\InvMovimiento();
        $arMovimientos = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->findBy(array('codigoDocumentoFk' => $codigoDocumento));
        $arMovimientos = $paginator->paginate($arMovimientos, $this->get('request')->query->get('page', 1),40);
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:lista.html.twig', array(
            'arMovimientos' => $arMovimientos,
            'arDocumento' => $arDocumento,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/inv/movimiento/movimiento/nuevo/{codigoDocumento}/{codigoMovimiento}", name="brs_inv_movimiento_movimiento_nuevo")
     */    
    public function nuevoAction($codigoDocumento, $codigoMovimiento = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arDocumento = new \Brasa\InventarioBundle\Entity\InvDocumento();
        $arDocumento = $em->getRepository('BrasaInventarioBundle:InvDocumento')->find($codigoDocumento);
        $arMovimiento = new \Brasa\InventarioBundle\Entity\InvMovimiento();        
        if($codigoMovimiento != 0) {
            $arMovimiento = $em->getRepository('BrasaInventarioBundle:InvMovimiento')->find($codigoMovimiento);
        } else {
            $arMovimiento->setFecha(new \DateTime('now'));                        
        }
        $form = $this->createForm(new InvMovimientoType, $arMovimiento);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arMovimiento->setDocumentoRel($arDocumento);
            $em->persist($arMovimiento);
            $em->flush();

            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_inv_movimiento_movimiento_nuevo', array('codigoDocumento' => 0, 'codigoMovimiento' => 0 )));
            } else {                
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        return $this->render('BrasaInventarioBundle:Movimiento/Movimiento:nuevo.html.twig', array(
            'arMovimiento' => $arMovimiento,
            'form' => $form->createView()));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaInventarioBundle:InvDocumento')->listaDql();
    }         

    private function filtrar ($form) { 
        
    }    
    
    private function formularioMovimiento() {                        
        $form = $this->createFormBuilder()
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))    
            ->getForm();
        return $form;
    }
    
    private function formularioFiltro() {                        
        $form = $this->createFormBuilder()
                
            ->getForm();
        return $form;
    }

}