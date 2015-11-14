<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_directorio_archivo")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenDirectorioArchivoRepository")
 */
class GenDirectorioArchivo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_directorio_archivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDirectorioArchivoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_directorio_fk", type="integer")
     */
    private $codigoDirectorioFk;

    /**
     * @ORM\Column(name="archivo", type="string", length=50)
     */
    private $archivo;
   
    /**
     * @ORM\ManyToOne(targetEntity="GenDirectorio", inversedBy="directoriosDirectorioArchivoRel")
     * @ORM\JoinColumn(name="codigo_directorio_fk", referencedColumnName="codigo_directorio_pk")
     */
    protected $directorioRel;    
    

    /**
     * Get codigoDirectorioArchivoPk
     *
     * @return integer
     */
    public function getCodigoDirectorioArchivoPk()
    {
        return $this->codigoDirectorioArchivoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenDirectorioArchivo
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
     * Set codigoDirectorioFk
     *
     * @param integer $codigoDirectorioFk
     *
     * @return GenDirectorioArchivo
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
     * Set archivo
     *
     * @param string $archivo
     *
     * @return GenDirectorioArchivo
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set directorioRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDirectorio $directorioRel
     *
     * @return GenDirectorioArchivo
     */
    public function setDirectorioRel(\Brasa\GeneralBundle\Entity\GenDirectorio $directorioRel = null)
    {
        $this->directorioRel = $directorioRel;

        return $this;
    }

    /**
     * Get directorioRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenDirectorio
     */
    public function getDirectorioRel()
    {
        return $this->directorioRel;
    }
}
