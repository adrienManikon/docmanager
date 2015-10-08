<?php

namespace SW\DocManagerBundle\Entity;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * UploadSession
 *
 */
class UploadSession
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var array
     *
     */
    private $documents;
    
     /**
     * @var Category
     *
     */
    private $category;
    
     /**
     * @var Category
     */
    private $subcategory1;
    
     /**
     * @var Category
     *
     */
    private $subcategory2;
    
     /**
     * @var Category
     *
     */
    private $subcategory3;
    
    /**
     * @var boolean
     *
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
    
    public function getCode() {
        $code = '';
        
        if ($this->category != null) {
            $code .= $this->category->getCode();
        }
        if ($this->subcategory1 != null) {
            $code .= $this->subcategory1->getCode();
        }
        if ($this->subcategory2 != null) {
            $code .= $this->subcategory2->getCode();
        }
        if ($this->subcategory3 != null) {
            $code .= $this->subcategory3->getCode();
        }     
        
        return $code;
    }    
}

