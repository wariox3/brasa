<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_departamento_empresa")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDepartamentoEmpresaRepository")
 */
class RhuDepartamentoEmpresa
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_departamento_empresa_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDepartamentoEmpresaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuRegistroVisita", mappedBy="depatamentoEmpresaRel")
     */
    protected $registroVisitaDepartamentoEmpresaRel;    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registroVisitaDepartamentoEmpresaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDepartamentoEmpresaPk
     *
     * @return integer
     */
    public function getCodigoDepartamentoEmpresaPk()
    {
        return $this->codigoDepartamentoEmpresaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuDepartamentoEmpresa
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
     * Add registroVisitaDepartamentoEmpresaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita $registroVisitaDepartamentoEmpresaRel
     *
     * @return RhuDepartamentoEmpresa
     */
    public function addRegistroVisitaDepartamentoEmpresaRel(\Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita $registroVisitaDepartamentoEmpresaRel)
    {
        $this->registroVisitaDepartamentoEmpresaRel[] = $registroVisitaDepartamentoEmpresaRel;

        return $this;
    }

    /**
     * Remove registroVisitaDepartamentoEmpresaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita $registroVisitaDepartamentoEmpresaRel
     */
    public function removeRegistroVisitaDepartamentoEmpresaRel(\Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita $registroVisitaDepartamentoEmpresaRel)
    {
        $this->registroVisitaDepartamentoEmpresaRel->removeElement($registroVisitaDepartamentoEmpresaRel);
    }

    /**
     * Get registroVisitaDepartamentoEmpresaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistroVisitaDepartamentoEmpresaRel()
    {
        return $this->registroVisitaDepartamentoEmpresaRel;
    }
}
