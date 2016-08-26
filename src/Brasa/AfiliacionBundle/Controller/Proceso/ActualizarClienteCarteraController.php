<?php
namespace Brasa\AfiliacionBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
//use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class ActualizarClienteCarteraController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/proceso/actualizar/clientecartera", name="brs_afi_proceso_actualizar_clientecartera")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if($form->get('BtnActualizar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoClientePk) {
                        $arClienteAfiliacion = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                        $arClienteAfiliacion = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigoClientePk);
                        $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                        $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arClienteAfiliacion->getNit()));
                        if ($arClienteCartera == null){
                            $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                            $arClienteCartera->setFormaPagoRel($arClienteAfiliacion->getFormaPagoRel());
                            $arClienteCartera->setAsesorRel($arClienteAfiliacion->getAsesorRel());
                            $arClienteCartera->setCiudadRel($arClienteAfiliacion->getCiudadRel());
                            $arClienteCartera->setNit($arClienteAfiliacion->getNit());
                            $arClienteCartera->setDigitoVerificacion($arClienteAfiliacion->getDigitoVerificacion());
                            $arClienteCartera->setNombreCorto($arClienteAfiliacion->getNombreCorto());
                            $arClienteCartera->setPlazoPago($arClienteAfiliacion->getPlazoPago());
                            $arClienteCartera->setDireccion($arClienteAfiliacion->getDireccion());
                            $arClienteCartera->setTelefono($arClienteAfiliacion->getTelefono());
                            $arClienteCartera->setCelular($arClienteAfiliacion->getCelular());
                            $arClienteCartera->setFax($arClienteAfiliacion->getFax());
                            $arClienteCartera->setEmail($arClienteAfiliacion->getEmail());
                            $em->persist($arClienteCartera);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_afi_proceso_actualizar_clientecartera'));
                }
            }
        }
        if ($form->get('BtnFiltrar')->isClicked()) {
            $this->filtrar($form);
        }
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 150);
        return $this->render('BrasaAfiliacionBundle:Proceso:lista.html.twig', array(
            'arClientes' => $arClientes,
            'form' => $form->createView()));
    }

    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->listaDQL(
                $session->get('filtroClienteNombre'),
                '',
                $session->get('filtroIndependiente')
                );
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $session->set('filtroClienteNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIndependiente', $form->get('independiente')->getData());
        $this->lista();
    }

    private function formularioFiltro() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroClienteNombre')))
            ->add('independiente', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroIndependiente')))
            ->add('BtnActualizar', 'submit', array('label'  => 'Actualizar',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

}