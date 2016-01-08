<?php
namespace Brasa\TurnoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class UtilidadAplicarNovedadController extends Controller
{
    var $strListaIncapacidadesDql = "";
    var $codigoPedido = "";
    var $fechDesde = "";
    var $fechHasta = "";
    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request); 
        $this->listaIncapacidades();
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listaIncapacidades();
            }
            if ($form->get('BtnAplicar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarIncapacidad');
                if($arrSeleccionados) {
                    foreach ($arrSeleccionados as $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findOneBy(array('codigoEmpleadoFk' => $arIncapacidad->getCodigoEmpleadoFk()));
                        if($arRecurso) {
                            $strDql   = "SELECT pd FROM BrasaTurnoBundle:TurProgramacionDetalle pd JOIN pd.programacionRel p "
                                      . "WHERE (p.fecha >= '" . $this->fechDesde . "' AND p.fecha <= '" . $this->fechHasta . "') "
                                      . "AND pd.codigoRecursoFk = " . $arRecurso->getCodigoRecursoPk();                        
                            $objQuery = $em->createQuery($strDql);  
                            $arProgramacionesDetalles = $objQuery->getResult();                            
                        }                                                
                    }
                }
                return $this->redirect($this->generateUrl('brs_tur_utilidad_aplicar_novedad'));
            }            
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }        
        
        $arIncapacidades = $paginator->paginate($em->createQuery($this->strListaIncapacidadesDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Utilidades:aplicarNovedad.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView()));
    }        
    
    private function listaIncapacidades() {
        $em = $this->getDoctrine()->getManager();        
        if(!$this->fechDesde) {
            $this->fechDesde = date('Y/m/d');
        }
        if(!$this->fechHasta) {
            $this->fechHasta = date('Y/m/d');
        }        
        $this->strListaIncapacidadesDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->pendientesAplicarTurnoDql($this->fechDesde, $this->fechHasta);        
    }

    private function filtrar ($form) {     
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if($dateFechaDesde) {
            $this->fechDesde = $dateFechaDesde->format('Y/m/d');                
        }
        if($dateFechaHasta) {
            $this->fechHasta = $dateFechaHasta->format('Y/m/d');                
        }        
        
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()            
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnAplicar', 'submit', array('label'  => 'Aplicar',))
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIG0')
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arPedidos = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arPedidos = $query->getResult();

        foreach ($arPedidos as $arPedido) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedido->getCodigoPedidoPk())
                    ->setCellValue('B' . $i, $arPedido->getTerceroRel()->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pedidos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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