<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_programacion_pago_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProgramacionPagoTipoRepository")
 */
class RhuProgramacionPagoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pago_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPagoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */         
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPago", mappedBy="programacionPagoTipoRel")
     */
    protected $programacionesPagosProgramacionPagoTipoRel;    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesPagosProgramacionPagoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProgramacionPagoTipoPk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoTipoPk()
    {
        return $this->codigoProgramacionPagoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuProgramacionPagoTipo
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
     * Add programacionesPagosProgramacionPagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosProgramacionPagoTipoRel
     *
     * @return RhuProgramacionPagoTipo
     */
    public function addProgramacionesPagosProgramacionPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosProgramacionPagoTipoRel)
    {
        $this->programacionesPagosProgramacionPagoTipoRel[] = $programacionesPagosProgramacionPagoTipoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosProgramacionPagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosProgramacionPagoTipoRel
     */
    public function removeProgramacionesPagosProgramacionPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosProgramacionPagoTipoRel)
    {
        $this->programacionesPagosProgramacionPagoTipoRel->removeElement($programacionesPagosProgramacionPagoTipoRel);
    }

    /**
     * Get programacionesPagosProgramacionPagoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosProgramacionPagoTipoRel()
    {
        return $this->programacionesPagosProgramacionPagoTipoRel;
    }
}
