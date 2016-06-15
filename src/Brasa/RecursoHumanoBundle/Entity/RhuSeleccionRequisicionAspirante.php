<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_requisicion_aspirante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRequisicionAspiranteRepository")
 */
class RhuSeleccionRequisicionAspirante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_requisicion_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionRequisicionAspirantePk;
    
    /**
     * @ORM\Column(name="codigo_seleccion_requisito_fk", type="integer", nullable=true)
     */
    private $codigoSeleccionRequisitoFk;
    
    /**
     * @ORM\Column(name="codigo_aspirante_fk", type="integer")
     */
    private $codigoAspiranteFk;

   /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionRequisito", inversedBy="seleccionesRequisicionesAspirantesSeleccionRequisitoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_requisito_fk", referencedColumnName="codigo_seleccion_requisito_pk")
     */
    protected $seleccionRequisitoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAspirante", inversedBy="seleccionesRequisicionesAspirantesAspiranteRel")
     * @ORM\JoinColumn(name="codigo_aspirante_fk", referencedColumnName="codigo_aspirante_pk")
     */
    protected $aspiranteRel;

    

    /**
     * Get codigoSeleccionRequisicionAspirantePk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisicionAspirantePk()
    {
        return $this->codigoSeleccionRequisicionAspirantePk;
    }

    /**
     * Set codigoSeleccionRequisitoFk
     *
     * @param integer $codigoSeleccionRequisitoFk
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setCodigoSeleccionRequisitoFk($codigoSeleccionRequisitoFk)
    {
        $this->codigoSeleccionRequisitoFk = $codigoSeleccionRequisitoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionRequisitoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoFk()
    {
        return $this->codigoSeleccionRequisitoFk;
    }

    /**
     * Set codigoAspiranteFk
     *
     * @param integer $codigoAspiranteFk
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setCodigoAspiranteFk($codigoAspiranteFk)
    {
        $this->codigoAspiranteFk = $codigoAspiranteFk;

        return $this;
    }

    /**
     * Get codigoAspiranteFk
     *
     * @return integer
     */
    public function getCodigoAspiranteFk()
    {
        return $this->codigoAspiranteFk;
    }

    /**
     * Set seleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel = null)
    {
        $this->seleccionRequisitoRel = $seleccionRequisitoRel;

        return $this;
    }

    /**
     * Get seleccionRequisitoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito
     */
    public function getSeleccionRequisitoRel()
    {
        return $this->seleccionRequisitoRel;
    }

    /**
     * Set aspiranteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspiranteRel
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setAspiranteRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspiranteRel = null)
    {
        $this->aspiranteRel = $aspiranteRel;

        return $this;
    }

    /**
     * Get aspiranteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAspirante
     */
    public function getAspiranteRel()
    {
        return $this->aspiranteRel;
    }
}
