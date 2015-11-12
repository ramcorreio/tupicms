<?php

namespace Tupi\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CrudRepository 
 */
class CrudRepository extends EntityRepository
{
	public function listItens($key = 'item', $first = 0, $max = 10) {
		 
		$builder = $this->createQueryBuilder($key);
		$builder->setFirstResult( $first );
		$builder->setMaxResults( $max );
		$builder->orderBy("{$key}.id","DESC");
		return $builder;
	}
	
	public function removeAll($key = 'item', array $values) 
	{
		$qb = $this->createQueryBuilder($key);
		$qb->where($qb->expr()->in($key . '.id', $values));
		
		$rs = 0;
		foreach($qb->getQuery()->getResult() as $media) 
		{
			$this->_em->remove($media);
			$rs++;
		}
		
		return $rs;
	}
	
	public function remove($entity)
	{
		$this->_em->remove($entity);
	}
	
	public function persist($entity)
	{
		$this->_em->persist($entity);
	}
	
	public function update($entity)
	{
		return $this->_em->merge($entity);
	}
	
	public function flush($entity = null)
	{
		return $this->_em->flush($entity);
	}
}
