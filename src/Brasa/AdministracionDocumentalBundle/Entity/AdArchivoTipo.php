<?php

namespace Brasa\AdministracionDocumentalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ad_archivo_tipo")
 * @ORM\Entity(repositoryClass="Brasa\AdministracionDocumentalBundle\Repository\AdArchivoTipoRepository")
 */
class AdArchivoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_archivo_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoArchivoTipoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;  
    
    /**
     * @ORM\OneToMany(targetEntity="AdArchivo", mappedBy="archivoTipoRel")
     */
    protected $archivosArchivoTipoRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivosArchivoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoArchivoTipoPk
     *
     * @return integer
     */
    public function getCodigoArchivoTipoPk()
    {
        return $this->codigoArchivoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AdArchivoTipo
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
     * Add archivosArchivoTipoRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosArchivoTipoRel
     *
     * @return AdArchivoTipo
     */
    public function addArchivosArchivoTipoRel(\Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosArchivoTipoRel)
    {
        $this->archivosArchivoTipoRel[] = $archivosArchivoTipoRel;

        return $this;
    }

    /**
     * Remove archivosArchivoTipoRel
     *
     * @param \Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosArchivoTipoRel
     */
    public function removeArchivosArchivoTipoRel(\Brasa\AdministracionDocumentalBundle\Entity\AdArchivo $archivosArchivoTipoRel)
    {
        $this->archivosArchivoTipoRel->removeElement($archivosArchivoTipoRel);
    }

    /**
     * Get archivosArchivoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArchivosArchivoTipoRel()
    {
        return $this->archivosArchivoTipoRel;
    }
}
