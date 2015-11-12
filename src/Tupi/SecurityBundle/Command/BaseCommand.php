<?php

namespace Tupi\SecurityBundle\Command;

use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;


/**
 * Classe base para comandos do admin.
 *
 * @author Rodrigo Macedo
 */
abstract class BaseCommand extends DoctrineCommand
{
	protected function getDoctrine()
	{
		if (!$this->getContainer()->has('doctrine')) {
			throw new \LogicException('The DoctrineBundle is not registered in your application.');
		}
	
		return $this->getContainer()->get('doctrine');
	}
	
	protected function getRepository($name)
	{
		return $this->getDoctrine()->getRepository($name);
	}
	
	protected function getDialogHelper()
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if (!$dialog || get_class($dialog) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper') {
            $this->getHelperSet()->set($dialog = new DialogHelper());
        }

        return $dialog;
    }
}
