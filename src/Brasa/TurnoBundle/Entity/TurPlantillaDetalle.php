<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_plantilla_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPlantillaDetalleRepository")
 */
class TurPlantillaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_plantilla_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPlantillaDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_plantilla_fk", type="integer")
     */    
    private $codigoPlantillaFk;
    
    /**
     * @ORM\Column(name="posicion", type="integer")
     */    
    private $posicion = 0;    
    
    /**
     * @ORM\Column(name="dia_1", type="string", length=5, nullable=true)
     */    
    private $dia1;    

    /**
     * @ORM\Column(name="dia_2", type="string", length=5, nullable=true)
     */    
    private $dia2;
    
    /**
     * @ORM\Column(name="dia_3", type="string", length=5, nullable=true)
     */    
    private $dia3;
    
    /**
     * @ORM\Column(name="dia_4", type="string", length=5, nullable=true)
     */    
    private $dia4;
    
    /**
     * @ORM\Column(name="dia_5", type="string", length=5, nullable=true)
     */    
    private $dia5;
    
    /**
     * @ORM\Column(name="dia_6", type="string", length=5, nullable=true)
     */    
    private $dia6;
    
    /**
     * @ORM\Column(name="dia_7", type="string", length=5, nullable=true)
     */    
    private $dia7;
    
    /**
     * @ORM\Column(name="dia_8", type="string", length=5, nullable=true)
     */    
    private $dia8;
    
    /**
     * @ORM\Column(name="dia_9", type="string", length=5, nullable=true)
     */    
    private $dia9;
    
    /**
     * @ORM\Column(name="dia_10", type="string", length=5, nullable=true)
     */    
    private $dia10;
    
    /**
     * @ORM\Column(name="dia_11", type="string", length=5, nullable=true)
     */    
    private $dia11;
    
    /**
     * @ORM\Column(name="dia_12", type="string", length=5, nullable=true)
     */    
    private $dia12;
    
    /**
     * @ORM\Column(name="dia_13", type="string", length=5, nullable=true)
     */    
    private $dia13;
    
    /**
     * @ORM\Column(name="dia_14", type="string", length=5, nullable=true)
     */    
    private $dia14;
    
    /**
     * @ORM\Column(name="dia_15", type="string", length=5, nullable=true)
     */    
    private $dia15;
    
    /**
     * @ORM\Column(name="dia_16", type="string", length=5, nullable=true)
     */    
    private $dia16;
    
    /**
     * @ORM\Column(name="dia_17", type="string", length=5, nullable=true)
     */    
    private $dia17;
    
    /**
     * @ORM\Column(name="dia_18", type="string", length=5, nullable=true)
     */    
    private $dia18;
    
    /**
     * @ORM\Column(name="dia_19", type="string", length=5, nullable=true)
     */    
    private $dia19;
    
    /**
     * @ORM\Column(name="dia_20", type="string", length=5, nullable=true)
     */    
    private $dia20;
    
    /**
     * @ORM\Column(name="dia_21", type="string", length=5, nullable=true)
     */    
    private $dia21;
    
    /**
     * @ORM\Column(name="dia_22", type="string", length=5, nullable=true)
     */    
    private $dia22;
    
    /**
     * @ORM\Column(name="dia_23", type="string", length=5, nullable=true)
     */    
    private $dia23;    

    /**
     * @ORM\Column(name="dia_24", type="string", length=5, nullable=true)
     */    
    private $dia24;    
    
    /**
     * @ORM\Column(name="dia_25", type="string", length=5, nullable=true)
     */    
    private $dia25;    
    
    /**
     * @ORM\Column(name="dia_26", type="string", length=5, nullable=true)
     */    
    private $dia26;    
    
    /**
     * @ORM\Column(name="dia_27", type="string", length=5, nullable=true)
     */    
    private $dia27;    
    
    /**
     * @ORM\Column(name="dia_28", type="string", length=5, nullable=true)
     */    
    private $dia28;    
    
    /**
     * @ORM\Column(name="dia_29", type="string", length=5, nullable=true)
     */    
    private $dia29;    
    
    /**
     * @ORM\Column(name="dia_30", type="string", length=5, nullable=true)
     */    
    private $dia30;    
    
    /**
     * @ORM\Column(name="dia_31", type="string", length=5, nullable=true)
     */    
    private $dia31;    

    /**
     * @ORM\Column(name="lunes", type="string", length=5, nullable=true)
     */    
    private $lunes;
    
    /**
     * @ORM\Column(name="martes", type="string", length=5, nullable=true)
     */    
    private $martes;
    
    /**
     * @ORM\Column(name="miercoles", type="string", length=5, nullable=true)
     */    
    private $miercoles;
    
    /**
     * @ORM\Column(name="jueves", type="string", length=5, nullable=true)
     */    
    private $jueves;
    
    /**
     * @ORM\Column(name="viernes", type="string", length=5, nullable=true)
     */    
    private $viernes;
    
    /**
     * @ORM\Column(name="sabado", type="string", length=5, nullable=true)
     */    
    private $sabado;
    
    /**
     * @ORM\Column(name="domingo", type="string", length=5, nullable=true)
     */    
    private $domingo;
    
    /**
     * @ORM\Column(name="festivo", type="string", length=5, nullable=true)
     */    
    private $festivo;    
    
    /**
     * @ORM\Column(name="domingo_festivo", type="string", length=5, nullable=true)
     */    
    private $domingoFestivo;
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPlantilla", inversedBy="plantillasDetallesPlantillaRel")
     * @ORM\JoinColumn(name="codigo_plantilla_fk", referencedColumnName="codigo_plantilla_pk")
     */
    protected $plantillaRel;    
    


    /**
     * Get codigoPlantillaDetallePk
     *
     * @return integer
     */
    public function getCodigoPlantillaDetallePk()
    {
        return $this->codigoPlantillaDetallePk;
    }

    /**
     * Set codigoPlantillaFk
     *
     * @param integer $codigoPlantillaFk
     *
     * @return TurPlantillaDetalle
     */
    public function setCodigoPlantillaFk($codigoPlantillaFk)
    {
        $this->codigoPlantillaFk = $codigoPlantillaFk;

        return $this;
    }

    /**
     * Get codigoPlantillaFk
     *
     * @return integer
     */
    public function getCodigoPlantillaFk()
    {
        return $this->codigoPlantillaFk;
    }

    /**
     * Set dia1
     *
     * @param string $dia1
     *
     * @return TurPlantillaDetalle
     */
    public function setDia1($dia1)
    {
        $this->dia1 = $dia1;

        return $this;
    }

    /**
     * Get dia1
     *
     * @return string
     */
    public function getDia1()
    {
        return $this->dia1;
    }

    /**
     * Set dia2
     *
     * @param string $dia2
     *
     * @return TurPlantillaDetalle
     */
    public function setDia2($dia2)
    {
        $this->dia2 = $dia2;

        return $this;
    }

    /**
     * Get dia2
     *
     * @return string
     */
    public function getDia2()
    {
        return $this->dia2;
    }

    /**
     * Set dia3
     *
     * @param string $dia3
     *
     * @return TurPlantillaDetalle
     */
    public function setDia3($dia3)
    {
        $this->dia3 = $dia3;

        return $this;
    }

    /**
     * Get dia3
     *
     * @return string
     */
    public function getDia3()
    {
        return $this->dia3;
    }

    /**
     * Set dia4
     *
     * @param string $dia4
     *
     * @return TurPlantillaDetalle
     */
    public function setDia4($dia4)
    {
        $this->dia4 = $dia4;

        return $this;
    }

    /**
     * Get dia4
     *
     * @return string
     */
    public function getDia4()
    {
        return $this->dia4;
    }

    /**
     * Set dia5
     *
     * @param string $dia5
     *
     * @return TurPlantillaDetalle
     */
    public function setDia5($dia5)
    {
        $this->dia5 = $dia5;

        return $this;
    }

    /**
     * Get dia5
     *
     * @return string
     */
    public function getDia5()
    {
        return $this->dia5;
    }

    /**
     * Set dia6
     *
     * @param string $dia6
     *
     * @return TurPlantillaDetalle
     */
    public function setDia6($dia6)
    {
        $this->dia6 = $dia6;

        return $this;
    }

    /**
     * Get dia6
     *
     * @return string
     */
    public function getDia6()
    {
        return $this->dia6;
    }

    /**
     * Set dia7
     *
     * @param string $dia7
     *
     * @return TurPlantillaDetalle
     */
    public function setDia7($dia7)
    {
        $this->dia7 = $dia7;

        return $this;
    }

    /**
     * Get dia7
     *
     * @return string
     */
    public function getDia7()
    {
        return $this->dia7;
    }

    /**
     * Set dia8
     *
     * @param string $dia8
     *
     * @return TurPlantillaDetalle
     */
    public function setDia8($dia8)
    {
        $this->dia8 = $dia8;

        return $this;
    }

    /**
     * Get dia8
     *
     * @return string
     */
    public function getDia8()
    {
        return $this->dia8;
    }

    /**
     * Set dia9
     *
     * @param string $dia9
     *
     * @return TurPlantillaDetalle
     */
    public function setDia9($dia9)
    {
        $this->dia9 = $dia9;

        return $this;
    }

    /**
     * Get dia9
     *
     * @return string
     */
    public function getDia9()
    {
        return $this->dia9;
    }

    /**
     * Set dia10
     *
     * @param string $dia10
     *
     * @return TurPlantillaDetalle
     */
    public function setDia10($dia10)
    {
        $this->dia10 = $dia10;

        return $this;
    }

    /**
     * Get dia10
     *
     * @return string
     */
    public function getDia10()
    {
        return $this->dia10;
    }

    /**
     * Set dia11
     *
     * @param string $dia11
     *
     * @return TurPlantillaDetalle
     */
    public function setDia11($dia11)
    {
        $this->dia11 = $dia11;

        return $this;
    }

    /**
     * Get dia11
     *
     * @return string
     */
    public function getDia11()
    {
        return $this->dia11;
    }

    /**
     * Set dia12
     *
     * @param string $dia12
     *
     * @return TurPlantillaDetalle
     */
    public function setDia12($dia12)
    {
        $this->dia12 = $dia12;

        return $this;
    }

    /**
     * Get dia12
     *
     * @return string
     */
    public function getDia12()
    {
        return $this->dia12;
    }

    /**
     * Set dia13
     *
     * @param string $dia13
     *
     * @return TurPlantillaDetalle
     */
    public function setDia13($dia13)
    {
        $this->dia13 = $dia13;

        return $this;
    }

    /**
     * Get dia13
     *
     * @return string
     */
    public function getDia13()
    {
        return $this->dia13;
    }

    /**
     * Set dia14
     *
     * @param string $dia14
     *
     * @return TurPlantillaDetalle
     */
    public function setDia14($dia14)
    {
        $this->dia14 = $dia14;

        return $this;
    }

    /**
     * Get dia14
     *
     * @return string
     */
    public function getDia14()
    {
        return $this->dia14;
    }

    /**
     * Set dia15
     *
     * @param string $dia15
     *
     * @return TurPlantillaDetalle
     */
    public function setDia15($dia15)
    {
        $this->dia15 = $dia15;

        return $this;
    }

    /**
     * Get dia15
     *
     * @return string
     */
    public function getDia15()
    {
        return $this->dia15;
    }

    /**
     * Set dia16
     *
     * @param string $dia16
     *
     * @return TurPlantillaDetalle
     */
    public function setDia16($dia16)
    {
        $this->dia16 = $dia16;

        return $this;
    }

    /**
     * Get dia16
     *
     * @return string
     */
    public function getDia16()
    {
        return $this->dia16;
    }

    /**
     * Set dia17
     *
     * @param string $dia17
     *
     * @return TurPlantillaDetalle
     */
    public function setDia17($dia17)
    {
        $this->dia17 = $dia17;

        return $this;
    }

    /**
     * Get dia17
     *
     * @return string
     */
    public function getDia17()
    {
        return $this->dia17;
    }

    /**
     * Set dia18
     *
     * @param string $dia18
     *
     * @return TurPlantillaDetalle
     */
    public function setDia18($dia18)
    {
        $this->dia18 = $dia18;

        return $this;
    }

    /**
     * Get dia18
     *
     * @return string
     */
    public function getDia18()
    {
        return $this->dia18;
    }

    /**
     * Set dia19
     *
     * @param string $dia19
     *
     * @return TurPlantillaDetalle
     */
    public function setDia19($dia19)
    {
        $this->dia19 = $dia19;

        return $this;
    }

    /**
     * Get dia19
     *
     * @return string
     */
    public function getDia19()
    {
        return $this->dia19;
    }

    /**
     * Set dia20
     *
     * @param string $dia20
     *
     * @return TurPlantillaDetalle
     */
    public function setDia20($dia20)
    {
        $this->dia20 = $dia20;

        return $this;
    }

    /**
     * Get dia20
     *
     * @return string
     */
    public function getDia20()
    {
        return $this->dia20;
    }

    /**
     * Set dia21
     *
     * @param string $dia21
     *
     * @return TurPlantillaDetalle
     */
    public function setDia21($dia21)
    {
        $this->dia21 = $dia21;

        return $this;
    }

    /**
     * Get dia21
     *
     * @return string
     */
    public function getDia21()
    {
        return $this->dia21;
    }

    /**
     * Set dia22
     *
     * @param string $dia22
     *
     * @return TurPlantillaDetalle
     */
    public function setDia22($dia22)
    {
        $this->dia22 = $dia22;

        return $this;
    }

    /**
     * Get dia22
     *
     * @return string
     */
    public function getDia22()
    {
        return $this->dia22;
    }

    /**
     * Set dia23
     *
     * @param string $dia23
     *
     * @return TurPlantillaDetalle
     */
    public function setDia23($dia23)
    {
        $this->dia23 = $dia23;

        return $this;
    }

    /**
     * Get dia23
     *
     * @return string
     */
    public function getDia23()
    {
        return $this->dia23;
    }

    /**
     * Set dia24
     *
     * @param string $dia24
     *
     * @return TurPlantillaDetalle
     */
    public function setDia24($dia24)
    {
        $this->dia24 = $dia24;

        return $this;
    }

    /**
     * Get dia24
     *
     * @return string
     */
    public function getDia24()
    {
        return $this->dia24;
    }

    /**
     * Set dia25
     *
     * @param string $dia25
     *
     * @return TurPlantillaDetalle
     */
    public function setDia25($dia25)
    {
        $this->dia25 = $dia25;

        return $this;
    }

    /**
     * Get dia25
     *
     * @return string
     */
    public function getDia25()
    {
        return $this->dia25;
    }

    /**
     * Set dia26
     *
     * @param string $dia26
     *
     * @return TurPlantillaDetalle
     */
    public function setDia26($dia26)
    {
        $this->dia26 = $dia26;

        return $this;
    }

    /**
     * Get dia26
     *
     * @return string
     */
    public function getDia26()
    {
        return $this->dia26;
    }

    /**
     * Set dia27
     *
     * @param string $dia27
     *
     * @return TurPlantillaDetalle
     */
    public function setDia27($dia27)
    {
        $this->dia27 = $dia27;

        return $this;
    }

    /**
     * Get dia27
     *
     * @return string
     */
    public function getDia27()
    {
        return $this->dia27;
    }

    /**
     * Set dia28
     *
     * @param string $dia28
     *
     * @return TurPlantillaDetalle
     */
    public function setDia28($dia28)
    {
        $this->dia28 = $dia28;

        return $this;
    }

    /**
     * Get dia28
     *
     * @return string
     */
    public function getDia28()
    {
        return $this->dia28;
    }

    /**
     * Set dia29
     *
     * @param string $dia29
     *
     * @return TurPlantillaDetalle
     */
    public function setDia29($dia29)
    {
        $this->dia29 = $dia29;

        return $this;
    }

    /**
     * Get dia29
     *
     * @return string
     */
    public function getDia29()
    {
        return $this->dia29;
    }

    /**
     * Set dia30
     *
     * @param string $dia30
     *
     * @return TurPlantillaDetalle
     */
    public function setDia30($dia30)
    {
        $this->dia30 = $dia30;

        return $this;
    }

    /**
     * Get dia30
     *
     * @return string
     */
    public function getDia30()
    {
        return $this->dia30;
    }

    /**
     * Set dia31
     *
     * @param string $dia31
     *
     * @return TurPlantillaDetalle
     */
    public function setDia31($dia31)
    {
        $this->dia31 = $dia31;

        return $this;
    }

    /**
     * Get dia31
     *
     * @return string
     */
    public function getDia31()
    {
        return $this->dia31;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurPlantillaDetalle
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set plantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel
     *
     * @return TurPlantillaDetalle
     */
    public function setPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel = null)
    {
        $this->plantillaRel = $plantillaRel;

        return $this;
    }

    /**
     * Get plantillaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPlantilla
     */
    public function getPlantillaRel()
    {
        return $this->plantillaRel;
    }

    /**
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return TurPlantillaDetalle
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return integer
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set lunes
     *
     * @param string $lunes
     *
     * @return TurPlantillaDetalle
     */
    public function setLunes($lunes)
    {
        $this->lunes = $lunes;

        return $this;
    }

    /**
     * Get lunes
     *
     * @return string
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * Set martes
     *
     * @param string $martes
     *
     * @return TurPlantillaDetalle
     */
    public function setMartes($martes)
    {
        $this->martes = $martes;

        return $this;
    }

    /**
     * Get martes
     *
     * @return string
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * Set miercoles
     *
     * @param string $miercoles
     *
     * @return TurPlantillaDetalle
     */
    public function setMiercoles($miercoles)
    {
        $this->miercoles = $miercoles;

        return $this;
    }

    /**
     * Get miercoles
     *
     * @return string
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * Set jueves
     *
     * @param string $jueves
     *
     * @return TurPlantillaDetalle
     */
    public function setJueves($jueves)
    {
        $this->jueves = $jueves;

        return $this;
    }

    /**
     * Get jueves
     *
     * @return string
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * Set viernes
     *
     * @param string $viernes
     *
     * @return TurPlantillaDetalle
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes
     *
     * @return string
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set sabado
     *
     * @param string $sabado
     *
     * @return TurPlantillaDetalle
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado
     *
     * @return string
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo
     *
     * @param string $domingo
     *
     * @return TurPlantillaDetalle
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo
     *
     * @return string
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set festivo
     *
     * @param string $festivo
     *
     * @return TurPlantillaDetalle
     */
    public function setFestivo($festivo)
    {
        $this->festivo = $festivo;

        return $this;
    }

    /**
     * Get festivo
     *
     * @return string
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * Set domingoFestivo
     *
     * @param string $domingoFestivo
     *
     * @return TurPlantillaDetalle
     */
    public function setDomingoFestivo($domingoFestivo)
    {
        $this->domingoFestivo = $domingoFestivo;

        return $this;
    }

    /**
     * Get domingoFestivo
     *
     * @return string
     */
    public function getDomingoFestivo()
    {
        return $this->domingoFestivo;
    }
}
