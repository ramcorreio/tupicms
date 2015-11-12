<?php
namespace Tupi\AdminBundle\Types;

class StatusType extends EnumType
{
	const ACTIVE = 'active';
	
	const INACTIVE = 'inactive';
	
	const WAIT = 'wait';
	
    protected $name = 'status';
    protected $values = array(self::ACTIVE, self::INACTIVE, self::WAIT);
    private $labels = array(self::ACTIVE => 'Ativo', self::INACTIVE => 'Inativo', self::WAIT => 'Pendente');
    
    public static function getTypeLabel($status)
    {
        $type = StatusType::getType('status');
        return $type->getLabel($status);
    }
    
    private function getLabel($status)
    {
        return $this->labels[$status];
    }
}