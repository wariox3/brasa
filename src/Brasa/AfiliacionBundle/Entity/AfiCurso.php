<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_curso")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCursoRepository")
 */
class AfiCurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoPk;         
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;      
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;             
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;         

    /**
     * @ORM\ManyToOne(targetEntity="AfiCliente", inversedBy="cursosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="AfiCursoDetalle", mappedBy="cursoRel")
     */
    protected $cursosDetallesCursoRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cursosDetallesCursoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCursoPk
     *
     * @return integer
     */
    public function getCodigoCursoPk()
    {
        return $this->codigoCursoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AfiCurso
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiCurso
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiCurso
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return AfiCurso
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel
     *
     * @return AfiCurso
     */
    public function setClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Add cursosDetallesCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel
     *
     * @return AfiCurso
     */
    public function addCursosDetallesCursoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel)
    {
        $this->cursosDetallesCursoRel[] = $cursosDetallesCursoRel;

        return $this;
    }

    /**
     * Remove cursosDetallesCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel
     */
    public function removeCursosDetallesCursoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel)
    {
        $this->cursosDetallesCursoRel->removeElement($cursosDetallesCursoRel);
    }

    /**
     * Get cursosDetallesCursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosDetallesCursoRel()
    {
        return $this->cursosDetallesCursoRel;
    }
}
