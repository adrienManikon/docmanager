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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;
    
        /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

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
    * @ORM\JoinColumn(nullable=false)
    */
    private $creator;
    
    /**
    * @ORM\ManyToOne(targetEntity="SW\DocManagerBundle\Entity\Format")
    * @ORM\JoinColumn(nullable=true)
    */
    private $format;
    
        /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;
    
    public function __construct() {
        
        $this->date = new \DateTime();
        $this->subCategories = new ArrayCollection();
        
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
        return $this->format;
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
            $this->alt = $this->name;
            
        } else {
            
            $file = new File($this->getUploadTempDir() . '/' . $this->path);
            
            if (null === $file)
                return;
            
            $file->move($this->getUploadRootDir(), $this->name);
            
        }

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
}

