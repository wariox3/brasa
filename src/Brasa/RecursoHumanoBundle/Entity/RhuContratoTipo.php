<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoTipoRepository")
 */
class RhuContratoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\Column(name="codigo_contenido_formato_fk", type="integer", nullable=true)
     */    
    private $codigoContenidoFormatoFk;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="contratoTipoRel")
     */
    protected $contratosContratoTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenContenidoFormato", inversedBy="contratosTiposContenidoFormatoRel")
     * @ORM\JoinColumn(name="codigo_contenido_formato_fk", referencedColumnName="codigo_contenido_formato_pk")
     */
    protected $contenidoFormatoRel;

   
   
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosContratoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContratoTipoPk
     *
     * @return integer
     */
    public function getCodigoContratoTipoPk()
    {
        return $this->codigoContratoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuContratoTipo
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
     * Set codigoContenidoFormatoFk
     *
     * @param integer $codigoContenidoFormatoFk
     *
     * @return RhuContratoTipo
     */
    public function setCodigoContenidoFormatoFk($codigoContenidoFormatoFk)
    {
        $this->codigoContenidoFormatoFk = $codigoContenidoFormatoFk;

        return $this;
    }

    /**
     * Get codigoContenidoFormatoFk
     *
     * @return integer
     */
    public function getCodigoContenidoFormatoFk()
    {
        return $this->codigoContenidoFormatoFk;
    }

    /**
     * Add contratosContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel
     *
     * @return RhuContratoTipo
     */
    public function addContratosContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel)
    {
        $this->contratosContratoTipoRel[] = $contratosContratoTipoRel;

        return $this;
    }

    /**
     * Remove contratosContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel
     */
    public function removeContratosContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel)
    {
        $this->contratosContratoTipoRel->removeElement($contratosContratoTipoRel);
    }

    /**
     * Get contratosContratoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosContratoTipoRel()
    {
        return $this->contratosContratoTipoRel;
    }

    /**
     * Set contenidoFormatoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel
     *
     * @return RhuContratoTipo
     */
    public function setContenidoFormatoRel(\Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel = null)
    {
        $this->contenidoFormatoRel = $contenidoFormatoRel;

        return $this;
    }

    /**
     * Get contenidoFormatoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenContenidoFormato
     */
    public function getContenidoFormatoRel()
    {
        return $this->contenidoFormatoRel;
    }
}
