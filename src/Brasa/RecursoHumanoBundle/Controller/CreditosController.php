<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CreditosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $form = $this->createFormBuilder()
            ->add('Generar', 'submit')
            ->getForm();
        $form->handleRequest($request);        
        
        $arCredito = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCredito = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findAll();                
       
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $objChkFecha = NULL;
            if (isset($arrControles['ChkFecha']))
                $objChkFecha = $arrControles['ChkFecha'];
            switch ($request->request->get('OpSubmit')) {
                case "OpEliminar";
                    foreach ($arrSeleccionados AS $codigoGuia) {
                        $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
                        $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
                        if($arGuia->getEstadoImpreso() == 0 && $arGuia->getEstadoDespachada() == 0 && $arGuia->getNumeroGuia() == 0) {
                            $em->remove($arGuia);
                            $em->flush();                            
                        }
                    }
                    break;

            }
        } 

        return $this->render('BrasaRecursoHumanoBundle:Creditos:lista.html.twig', array(
            'arCredito' => $arCredito,
            'form' => $form->createView()));
    }       
        
}
