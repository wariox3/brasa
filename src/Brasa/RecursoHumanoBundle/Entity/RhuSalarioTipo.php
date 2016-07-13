<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_salario_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSalarioTipoRepository")
 */
class RhuSalarioTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_salario_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSalarioTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre; 

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="salarioTipoRel")
     */
    protected $contratosSalarioRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosSalarioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSalarioTipoPk
     *
     * @return integer
     */
    public function getCodigoSalarioTipoPk()
    {
        return $this->codigoSalarioTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSalarioTipo
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
     * Add contratosSalarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioRel
     *
     * @return RhuSalarioTipo
     */
    public function addContratosSalarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioRel)
    {
        $this->contratosSalarioRel[] = $contratosSalarioRel;

        return $this;
    }

    /**
     * Remove contratosSalarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioRel
     */
    public function removeContratosSalarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioRel)
    {
        $this->contratosSalarioRel->removeElement($contratosSalarioRel);
    }

    /**
     * Get contratosSalarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSalarioRel()
    {
        return $this->contratosSalarioRel;
    }
}
