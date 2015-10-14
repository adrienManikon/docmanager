<?php

namespace SW\DocManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;
    
        /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="initials", type="string", length=255, nullable=true)
     */
    private $initials;

    /**
     * @var UploadedFile
     *
     */
    private $file;

  /**
   * @ORM\ManyToMany(targetEntity="SW\DocManagerBundle\Entity\Category", cascade={"persist"})
   */
    private $subCategories;
    
    /**
    * @ORM\ManyToOne(targetEntity="SW\DocManagerBundle\Entity\Category")
    * @ORM\JoinColumn(nullable=false)
    */
    private $category;
    
    /**
    * @ORM\ManyToOne(targetEntity="SW\DocManagerBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $creator;
    
    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=255, nullable=true)
     */
    private $format;
    
        /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="disabled", type="boolean")
     */
    private $disabled;
    
    /**
    * @ORM\ManyToOne(targetEntity="SW\DocManagerBundle\Entity\UploadSession", inversedBy="documents")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $uploadSession;

    private $nameAlreadyUsed;
    
    public function __construct() {
        
        $this->date = new \DateTime();
        $this->subCategories = new ArrayCollection();
        $this->nameAlreadyUsed = false;
        
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
     * @param UploadedFile $file
     *
     * @return Document
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * Set creator
     *
     * @param SW\DocManagerBundle\Entity\User $creator
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
     * Get SW\DocManagerBundle\Entity\Category
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
     * @param SW\DocManagerBundle\Entity\Category $category
     */
    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }
    
    /**
     * Get creator
     *
     * @return SW\DocManagerBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }
        
    public function getFormat() {
        return $this->format == null ? '' : $this->format;
    }

    public function setFormat($format) {
        $this->format = $format;
        return $this;
    }

    public function upload($temporary)
    {
        if ($temporary) {
            
            if (null === $this->file) {
                return;
            }
            //$this->creator = $this->file->getClientOriginalName();
            $this->file = $this->file->move($this->getUploadTempDir(), $this->file->getFilename());
            $this->path = $this->file->getFilename();
            $this->alt = $this->code;
            
        } else {
            
            $file = new File($this->getUploadTempDir() . '/' . $this->path);
            
            if (null === $file)
                return;
            
            $file->move($this->getUploadRootDir(), $this->name);
            $this->path = $this->getUploadRootDir() . '/' . $this->name;
        }
        
        return $this;

    }
    
    public function renameFile($newname)
    {
        
        $file = new File($this->getFilePath());

        if (null === $file)
            return;
        
        $file->move($this->getUploadRootDir(), $newname);        
        $this->name = $newname;
        $this->path = $this->getUploadRootDir() . '/' . $newname;
        
        return $this;
        
    }
    
    public function getFilePath()
    {
        return $this->getUploadRootDir() . '/' . $this->name;
    }

    public function getUploadDir()
    {
        return 'uploads/document';
    }
    
    public function getUploadTempDir()
    {
        return 'uploads/temp';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadRootTempDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadTempDir();
    }
    
    public function generateCode($categories = null)
    {
        $this->code = '';
        
        if ($this->category != null) {
            $this->code .= $this->category->getCode();
        }
        
        $categories = $categories == null ? $this->subCategories : $categories;
        
        foreach ($categories as $subCategory) {
            $this->code .= $subCategory->getCode();
        }
        
        return $this;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Document
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Add subCategory
     *
     * @param \SW\DocManagerBundle\Entity\Category $subCategory
     *
     * @return Document
     */
    public function addSubCategory(\SW\DocManagerBundle\Entity\Category $subCategory)
    {
        $this->subCategories[] = $subCategory;

        return $this;
    }

    /**
     * Remove subCategory
     *
     * @param \SW\DocManagerBundle\Entity\Category $subCategory
     */
    public function removeSubCategory(\SW\DocManagerBundle\Entity\Category $subCategory)
    {
        $this->subCategories->removeElement($subCategory);
    }

    /**
     * Set disabled
     *
     * @param boolean $disabled
     *
     * @return Document
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
    
    public function getNameAlreadyUsed() {
        return $this->nameAlreadyUsed;
    }

    public function setNameAlreadyUsed($nameAlreadyUsed) {
        $this->nameAlreadyUsed = $nameAlreadyUsed;
    }



    /**
     * Set uploadSession
     *
     * @param \SW\DocManagerBundle\Entity\UploadSession $uploadSession
     *
     * @return Document
     */
    public function setUploadSession(\SW\DocManagerBundle\Entity\UploadSession $uploadSession = null)
    {
        $this->uploadSession = $uploadSession;

        return $this;
    }

    /**
     * Get uploadSession
     *
     * @return \SW\DocManagerBundle\Entity\UploadSession
     */
    public function getUploadSession()
    {
        return $this->uploadSession;
    }
}
