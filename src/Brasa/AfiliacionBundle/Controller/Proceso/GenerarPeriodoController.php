<?php
namespace Brasa\AfiliacionBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class GenerarPeriodoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/proceso/generar/periodo", name="brs_afi_proceso_generar_periodo")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();                
        $form = $this->formulario();
        $form->handleRequest($request);        
        if ($form->isValid()) {           
            if ($form->get('BtnGenerar')->isClicked()) { 
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                $arClientes = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                $arClientes = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findAll();
                foreach ($arClientes as $arCliente) {
                    $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                    $arPeriodo->setClienteRel($arCliente);
                    $arPeriodo->setFechaDesde($fechaDesde);
                    $arPeriodo->setFechaHasta($fechaHasta);
                    $em->persist($arPeriodo);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_proceso_generar_periodo'));
            }
        }
                
        return $this->render('BrasaAfiliacionBundle:Proceso/GenerarPeriodo:lista.html.twig', array(        
            'form' => $form->createView()));
    }                    
    
    private function formulario() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()   
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                            
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                            
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->getForm();
        return $form;
    }           
}