<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurRecursoRepository")
 */
class TurRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recurso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecursoPk;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="nombreCorto", type="string", length=120, nullable=true)
     */    
    private $nombreCorto;    
    
    /**
     * @ORM\Column(name="codigo_recurso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoTipoFk;    
    
    /**     
     * @ORM\Column(name="pago_promedio", type="boolean")
     */    
    private $pagoPromedio = false;    

    /**     
     * @ORM\Column(name="pago_variable", type="boolean")
     */    
    private $pagoVariable = false;                
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", inversedBy="turRecursosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecursoTipo", inversedBy="recursosRecursoTipoRel")
     * @ORM\JoinColumn(name="codigo_recurso_tipo_fk", referencedColumnName="codigo_recurso_tipo_pk")
     */
    protected $recursoTipoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="recursoRel")
     */
    protected $programacionesDetallesRecursoRel;    

   /**
     * @ORM\OneToMany(targetEntity="TurSoportePago", mappedBy="recursoRel")
     */
    protected $soportesPagosRecursoRel;      
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="recursoRel")
     */
    protected $soportesPagosDetallesRecursoRel;            
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleRecurso", mappedBy="recursoRel")
     */
    protected $pedidosDetallesRecursosRecursoRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesDetallesRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosDetallesRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRecursoPk
     *
     * @return integer
     */
    public function getCodigoRecursoPk()
    {
        return $this->codigoRecursoPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return TurRecurso
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return TurRecurso
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set codigoRecursoTipoFk
     *
     * @param integer $codigoRecursoTipoFk
     *
     * @return TurRecurso
     */
    public function setCodigoRecursoTipoFk($codigoRecursoTipoFk)
    {
        $this->codigoRecursoTipoFk = $codigoRecursoTipoFk;

        return $this;
    }

    /**
     * Get codigoRecursoTipoFk
     *
     * @return integer
     */
    public function getCodigoRecursoTipoFk()
    {
        return $this->codigoRecursoTipoFk;
    }

    /**
     * Set pagoPromedio
     *
     * @param boolean $pagoPromedio
     *
     * @return TurRecurso
     */
    public function setPagoPromedio($pagoPromedio)
    {
        $this->pagoPromedio = $pagoPromedio;

        return $this;
    }

    /**
     * Get pagoPromedio
     *
     * @return boolean
     */
    public function getPagoPromedio()
    {
        return $this->pagoPromedio;
    }

    /**
     * Set pagoVariable
     *
     * @param boolean $pagoVariable
     *
     * @return TurRecurso
     */
    public function setPagoVariable($pagoVariable)
    {
        $this->pagoVariable = $pagoVariable;

        return $this;
    }

    /**
     * Get pagoVariable
     *
     * @return boolean
     */
    public function getPagoVariable()
    {
        return $this->pagoVariable;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurRecurso
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
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return TurRecurso
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set recursoTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecursoTipo $recursoTipoRel
     *
     * @return TurRecurso
     */
    public function setRecursoTipoRel(\Brasa\TurnoBundle\Entity\TurRecursoTipo $recursoTipoRel = null)
    {
        $this->recursoTipoRel = $recursoTipoRel;

        return $this;
    }

    /**
     * Get recursoTipoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecursoTipo
     */
    public function getRecursoTipoRel()
    {
        return $this->recursoTipoRel;
    }

    /**
     * Add programacionesDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel
     *
     * @return TurRecurso
     */
    public function addProgramacionesDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel)
    {
        $this->programacionesDetallesRecursoRel[] = $programacionesDetallesRecursoRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel
     */
    public function removeProgramacionesDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel)
    {
        $this->programacionesDetallesRecursoRel->removeElement($programacionesDetallesRecursoRel);
    }

    /**
     * Get programacionesDetallesRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesRecursoRel()
    {
        return $this->programacionesDetallesRecursoRel;
    }

    /**
     * Add soportesPagosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel
     *
     * @return TurRecurso
     */
    public function addSoportesPagosRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel)
    {
        $this->soportesPagosRecursoRel[] = $soportesPagosRecursoRel;

        return $this;
    }

    /**
     * Remove soportesPagosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel
     */
    public function removeSoportesPagosRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel)
    {
        $this->soportesPagosRecursoRel->removeElement($soportesPagosRecursoRel);
    }

    /**
     * Get soportesPagosRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosRecursoRel()
    {
        return $this->soportesPagosRecursoRel;
    }

    /**
     * Add soportesPagosDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel
     *
     * @return TurRecurso
     */
    public function addSoportesPagosDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel)
    {
        $this->soportesPagosDetallesRecursoRel[] = $soportesPagosDetallesRecursoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel
     */
    public function removeSoportesPagosDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel)
    {
        $this->soportesPagosDetallesRecursoRel->removeElement($soportesPagosDetallesRecursoRel);
    }

    /**
     * Get soportesPagosDetallesRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesRecursoRel()
    {
        return $this->soportesPagosDetallesRecursoRel;
    }

    /**
     * Add pedidosDetallesRecursosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel
     *
     * @return TurRecurso
     */
    public function addPedidosDetallesRecursosRecursoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel)
    {
        $this->pedidosDetallesRecursosRecursoRel[] = $pedidosDetallesRecursosRecursoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesRecursosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel
     */
    public function removePedidosDetallesRecursosRecursoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel)
    {
        $this->pedidosDetallesRecursosRecursoRel->removeElement($pedidosDetallesRecursosRecursoRel);
    }

    /**
     * Get pedidosDetallesRecursosRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesRecursosRecursoRel()
    {
        return $this->pedidosDetallesRecursosRecursoRel;
    }
}
