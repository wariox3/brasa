<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_directorio")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenDirectorioRepository")
 */
class GenDirectorio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_directorio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDirectorioPk;

    /**
     * @ORM\Column(name="ruta", type="string", length=500)
     */
    private $ruta; 
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="GenDirectorioArchivo", mappedBy="directorioRel")
     */
    protected $directoriosDirectorioArchivoRel;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->directoriosDirectorioArchivoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDirectorioPk
     *
     * @return integer
     */
    public function getCodigoDirectorioPk()
    {
        return $this->codigoDirectorioPk;
    }

    /**
     * Set ruta
     *
     * @param string $ruta
     *
     * @return GenDirectorio
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta
     *
     * @return string
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenDirectorio
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
     * Add directoriosDirectorioArchivoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDirectorioArchivo $directoriosDirectorioArchivoRel
     *
     * @return GenDirectorio
     */
    public function addDirectoriosDirectorioArchivoRel(\Brasa\GeneralBundle\Entity\GenDirectorioArchivo $directoriosDirectorioArchivoRel)
    {
        $this->directoriosDirectorioArchivoRel[] = $directoriosDirectorioArchivoRel;

        return $this;
    }

    /**
     * Remove directoriosDirectorioArchivoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDirectorioArchivo $directoriosDirectorioArchivoRel
     */
    public function removeDirectoriosDirectorioArchivoRel(\Brasa\GeneralBundle\Entity\GenDirectorioArchivo $directoriosDirectorioArchivoRel)
    {
        $this->directoriosDirectorioArchivoRel->removeElement($directoriosDirectorioArchivoRel);
    }

    /**
     * Get directoriosDirectorioArchivoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirectoriosDirectorioArchivoRel()
    {
        return $this->directoriosDirectorioArchivoRel;
    }
}
