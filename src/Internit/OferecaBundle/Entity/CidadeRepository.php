<?php

namespace Internit\OferecaBundle\Entity;

use Tupi\AdminBundle\Entity\CrudRepository;

class CidadeRepository extends CrudRepository
{
	public function getCidadeByName($name, $estado){
		$builder = $this->createQueryBuilder("c")
		->select("c.id, c.nome")
		->where("c.estado = :estado")
		->setParameter("estado", $estado)
		->andWhere("c.nome LIKE :name")
		->setParameter("name", $name)
		->getQuery();
		
		return $builder->getSingleResult();
	}
}