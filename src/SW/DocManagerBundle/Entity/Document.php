<?php

namespace SW\DocManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SW\DocManagerBundle\Entity\DocumentRepository")
 */
class Document
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="initials", type="string", length=255)
     */
    private $initials;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;

    /**
     * @var array
     *
     * @ORM\Column(name="subCategories", type="array")
     */
    private $subCategories;
    
        /**
     * @var string
     *
     * @ORM\Column(name="category", type="string")
     */
    private $category;

    
    /**
     * @var string
     *
     * @ORM\Column(name="creator", type="string", length=255)
     */
    private $creator;


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
     * Set code
     *
     * @param string $code
     *
     * @return Document
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
     * Set name
     *
     * @param string $name
     *
     * @return Document
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Document
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set initials
     *
     * @param string $initials
     *
     * @return Document
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;

        return $this;
    }

    /**
     * Get initials
     *
     * @return string
     */
    public function getInitials()
    {
        return $this->initials;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Document
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set creator
     *
     * @param string $creator
     *
     * @return Document
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }
    
    /**
     * Get subCategories
     * 
     * @return array
     */
    public function getSubCategories() {
        return $this->subCategories;
    }

    /**
     * Get category
     * 
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set subcategories
     * 
     * @param array $subCategories
     */
    public function setSubCategories($subCategories) {
        $this->subCategories = $subCategories;
    }

    /**
     * Set category
     * 
     * @param string $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }
    
    /**
     * Get creator
     *
     * @return string
     */
    public function getCreator()
    {
        return $this->creator;
    }
}

