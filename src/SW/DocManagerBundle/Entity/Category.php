<?php

namespace SW\DocManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SW\DocManagerBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;
    
    /**
    * @ORM\ManyToOne(targetEntity="SW\DocManagerBundle\Entity\Category")
    * @ORM\JoinColumn(nullable=true)
    */
    private $parent;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="main", type="boolean", options={"default": false}), nullable=true)
     */
    private $main;
    
    private $classe;
    private $attribut;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Category
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set parent
     *
     * @param \SW\DocManagerBundle\Entity\Category $parent
     *
     * @return Category
     */
    public function setParent(\SW\DocManagerBundle\Entity\Category $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \SW\DocManagerBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set main
     *
     * @param boolean $main
     *
     * @return Category
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }
    
    public function getClasse() {
        return $this->classe;
    }

    public function getAttribut() {
        return $this->attribut;
    }

    public function setClasse($classe) {
        $this->classe = $classe;
    }

    public function setAttribut($attribut) {
        $this->attribut = $attribut;
    }


}
