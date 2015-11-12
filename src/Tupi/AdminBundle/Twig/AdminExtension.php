<?php
namespace Tupi\AdminBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

use Tupi\AdminBundle\Types\StatusType;

use Tupi\SecurityBundle\Form\PermissionHelper;
use Tupi\SecurityBundle\Entity\User;
use Tupi\SecurityBundle\Form\Authorization;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Config\FileLocator;

use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;

class AdminExtension extends Twig_Extension
{
	private $container;
	
	/**
	 * Constructor.
	 *
	 * @param ContainerInterface $container The service container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
	
	public function getFunctions()
	{
	    return array(
            new Twig_SimpleFunction('tupi_version', array($this, 'getVersion')), 
    		new Twig_SimpleFunction('drop', array($this, 'dropKey'), array('is_safe' => array('html'))),	    	
	    	new Twig_SimpleFunction('verificarPermissao', array($this, 'verificarPermissao')),
	    	new Twig_SimpleFunction('verificarAcl', array($this, 'verificarAcl')),
	    	new Twig_SimpleFunction('getParameter', array($this, 'getParameter'))
        );
	}
	
    public function getFilters()
    {
    	return array(
    	    new Twig_SimpleFilter('status', array($this, 'getStatus')),
            new Twig_SimpleFilter('validator', array($this, 'validator')),
    	    new Twig_SimpleFilter('has_route', array($this, 'hasRoute'))
    	);
    }
    
    public function getParameter($parameter)
    {
    	return $this->container->getParameter($parameter);
    }
    
    public function getVersion()
    {
    	$encoder = new JsonEncoder();
        $serializer = new Serializer(array(), array($encoder));
        $composer = $serializer->decode(file_get_contents(__DIR__ . '/../../../../composer.json'), 'json');
        
        return $composer['extra']['branch-alias']['dev-master'];
    }
    
    public function dropKey(array $keys, array $array)
    {
    	$values = array();
    	foreach ($keys as $key) {
    		
    		if(array_key_exists($key, $array)) {
    			
    			$values[$key] = $array[$key];
    			unset($array[$key]);
    		}
		}
    	
    	$values['input'] = $array;
    	return $values;
    }
    
    
    public function validator(array $input, $cardinality = 1)
    {
    	$validator = '';
    	for($i = 0, $len = sizeof($input); $i < $len; $i++) {
    		
    		$start = ($i > 0 ? 0 : ($input[$i] == 0 ? 0 : 1));
            $end = ($cardinality > $len ? 9 : $input[$i]);
            $validator .= '[' . $start . '-' . $end . ']';
        }
        
        return $validator;
    }
    
    public function getStatus($status)
    {
        return StatusType::getTypeLabel($status);
    }
	
    public function hasRoute($path) 
    {
        $router = $this->container->get('router');
        
        return $router->getRouteCollection()->get($path) != null;
    }

    public function getName()
    {
        return 'admin_extension';
    }
    
    public function verificarPermissao($path=null,$permissao=null)
    {
    	
    	if($this->container->get('security.context')->getToken()->getUser()->getRole()=='ROLE_ADMIN')
    		return true;
    	
    	$urlAtual = $path;
    	preg_match("/admin(\/[a-z]{1,})?(\/[a-z]{1,})?/", $urlAtual, $url);
    	
    	$urlP = explode("/", $url[0]);
    	
    	$action = count(explode("/", $url[0]))>2?$urlP[2]:'';
    	
    	$url[0] = $urlP[0]."/".$urlP[1];
    	
    	
    	$perm = $this->getPermissoes($path);
    		$permitido = false;
    		foreach($perm->getPermissoes() as $p)
    		{
    			
    			if($url[0] == $p->getResource()->getPath())
    			{
    				if(count($p->getAuthorizations())>0)
    				{
    					if($action)
    					{
    						$permitido = $this->verificarAcao($p->getAuthorizations(), $action);
    						
    						
    					}
    					else
    					{	
    						$permitido = true;
    					}
    				}
    			}
    		}
    		return $permitido;
    		//return 'ok';
    	
    }
    public function verificarAcao($autorizacoes,$acao)
    {
    	$perm = Array(
    			'new'=>'CREATE',
    			'remove'=>'DELETE',
    			'edit'=>'EDIT',
    	);
    	foreach($autorizacoes as $a)
    	{
    		if($perm[$acao]==$a)
    			return true;
    	}
    	
    	return false;
    }
    public function verificarAcl($path=null, $permissao=null)
    {
    	if($this->container->get('security.context')->getToken()->getUser()->getRole()=='ROLE_ADMIN')
    		return true;
    	
    	$x = array('salvar'=>'CREATE', 'excluir'=>'DELETE', 'editar'=>'EDIT', 'visualizar'=>'VIEW');

    	
    	if(!$path)
    	{
    		$path = $this->container->get('request')->getRequestUri();
    	}
    
    	$urlAtual = $path;
    	preg_match("/admin(\/[a-z]{1,})?/", $urlAtual, $url);
    	 
    	$perm = $this->getPermissoes($path);
    	
    	$permitido = false;
    	foreach($perm->getPermissoes() as $p)
    	{
    		
    		if($url[0] == $p->getResource()->getPath())
    		{
    			if(count($p->getAuthorizations())>0)
    				$permitido = $p->getAuthorizations();
    		}
    	}  
    	if($permitido != false)
    	{
	    	if(in_array("EDIT", $permitido) && $x[strtolower($permissao)] == "VIEW")
	    		return true;
    	
	    	foreach ($permitido as $z)
	    	{
	    		if($x[strtolower($permissao)] == $z )
	    			return true;
	    	}
    	}
    	return false;
    }
    
    
    
    public function getPermissoes($path)
    {
    	
    	 
    	$id = $this->container->get('security.context')->getToken()->getUser()->getId();
    	$aclProvider = $this->container->get('security.acl.provider');
    	 
    	$resources = $this->container->get('doctrine')->getRepository('TupiSecurityBundle:Resource')->findAll();
    	 
    	$perm = new PermissionHelper();
    	$perm->setUser(new User());
    	 
    	$permissoes = array();
    	foreach ($resources as $resource) {
    		$auth = new Authorization();
    		$auth->setResource($resource);
    		$auth->setAuthorizations(array());
    		array_push($permissoes, $auth);
    	}
    	$perm->setPermissoes($permissoes);
    	$perm->setUser($this->container->get('doctrine')->getRepository('TupiSecurityBundle:User')->findOneBy(array('id' => $id)));
    	
    	$securityIdentity = UserSecurityIdentity::fromAccount($perm->getUser());
    	$permissoes = $perm->getPermissoes();
    	if(!empty($permissoes)) {
    		 
    		foreach ($permissoes as $permissao) {
    			 
    			$authorizations = array();
    			$objectIdentity = ObjectIdentity::fromDomainObject($permissao->getResource());
    			try {
    				$acl = $aclProvider->findAcl($objectIdentity, array($securityIdentity));
    				foreach($acl->getObjectAces() as $index => $ace) {
    						
    					if($securityIdentity->equals($ace->getSecurityIdentity())) {
    	
    						$masker = new MaskBuilder();
    						foreach (Authorization::$AUTHORIZATIONS as $key => $label)
    						{
    								
    							$masker->reset();
    							$masker->add($key);
    							if(($ace->getMask() & $masker->get()) === 0) {
    								continue;
    							}
    								
    							array_push($authorizations, $key);
    						}
    					}
    				}
    	
    				$permissao->setAuthorizations($authorizations);
    			}
    			catch (\Symfony\Component\Security\Acl\Exception\AclNotFoundException $e)
    			{
    				 
    			}
    		}
    	}
    	return $perm;
    }
}