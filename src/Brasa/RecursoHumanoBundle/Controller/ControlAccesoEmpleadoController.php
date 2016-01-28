<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuControlAccesoEmpleadoType;
use Doctrine\ORM\EntityRepository;


class ControlAccesoEmpleadoController extends Controller
{
    var $strSqlLista = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoControlAccesoEmpleado) {
                        $arControlAccesoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                        $arControlAccesoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoControlAccesoEmpleado);
                        $em->remove($arControlAccesoEmpleado);
                        $em->flush();
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
                }
                $this->filtrarLista($form);
                $this->listar();
            }
            

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arControlAccesoEmpleados = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ControlAcceso:empleado.html.twig', array(
            'arControlAccesoEmpleados' => $arControlAccesoEmpleados,
            'form' => $form->createView()
            ));
    }
    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->listaDql(                    
            $session->get('filtroIdentificacion'),    
            $session->get('filtroNombre'),
            $session->get('filtroDesde'),
            $session->get('filtroHasta')
            );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))    
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $session->set('filtroNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

   public function nuevoAction($codigoControlAcceso) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arControlAccesoEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        if ($codigoControlAcceso != 0)
        {
            $arControlAccesoEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->find($codigoControlAcceso);
        }    
        $form = $this->createFormBuilder()
            ->add('identificacion', 'text', array('data' => $arControlAccesoEmpleado->getEmpleadoRel()->getNumeroIdentificacion()))
            ->add('nombre', 'text', array('data' => $arControlAccesoEmpleado->getEmpleadoRel()->getNombreCorto()))    
            ->add('fechaEntrada', 'datetime', array('required' => true, 'data' => $arControlAccesoEmpleado->getFechaEntrada()))
            ->add('fechaSalida', 'datetime', array('required' => true, 'data' => $arControlAccesoEmpleado->getFechaSalida()))
            ->add('duracionVisita', 'text', array('data' => $arControlAccesoEmpleado->getDuracionRegistro(),'required' => false))
            ->add('comentarios', 'textarea', array('data' => $arControlAccesoEmpleado->getComentarios(),'required' => false))    
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $arControlAccesoEmpleado->setFechaEntrada($form->get('fechaEntrada')->getData());
            $arControlAccesoEmpleado->setFechaSalida($form->get('fechaSalida')->getData());
            $dateEntrada = $arControlAccesoEmpleado->getFechaEntrada();
            $dateSalida = $arControlAccesoEmpleado->getFechaSalida();
            $horaEntrada = $dateEntrada->format('H');
            $horaSalida = $dateSalida->format('H');
            if ($horaSalida < $horaEntrada){
               $objMensaje->Mensaje("error", "La hora de salida no puede ser menor a la hora de entrada", $this);
            } else {
               $dateEntrada = $arControlAccesoEmpleado->getFechaEntrada();
               $dateSalida = $arControlAccesoEmpleado->getFechaSalida();
               $dateDiferencia = date_diff($dateSalida, $dateEntrada);
               $horas = $dateDiferencia->format('%H');
               $minutos = $dateDiferencia->format('%i');
               $segundos = $dateDiferencia->format('%s');
               $diferencia = $horas.":".$minutos.":".$segundos;
               $arControlAccesoEmpleado->setDuracionRegistro($diferencia);
               $arControlAccesoEmpleado->setComentarios($form->get('comentarios')->getData());
               $em->persist($arControlAccesoEmpleado);  
               $em->flush();
               return $this->redirect($this->generateUrl('brs_rhu_control_acceso_empleado_lista'));
           }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/ControlAcceso:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
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
                    ->setCellValue('A1', 'NRO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'DEPARTAMENTO EMPRESA')                    
                    ->setCellValue('E1', 'FECHA')
                    ->setCellValue('F1', 'HORA ENTRADA')
                    ->setCellValue('G1', 'HORA SALIDA')
                    ->setCellValue('H1', 'DURACIÓN REGISTRO')
                    ->setCellValue('I1', 'COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        $arHorarioAcceso = $query->getResult();
        $j = 1;
        foreach ($arHorarioAcceso as $arHorarioAcceso) {
            if ($arHorarioAcceso->getFechaSalida() == null){
                    $dateFechaSalida = "";
                }else {
                    $dateFechaSalida = $arHorarioAcceso->getFechaSalida()->Format('H:i:s');
                }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $j)    
                ->setCellValue('B' . $i, $arHorarioAcceso->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('C' . $i, $arHorarioAcceso->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('D' . $i, $arHorarioAcceso->getEmpleadoRel()->getDepartamentoEmpresaRel()->getNombre())                    
                ->setCellValue('E' . $i, $arHorarioAcceso->getFechaEntrada()->Format('Y-m-d'))
                ->setCellValue('F' . $i, $arHorarioAcceso->getFechaEntrada()->Format('H:i:s'))
                ->setCellValue('G' . $i, $dateFechaSalida)
                ->setCellValue('H' . $i, $arHorarioAcceso->getDuracionRegistro())
                ->setCellValue('I' . $i, $arHorarioAcceso->getComentarios());
            $i++;
            $j++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ControlAccesoEmpleado');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ControlAccesoEmpleado.xlsx"');
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
