<?php
namespace Tupi\ContentBundle\Types;

use Tupi\AdminBundle\Types\EnumType;

class PageStatusType extends EnumType
{
	const PUBLISHED = 'published';
	
	const DRAFT = 'draft';
	
	const TO_REVIEW = 'to_review';
	
    protected $name = 'page_status';
    protected $values = array(self::PUBLISHED, self::DRAFT, self::TO_REVIEW);
    private $labels = array(self::PUBLISHED => 'Publicado', self::DRAFT => 'Rascunho', self::TO_REVIEW => 'Pendente Revisão');
    
    public static function getTypeLabel($status)
    {
        $type = PageStatusType::getType('page_status');
        return $type->getLabel($status);
    }
    
    private function getLabel($status)
    {
        return $this->labels[$status];
    }
}