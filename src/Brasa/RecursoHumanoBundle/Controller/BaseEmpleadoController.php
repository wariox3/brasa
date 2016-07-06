<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoType;
use Doctrine\ORM\EntityRepository;

class BaseEmpleadoController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoEmpleado = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEmpleado();
                $objFormatoEmpleado->Generar($this, $this->strSqlLista);

            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcelInterfaz();
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleado) {
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
                        if($arEmpleado->getEstadoActivo() == 1) {
                            $arEmpleado->setEstadoActivo(0);
                        } else {
                            $arEmpleado->setEstadoActivo(1);
                        }
                        $em->persist($arEmpleado);
                    }
                    $em->flush();
                }
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:lista.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }

    public function detalleAction($codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnInactivarContrato', 'submit', array('label'  => 'Inactivar',))
            ->add('BtnEliminarEmpleadoEstudio', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnEliminarEmpleadoFamilia', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        //inicio - permiso para ver el salario del empleado        
        $permisoVerSalario = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),2);
        //fin - permiso para ver el salario del empleado
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
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'control' => 1 ));
        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arAdicionalesPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arAdicionalesPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'permanente' => 1));
        if($form->isValid()) {
            if($form->get('BtnInactivarContrato')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarContrato');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoContrato) {
                        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                        if ($arContratos->getEstadoActivo() == 0 && $arContratos->getIndefinido() == 0){
                            $objMensaje->Mensaje("error", "El contrato " . $codigoContrato . " ya esta activo", $this);
                        } else {
                            $arContratos->setEstadoActivo(0);
                            $arContratos->setIndefinido(0);
                            $arContratos->setEstadoLiquidado(0);
                            $arContratos->setCodigoMotivoTerminacionContratoFk(8);
                            $arEmpleado->setCodigoCentroCostoFk(NULL);
                            $arEmpleado->setCodigoTipoTiempoFk(NULL);
                            $arEmpleado->setVrSalario(0);
                            $arEmpleado->setCodigoClasificacionRiesgoFk(NULL);
                            $arEmpleado->setCodigoCargoFk(NULL);
                            $arEmpleado->setCargoDescripcion(NULL);
                            $arEmpleado->setCodigoTipoPensionFk(NULL);
                            $arEmpleado->setCodigoTipoCotizanteFk(NULL);
                            $arEmpleado->setCodigoSubtipoCotizanteFk(NULL);
                            $arEmpleado->setCodigoEntidadSaludFk(NULL);
                            $arEmpleado->setCodigoEntidadPensionFk(NULL);
                            $arEmpleado->setCodigoEntidadCajaFk(NULL);
                            $arEmpleado->setEstadoContratoActivo(0);
                            $arEmpleado->setCodigoContratoActivoFk(NULL);
                            $arEmpleado->setCodigoContratoUltimoFk($codigoContrato);
                            $em->persist($arContratos);
                            $em->flush();
                        }
                        
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }

            if($form->get('BtnEliminarEmpleadoEstudio')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarEmpleadoEstudio');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleadoEstudio) {
                        $arEmpleadoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                        $arEmpleadoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEmpleadoEstudio);
                        $em->remove($arEmpleadoEstudios);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }

            if($form->get('BtnEliminarEmpleadoFamilia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarEmpleadoFamilia');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleadoFamilia) {
                        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
                        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->find($codigoEmpleadoFamilia);
                        $em->remove($arEmpleadoFamilia);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }

            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoHojaVida = new \Brasa\RecursoHumanoBundle\Formatos\FormatoHojaVida();
                $objFormatoHojaVida->Generar($this, $codigoEmpleado);
            }
        }
        $strRutaImagen = "";
        if($arEmpleado->getRutaFoto() != "") {
            $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
            $strRutaImagen = $arConfiguracion->getRutaImagenes()."empleados/" . $arEmpleado->getRutaFoto();
        }
        $arIncapacidades = $paginator->paginate($arIncapacidades, $this->get('request')->query->get('page', 1),5);
        $arVacaciones = $paginator->paginate($arVacaciones, $this->get('request')->query->get('page', 1),5);
        $arLicencias = $paginator->paginate($arLicencias, $this->get('request')->query->get('page', 1),5);
        $arContratos = $paginator->paginate($arContratos, $this->get('request')->query->get('page', 1),5);
        $arCreditos = $paginator->paginate($arCreditos, $this->get('request')->query->get('page', 1),5);
        $arDisciplinarios = $paginator->paginate($arDisciplinarios, $this->get('request')->query->get('page', 1),5);
        $arEmpleadoEstudios = $paginator->paginate($arEmpleadoEstudios, $this->get('request')->query->get('page', 1),6);
        $arExamenes = $paginator->paginate($arExamenes, $this->get('request')->query->get('page', 1),6);
        $arEmpleadoFamilia = $paginator->paginate($arEmpleadoFamilia, $this->get('request')->query->get('page', 1),8);
        $arDotacion = $paginator->paginate($arDotacion, $this->get('request')->query->get('page', 1),8);
        $arAdicionalesPago = $paginator->paginate($arAdicionalesPago, $this->get('request')->query->get('page', 1),8);
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:detalle.html.twig', array(
                    'arEmpleado' => $arEmpleado,
                    'arIncapacidades' => $arIncapacidades,
                    'arVacaciones' => $arVacaciones,
                    'arLicencias' => $arLicencias,
                    'arContratos' => $arContratos,
                    'arCreditos' => $arCreditos,
                    'arDisciplinarios' => $arDisciplinarios,
                    'arEmpleadoEstudios' => $arEmpleadoEstudios,
                    'arExamenes' => $arExamenes,
                    'arEmpleadoFamilia' => $arEmpleadoFamilia,
                    'arDotacion' => $arDotacion,
                    'arAdicionalesPago' => $arAdicionalesPago,
                    'strRutaImagen' => $strRutaImagen,
                    'permisoVerSalario' => $permisoVerSalario,
                    'form' => $form->createView()
                    ));
    }

    public function nuevoAction($codigoEmpleado, $codigoSeleccion = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion;
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if($codigoEmpleado != 0) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        } else {
            if($codigoSeleccion != 0) {
                $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                $arEmpleado->setTipoIdentificacionRel($arSeleccion->getTipoIdentificacionRel());
                $arEmpleado->setNumeroIdentificacion($arSeleccion->getNumeroIdentificacion());
                $arEmpleado->setNombre1($arSeleccion->getNombre1());
                $arEmpleado->setNombre2($arSeleccion->getNombre2());
                $arEmpleado->setApellido1($arSeleccion->getApellido1());
                $arEmpleado->setApellido2($arSeleccion->getApellido2());
                $arEmpleado->setEstadoCivilRel($arSeleccion->getEstadoCivilRel());
                $arEmpleado->setFechaNacimiento($arSeleccion->getFechaNacimiento());
                $arEmpleado->setTelefono($arSeleccion->getTelefono());
                $arEmpleado->setCelular($arSeleccion->getCelular());
                $arEmpleado->setCorreo($arSeleccion->getCorreo());
                $arEmpleado->setDireccion($arSeleccion->getDireccion());
                $arEmpleado->setBarrio($arSeleccion->getBarrio());
                $arEmpleado->setCiudadRel($arSeleccion->getCiudadRel());
                $arEmpleado->setCiudadExpedicionRel($arSeleccion->getCiudadExpedicionRel());
                $arEmpleado->setCiudadNacimientoRel($arSeleccion->getCiudadNacimientoRel());
                $arEmpleado->setCodigoSexoFk($arSeleccion->getCodigoSexoFk());
            }
            $arEmpleado->setVrSalario(0); //Parametrizar con configuracion salario minimo
            if($request->request->get('ChkCabezaHogar')){
               $arEmpleado->setCabezaHogar(1);
            }
        }
        $form = $this->createForm(new RhuEmpleadoType(), $arEmpleado);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $boolErrores = 0;
            $arrControles = $request->request->All();
            $arEmpleado = $form->getData();
            if($codigoEmpleado == 0) {
                $arEmpleado->setCodigoUsuario($arUsuario->getUserName());
            }
            $arEmpleado->setNombreCorto($arEmpleado->getNombre1() . " " . $arEmpleado->getNombre2() . " " .$arEmpleado->getApellido1() . " " . $arEmpleado->getApellido2());
            if ($arEmpleado->getCodigoTipoLibreta() != 0){
                $arEmpleado->setLibretaMilitar($arEmpleado->getNumeroIdentificacion());
            }
            else {
                $arEmpleado->setLibretaMilitar('');
            }
            $arEmpleado->setCodigoTipoLibreta($arEmpleado->getCodigoTipoLibreta());
            if($arEmpleado->getCuenta() != "") {
                if (strlen($arEmpleado->getCuenta()) != $arEmpleado->getBancoRel()->getNumeroDigitos()){
                    $objMensaje->Mensaje("error", "El numero de digitos son (". $arEmpleado->getBancoRel()->getNumeroDigitos() .") para el banco ". $arEmpleado->getBancoRel()->getNombre(), $this);
                    $boolErrores = 1;
                }
            }
            if($boolErrores == 0) {
                //Calculo edad
                    $varFechaNacimientoAnio = $arEmpleado->getFechaNacimiento()->format('Y');
                    $varFechaNacimientoMes = $arEmpleado->getFechaNacimiento()->format('m');
                    $varMesActual = date('m');
                    if ($varMesActual >= $varFechaNacimientoMes){
                        $varEdad = date('Y') - $varFechaNacimientoAnio;
                    } else {
                        $varEdad = date('Y') - $varFechaNacimientoAnio -1;
                    }
                //Fin calculo edad
                $intEdadEmpleado = $arConfiguracion->getEdadMinimaEmpleado();
                if ($varEdad < $intEdadEmpleado){
                    $objMensaje->Mensaje("error", "El empleado debe ser mayor de " .$intEdadEmpleado. " años!", $this);
                }else{
                    $em->persist($arEmpleado);
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_base_empleados_nuevo', array('codigoEmpleado' => 0, 'codigoSeleccion' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_base_empleados_lista'));
                    }
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }

    public function enlazarAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioEnlazar();
        $form->handleRequest($request);
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                if($form->get('TxtIdentificacion')->getData() != "") {
                    $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('numeroIdentificacion' => $form->get('TxtIdentificacion')->getData()));
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:enlazar.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'form' => $form->createView()));
    }

    public function cargarFotoAction($codigoEmpleado) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioCargarFoto();
        $form->handleRequest($request);
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $objArchivo = $form['attachment']->getData();
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strNombreArchivo = $arEmpleado->getCodigoEmpleadoPk() . "_" . $objArchivo->getClientOriginalName();
                $strRuta = $arConfiguracion->getRutaAlmacenamiento() . "imagenes/empleados/" . $strNombreArchivo;
                if(!file_exists($strRuta)) {
                    $form['attachment']->getData()->move($arConfiguracion->getRutaAlmacenamiento() . "imagenes/empleados", $strNombreArchivo);
                    $arEmpleado->setRutaFoto($strNombreArchivo);
                    $em->persist($arEmpleado);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $arEmpleado->setRutaFoto($strNombreArchivo);
                    $em->persist($arEmpleado);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }

            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:cargarFoto.html.twig', array('form' => $form->createView()));
    }

    private function formularioEnlazar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionSeleccion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioCargarFoto() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        return $form;
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
            ->add('estadoActivo', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'ACTIVOS', '0' => 'INACTIVOS')))
            ->add('estadoContratado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO')))    
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnInterfaz', 'submit', array('label'  => 'Interfaz',))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnInactivar', 'submit', array('label'  => 'Activar / Inactivar',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroEmpleadoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroEmpleadoContratado', $form->get('estadoContratado')->getData());
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroEmpleadoActivo'),
                $session->get('filtroIdentificacion'),
                "",
                $session->get('filtroEmpleadoContratado')
                );
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AR'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }        

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO')
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
                    ->setCellValue('AC1', 'FECHA CONTRATO')
                    ->setCellValue('AD1', 'FECHA FINALIZA CONTRATO')
                    ->setCellValue('AE1', 'CARGO')
                    ->setCellValue('AF1', 'DESCRIPCIÓN CARGO')
                    ->setCellValue('AG1', 'TIPO PENSIÓN')
                    ->setCellValue('AH1', 'TIPO COTIZANTE')
                    ->setCellValue('AI1', 'SUBTIPO COTIZANTE')
                    ->setCellValue('AJ1', 'ESTADO ACTIVO')
                    ->setCellValue('AK1', 'ESTADO CONTRATO')
                    ->setCellValue('AL1', 'CODIGO CONTRATO')
                    ->setCellValue('AM1', 'TALLA CAMISA')
                    ->setCellValue('AN1', 'TALLA JEANS')
                    ->setCellValue('AO1', 'TALLA CALZADO')
                    ->setCellValue('AP1', 'DEPARTAMENTO')
                    ->setCellValue('AQ1', 'HORARIO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
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
            if ($arEmpleado->getCodigoSubtipoCotizanteFk() == null){
                $subtipoCotizante = "";
            }else{
                $subtipoCotizante = $arEmpleado->getSsoSubtipoCotizanteRel()->getNombre();
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
            if ($arEmpleado->getCodigoDepartamentoEmpresaFk() == null){
                $departamentoEmpresa = "";
            }else{
                $departamentoEmpresa = $arEmpleado->getDepartamentoEmpresaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoHorarioFk() == null){
                $horario = "";
            }else{
                $horario = $arEmpleado->getHorarioRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEmpleadoEstudioTipoFk() == null){
                $empleadoEstudioTipo = "";
            }else{
                $empleadoEstudioTipo = $arEmpleado->getEmpleadoEstudioTipoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                    ->setCellValue('B' . $i, $arEmpleado->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('C' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arEmpleado->getciudadExpedicionRel()->getNombre())
                    ->setCellValue('E' . $i, $arEmpleado->getFechaExpedicionIdentificacion())
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
                    ->setCellValue('Q' . $i, $arEmpleado->getFechaNacimiento())
                    ->setCellValue('R' . $i, $arEmpleado->getCiudadNacimientoRel()->getNombre())
                    ->setCellValue('S' . $i, $arEmpleado->getEstadoCivilRel()->getNombre())
                    ->setCellValue('T' . $i, $padreFamilia)
                    ->setCellValue('U' . $i, $cabezaHogar)
                    ->setCellValue('V' . $i, $empleadoEstudioTipo)
                    ->setCellValue('W' . $i, $entidadSalud)
                    ->setCellValue('X' . $i, $entidadPension)
                    ->setCellValue('Y' . $i, $entidadCaja)
                    ->setCellValue('Z' . $i, $clasificacionRiesgo)
                    ->setCellValue('AA' . $i, $arEmpleado->getCuenta())
                    ->setCellValue('AB' . $i, $arEmpleado->getBancoRel()->getNombre())
                    ->setCellValue('AC' . $i, $arEmpleado->getFechaContrato())
                    ->setCellValue('AD' . $i, $arEmpleado->getFechaFinalizaContrato())
                    ->setCellValue('AE' . $i, $cargo)
                    ->setCellValue('AF' . $i, $arEmpleado->getCargoDescripcion())
                    ->setCellValue('AG' . $i, $tipoPension)
                    ->setCellValue('AH' . $i, $tipoCotizante)
                    ->setCellValue('AI' . $i, $subtipoCotizante)
                    ->setCellValue('AJ' . $i, $estadoActivo)
                    ->setCellValue('AK' . $i, $estadoContratoActivo)
                    ->setCellValue('AL' . $i, $arEmpleado->getCodigoContratoActivoFk())
                    ->setCellValue('AM' . $i, $arEmpleado->getCamisa())
                    ->setCellValue('AN' . $i, $arEmpleado->getJeans())
                    ->setCellValue('AO' . $i, $arEmpleado->getCalzado())
                    ->setCellValue('AP' . $i, $departamentoEmpresa)
                    ->setCellValue('AQ' . $i, $horario);
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
    
    private function generarExcelInterfaz() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'AR'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }        

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'APELLIDO1')
                    ->setCellValue('B1', 'APELLIDO2')
                    ->setCellValue('C1', 'AUTORET')
                    ->setCellValue('D1', 'AVALCOD')
                    ->setCellValue('E1', 'AVALDIREC')
                    ->setCellValue('F1', 'AVALNOMBRE')
                    ->setCellValue('G1', 'AVALTEL')
                    ->setCellValue('H1', 'BANCO')
                    ->setCellValue('I1', 'CALIFCALID')
                    ->setCellValue('J1', 'CALIFTIEMP')
                    ->setCellValue('K1', 'CANAL')
                    ->setCellValue('L1', 'CDCIIU')
                    ->setCellValue('M1', 'CIIU')
                    ->setCellValue('N1', 'CIUDAD')
                    ->setCellValue('O1', 'CIUDADMX')
                    ->setCellValue('P1', 'CIUDADPRV')
                    ->setCellValue('Q1', 'CLASE')
                    ->setCellValue('R1', 'CODALTERNO')
                    ->setCellValue('S1', 'CODCTANIIF')
                    ->setCellValue('T1', 'CODDEPTO')
                    ->setCellValue('U1', 'CODDESFIN')
                    ->setCellValue('V1', 'CODDESFINP')
                    ->setCellValue('W1', 'CODIGOCTA')
                    ->setCellValue('X1', 'CODIGOCTAP')
                    ->setCellValue('Y1', 'CODPOSTAL')
                    ->setCellValue('Z1', 'CODPOSTALP')
                    ->setCellValue('AA1', 'CODPRECIO')
                    ->setCellValue('AB1', 'CODPRECIOP')
                    ->setCellValue('AC1', 'CODRETE')
                    ->setCellValue('AD1', 'CODRETEP')
                    ->setCellValue('AE1', 'CODRUTA')
                    ->setCellValue('AF1', 'COL_DEL')						                
                    ->setCellValue('AG1', 'COMENTARIO')
                    ->setCellValue('AH1', 'CONSUMIDOR')
                    ->setCellValue('AI1', 'CONTACTO')
                    ->setCellValue('AJ1', 'CONTESP')
                    ->setCellValue('AK1', 'CONTRIBUYE')
                    ->setCellValue('AL1', 'CTACTE')
                    ->setCellValue('AM1', 'CTANIIFPRV')		
                    ->setCellValue('AN1', 'CUPOCR')
                    ->setCellValue('AO1', 'CUPOCRP')
                    ->setCellValue('AP1', 'CURP')
                    ->setCellValue('AQ1', 'DELEGACION')
                    ->setCellValue('AR1', 'DESCCOMER')
                    ->setCellValue('AS1', 'DESCCOMERP')
                    ->setCellValue('AT1', 'DESCFINAN')
                    ->setCellValue('AU1', 'DESCFINANP')
                    ->setCellValue('AV1', 'DETALLE')
                    ->setCellValue('AW1', 'DIRECCION')
                    ->setCellValue('AX1', 'EMAIL')
                    ->setCellValue('AY1', 'EMAILP')
                    ->setCellValue('AZ1', 'ENTRECALLE')
                    ->setCellValue('BA1', 'ESCLIENTE')
                    ->setCellValue('BB1', 'ESDECLARA')
                    ->setCellValue('BC1', 'ESMAQUILA')
                    ->setCellValue('BD1', 'ESPERCAR')
                    ->setCellValue('BE1', 'ESPROVEE')						                
                    ->setCellValue('BF1', 'ESTADOMX')
                    ->setCellValue('BG1', 'ESRETCREE')
                    ->setCellValue('BH1', 'ESTADOMX')
                    ->setCellValue('BI1', 'ESTRATO')
                    ->setCellValue('BJ1', 'EXTERIOR')
                    ->setCellValue('BK1', 'FACELECTRO')
                    ->setCellValue('BL1', 'FECHAING')
                    ->setCellValue('BM1', 'FECING')
                    ->setCellValue('BN1', 'FECMOD')
                    ->setCellValue('BO1', 'FECNAC')
                    ->setCellValue('BP1', 'HABILITADO')
                    ->setCellValue('BQ1', 'IDADJUNTOS')
                    ->setCellValue('BR1', 'IDCANALENT')
                    ->setCellValue('BS1', 'IDENTIFICA')
                    ->setCellValue('BT1', 'INDEPENDIE')
                    ->setCellValue('BU1', 'INTCAR')
                    ->setCellValue('BV1', 'ISPROSPECT')
                    ->setCellValue('BW1', 'LOCAL')
                    ->setCellValue('BX1', 'LOCALIDAD')
                    ->setCellValue('BY1', 'MEADENDA')
                    ->setCellValue('BZ1', 'NDIAGRACIA')
                    ->setCellValue('CA1', 'NIT')
                    ->setCellValue('CB1', 'NITSUCUR')
                    ->setCellValue('CC1', 'NOMBRE')
                    ->setCellValue('CD1', 'NOMBRE1')
                    ->setCellValue('CE1', 'NOMBRE2')
                    ->setCellValue('CF1', 'NROENTREGA')
                    ->setCellValue('CG1', 'NROINTERNO')
                    ->setCellValue('CH1', 'NRORESOL')
                    ->setCellValue('CI1', 'NUMCTA')										                
                    ->setCellValue('CJ1', 'NUMCUENTA')
                    ->setCellValue('CK1', 'PAGINAWEB')
                    ->setCellValue('CL1', 'PAIS')
                    ->setCellValue('CM1', 'PAISMX')
                    ->setCellValue('CN1', 'PASSWORD')
                    ->setCellValue('CO1', 'PASSWORDIN')
                    ->setCellValue('CP1', 'PASSWORDMO')
                    ->setCellValue('CQ1', 'PASSWPROV')
                    ->setCellValue('CR1', 'PERIODOFAC')
                    ->setCellValue('CS1', 'PERSONANJ')
                    ->setCellValue('CT1', 'PLAZO')								                
                    ->setCellValue('CU1', 'PLAZOP')
                    ->setCellValue('CV1', 'PORAIU')
                    ->setCellValue('CW1', 'PORAIUP')
                    ->setCellValue('CX1', 'PRETICA')
                    ->setCellValue('CY1', 'PRETICAP')
                    ->setCellValue('CZ1', 'PRETIVA')
                    ->setCellValue('DA1', 'PRETIVAP')
                    ->setCellValue('DB1', 'PRETPERC')
                    ->setCellValue('DC1', 'PRETPERP')
                    ->setCellValue('DD1', 'REGSIMP')
                    ->setCellValue('DE1', 'REPORTDC')
                    ->setCellValue('DF1', 'RESPRETE')
                    ->setCellValue('DG1', 'RETICA')
                    ->setCellValue('DH1', 'RETICAP')
                    ->setCellValue('DI1', 'STADSINCRO')
                    ->setCellValue('DJ1', 'SUCURSAL')
                    ->setCellValue('DK1', 'TEL1')
                    ->setCellValue('DL1', 'TEL2')
                    ->setCellValue('DM1', 'TIDENTI')
                    ->setCellValue('DN1', 'TIPOCAR')
                    ->setCellValue('DO1', 'TIPOCLI')
                    ->setCellValue('DP1', 'TIPOCTA')
                    ->setCellValue('DQ1', 'TIPOCXP')
                    ->setCellValue('DR1', 'TIPOIDEN')
                    ->setCellValue('DS1', 'TIPOPER')
                    ->setCellValue('DT1', 'TIPOPRV')
                    ->setCellValue('DU1', 'VENDEDOR')
                    ->setCellValue('DV1', 'ZONA');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();        
        $arEmpleados = $query->getResult();
        foreach ($arEmpleados as $arEmpleado) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getApellido1())
                    ->setCellValue('B' . $i, $arEmpleado->getApellido2())
                    ->setCellValue('Q' . $i, '13')
                    ->setCellValue('AW' . $i, $arEmpleado->getDireccion())
                    ->setCellValue('AY' . $i, $arEmpleado->getCorreo())
                    ->setCellValue('BE' . $i, 'S')
                    ->setCellValue('CA' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('CC' . $i, $arEmpleado->getNombreCorto())
                    ->setCellValue('CD' . $i, $arEmpleado->getNombre1())
                    ->setCellValue('CE' . $i, $arEmpleado->getNombre2())
                    ->setCellValue('CL' . $i, '169')
                    ;
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
    
}
