<?php

namespace SW\DocManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SW\DocManagerBundle\Entity\Category;

/**
 * Description of LoadCategory
 *
 * @author adrien.manikon
 */
class LoadCategory implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
    $categories = array(
        array(
            'code' => 'A',
            'name' => 'Vertrieb',
        ),
        array(
            'code' => 'B',
            'name' => 'Produktion',   
        ),
        array(
            'code' => 'C',
            'name' => 'Montage', 
        ),
        array(
            'code' => 'D',
            'name' => 'Technik', 
        ),
        array(
            'code' => 'E',
            'name' => 'Übrige',
        ),
        array(
            'code' => 'F',
            'name' => 'luuXa',
        ),
    );
    
    $subcategories = array(
          'Administration',                    
           'Management',            
           'Finance',            
    );
    
    $subsubcategories = array(
        'classic/isolux',
        'madera',
        'imago',
        'Läden',
        'Schiebetüren',
        'Sicherheit',
        'Wärmeschutz',
        'Alubauteile',
        'Zubehör',
    );
    
    $codeSubCat = 'A';
    $codeSubSubCat = 'A';

    foreach ($categories as $parent) {
        
      $categoryParent = new Category();
      $categoryParent->setName($parent['name']);
      $categoryParent->setCode($parent['code']);
      $categoryParent->setMain(true);
      
      $manager->persist($categoryParent);
    }
      
    foreach ($subcategories as $child) {

        $codeSubCat++;
        $subcategory = new Category();
        $subcategory->setName($child);
        $subcategory->setCode($codeSubCat);
        $subcategory->setMain(false);

        $manager->persist($subcategory);

        foreach($subsubcategories as $subchild) {
          $codeSubSubCat++;
          $subsubcategory = new Category();
          $subsubcategory->setName($subchild);
          $subsubcategory->setCode($codeSubSubCat);
          $subsubcategory->setParent($subcategory);
          $subsubcategory->setMain(false);

          $manager->persist($subsubcategory);
        }

    }      
                
    $manager->flush();
  }
    
    
}
