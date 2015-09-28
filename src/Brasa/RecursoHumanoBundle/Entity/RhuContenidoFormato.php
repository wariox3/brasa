<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contenido_formato")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContenidoFormatoRepository")
 */
class RhuContenidoFormato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contenido_formato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContenidoFormatoPk;
    
    /**
     * @ORM\Column(name="numero_formato", type="integer", nullable=true)
     */    
    private $numero_formato;    

    /**
     * @ORM\Column(name="titulo", type="string", length=300, nullable=true)
     */    
    private $titulo;     
    
    /**
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */    
    private $contenido; 
    
    /**
     * @ORM\Column(name="codigo_contrato_tipo_fk", type="integer")
     */    
    private $codigoContratoTipoFk;
    
    /**
     * @ORM\Column(name="codigo_disciplinario_tipo_fk", type="integer")
     */    
    private $codigoDisciplinarioTipoFk;

    /**
     * Get codigoContenidoFormatoPk
     *
     * @return integer
     */
    public function getCodigoContenidoFormatoPk()
    {
        return $this->codigoContenidoFormatoPk;
    }

    /**
     * Set numeroFormato
     *
     * @param integer $numeroFormato
     *
     * @return RhuContenidoFormato
     */
    public function setNumeroFormato($numeroFormato)
    {
        $this->numero_formato = $numeroFormato;

        return $this;
    }

    /**
     * Get numeroFormato
     *
     * @return integer
     */
    public function getNumeroFormato()
    {
        return $this->numero_formato;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return RhuContenidoFormato
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return RhuContenidoFormato
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set codigoContratoTipoFk
     *
     * @param integer $codigoContratoTipoFk
     *
     * @return RhuContenidoFormato
     */
    public function setCodigoContratoTipoFk($codigoContratoTipoFk)
    {
        $this->codigoContratoTipoFk = $codigoContratoTipoFk;

        return $this;
    }

    /**
     * Get codigoContratoTipoFk
     *
     * @return integer
     */
    public function getCodigoContratoTipoFk()
    {
        return $this->codigoContratoTipoFk;
    }

    /**
     * Set codigoDisciplinarioTipoFk
     *
     * @param integer $codigoDisciplinarioTipoFk
     *
     * @return RhuContenidoFormato
     */
    public function setCodigoDisciplinarioTipoFk($codigoDisciplinarioTipoFk)
    {
        $this->codigoDisciplinarioTipoFk = $codigoDisciplinarioTipoFk;

        return $this;
    }

    /**
     * Get codigoDisciplinarioTipoFk
     *
     * @return integer
     */
    public function getCodigoDisciplinarioTipoFk()
    {
        return $this->codigoDisciplinarioTipoFk;
    }
}
