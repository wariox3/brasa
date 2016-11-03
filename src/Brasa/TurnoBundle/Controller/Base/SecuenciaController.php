<?php

namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurPlantillaType;

class SecuenciaController extends Controller {

    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";

    /**
     * @Route("/tur/base/secuencia/lista", name="brs_tur_base_secuencia_lista")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 82, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurPlantilla')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_lista'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }

        $arPlantillas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Plantilla:lista.html.twig', array(
                    'arPlantillas' => $arPlantillas,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/plantilla/nuevo/{codigoPlantilla}", name="brs_tur_base_plantilla_nuevo")
     */     
    public function nuevoAction($codigoPlantilla = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arPlantilla = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        if ($codigoPlantilla != 0) {
            $arPlantilla = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->find($codigoPlantilla);
        }
        $form = $this->createForm(new TurPlantillaType, $arPlantilla);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPlantilla = $form->getData();
            $arUsuario = $this->getUser();
            $arPlantilla->setUsuario($arUsuario->getUserName());
            $em->persist($arPlantilla);
            $em->flush();

            if ($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_nuevo', array('codigoPlantilla' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_plantilla_detalle', array('codigoPlantilla' => $arPlantilla->getCodigoPlantillaPk())));
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Plantilla:nuevo.html.twig', array(
                    'arPlantilla' => $arPlantilla,
                    'form' => $form->createView()));
    }
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurPlantilla')->listaDQL(
                $this->strNombre, $this->strCodigo
        );
    }

    private function filtrar($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
                ->add('TxtNombre', 'text', array('label' => 'Nombre', 'data' => $this->strNombre))
                ->add('TxtCodigo', 'text', array('label' => 'Codigo', 'data' => $this->strCodigo))
                ->add('BtnEliminar', 'submit', array('label' => 'Eliminar',))
                ->add('BtnExcel', 'submit', array('label' => 'Excel',))
                ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
                ->getForm();
        return $form;
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arPlantillas = new \Brasa\TurnoBundle\Entity\TurPlantilla();
        $arPlantillas = $query->getResult();

        foreach ($arPlantillas as $arPlantilla) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPlantilla->getCodigoPlantillaPk())
                    ->setCellValue('B' . $i, $arPlantilla->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Plantilla');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Plantillas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

}