<?php
namespace Tupi\SecurityBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\ClassUtils;
use ReflectionClass;


/**
 * Esta classe é parta do SecurityBunble
 * 
 * @author Rodrigo Macedo <rodrigo.macedo@internit.com.br>
 * 
 * Esta classe foi criada para validar chaves duplicadas no banco de dados.
 * 
 */
class DuplicatedKeyValidator extends ConstraintValidator {
	
	private function getPropertiesFromClass(ReflectionClass $reflectionClass)
	{
		if(!$reflectionClass->getParentClass())
			return $reflectionClass->getProperties();
		
		$props = $this->getPropertiesFromClass($reflectionClass->getParentClass());
		$localProps = $reflectionClass->getProperties();
		return array_merge($props, $localProps);
	}
	
	private function getProperties($object)
	{
		$reflectionClass = new ReflectionClass(ClassUtils::getClass($object));
		return $this->getPropertiesFromClass($reflectionClass);
	}
	
	/**
	* {@inheritDoc}
	*/
	public function validate($value, Constraint $constraint)
	{
		$idName = "id";
		$idVal = null;
		
		$path = $this->context->getPropertyPath();
		$data = $this->context->getRoot()->getData();
		
		$patternChild = "/children\[([^\]]*)\]/i";
		$path = preg_replace($patternChild, "$1", $path);
		
		$patternAttr = "/\w+\.+/i";
		
		$matches = array();
		preg_match_all($patternAttr, $path, $matches);
		if(sizeof($matches[0]) > 1)
		{
			$count = sizeof($matches[0]);
			for ($i = 0; $i < $count - 1; $i++) {
				
				$subPath = $matches[0][$i];
				$propName = str_replace(".", "", $subPath);
				
				$reflClass = new \ReflectionClass(get_class($data));
				
				$p = $reflClass->getProperty($propName);
				$p->setAccessible(true);
				$idName = $p->getName();
				$data = $p->getValue($data);
				$path = str_replace($subPath, "", $path);
			}
		}
		
		$reader = new AnnotationReader();
		$props = $this->getProperties($data);
		foreach ($props as $p) {
			$propsAnnotations = $reader->getPropertyAnnotation($p, "Doctrine\ORM\Mapping\Id");
			if(!empty($propsAnnotations)) {
				$p->setAccessible(true);
				$idName = $p->getName();
				$idVal = $p->getValue($data);
				break;
			}	
		}
		
		$path = str_replace(".data", "", $path);
		
		$criteria = array($path => $value);
		$returnVal = $constraint->repository->findBy($criteria);
		if(!empty($idVal))
			$criteria[$idName] = $idVal;
		
		$returnValAndId = $constraint->repository->findBy($criteria);

		//verificar existência de chave para novos cadastros
		if(!empty($returnVal) && empty($idVal) && !empty($returnValAndId))
		{
			$this->context->addViolation($constraint->message, array('{{valor}}' => $value));
		}
		//verificar existência de chave para cadastro em edição
		else if(!empty($returnVal) && !empty($idVal) && empty($returnValAndId))
		{
			$this->context->addViolation($constraint->message, array('{{valor}}' => $value));
		}
	}
}