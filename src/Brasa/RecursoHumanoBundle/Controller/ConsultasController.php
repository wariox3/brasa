<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

class ConsultasController extends Controller
{
    var $strSqlCreditoLista = "";
    var $strSqlServiciosPorCobrarLista = "";
    var $strSqlProgramacionesPagoLista = "";
    var $strSqlIncapacidadesLista = "";
    var $strSqlIncapacidadesCobrarLista = "";
    var $strSqlAportesLista = "";
    var $strSqlVacacionesPagarLista = "";
    var $strSqlFechaTerminacionLista = "";
    var $strSqlCostosIbcLista = "";
    var $strSqlPagoPendientesBancoLista = "";
    var $strSqlEmpleadosLista = "";
    var $strSqlDotacionesPendientesLista = "";
    var $strSqlProcesosDisciplinariosLista = "";    

    public function PagoPendientesBancoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioPagoPendientesBancoLista();
        $form->handleRequest($request);
        $this->listarPagoPendientesBanco();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarPagoPendientesBancoLista($form);
                $this->listarPagoPendientesBanco();
                $this->generarPagoPendientesBancoExcel();
            }
            if($form->get('BtnPDF')->isClicked()) {
                $this->filtrarPagoPendientesBancoLista($form);
                $this->listarPagoPendientesBanco();
                $objReportePagoPendientesBanco = new \Brasa\RecursoHumanoBundle\Reportes\ReportePagoPendientesBanco();
                $objReportePagoPendientesBanco->Generar($this, $this->strSqlPagoPendientesBancoLista);
            }
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarPagoPendientesBancoLista($form);
                $this->listarPagoPendientesBanco();
            }

        }
        $arPagos = $paginator->paginate($em->createQuery($this->strSqlPagoPendientesBancoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/PagoPendientesBanco:PagoPendientesBanco.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()
            ));
    }

    public function creditosGeneralAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioCreditosLista();
        $form->handleRequest($request);
        $this->CreditosListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
                $this->generarCreditoExcel();
            }
            if($form->get('BtnPDFCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
                $objReporteCreditos = new \Brasa\RecursoHumanoBundle\Reportes\ReporteCreditos();
                $objReporteCreditos->Generar($this, $this->strSqlCreditoLista);
            }
            if($form->get('BtnFiltrarCredito')->isClicked()) {
                $this->filtrarCreditoLista($form);
                $this->CreditosListar();
            }

        }
        $arCreditos = $paginator->paginate($em->createQuery($this->strSqlCreditoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Creditos:general.html.twig', array(
            'arCreditos' => $arCreditos,
            'form' => $form->createView()
            ));
    }

    public function programacionesPagoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioProgramacionesPagoLista();
        $form->handleRequest($request);
        $this->ProgramacionesPagoListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelProgramacionesPago')->isClicked()) {
                $this->filtrarProgramacionesPagoLista($form);
                $this->ProgramacionesPagoListar();
                $this->generarProgramacionesPagoExcel();
            }
            if($form->get('BtnPDFProgramacionesPago')->isClicked()) {
                $this->filtrarProgramacionesPagoLista($form);
                $this->ProgramacionesPagoListar();
                $objReporteProgramacionesPago = new \Brasa\RecursoHumanoBundle\Reportes\ReporteProgramacionesPago();
                $objReporteProgramacionesPago->Generar($this, $this->strSqlProgramacionesPagoLista);

            }
            if($form->get('BtnFiltrarProgramacionesPago')->isClicked()) {
                $this->filtrarProgramacionesPagoLista($form);
                $this->ProgramacionesPagoListar();
            }

        }
        $arPagos = $paginator->paginate($em->createQuery($this->strSqlProgramacionesPagoLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/ProgramacionesPagos:ProgramacionesPago.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()
            ));
    }

    public function serviciosCobrarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioServiciosPorCobrarLista();
        $form->handleRequest($request);
        $this->ServiciosPorCobrarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $this->generarServiciosPorCobrarExcel();
            }
            if($form->get('BtnPDFServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
                $objReporteServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Reportes\ReporteServiciosPorCobrar();
                $objReporteServiciosPorCobrar->Generar($this, $this->strSqlServiciosPorCobrarLista);
            }
            if($form->get('BtnFiltrarServiciosPorCobrar')->isClicked()) {
                $this->filtrarServiciosPorCobrarLista($form);
                $this->ServiciosPorCobrarListar();
            }

        }
        $arServiciosPorCobrar = $paginator->paginate($em->createQuery($this->strSqlServiciosPorCobrarLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Servicios:porCobrar.html.twig', array(
            'arServiciosPorCobrar' => $arServiciosPorCobrar,
            'form' => $form->createView()
            ));
    }

    public function IncapacidadesAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioIncapacidadesLista();
        $form->handleRequest($request);
        $this->IncapacidadesListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelIncapacidades')->isClicked()) {
                $this->filtrarIncapacidadesLista($form);
                $this->IncapacidadesListar();
                $this->generarIncapacidadesExcel();
            }
            if($form->get('BtnPDFIncapacidades')->isClicked()) {
                $this->filtrarIncapacidadesLista($form);
                $this->IncapacidadesListar();
                $objReporteIncapacidades = new \Brasa\RecursoHumanoBundle\Reportes\ReporteIncapacidades();
                $objReporteIncapacidades->Generar($this, $this->strSqlIncapacidadesLista);
            }
            if($form->get('BtnFiltrarIncapacidades')->isClicked()) {
                $this->filtrarIncapacidadesLista($form);
                $this->IncapacidadesListar();
            }

        }
        $arIncapacidades = $paginator->paginate($em->createQuery($this->strSqlIncapacidadesLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Incapacidades:Incapacidades.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView()
            ));
    }

    public function IncapacidadesCobrarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioIncapacidadesCobrarLista();
        $form->handleRequest($request);
        $this->IncapacidadesCobrarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
                $this->generarIncapacidadesCobrarExcel();
            }
            if($form->get('BtnPDFIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
                $objReporteIncapacidadesCobrar = new \Brasa\RecursoHumanoBundle\Reportes\ReporteIncapacidadesCobrar();
                $objReporteIncapacidadesCobrar->Generar($this, $this->strSqlIncapacidadesCobrarLista);
            }
            if($form->get('BtnFiltrarIncapacidadesCobrar')->isClicked()) {
                $this->filtrarIncapacidadesCobrarLista($form);
                $this->IncapacidadesCobrarListar();
            }

        }
        $arIncapacidadesCobrar = $paginator->paginate($em->createQuery($this->strSqlIncapacidadesCobrarLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/IncapacidadesCobrar:Incapacidades.html.twig', array(
            'arIncapacidadesCobrar' => $arIncapacidadesCobrar,
            'form' => $form->createView()
            ));
    }

    public function ProcesosDisciplinariosAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioProcesosDisciplinariosLista();
        $form->handleRequest($request);
        $this->ProcesosDisciplinariosListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelProcesosDisciplinarios')->isClicked()) {
                $this->filtrarProcesosDisciplinariosLista($form);
                $this->ProcesosDisciplinariosListar();
                $this->generarProcesosDisciplinariosExcel();
            }
            /*if($form->get('BtnPDFProcesosDisciplinarios')->isClicked()) {
                $this->filtrarProcesosDisciplinariosLista($form);
                $this->ProcesosDisciplinariosListar();
                $objReporteProcesosDisciplinarios = new \Brasa\RecursoHumanoBundle\Reportes\ReporteProcesosDisciplinarios();
                $objReporteProcesosDisciplinarios->Generar($this, $this->strSqlProcesosDisciplinariosLista);
            }*/
            if($form->get('BtnFiltrarProcesosDisciplinarios')->isClicked()) {
                $this->filtrarProcesosDisciplinariosLista($form);
                $this->ProcesosDisciplinariosListar();
            }

        }
        $arProcesosDisciplinarios = $paginator->paginate($em->createQuery($this->strSqlProcesosDisciplinariosLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/ProcesosDisciplinarios:ProcesosDisciplinarios.html.twig', array(
            'arProcesosDisciplinarios' => $arProcesosDisciplinarios,
            'form' => $form->createView()
            ));
    }

    public function AportesAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioAportesLista();
        $form->handleRequest($request);
        $this->AportesListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelAportes')->isClicked()) {
                $this->filtrarAportesLista($form);
                $this->AportesListar();
                $this->generarAportesExcel();
            }
            /*if($form->get('BtnPDFAportes')->isClicked()) {
                $this->filtrarAportesLista($form);
                $this->AportesListar();
                $objReporteAportes = new \Brasa\RecursoHumanoBundle\Reportes\ReporteAportes();
                $objReporteAportes->Generar($this, $this->strSqlAportesLista);
            }*/
            if($form->get('BtnFiltrarAportes')->isClicked()) {
                $this->filtrarAportesLista($form);
                $this->AportesListar();
            }

        }
        $arSsoAportes = $paginator->paginate($em->createQuery($this->strSqlAportesLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Aportes:Aportes.html.twig', array(
            'arSsoAportes' => $arSsoAportes,
            'form' => $form->createView()
            ));
    }

    public function VacacionesPagarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioVacacionesPagarLista();
        $form->handleRequest($request);
        $this->VacacionesPagarListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelVacacionesPagar')->isClicked()) {
                $this->filtrarVacacionesPagarLista($form);
                $this->VacacionesPagarListar();
                $this->generarVacacionesPagarExcel();
            }
            if($form->get('BtnFiltrarVacacionesPagar')->isClicked()) {
                $this->filtrarVacacionesPagarLista($form);
                $this->VacacionesPagarListar();
            }

        }
        $arVacacionesPagar = $paginator->paginate($em->createQuery($this->strSqlVacacionesPagarLista), $request->query->get('page', 1), 40);

        return $this->render('BrasaRecursoHumanoBundle:Consultas/VacacionesPagar:VacacionesPagar.html.twig', array(
            'arVacacionesPagar' => $arVacacionesPagar,
            'form' => $form->createView()
            ));
    }
    
    public function FechaTerminacionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFechaTerminacionLista();
        $form->handleRequest($request);
        $this->FechaTerminacionListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelFechaTerminacion')->isClicked()) {
                $this->filtrarFechaTerminacionLista($form);
                $this->FechaTerminacionListar();
                $this->generarFechaTerminacionExcel();
            }
            if($form->get('BtnFiltrarFechaTerminacion')->isClicked()) {
                $this->filtrarFechaTerminacionLista($form);
                $this->FechaTerminacionListar();
            }

        }
        $arFechaTerminacion = $paginator->paginate($em->createQuery($this->strSqlFechaTerminacionLista), $request->query->get('page', 1), 40);

        return $this->render('BrasaRecursoHumanoBundle:Consultas/FechaTerminacion:FechaTerminacion.html.twig', array(
            'arFechaTerminacion' => $arFechaTerminacion,
            'form' => $form->createView()
            ));
    }

    public function EmpleadoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioEmpleadoLista();
        $form->handleRequest($request);
        $this->EmpleadoListar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
            }

            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
                $objFormatoEmpleado = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEmpleado();
                $objFormatoEmpleado->Generar($this, $this->strSqlEmpleadosLista);

            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarEmpleadoLista($form);
                $this->EmpleadoListar();
                $this->generarEmpleadoExcel();
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strSqlEmpleadosLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:lista.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }
    
    public function DotacionesPendientesAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioDotacionesPendientesLista();
        $form->handleRequest($request);
        $this->DotacionesPendientesListar();
        if ($form->isValid())
        {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcelDotacionesPendientes')->isClicked()) {
                $this->filtrarDotacionesPendientesLista($form);
                $this->DotacionesPendientesListar();
                $this->generarDotacionesPendientesExcel();
            }
            
            if($form->get('BtnFiltrarDotacionesPendientes')->isClicked()) {
                $this->filtrarDotacionesPendientesLista($form);
                $this->DotacionesPendientesListar();
            }

        }
        $arDotacionesPendientes = $paginator->paginate($em->createQuery($this->strSqlDotacionesPendientesLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/DotacionesPendientes:DotacionesPendientes.html.twig', array(
            'arDotacionesPendientes' => $arDotacionesPendientes,
            'form' => $form->createView()
            ));
    }

    public function EmpleadodetalleAction($codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinarios = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEmpleadoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arEmpleadoDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        if($form->isValid()) {
        }
        $arIncapacidades = $paginator->paginate($arIncapacidades, $this->get('request')->query->get('page', 1),5);
        $arVacaciones = $paginator->paginate($arVacaciones, $this->get('request')->query->get('page', 1),5);
        $arLicencias = $paginator->paginate($arLicencias, $this->get('request')->query->get('page', 1),5);
        $arContratos = $paginator->paginate($arContratos, $this->get('request')->query->get('page', 1),5);
        $arCreditos = $paginator->paginate($arCreditos, $this->get('request')->query->get('page', 1),5);
        $arDisciplinarios = $paginator->paginate($arDisciplinarios, $this->get('request')->query->get('page', 1),5);
        $arEmpleadoEstudios = $paginator->paginate($arEmpleadoEstudios, $this->get('request')->query->get('page', 1),6);
        $arEmpleadoFamilia = $paginator->paginate($arEmpleadoFamilia, $this->get('request')->query->get('page', 1),8);
        $arEmpleadoDotacion = $paginator->paginate($arEmpleadoDotacion, $this->get('request')->query->get('page', 1),8);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:detalle.html.twig', array(
                    'arEmpleado' => $arEmpleado,
                    'arIncapacidades' => $arIncapacidades,
                    'arVacaciones' => $arVacaciones,
                    'arLicencias' => $arLicencias,
                    'arContratos' => $arContratos,
                    'arCreditos' => $arCreditos,
                    'arDisciplinarios' => $arDisciplinarios,
                    'arEmpleadoEstudios' => $arEmpleadoEstudios,
                    'arEmpleadoFamilia' => $arEmpleadoFamilia,
                    'arEmpleadoDotacion' => $arEmpleadoDotacion,
                    'form' => $form->createView()
                    ));
    }  

    private function listarPagoPendientesBanco() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlPagoPendientesBancoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaPagoPendientesBancoDql(

                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function CreditosListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlCreditoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->listaDQL(
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function ServiciosPorCobrarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlServiciosPorCobrarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuServicioCobrar')->listaServiciosPorCobrarDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function ProgramacionesPagoListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlProgramacionesPagoLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->listaConsultaPagosDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    $session->get('filtroCodigoPago')
                    );
    }

    private function EmpleadoListar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strSqlEmpleadosLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroEmpleadoActivo'),
                $session->get('filtroIdentificacion'),
                ""
                );
    }

    private function AportesListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlAportesLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSsoAporte')->listaAportesDQL(
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function VacacionesPagarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlVacacionesPagarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosVacacionCumplidaDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function FechaTerminacionListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlFechaTerminacionLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->listaContratosFechaTerminacionDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroHasta')
                    );
    }

    private function IncapacidadesListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlIncapacidadesLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadesDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    $session->get('filtroCodigoEntidadSalud')
                    );
    }

    private function IncapacidadesCobrarListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlIncapacidadesCobrarLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaIncapacidadesCobrarDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta'),
                    $session->get('filtroCodigoEntidadSalud')
                    );
    }

    private function ProcesosDisciplinariosListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlProcesosDisciplinariosLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->listaProcesosDisciplinariosDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }
    
    private function DotacionesPendientesListar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlDotacionesPendientesLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->listaDotacionesPendientesDQL(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDF', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioPagoPendientesBancoLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDF', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioCreditosLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();


        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarCredito', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelCredito', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFCredito', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioProgramacionesPagoLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('codigoPago', 'text', array('label'  => 'codigoPago'))
            ->add('BtnFiltrarProgramacionesPago', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelProgramacionesPago', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFProgramacionesPago', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioServiciosPorCobrarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarServiciosPorCobrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelServiciosPorCobrar', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFServiciosPorCobrar', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioAportesLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();

        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarAportes', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelAportes', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioEmpleadoLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('estadoActivo', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ACTIVOS', '0' => 'INACTIVOS')))
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioIncapacidadesLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $arrayPropiedadesEntidadSalud = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEntidadSalud')) {
            $arrayPropiedadesEntidadSalud['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadSalud", $session->get('filtroCodigoEntidadSalud'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('entidadSaludRel', 'entity', $arrayPropiedadesEntidadSalud)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarIncapacidades', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelIncapacidades', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFIncapacidades', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioIncapacidadesCobrarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $arrayPropiedadesEntidadSalud = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoEntidadSalud')) {
            $arrayPropiedadesEntidadSalud['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuEntidadSalud", $session->get('filtroCodigoEntidadSalud'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('entidadSaludRel', 'entity', $arrayPropiedadesEntidadSalud)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarIncapacidadesCobrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelIncapacidadesCobrar', 'submit', array('label'  => 'Excel',))
            ->add('BtnPDFIncapacidadesCobrar', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }

    private function formularioProcesosDisciplinariosLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarProcesosDisciplinarios', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelProcesosDisciplinarios', 'submit', array('label'  => 'Excel',))
            //->add('BtnPDFProcesosDisciplinarios', 'submit', array('label'  => 'PDF',))
            ->getForm();
        return $form;
    }
    
    private function formularioDotacionesPendientesLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarDotacionesPendientes', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelDotacionesPendientes', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioVacacionesPagarLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarVacacionesPagar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelVacacionesPagar', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }
    
    private function formularioFechaTerminacionLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }

        $form = $this->createFormBuilder()
            ->add('centroCostoRel', 'entity', $arrayPropiedades)
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrarFechaTerminacion', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcelFechaTerminacion', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }    

    private function filtrarPagoPendientesBancoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    private function filtrarCreditoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    private function filtrarProgramacionesPagoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $session->set('filtroCodigoPago', $form->get('codigoPago')->getData());
    }

    private function filtrarEmpleadoLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroEmpleadoActivo', $form->get('estadoActivo')->getData());
    }

    private function filtrarIncapacidadesLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $session->set('filtroCodigoEntidadSalud', $controles['entidadSaludRel']);
    }

    private function filtrarIncapacidadesCobrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        $session->set('filtroCodigoEntidadSalud', $controles['entidadSaludRel']);
    }

    private function filtrarProcesosDisciplinariosLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function filtrarDotacionesPendientesLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    private function filtrarAportesLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');

        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    private function filtrarServiciosPorCobrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroDesde', $form->get('fechaDesde')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    private function filtrarVacacionesPagarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }
    
    private function filtrarFechaTerminacionLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroHasta', $form->get('fechaHasta')->getData());
    }

    private function generarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'ARP')
                    ->setCellValue('L1', 'EPS')
                    ->setCellValue('M1', 'PENSION')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'SENA')
                    ->setCellValue('Q1', 'CESANTIAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'ADMON')
                    ->setCellValue('T1', 'COSTO')
                    ->setCellValue('U1', 'TOTAL')
                    ->setCellValue('W1', 'NETO')
                    ->setCellValue('X1', 'IBC')
                    ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('Z1', 'DIAS PERIODO')
                    ->setCellValue('AA1', 'SALARIO PERIODO')
                    ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('costos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Costos.xlsx"');
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

    private function generarPagoPendientesBancoExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'DESDE')
                    ->setCellValue('C1', 'HASTA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'BASICO')
                    ->setCellValue('H1', 'TIEMPO EXTRA')
                    ->setCellValue('I1', 'VALORES ADICIONALES')
                    ->setCellValue('J1', 'AUX. TRANSPORTE')
                    ->setCellValue('K1', 'ARP')
                    ->setCellValue('L1', 'EPS')
                    ->setCellValue('M1', 'PENSION')
                    ->setCellValue('N1', 'CAJA')
                    ->setCellValue('O1', 'ICBF')
                    ->setCellValue('P1', 'SENA')
                    ->setCellValue('Q1', 'CESANTIAS')
                    ->setCellValue('R1', 'VACACIONES')
                    ->setCellValue('S1', 'ADMON')
                    ->setCellValue('T1', 'COSTO')
                    ->setCellValue('U1', 'TOTAL')
                    ->setCellValue('W1', 'NETO')
                    ->setCellValue('X1', 'IBC')
                    ->setCellValue('Y1', 'AUX. TRANSPORTE COTIZACION')
                    ->setCellValue('Z1', 'DIAS PERIODO')
                    ->setCellValue('AA1', 'SALARIO PERIODO')
                    ->setCellValue('AB1', 'SALARIO EMPLEADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlPagoPendientesBancoLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('C' . $i, $arPago->getFechaHasta()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getVrSalario())
                    ->setCellValue('H' . $i, $arPago->getVrAdicionalTiempo())
                    ->setCellValue('I' . $i, $arPago->getVrAdicionalValor())
                    ->setCellValue('J' . $i, $arPago->getVrAuxilioTransporte())
                    ->setCellValue('K' . $i, $arPago->getVrArp())
                    ->setCellValue('L' . $i, $arPago->getVrEps())
                    ->setCellValue('M' . $i, $arPago->getVrPension())
                    ->setCellValue('N' . $i, $arPago->getVrCaja())
                    ->setCellValue('O' . $i, $arPago->getVrIcbf())
                    ->setCellValue('P' . $i, $arPago->getVrSena())
                    ->setCellValue('Q' . $i, $arPago->getVrCesantias())
                    ->setCellValue('R' . $i, $arPago->getVrVacaciones())
                    ->setCellValue('S' . $i, $arPago->getVrAdministracion())
                    ->setCellValue('T' . $i, $arPago->getVrCosto())
                    ->setCellValue('U' . $i, $arPago->getVrTotalCobrar())
                    ->setCellValue('W' . $i, $arPago->getVrNeto())
                    ->setCellValue('X' . $i, $arPago->getVrIngresoBaseCotizacion())
                    ->setCellValue('Y' . $i, $arPago->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('Z' . $i, $arPago->getDiasPeriodo())
                    ->setCellValue('AA' . $i, $arPago->getVrSalarioPeriodo())
                    ->setCellValue('AB' . $i, $arPago->getVrSalarioEmpleado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PagoPendientesBanco');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PagoPendientesBanco.xlsx"');
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

    private function generarCostosIbcExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'CODIGO CONTRATO')
                    ->setCellValue('E1', 'IBC')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA');

        $i = 2;
        $query = $em->createQuery($this->strSqlCostosIbcLista);
        $arIbc = new \Brasa\RecursoHumanoBundle\Entity\RhuIbc();
        $arIbc = $query->getResult();
        foreach ($arIbc as $arIbc) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIbc->getCodigoIbcPk())
                    ->setCellValue('B' . $i, $arIbc->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arIbc->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arIbc->getCodigoContratoFk())
                    ->setCellValue('E' . $i, $arIbc->getvrIngresoBaseCotizacion())
                    ->setCellValue('F' . $i, $arIbc->getFechaDesde()->Format('Y-m-d'))
                    ->setCellValue('G' . $i, $arIbc->getFechaDesde()->Format('Y-m-d'));
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('CostosIbcc');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CostosIbc.xlsx"');
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

    private function generarCreditoExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'NOMBRE')
                    ->setCellValue('F1', 'VR. CREDITO')
                    ->setCellValue('G1', 'VR. CUOTA')
                    ->setCellValue('H1', 'VR. SALDO')
                    ->setCellValue('I1', 'CUOTAS')
                    ->setCellValue('J1', 'CUOTA ACTUAL')
                    ->setCellValue('K1', 'APROBADO')
                    ->setCellValue('L1', 'SUSPENDIDO');

        $i = 2;
        $query = $em->createQuery($this->strSqlCreditoLista);
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $query->getResult();
        foreach ($arCreditos as $arCredito) {
            if ($arCredito->getAprobado() == 1) {
                $Aprobado = "SI";
            }
            if ($arCredito->getEstadoSuspendido() == 1) {
                $Suspendido = "SI";
            }
            else {
                $Suspendido = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCredito->getCodigoCreditoPk())
                    ->setCellValue('B' . $i, $arCredito->getCreditoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arCredito->getFecha()->Format('Y-m-d'))
                    ->setCellValue('D' . $i, $arCredito->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arCredito->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arCredito->getVrPagar())
                    ->setCellValue('G' . $i, $arCredito->getVrCuota())
                    ->setCellValue('H' . $i, $arCredito->getSaldo())
                    ->setCellValue('I' . $i, $arCredito->getNumeroCuotas())
                    ->setCellValue('J' . $i, $arCredito->getNumeroCuotaActual())
                    ->setCellValue('K' . $i, $Aprobado)
                    ->setCellValue('L' . $i, $Suspendido);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('creditos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConsultaCreditos.xlsx"');
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

    private function generarServiciosPorCobrarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'CENTRO COSTOS')
                    ->setCellValue('C1', 'IDENTIFICACION')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'VR. SALARIO')
                    ->setCellValue('H1', 'VR. SALARIO PERIODO')
                    ->setCellValue('I1', 'VR. SALARIO EMPLEADO')
                    ->setCellValue('J1', 'VR. DEVENGADO')
                    ->setCellValue('K1', 'VR. DEDUCCIONES')
                    ->setCellValue('L1', 'VR. ADICIONAL TIEMPO')
                    ->setCellValue('M1', 'VR. ADICIONAL VALOR')
                    ->setCellValue('N1', 'VR. AUXILIO TRANSPORTE')
                    ->setCellValue('O1', 'VR. AUXILIO TRANSPORTE COTIZACION')
                    ->setCellValue('P1', 'VR. ARP')
                    ->setCellValue('Q1', 'VR. EPS')
                    ->setCellValue('R1', 'VR. PENSION')
                    ->setCellValue('S1', 'VR. CAJA COMPENSACION')
                    ->setCellValue('T1', 'VR. SENA')
                    ->setCellValue('U1', 'VR. ICBF')
                    ->setCellValue('V1', 'VR. CESANTIAS')
                    ->setCellValue('W1', 'VR. VACACIONES')
                    ->setCellValue('X1', 'VR. ADMINISTRACION')
                    ->setCellValue('Y1', 'VR. NETO')
                    ->setCellValue('Z1', 'VR. BRUTO')
                    ->setCellValue('AA1', 'VR. TOTAL COBRAR')
                    ->setCellValue('AB1', 'VR. COSTO')
                    ->setCellValue('AC1', 'VR. INGRESO BASE COTIZACION')
                    ->setCellValue('AD1', 'ESTADO COBRADO')
                    ->setCellValue('AE1', 'DIAS PERIODO');

        $i = 2;
        $query = $em->createQuery($this->strSqlServiciosPorCobrarLista);
        $arServiciosPorCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuServicioCobrar();
        $arServiciosPorCobrar = $query->getResult();
        foreach ($arServiciosPorCobrar as $arServiciosPorCobrar) {
            if ($arServiciosPorCobrar->getEstadoCobrado() == 1) {
                $estado = "SI";
            } else {
                $estado = "NO";
            }

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServiciosPorCobrar->getCodigoServicioCobrarPk())
                    ->setCellValue('B' . $i, $arServiciosPorCobrar->getCentroCostoRel()->getNombre())
                    ->setCellValue('C' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arServiciosPorCobrar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arServiciosPorCobrar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arServiciosPorCobrar->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('G' . $i, $arServiciosPorCobrar->getVrSalario())
                    ->setCellValue('H' . $i, $arServiciosPorCobrar->getVrSalarioPeriodo())
                    ->setCellValue('I' . $i, $arServiciosPorCobrar->getVrSalarioEmpleado())
                    ->setCellValue('J' . $i, $arServiciosPorCobrar->getVrDevengado())
                    ->setCellValue('K' . $i, $arServiciosPorCobrar->getVrDeducciones())
                    ->setCellValue('L' . $i, $arServiciosPorCobrar->getVrAdicionalTiempo())
                    ->setCellValue('M' . $i, $arServiciosPorCobrar->getVrAdicionalValor())
                    ->setCellValue('N' . $i, $arServiciosPorCobrar->getVrAuxilioTransporte())
                    ->setCellValue('O' . $i, $arServiciosPorCobrar->getVrAuxilioTransporteCotizacion())
                    ->setCellValue('P' . $i, $arServiciosPorCobrar->getVrArp())
                    ->setCellValue('Q' . $i, $arServiciosPorCobrar->getVrEps())
                    ->setCellValue('R' . $i, $arServiciosPorCobrar->getVrPension())
                    ->setCellValue('S' . $i, $arServiciosPorCobrar->getVrCaja())
                    ->setCellValue('T' . $i, $arServiciosPorCobrar->getVrSena())
                    ->setCellValue('U' . $i, $arServiciosPorCobrar->getVrIcbf())
                    ->setCellValue('V' . $i, $arServiciosPorCobrar->getVrCesantias())
                    ->setCellValue('W' . $i, $arServiciosPorCobrar->getVrVacaciones())
                    ->setCellValue('X' . $i, $arServiciosPorCobrar->getVrAdministracion())
                    ->setCellValue('Y' . $i, $arServiciosPorCobrar->getVrNeto())
                    ->setCellValue('Z' . $i, $arServiciosPorCobrar->getVrBruto())
                    ->setCellValue('AA' . $i, $arServiciosPorCobrar->getVrTotalCobrar())
                    ->setCellValue('AB' . $i, $arServiciosPorCobrar->getVrCosto())
                    ->setCellValue('AC' . $i, $arServiciosPorCobrar->getVrIngresoBaseCotizacion())
                    ->setCellValue('AD' . $i, $estado)
                    ->setCellValue('AE' . $i, $arServiciosPorCobrar->getDiasPeriodo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ServiciosPorCobrar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ConsultaServiciosPorCobrar.xlsx"');
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

    private function generarProgramacionesPagoExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'TIPO')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CENTRO COSTOS')
                    ->setCellValue('G1', 'PERIODO DESDE')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'VR. SALARIO ')
                    ->setCellValue('K1', 'VR. DEVENGADO')
                    ->setCellValue('L1', 'VR. DEDUCCIONES')
                    ->setCellValue('M1', 'VR. NETO');

        $i = 2;
        $query = $em->createQuery($this->strSqlProgramacionesPagoLista);
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $query->getResult();
        foreach ($arPagos as $arPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPago->getCodigoPagoPk())
                    ->setCellValue('B' . $i, $arPago->getNumero())
                    ->setCellValue('C' . $i, $arPago->getPagoTipoRel()->getNombre())
                    ->setCellValue('D' . $i, $arPago->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arPago->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arPago->getCentroCostoRel()->getNombre())
                    ->setCellValue('G' . $i, $arPago->getFechaDesdePago()->format('Y/m/d'))
                    ->setCellValue('H' . $i, $arPago->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arPago->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arPago->getVrSalario())
                    ->setCellValue('K' . $i, $arPago->getVrDevengado())
                    ->setCellValue('L' . $i, $arPago->getVrDeducciones())
                    ->setCellValue('M' . $i, $arPago->getVrNeto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ProgramacionesPagos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteProgramacionesPagos.xlsx"');
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

    private function generarIncapacidadesExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'DIAGNOSTICO')
                    ->setCellValue('D1', 'EPS')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'DÍAS')
                    ->setCellValue('K1', 'PRORROGA')
                    ->setCellValue('L1', 'TRANSCRIPCIÓN')
                    ->setCellValue('M1', 'VR. INCAPACIDAD')
                    ->setCellValue('N1', 'VR. PAGADO')
                    ->setCellValue('O1', 'VR. SALDO');

        $i = 2;
        $query = $em->createQuery($this->strSqlIncapacidadesLista);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $query->getResult();
        foreach ($arIncapacidades as $arIncapacidad) {
            if ($arIncapacidad->getEstadoProrroga() == 1){
                $prorroga = "SI";
            }else {
                $prorroga = "NO";
            }
            if ($arIncapacidad->getEstadoTranscripcion() == 1){
                $transcripcion = "SI";
            }else {
                $transcripcion = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidad->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidad->getIncapacidadTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arIncapacidad->getIncapacidadDiagnosticoRel()->getNombre())
                    ->setCellValue('D' . $i, $arIncapacidad->getEntidadSaludRel()->getNombre())
                    ->setCellValue('E' . $i, $arIncapacidad->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arIncapacidad->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arIncapacidad->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arIncapacidad->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arIncapacidad->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arIncapacidad->getCantidad())
                    ->setCellValue('K' . $i, $prorroga)
                    ->setCellValue('L' . $i, $transcripcion)
                    ->setCellValue('M' . $i, $arIncapacidad->getVrIncapacidad())
                    ->setCellValue('N' . $i, $arIncapacidad->getVrPagado())
                    ->setCellValue('O' . $i, $arIncapacidad->getVrSaldo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Incapacidades');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteIncapacidades.xlsx"');
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

    private function generarIncapacidadesCobrarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'DIAGNOSTICO')
                    ->setCellValue('D1', 'EPS')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTOS')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'DÍAS')
                    ->setCellValue('K1', 'PRORROGA')
                    ->setCellValue('L1', 'TRANSCRIPCIÓN')
                    ->setCellValue('M1', 'VR. INCAPACIDAD')
                    ->setCellValue('N1', 'VR. PAGADO')
                    ->setCellValue('O1', 'VR. SALDO');

        $i = 2;
        $query = $em->createQuery($this->strSqlIncapacidadesCobrarLista);
        $arIncapacidadesCobrar = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidadesCobrar = $query->getResult();
        foreach ($arIncapacidadesCobrar as $arIncapacidadesCobrar) {
            if ($arIncapacidadesCobrar->getEstadoProrroga() == 1){
                $prorroga = "SI";
            }else {
                $prorroga = "NO";
            }
            if ($arIncapacidadesCobrar->getEstadoTranscripcion() == 1){
                $transcripcion = "SI";
            }else {
                $transcripcion = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidadesCobrar->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidadesCobrar->getIncapacidadTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arIncapacidadesCobrar->getIncapacidadDiagnosticoRel()->getNombre())
                    ->setCellValue('D' . $i, $arIncapacidadesCobrar->getEntidadSaludRel()->getNombre())
                    ->setCellValue('E' . $i, $arIncapacidadesCobrar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arIncapacidadesCobrar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arIncapacidadesCobrar->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arIncapacidadesCobrar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arIncapacidadesCobrar->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arIncapacidadesCobrar->getCantidad())
                    ->setCellValue('K' . $i, $prorroga)
                    ->setCellValue('L' . $i, $transcripcion)
                    ->setCellValue('M' . $i, $arIncapacidadesCobrar->getVrIncapacidad())
                    ->setCellValue('N' . $i, $arIncapacidadesCobrar->getVrPagado())
                    ->setCellValue('O' . $i, $arIncapacidadesCobrar->getVrSaldo());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('IncapacidadesCobrar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteIncapacidadesCobrar.xlsx"');
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

    private function generarProcesosDisciplinariosExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'CENTRO COSTOS')
                    ->setCellValue('D1', 'IDENTIFICACIÓN')
                    ->setCellValue('E1', 'EMPLEADO')
                    ->setCellValue('F1', 'CARGO')
                    ->setCellValue('G1', 'PROCESO')
                    ->setCellValue('H1', 'CAUSAL')
                    ->setCellValue('I1', 'DESCARGOS')
                    ->setCellValue('J1', 'FECHA SUSPENSIÓN');

        $i = 2;
        $query = $em->createQuery($this->strSqlProcesosDisciplinariosLista);
        $arProcesosDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arProcesosDisciplinarios = $query->getResult();
        foreach ($arProcesosDisciplinarios as $arProcesoDisciplinario) {
            if ($arProcesoDisciplinario->getAsunto() == Null){
                $asunto = "NO APLICA";
            } else {
                $asunto = $arProcesoDisciplinario->getAsunto();
            }
            if ($arProcesoDisciplinario->getDescargos() == Null){
                $descargos = "NO APLICA";
            } else {
                $descargos = $arProcesoDisciplinario->getDescargos();
            }
            if ($arProcesoDisciplinario->getSuspension() == Null){
                $suspension = "NO APLICA";
            } else {
                $suspension = $arProcesoDisciplinario->getSuspension();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProcesoDisciplinario->getCodigoDisciplinarioPk())
                    ->setCellValue('B' . $i, $arProcesoDisciplinario->getFecha()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arProcesoDisciplinario->getCentroCostoRel()->getNombre())
                    ->setCellValue('D' . $i, $arProcesoDisciplinario->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arProcesoDisciplinario->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('F' . $i, $arProcesoDisciplinario->getEmpleadoRel()->getCargoDescripcion())
                    ->setCellValue('G' . $i, $arProcesoDisciplinario->getDisciplinarioTipoRel()->getNombre())
                    ->setCellValue('H' . $i, $asunto)
                    ->setCellValue('I' . $i, $descargos)
                    ->setCellValue('J' . $i, $suspension);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ProcesosDisciplinarios');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteProcesosDisciplinarios.xlsx"');
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
    
    private function generarDotacionesPendientesExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'CÓDIGO DOTACIÓN')    
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'ELEMENTO DOTACIÓN')
                    ->setCellValue('H1', 'CANTIDAD SOLICITADA')
                    ->setCellValue('I1', 'CANTIDAD PENDIENTE')
                    ->setCellValue('J1', 'NÚMERO INTERNO REFERENCIA')
                    ->setCellValue('K1', 'SERIE')
                    ->setCellValue('L1', 'LOTE');

        $i = 2;
        $query = $em->createQuery($this->strSqlDotacionesPendientesLista);
        $arDotacionesPendientes = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle();
        $arDotacionesPendientes = $query->getResult();
        foreach ($arDotacionesPendientes as $arDotacionPendiente) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arDotacionPendiente->getCodigoDotacionDetallePk())
                    ->setCellValue('B' . $i, $arDotacionPendiente->getDotacionRel()->getFecha()->format('Y/m/d'))
                    ->setCellValue('C' . $i, $arDotacionPendiente->getDotacionRel()->getCodigoDotacionPk())
                    ->setCellValue('D' . $i, $arDotacionPendiente->getDotacionRel()->getCentroCostoRel()->getNombre())
                    ->setCellValue('E' . $i, $arDotacionPendiente->getDotacionRel()->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arDotacionPendiente->getDotacionRel()->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arDotacionPendiente->getDotacionElementoRel()->getDotacion())
                    ->setCellValue('H' . $i, $arDotacionPendiente->getCantidadAsignada())
                    ->setCellValue('I' . $i, $arDotacionPendiente->getCantidadAsignada() - $arDotacionPendiente->getCantidadDevuelta())
                    ->setCellValue('J' . $i, $arDotacionPendiente->getDotacionRel()->getCodigoInternoReferencia())
                    ->setCellValue('K' . $i, $arDotacionPendiente->getSerie())
                    ->setCellValue('L' . $i, $arDotacionPendiente->getLote());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('DotacionesPendientes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteDotacionesPendientes.xlsx"');
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

    private function generarEmpleadoExcel() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO IDENTIFICACIÓN')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'CIUDAD EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('E1', 'FECHA EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('F1', 'LIBRETA MILITAR')
                    ->setCellValue('G1', 'CENTRO COSTO')
                    ->setCellValue('H1', 'NOMBRE')
                    ->setCellValue('I1', 'TELÉFONO')
                    ->setCellValue('J1', 'CELULAR')
                    ->setCellValue('K1', 'DIRECCIÓN')
                    ->setCellValue('L1', 'BARRIO')
                    ->setCellValue('M1', 'CIUDAD RESIDENCIA')
                    ->setCellValue('N1', 'RH')
                    ->setCellValue('O1', 'SEXO')
                    ->setCellValue('P1', 'CORREO')
                    ->setCellValue('Q1', 'FECHA NACIMIENTO')
                    ->setCellValue('R1', 'CIUDAD DE NACIMIENTO')
                    ->setCellValue('S1', 'ESTADO CIVIL')
                    ->setCellValue('T1', 'PADRE DE FAMILIA')
                    ->setCellValue('U1', 'CABEZA DE HOGAR')
                    ->setCellValue('V1', 'NIVEL DE ESTUDIO')
                    ->setCellValue('W1', 'ENTIDAD SALUD')
                    ->setCellValue('X1', 'ENTIDAD PENSION')
                    ->setCellValue('Y1', 'ENTIDAD CAJA DE COMPESACIÓN')
                    ->setCellValue('Z1', 'CLASIFICACIÓN DE RIESGO')
                    ->setCellValue('AA1', 'CUENTA BANCARIA')
                    ->setCellValue('AB1', 'BANCO')
                    ->setCellValue('AC1', 'SALARIO')
                    ->setCellValue('AD1', 'FECHA CONTRATO')
                    ->setCellValue('AE1', 'FECHA FINALIZA CONTRATO')
                    ->setCellValue('AF1', 'CARGO')
                    ->setCellValue('AG1', 'DESCRIPCIÓN CARGO')
                    ->setCellValue('AH1', 'TIPO PENSIÓN')
                    ->setCellValue('AI1', 'TIPO COTIZANTE')
                    ->setCellValue('AJ1', 'SUBTIPO COTIZANTE')
                    ->setCellValue('AK1', 'ESTADO ACTIVO')
                    ->setCellValue('AL1', 'ESTADO CONTRATO')
                    ->setCellValue('AM1', 'CODIGO CONTRATO')
                    ->setCellValue('AN1', 'TALLA CAMISA')
                    ->setCellValue('AO1', 'TALLA JEANS')
                    ->setCellValue('AP1', 'TALLA CALZADO');

        $i = 2;
        $query = $em->createQuery($this->strSqlEmpleadosLista);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $query->getResult();
        foreach ($arEmpleados as $arEmpleado) {
            if ($arEmpleado->getCodigoCentroCostoFk() == null){
                $centroCosto = "";
            }else{
                $centroCosto = $arEmpleado->getCentroCostoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoClasificacionRiesgoFk() == null){
                $clasificacionRiesgo = "";
            }else{
                $clasificacionRiesgo = $arEmpleado->getClasificacionRiesgoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoCargoFk() == null){
                $cargo = "";
            }else{
                $cargo = $arEmpleado->getCargoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoPensionFk() == null){
                $tipoPension = "";
            }else{
                $tipoPension = $arEmpleado->getTipoPensionRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoCotizanteFk() == null){
                $tipoCotizante = "";
            }else{
                $tipoCotizante = $arEmpleado->getSsoTipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadSaludFk() == null){
                $entidadSalud = "";
            }else{
                $entidadSalud = $arEmpleado->getEntidadSaludRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadPensionFk() == null){
                $entidadPension = "";
            }else{
                $entidadPension = $arEmpleado->getEntidadPensionRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadCajaFk() == null){
                $entidadCaja = "";
            }else{
                $entidadCaja = $arEmpleado->getEntidadCajaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoSubtipoCotizanteFk() == null){
                $subtipoCotizante = "";
            }else{
                $subtipoCotizante = $arEmpleado->getSsoSubtipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            }else{
                $sexo = "FEMENINO";
            }
            if ($arEmpleado->getPadreFamilia() == 0){
                $padreFamilia = "NO";
            }else{
                $padreFamilia = "SI";
            }
            if ($arEmpleado->getCabezaHogar() == 0){
                $cabezaHogar = "NO";
            }else{
                $cabezaHogar = "SI";
            }
            if ($arEmpleado->getEstadoActivo() == 0){
                $estadoActivo = "NO";
            }else{
                $estadoActivo = "SI";
            }
            if ($arEmpleado->getEstadoContratoActivo() == 0){
                $estadoContratoActivo = "NO VIGENTE";
            }else{
                $estadoContratoActivo = "VIGENTE";
            }
            if ($arEmpleado->getFechaContrato() == null){
                $fechaContrato = "";
            } else {
                $fechaContrato = $arEmpleado->getFechaContrato()->format('Y-m-d');
            }
            if ($arEmpleado->getFechaFinalizaContrato() == null){
                $fechaFinalizacionContrato = "";
            } else {
                $fechaFinalizacionContrato = $arEmpleado->getFechaFinalizaContrato()->format('Y-m-d');
            }       
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                    ->setCellValue('B' . $i, $arEmpleado->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('C' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arEmpleado->getciudadExpedicionRel()->getNombre())
                    ->setCellValue('E' . $i, $arEmpleado->getFechaExpedicionIdentificacion()->format('Y-m-d'))
                    ->setCellValue('F' . $i, $arEmpleado->getLibretaMilitar())
                    ->setCellValue('G' . $i, $centroCosto)
                    ->setCellValue('H' . $i, $arEmpleado->getNombreCorto())
                    ->setCellValue('I' . $i, $arEmpleado->getTelefono())
                    ->setCellValue('J' . $i, $arEmpleado->getCelular())
                    ->setCellValue('K' . $i, $arEmpleado->getDireccion())
                    ->setCellValue('L' . $i, $arEmpleado->getBarrio())
                    ->setCellValue('M' . $i, $arEmpleado->getciudadRel()->getNombre())
                    ->setCellValue('N' . $i, $arEmpleado->getRhRel()->getTipo())
                    ->setCellValue('O' . $i, $sexo)
                    ->setCellValue('P' . $i, $arEmpleado->getCorreo())
                    ->setCellValue('Q' . $i, $arEmpleado->getFechaNacimiento()->format('Y-m-d'))
                    ->setCellValue('R' . $i, $arEmpleado->getCiudadNacimientoRel()->getNombre())
                    ->setCellValue('S' . $i, $arEmpleado->getEstadoCivilRel()->getNombre())
                    ->setCellValue('T' . $i, $padreFamilia)
                    ->setCellValue('U' . $i, $cabezaHogar)
                    ->setCellValue('V' . $i, $arEmpleado->getEmpleadoEstudioTipoRel()->getNombre())
                    ->setCellValue('W' . $i, $entidadSalud)
                    ->setCellValue('X' . $i, $entidadPension)
                    ->setCellValue('Y' . $i, $entidadCaja)
                    ->setCellValue('Z' . $i, $clasificacionRiesgo)
                    ->setCellValue('AA' . $i, $arEmpleado->getCuenta())
                    ->setCellValue('AB' . $i, $arEmpleado->getBancoRel()->getNombre())
                    ->setCellValue('AC' . $i, $arEmpleado->getVrSalario())
                    ->setCellValue('AD' . $i, $fechaContrato)
                    ->setCellValue('AE' . $i, $fechaFinalizacionContrato)
                    ->setCellValue('AF' . $i, $cargo)
                    ->setCellValue('AG' . $i, $arEmpleado->getCargoDescripcion())
                    ->setCellValue('AH' . $i, $tipoPension)
                    ->setCellValue('AI' . $i, $tipoCotizante)
                    ->setCellValue('AJ' . $i, $subtipoCotizante)
                    ->setCellValue('AK' . $i, $estadoActivo)
                    ->setCellValue('AL' . $i, $estadoContratoActivo)
                    ->setCellValue('AM' . $i, $arEmpleado->getCodigoContratoActivoFk())
                    ->setCellValue('AN' . $i, $arEmpleado->getCamisa())
                    ->setCellValue('AO' . $i, $arEmpleado->getJeans())
                    ->setCellValue('AP' . $i, $arEmpleado->getCalzado());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Empleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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

    private function generarAportesExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'SUCURSAL')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'SECUENCIA')
                    ->setCellValue('F1', 'TIPO DOCUMENTO')
                    ->setCellValue('G1', 'TIPO COTIZANTE')
                    ->setCellValue('H1', 'SUBTIPO COTIZANTE')
                    ->setCellValue('I1', 'DEPARTAMENTO')
                    ->setCellValue('J1', 'MUNICIPIO')
                    ->setCellValue('K1', 'INGRESO')
                    ->setCellValue('L1', 'RETIRO')
                    ->setCellValue('M1', 'TRANSLADO DESDE OTRA EPS')
                    ->setCellValue('N1', 'TRANSLADO A OTRA EPS')
                    ->setCellValue('O1', 'TRANSLADO DESDE OTRA PENSIÓN')
                    ->setCellValue('P1', 'TRANSLADO A OTRA PENSIÓN')
                    ->setCellValue('Q1', 'VARIACIÓN PERMANENTE SALARIO')
                    ->setCellValue('R1', 'CORRECCIONES')
                    ->setCellValue('S1', 'VARIACIÓN TRANSITORIA SALARIO')
                    ->setCellValue('T1', 'SUSPENCIÓN TEMPORAL CONTRATO LICENCIA SERVICIOS')
                    ->setCellValue('U1', 'DÍAS LICENCIAS')
                    ->setCellValue('V1', 'SALARIO BÁSICO')
                    ->setCellValue('W1', 'SALARIO MES ANTERIOR')
                    ->setCellValue('X1', 'SALARIO INTEGRAL')
                    ->setCellValue('Y1', 'SUPLEMENTARIO')
                    ->setCellValue('Z1', 'INCAPACIDAD GENERAL')
                    ->setCellValue('AA1', 'DÍAS INCAPACIDAD GENERAL')
                    ->setCellValue('AB1', 'LICENCIA MATERNIDAD')
                    ->setCellValue('AC1', 'DÍAS LICENCIAS MATERNIDAD')
                    ->setCellValue('AD1', 'VACACIONES')
                    ->setCellValue('AE1', 'APORTE VOLUNTARIO')
                    ->setCellValue('AF1', 'VARIACIÓN CENTRO TRABAJO')
                    ->setCellValue('AG1', 'INCAPACIDAD ACCIDENTE TRABAJO ENFERMEDAD PROFESIONAL')
                    ->setCellValue('AH1', 'ENTIDAD PENSIÓN')
                    ->setCellValue('AI1', 'ENTIDAD PENSIÓN TRASLADA')
                    ->setCellValue('AJ1', 'ENTIDAD SALUD')
                    ->setCellValue('AK1', 'ENTIDAD SALUD TRASLADA')
                    ->setCellValue('AL1', 'CAJA COMPENSACIÓN')
                    ->setCellValue('AM1', 'DÍAS COTIZADOS PENSIÓN')
                    ->setCellValue('AN1', 'DÍAS COTIZADOS SALUD')
                    ->setCellValue('AO1', 'DIAS COTIZADOS RIESGOS PROFESIONALES')
                    ->setCellValue('AP1', 'DIAS COTIZADOS CAJAS COMPENSACIÓN')
                    ->setCellValue('AQ1', 'IBC PENSIÓN')
                    ->setCellValue('AR1', 'IBC SALUD')
                    ->setCellValue('AS1', 'IBC RIESGOS PROFESIONALES')
                    ->setCellValue('AT1', 'IBC CAJA COMPENSACIÓN')
                    ->setCellValue('AU1', 'TARIFA PENSIÓN')
                    ->setCellValue('AV1', 'TARIFA SALUD')
                    ->setCellValue('AW1', 'TARIFA RIESGOS PROFESIONALES')
                    ->setCellValue('AX1', 'TARIFA CAJA COMPENSACIÓN')
                    ->setCellValue('AY1', 'COTIZACIÓN PENSIÓN')
                    ->setCellValue('AZ1', 'COTIZACIÓN SALUD')
                    ->setCellValue('BA1', 'COTIZACIÓN RIESGOS PROFESIONALES')
                    ->setCellValue('BB1', 'COTIZACION CAJA COMPENSACIÓN')
                    ->setCellValue('BC1', 'APORTE VOLUNTARIO FONDO PENSIONES OBLIGATORIAS')
                    ->setCellValue('BD1', 'COTIZACIÓN VOLUNTARIO FONDO PENSIONES OBLIGATORIAS')
                    ->setCellValue('BE1', 'TOTAL COTIZACIÓN')
                    ->setCellValue('BF1', 'APORTES FONDO SOLIDARIDAD PENSIONAL SOLIDARIDAD');
        $i = 2;
        $query = $em->createQuery($this->strSqlAportesLista);
        $arAportes = new \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte();
        $arAportes = $query->getResult();

        foreach ($arAportes as $arAporte) {
        $arEntidadPension = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $arEntidadPension = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadPensionPertenece()));
        $arEntidadPensionPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension();
        $arEntidadPensionPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadPension')->find($arEntidadPension[0]);

        $arEntidadSalud = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arEntidadSalud = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadSaludPertenece()));
        $arEntidadSaludPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud();
        $arEntidadSaludPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadSalud')->find($arEntidadSalud[0]);

        $arEntidadCaja = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        $arEntidadCaja = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->findBy(array('codigoInterface' =>$arAporte->getCodigoEntidadCajaPertenece()));
        $arEntidadCajaPertenece = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja();
        $arEntidadCajaPertenece = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadCaja')->find($arEntidadCaja[0]);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAporte->getCodigoAportePk())
                    ->setCellValue('B' . $i, $arAporte->getSsoSucursalRel()->getNombre())
                    ->setCellValue('C' . $i, $arAporte->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arAporte->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arAporte->getSecuencia())
                    ->setCellValue('F' . $i, $arAporte->getEmpleadoRel()->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('G' . $i, $arAporte->getContratoRel()->getSsoTipoCotizanteRel()->getNombre())
                    ->setCellValue('H' . $i, $arAporte->getContratoRel()->getSsoSubtipoCotizanteRel()->getNombre())
                    ->setCellValue('I' . $i, $arAporte->getEmpleadoRel()->getCiudadRel()->getDepartamentoRel()->getNombre())
                    ->setCellValue('J' . $i, $arAporte->getEmpleadoRel()->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arAporte->getIngreso())
                    ->setCellValue('L' . $i, $arAporte->getRetiro())
                    ->setCellValue('M' . $i, $arAporte->getTrasladoDesdeOtraEps())
                    ->setCellValue('N' . $i, $arAporte->getTrasladoAOtraEps())
                    ->setCellValue('O' . $i, $arAporte->getTrasladoDesdeOtraPension())
                    ->setCellValue('P' . $i, $arAporte->getTrasladoAOtraPension())
                    ->setCellValue('Q' . $i, $arAporte->getVariacionPermanenteSalario())
                    ->setCellValue('R' . $i, $arAporte->getCorrecciones())
                    ->setCellValue('S' . $i, $arAporte->getVariacionTransitoriaSalario())
                    ->setCellValue('T' . $i, $arAporte->getSuspensionTemporalContratoLicenciaServicios())
                    ->setCellValue('U' . $i, $arAporte->getDiasLicencia())
                    ->setCellValue('V' . $i, $arAporte->getSalarioBasico())
                    ->setCellValue('W' . $i, $arAporte->getSalarioMesAnterior())
                    ->setCellValue('X' . $i, $arAporte->getSalarioIntegral())
                    ->setCellValue('Y' . $i, $arAporte->getSuplementario())
                    ->setCellValue('Z' . $i, $arAporte->getIncapacidadGeneral())
                    ->setCellValue('AA' . $i, $arAporte->getDiasIncapacidadGeneral())
                    ->setCellValue('AB' . $i, $arAporte->getLicenciaMaternidad())
                    ->setCellValue('AC' . $i, $arAporte->getDiasLicenciaMaternidad())
                    ->setCellValue('AD' . $i, $arAporte->getVacaciones())
                    ->setCellValue('AE' . $i, $arAporte->getAporteVoluntario())
                    ->setCellValue('AF' . $i, $arAporte->getVariacionCentrosTrabajo())
                    ->setCellValue('AG' . $i, $arAporte->getIncapacidadAccidenteTrabajoEnfermedadProfesional())
                    ->setCellValue('AH' . $i, $arEntidadPensionPertenece->getNombre())
                    ->setCellValue('AI' . $i, $arAporte->getCodigoEntidadPensionTraslada())
                    ->setCellValue('AJ' . $i, $arEntidadSaludPertenece->getNombre())
                    ->setCellValue('AK' . $i, $arAporte->getCodigoEntidadSaludTraslada())
                    ->setCellValue('AL' . $i, $arEntidadCajaPertenece->getNombre())
                    ->setCellValue('AM' . $i, $arAporte->getDiasCotizadosPension())
                    ->setCellValue('AN' . $i, $arAporte->getDiasCotizadosSalud())
                    ->setCellValue('AO' . $i, $arAporte->getDiasCotizadosRiesgosProfesionales())
                    ->setCellValue('AP' . $i, $arAporte->getDiasCotizadosCajaCompensacion())
                    ->setCellValue('AQ' . $i, $arAporte->getIbcPension())
                    ->setCellValue('AR' . $i, $arAporte->getIbcSalud())
                    ->setCellValue('AS' . $i, $arAporte->getIbcRiesgosProfesionales())
                    ->setCellValue('AT' . $i, $arAporte->getIbcCaja())
                    ->setCellValue('AU' . $i, $arAporte->getTarifaPension())
                    ->setCellValue('AV' . $i, $arAporte->getTarifaSalud())
                    ->setCellValue('AW' . $i, $arAporte->getTarifaRiesgos())
                    ->setCellValue('AX' . $i, $arAporte->getTarifaCaja())
                    ->setCellValue('AY' . $i, $arAporte->getCotizacionPension())
                    ->setCellValue('AZ' . $i, $arAporte->getCotizacionSalud())
                    ->setCellValue('BA' . $i, $arAporte->getCotizacionRiesgos())
                    ->setCellValue('BB' . $i, $arAporte->getCotizacionCaja())
                    ->setCellValue('BC' . $i, $arAporte->getAporteVoluntarioFondoPensionesObligatorias())
                    ->setCellValue('BD' . $i, $arAporte->getCotizacionVoluntarioFondoPensionesObligatorias())
                    ->setCellValue('BE' . $i, $arAporte->getTotalCotizacion())
                    ->setCellValue('BF' . $i, $arAporte->getAportesFondoSolidaridadPensionalSolidaridad())
                    ;
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Aportes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteAportes.xlsx"');
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

    private function generarVacacionesPagarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO CONTRATO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NÚMERO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'FECHA ULTIMO PAGO')
                    ->setCellValue('J1', 'FECHA ULTIMO VACACIONES')
                    ->setCellValue('K1', 'SALARIO')
                    ->setCellValue('L1', 'VIGENTE');
        $i = 2;
        $query = $em->createQuery($this->strSqlVacacionesPagarLista);
        $arVacacionesPagar = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arVacacionesPagar = $query->getResult();

        foreach ($arVacacionesPagar as $arVacacionesPagar) {
            if ($arVacacionesPagar->getEstadoActivo() == 1){
                $vigente = "SI";
            } else {
                $vigente = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arVacacionesPagar->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arVacacionesPagar->getContratoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arVacacionesPagar->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arVacacionesPagar->getNumero())
                    ->setCellValue('E' . $i, $arVacacionesPagar->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arVacacionesPagar->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arVacacionesPagar->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arVacacionesPagar->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arVacacionesPagar->getFechaUltimoPago()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arVacacionesPagar->getFechaUltimoPagoVacaciones()->format('Y/m/d'))
                    ->setCellValue('K' . $i, $arVacacionesPagar->getVrSalarioPago())
                    ->setCellValue('L' . $i, $vigente);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ReporteVacacionesPorPagar');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteVacacionesPorPagar.xlsx"');
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
    
    private function generarFechaTerminacionExcel() {
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
                    ->setCellValue('B1', 'TIPO CONTRATO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'NÚMERO')
                    ->setCellValue('E1', 'IDENTIFICACIÓN')
                    ->setCellValue('F1', 'EMPLEADO')
                    ->setCellValue('G1', 'CENTRO COSTO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'FECHA ULTIMO PAGO')
                    ->setCellValue('K1', 'FECHA ULTIMO VACACIONES')
                    ->setCellValue('L1', 'SALARIO');
        $i = 2;
        $query = $em->createQuery($this->strSqlFechaTerminacionLista);
        $arFechaTerminaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arFechaTerminaciones = $query->getResult();

        foreach ($arFechaTerminaciones as $arFechaTerminacion) {
            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arFechaTerminacion->getCodigoContratoPk())
                    ->setCellValue('B' . $i, $arFechaTerminacion->getContratoTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arFechaTerminacion->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arFechaTerminacion->getNumero())
                    ->setCellValue('E' . $i, $arFechaTerminacion->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $arFechaTerminacion->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arFechaTerminacion->getCentroCostoRel()->getNombre())
                    ->setCellValue('H' . $i, $arFechaTerminacion->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('I' . $i, $arFechaTerminacion->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('J' . $i, $arFechaTerminacion->getFechaUltimoPago()->format('Y/m/d'))
                    ->setCellValue('K' . $i, $arFechaTerminacion->getFechaUltimoPagoVacaciones()->format('Y/m/d'))
                    ->setCellValue('L' . $i, $arFechaTerminacion->getVrSalarioPago());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('ReporteFechaTerminacion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ReporteFechaTerminacion.xlsx"');
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
