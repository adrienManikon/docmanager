<?php

namespace SW\DocManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SW\DocManagerBundle\Entity\Format;

/**
 * Description of LoadFormat
 *
 * @author adrien.manikon
 */
class LoadFormat implements FixtureInterface
{
    public function load(ObjectManager $manager) {
        
        $formats = array(
            array(
                'name' => 'pdf',
                'icon' => 'file-pdf'
            ),
            array(
                'name' => 'png',
                'icon' => 'file-image'
            ),
            array(
                'name' => 'jpeg',
                'icon' => 'file-image'
            ),
        );
        
        foreach ($formats as $formatData) {
            
            $format = new Format();
            $format->setIcon($formatData['icon']);
            $format->setName($formatData['name']);
            
            $manager->persist($format);
        }
        
        $manager->flush();
        
    }
}
