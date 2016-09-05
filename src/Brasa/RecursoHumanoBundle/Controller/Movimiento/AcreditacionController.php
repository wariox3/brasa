<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAcreditacionType;

class AcreditacionController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/movimiento/acreditacion/", name="brs_rhu_movimiento_acreditacion")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 12, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }            

            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoAcreditacion) {
                        $arAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
                        $arAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($codigoAcreditacion);
                        $em->remove($arAcreditacion);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion'));
                }
            }
            
        }
        $arAcreditaciones = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:lista.html.twig', array(
            'arAcreditaciones' => $arAcreditaciones,
            'form' => $form->createView()
            ));
    }    

    /**
     * @Route("/rhu/movimiento/acreditacion/nuevo/{codigoAcreditacion}", name="brs_rhu_movimiento_acreditacion_nuevo")
     */    
    public function nuevoAction($codigoAcreditacion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arAcreditacion = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();       
        if($codigoAcreditacion != 0) {
            $arAcreditacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->find($codigoAcreditacion);
        } else {
            $arAcreditacion->setEstadoActivo(true);
            $arAcreditacion->setFecha(new \DateTime('now'));
        }        

        $form = $this->createForm(new RhuAcreditacionType(), $arAcreditacion);                     
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arAcreditacion = $form->getData();                          
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));                
                if(count($arEmpleado) > 0) {                                            
                    $arAcreditacion->setEmpleadoRel($arEmpleado);                    
                    if($codigoAcreditacion == 0) {
                        $arAcreditacion->setCodigoUsuario($arUsuario->getUserName());                                           
                    }
                    $em->persist($arAcreditacion);
                    $em->flush();

                    if($form->get('guardarnuevo')->isClicked()) {                                                        
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion_nuevo', array('codigoAcreditacion' => 0)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_acreditacion'));
                    }                                                                                                                             
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);                                    
                }
            }            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Acreditacion:nuevo.html.twig', array(
            'arAcreditacion' => $arAcreditacion,
            'form' => $form->createView()));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                                    
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => $session->get('filtroAcreditacionNumero')))                                                                                
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuAcreditacion')->listaDQL(                   
                );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        $session->set('filtroAcreditacionNumero', $form->get('TxtNumero')->getData());
    }         
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'NUMERO')                    
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'NOMBRE')                    
                    ->setCellValue('F1', 'FECHA');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);        
        $arAcreditaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuAcreditacion();
        $arAcreditaciones = $query->getResult();
        foreach ($arAcreditaciones as $arAcreditacion) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAcreditacion->getCodigoAcreditacionPk())
                    ->setCellValue('B' . $i, $arAcreditacion->getAcreditacionTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arAcreditacion->getNumero())                                        
                    ->setCellValue('D' . $i, $arAcreditacion->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('E' . $i, $arAcreditacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arAcreditacion->getFecha()->format('Y-m-d'));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Acreditacions');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Acreditacions.xlsx"');
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
