<?php

namespace SW\DocManagerBundle\Services;

use DateTime;
/**
 * Description of DateService
 *
 * @author adrien.manikon
 */
class DateService {
    
    const DATE_FORMAT = "d.m.Y";
    
    public function dateToString(DateTime $date) {
        
        return $date->format(self::DATE_FORMAT);
        
    }
    
    public function StringToDate($date) {
        
        return DateTime::createFromFormat(self::DATE_FORMAT, $date);
        
    }
}
