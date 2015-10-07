<?php

namespace SW\DocManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * UploadSession
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UploadSession
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
     * @var array
     *
     * @ORM\Column(name="documents", type="array")
     */
    private $documents;
    
     /**
     * @var string
     *
     * @ORM\Column(name="category", type="string")
     */
    private $category;
    
     /**
     * @var string
     *
     * @ORM\Column(name="subcategory1", type="string")
     */
    private $subcategory1;
    
     /**
     * @var string
     *
     * @ORM\Column(name="subcategory2", type="string")
     */
    private $subcategory2;
    
     /**
     * @var string
     *
     * @ORM\Column(name="subcategory3", type="string")
     */
    private $subcategory3;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="publish", type="bolean")
     */
    private $publish;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

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
     * Set documents
     *
     * @param array $documents
     *
     * @return UploadSession
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * Get documents
     *
     * @return array
     */
    public function getDocuments()
    {
        return $this->documents;
    }
    
    public function getCategory() {
        return $this->category;
    }

    public function getSubcategory1() {
        return $this->subcategory1;
    }

    public function getSubcategory2() {
        return $this->subcategory2;
    }

    public function getSubcategory3() {
        return $this->subcategory3;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    public function setSubcategory1($subcategory1) {
        $this->subcategory1 = $subcategory1;
    }

    public function setSubcategory2($subcategory2) {
        $this->subcategory2 = $subcategory2;
    }

    public function setSubcategory3($subcategory3) {
        $this->subcategory3 = $subcategory3;
    }

    public function getPublish() {
        return $this->publish;
    }

    public function setPublish($publish) {
        $this->publish = $publish;
    }
}

