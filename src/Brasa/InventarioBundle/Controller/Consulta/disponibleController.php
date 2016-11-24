<?php
namespace Brasa\InventarioBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class disponibleController extends Controller
{
    var $strListaDql = "";    
    
    /**
     * @Route("/inv/consulta/disponible", name="brs_inv_consultas_disponible")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 48)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        //$this->filtrarFecha = TRUE;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();                
        if ($form->isValid()) {                             
            if ($form->get('BtnFiltrar')->isClicked()) { 
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }

        $arDisponible = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaInventarioBundle:Consultas:disponible.html.twig', array(
            'arDisponibles' => $arDisponible,                        
            'form' => $form->createView()));
    }        
    
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();        
        $this->strListaDql =  $em->getRepository('BrasaInventarioBundle:InvLote')->consultaDisponibleDql(
                $session->get('filtroCodigoItem'));                    
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();        
        $session->set('filtroCodigoItem', $form->get('TxtCodigoItem')->getData());
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();      
        $strNombreItem = "";
        if($session->get('filtroCodigoItem')) {
            $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->find($session->get('filtroCodigoItem'));
            if($arItem) {                
                $strNombreItem = $arItem->getNombreCorto();
            }  else {
                $session->set('filtroCodigoItem', null);
            }          
        }

        $form = $this->createFormBuilder()
            ->add('TxtCodigoItem', 'text', array('label'  => 'Item','data' => $session->get('filtroCodigoItem')))
            ->add('TxtNombreItem', 'text', array('label'  => 'NombreItem','data' => $strNombreItem))                                                                    
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }                 
    
    private function generarExcel() {
        ob_clean();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }        
        for($col = 'F'; $col !== 'AZ'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('right');                
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'AÑO')
                    ->setCellValue('B1', 'MES')
                    ->setCellValue('C1', 'CODIGO')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'C.COSTO')
                    ->setCellValue('G1', 'C.NOMINA')
                    ->setCellValue('H1', 'C.PRESTACIONES')
                    ->setCellValue('I1', 'C.APORTES')
                    ->setCellValue('J1', 'TOTAL');
        
        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arCostoRecursos = new \Brasa\TurnoBundle\Entity\TurCostoRecurso();
        $arCostoRecursos = $query->getResult();
        foreach ($arCostoRecursos as $arCostoRecurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCostoRecurso->getAnio())
                    ->setCellValue('B' . $i, $arCostoRecurso->getMes())
                    ->setCellValue('C' . $i, $arCostoRecurso->getCodigoRecursoFk())
                    ->setCellValue('D' . $i, $arCostoRecurso->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arCostoRecurso->getRecursoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arCostoRecurso->getRecursoRel()->getEmpleadoRel()->getCodigoCentroCostoContabilidadFk())
                    ->setCellValue('G' . $i, $arCostoRecurso->getVrNomina())
                    ->setCellValue('H' . $i, $arCostoRecurso->getVrPrestaciones())
                    ->setCellValue('I' . $i, $arCostoRecurso->getVrAportesSociales())
                    ->setCellValue('J' . $i, $arCostoRecurso->getVrCostoTotal());                         
            $i++;
        }                
        //$objPHPExcel->getActiveSheet()->getStyle('A1:AL1')->getFont()->setBold(true);        
        
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        
        $objPHPExcel->getActiveSheet()->setTitle('CostoRecurso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CostoRecurso.xlsx"');
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