<?php

namespace Brasa\AdministracionDocumentalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ad_archivo")
 * @ORM\Entity(repositoryClass="Brasa\AdministracionDocumentalBundle\Repository\AdArchivoRepository")
 */
class AdArchivo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_archivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoArchivoPk;    

    /**
     * @ORM\Column(name="codigo_archivo_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoArchivoTipoFk;     

    /**
     * @ORM\Column(name="codigo_directorio_fk", type="integer", nullable=true)
     */    
    private $codigoDirectorioFk;    
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=250, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="extensionOriginal", type="string", length=250, nullable=true)
     */    
    private $extensionOriginal;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=250, nullable=true)
     */    
    private $tipo;            
    
    /**
     * @ORM\Column(name="tamano", type="float", nullable=true)
     */    
    private $tamano = 0;         
    
    /**
     * @ORM\ManyToOne(targetEntity="AdArchivoTipo", inversedBy="archivosArchivoTipoRel")
     * @ORM\JoinColumn(name="codigo_archivo_tipo_fk", referencedColumnName="codigo_archivo_tipo_pk")
     */
    protected $archivoTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AdDirectorio", inversedBy="archivosDirectorioRel")
     * @ORM\JoinColumn(name="codigo_directorio_fk", referencedColumnName="codigo_directorio_pk")
     */
    protected $directorioRel;     
    
    /**
     * Get codigoArchivoPk
     *
     * @return integer
     */
    public function getCodigoArchivoPk()
    {
        return $this->codigoArchivoPk;
    }

    /**
     * Set codigoArchivoTipoFk
     *
     * @param integer $codigoArchivoTipoFk
     *
     * @return AdArchivo
     */
    public function setCodigoArchivoTipoFk($codigoArchivoTipoFk)
    {
        $this->codigoArchivoTipoFk = $codigoArchivoTipoFk;

        return $this;
    }

    /**
     * Get codigoArchivoTipoFk
     *
     * @return integer
     */
    public function getCodigoArchivoTipoFk()
    {
        return $this->codigoArchivoTipoFk;
    }

    /**
     * Set archivoTipoRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdArchivoTipo $archivoTipoRel
     *
     * @return AdArchivo
     */
    public function setArchivoTipoRel(\Brasa\AdministracionDocumentalBundle\Entity\AdArchivoTipo $archivoTipoRel = null)
    {
        $this->archivoTipoRel = $archivoTipoRel;

        return $this;
    }

    /**
     * Get archivoTipoRel
     *
     * @return \Brasa\AdministracionDocumentalBundle\Entity\AdArchivoTipo
     */
    public function getArchivoTipoRel()
    {
        return $this->archivoTipoRel;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AdArchivo
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
     * Set extensionOriginal
     *
     * @param string $extensionOriginal
     *
     * @return AdArchivo
     */
    public function setExtensionOriginal($extensionOriginal)
    {
        $this->extensionOriginal = $extensionOriginal;

        return $this;
    }

    /**
     * Get extensionOriginal
     *
     * @return string
     */
    public function getExtensionOriginal()
    {
        return $this->extensionOriginal;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return AdArchivo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return AdArchivo
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set tamano
     *
     * @param float $tamano
     *
     * @return AdArchivo
     */
    public function setTamano($tamano)
    {
        $this->tamano = $tamano;

        return $this;
    }

    /**
     * Get tamano
     *
     * @return float
     */
    public function getTamano()
    {
        return $this->tamano;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return AdArchivo
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set codigoDirectorioFk
     *
     * @param integer $codigoDirectorioFk
     *
     * @return AdArchivo
     */
    public function setCodigoDirectorioFk($codigoDirectorioFk)
    {
        $this->codigoDirectorioFk = $codigoDirectorioFk;

        return $this;
    }

    /**
     * Get codigoDirectorioFk
     *
     * @return integer
     */
    public function getCodigoDirectorioFk()
    {
        return $this->codigoDirectorioFk;
    }

    /**
     * Set directorioRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdDirectorio $directorioRel
     *
     * @return AdArchivo
     */
    public function setDirectorioRel(\Brasa\AdministracionDocumentalBundle\Entity\AdDirectorio $directorioRel = null)
    {
        $this->directorioRel = $directorioRel;

        return $this;
    }

    /**
     * Get directorioRel
     *
     * @return \Brasa\AdministracionDocumentalBundle\Entity\AdDirectorio
     */
    public function getDirectorioRel()
    {
        return $this->directorioRel;
    }
}
