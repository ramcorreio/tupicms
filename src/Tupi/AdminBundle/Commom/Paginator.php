<?php
namespace Tupi\AdminBundle\Commom;

use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\ORM\EntityManager;

class Paginator extends DoctrinePaginator
{
    private $currentPage;
    private $totalPages;
    private $limit;
    private $name;
    
    /**
     * @see DoctrinePaginator
     */
    public function __construct($query, $fetchJoinCollection = true)
    {
        parent::__construct($query, $fetchJoinCollection);
    }
    
    private function initCache() {
        
        if($this->currentPage === null || $this->currentPage == 1)  {
            $this->name = Util::toAscii($this->getQuery()->getDQL());
            
            $cacheDriver = $this->getQuery()->getEntityManager()->getConfiguration()->getResultCacheImpl();
            
            if($cacheDriver->contains($this->name)) {
                $cacheDriver->delete($this->name);
            }
            
            $this->getQuery()->setResultCacheId($this->name);
            // or shorter nonation with lifetime option
            $this->getQuery()->useResultCache(true, 3600, $this->name);
        }
    }
    
    public function init($page = 1, $limit = 10)
    {
        $this->currentPage = $page;
        $this->limit = $limit;
        if($this->totalPages === null)
        {
            // set total pages
            $this->totalPages = ceil($this->count() / $this->limit);
        }
        
        $this->currentPage = ($this->totalPages > 0 ? $this->currentPage : 0);
        $this->calculatePages();
        $this->initCache();
    }
    
    private function calculatePages()
    {
        $this->getQuery()->setFirstResult($this->currentPage > 1 ? ($this->currentPage - 1) * $this->limit : 0);
        $this->getQuery()->setMaxResults($this->limit);
    }

    /**
     * get current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;   
    }

    /**
     * get total pages
     *
     * @return int
     */
    public function getTotalPages()
    {
    	return $this->totalPages;
    }
    
    /**
     * get limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }
}