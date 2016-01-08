<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_registro_visita")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRegistroVisitaRepository")
 */
class RhuRegistroVisita
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_visita_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRegistroVisitaPk;                    
    
    /**
     * @ORM\Column(name="codigo_tipo_acceso_fk", type="integer", nullable=true)
     */    
    private $codigoTipoAccesoFk;     
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=25, nullable=true)
     */    
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_departamento_empresa_fk", type="integer", nullable=true)
     */    
    private $codigoDepartamentoEmpresaFk;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="motivo", type="string", length=250, nullable=true)
     */    
    private $motivo;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;           
   
    /**
     * @ORM\ManyToOne(targetEntity="RhuTipoAcceso", inversedBy="horarioAccesoTipoAccesoRel")
     * @ORM\JoinColumn(name="codigo_tipo_acceso_fk", referencedColumnName="codigo_tipo_acceso_pk")
     */
    protected $tipoAccesoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDepartamentoEmpresa", inversedBy="registroVisitaDepartamentoEmpresaRel")
     * @ORM\JoinColumn(name="codigo_departamento_empresa_fk", referencedColumnName="codigo_departamento_empresa_pk")
     */
    protected $depatamentoEmpresaRel;

    

    /**
     * Get codigoRegistroVisitaPk
     *
     * @return integer
     */
    public function getCodigoRegistroVisitaPk()
    {
        return $this->codigoRegistroVisitaPk;
    }

    /**
     * Set codigoTipoAccesoFk
     *
     * @param integer $codigoTipoAccesoFk
     *
     * @return RhuRegistroVisita
     */
    public function setCodigoTipoAccesoFk($codigoTipoAccesoFk)
    {
        $this->codigoTipoAccesoFk = $codigoTipoAccesoFk;

        return $this;
    }

    /**
     * Get codigoTipoAccesoFk
     *
     * @return integer
     */
    public function getCodigoTipoAccesoFk()
    {
        return $this->codigoTipoAccesoFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuRegistroVisita
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuRegistroVisita
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigoDepartamentoEmpresaFk
     *
     * @param integer $codigoDepartamentoEmpresaFk
     *
     * @return RhuRegistroVisita
     */
    public function setCodigoDepartamentoEmpresaFk($codigoDepartamentoEmpresaFk)
    {
        $this->codigoDepartamentoEmpresaFk = $codigoDepartamentoEmpresaFk;

        return $this;
    }

    /**
     * Get codigoDepartamentoEmpresaFk
     *
     * @return integer
     */
    public function getCodigoDepartamentoEmpresaFk()
    {
        return $this->codigoDepartamentoEmpresaFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuRegistroVisita
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return RhuRegistroVisita
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuRegistroVisita
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set tipoAccesoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoAcceso $tipoAccesoRel
     *
     * @return RhuRegistroVisita
     */
    public function setTipoAccesoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoAcceso $tipoAccesoRel = null)
    {
        $this->tipoAccesoRel = $tipoAccesoRel;

        return $this;
    }

    /**
     * Get tipoAccesoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuTipoAcceso
     */
    public function getTipoAccesoRel()
    {
        return $this->tipoAccesoRel;
    }

    /**
     * Set depatamentoEmpresaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $depatamentoEmpresaRel
     *
     * @return RhuRegistroVisita
     */
    public function setDepatamentoEmpresaRel(\Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $depatamentoEmpresaRel = null)
    {
        $this->depatamentoEmpresaRel = $depatamentoEmpresaRel;

        return $this;
    }

    /**
     * Get depatamentoEmpresaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa
     */
    public function getDepatamentoEmpresaRel()
    {
        return $this->depatamentoEmpresaRel;
    }
}
