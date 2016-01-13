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
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()
            ->add('BtnInactivarContrato', 'submit', array('label'  => 'Inactivar',))
            ->add('BtnEliminarEmpleadoEstudio', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnEliminarEmpleadoFamilia', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
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
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'control' => 1 ));
        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        if($form->isValid()) {
            if($form->get('BtnInactivarContrato')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarContrato');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoContrato) {
                        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
                        $arContratos->setEstadoActivo(0);
                        $em->persist($arContratos);
                        $em->flush();
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
            $strRutaImagen = "/almacenamiento/imagenes/empleados/" . $arEmpleado->getRutaFoto();
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
                    'strRutaImagen' => $strRutaImagen,
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
            $boolErrores = 0;
            $arrControles = $request->request->All();
            $arEmpleado = $form->getData();
            $arEmpleado->setNombreCorto($arEmpleado->getNombre1() . " " . $arEmpleado->getNombre2() . " " .$arEmpleado->getApellido1() . " " . $arEmpleado->getApellido2());
            if ($arEmpleado->getLibretaMilitar() <> 0){
                $arEmpleado->setLibretaMilitar($arEmpleado->getNumeroIdentificacion());
            }
            else {
                $arEmpleado->setLibretaMilitar("");
            }

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
                $strRuta = $arConfiguracion->getRutaAlmacenamiento() . "imagenes/empleados/" . $objArchivo->getClientOriginalName();
                if(!file_exists($strRuta)) {
                    $form['attachment']->getData()->move($arConfiguracion->getRutaAlmacenamiento() . "imagenes/empleados", $objArchivo->getClientOriginalName());
                    $arEmpleado->setRutaFoto($objArchivo->getClientOriginalName());
                    $em->persist($arEmpleado);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $arEmpleado->setRutaFoto($objArchivo->getClientOriginalName());
                    $em->persist($arEmpleado);
                    $em->flush();
                    echo "El archivo " . $strRuta . " ya existe";
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
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
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
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroEmpleadoActivo'),
                $session->get('filtroIdentificacion'),
                ""
                );
    }

    private function generarExcel() {
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
                $subtipoCotizante = $arEmpleado->getSubtipoCotizacion()->getNombre();
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
                    ->setCellValue('V' . $i, $arEmpleado->getEmpleadoEstudioTipoRel()->getNombre())
                    ->setCellValue('W' . $i, $entidadSalud)
                    ->setCellValue('X' . $i, $entidadPension)
                    ->setCellValue('Y' . $i, $entidadCaja)
                    ->setCellValue('Z' . $i, $clasificacionRiesgo)
                    ->setCellValue('AA' . $i, $arEmpleado->getCuenta())
                    ->setCellValue('AB' . $i, $arEmpleado->getBancoRel()->getNombre())
                    ->setCellValue('AC' . $i, $arEmpleado->getVrSalario())
                    ->setCellValue('AD' . $i, $arEmpleado->getFechaContrato())
                    ->setCellValue('AE' . $i, $arEmpleado->getFechaFinalizaContrato())
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

}
