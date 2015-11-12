<?php

namespace Internit\OferecaBundle\Entity;

use Tupi\AdminBundle\Entity\CrudRepository;

class EstadoRepository extends CrudRepository
{
	public function getEstadoByUF($uf){
		$builder = $this->createQueryBuilder("e")
		->select("e.id, e.nome")
		->where("e.uf = :uf")
		->setParameter("uf", $uf)
		->getQuery();
		return $builder->getSingleResult();
	}
}