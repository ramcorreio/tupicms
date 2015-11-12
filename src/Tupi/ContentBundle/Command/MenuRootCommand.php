<?php
namespace Tupi\ContentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use InvalidArgumentException;

use Tupi\SecurityBundle\Command\BaseCommand;
use Tupi\ContentBundle\Entity\Menu;
use Tupi\ContentBundle\Controller\MenuController;

class MenuRootCommand extends BaseCommand
{
	protected function configure()
    {
        $this
            ->setName('admin:menu:root')
            ->setDescription('Criar raiz do menu.');
    }
    
    private function createMenuRoot()
    {
    	$repo = $this->getRepository(MenuController::REPOSITORY_NAME);
    	
    	$menuRoot = null;
    	
    	try {
    		$menuRoot = $repo->getRoot();
    	} catch (InvalidArgumentException $e) {
    		$menuRoot = new Menu();
    	}
    	
    	$menuRoot->setTemplateName(null);
    	$menuRoot->setPosition(0);
    	$menuRoot->setCreatedAt(new \DateTime());
    	$menuRoot->setUpdatedAt(new \DateTime());
    	$menuRoot->setTitle('Site');
    	
    	$action = '';
    	if(!is_null($menuRoot->getId()))
    	{
    		$repo->update($menuRoot);
    		$action = 'atualizado';
    	}
    	else
    	{
    		$repo->persist($menuRoot);
    		$action = 'criado';
    	}
    	
    	$repo->flush();
    	return $action;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$error = 0;
    	$dialog = $this->getDialogHelper();
    	
    	try {
	    	$action = $this->createMenuRoot();
    		$output->writeln(sprintf('<info>Raiz do menu %s com sucesso!</info>',     	$action));
        } catch (\Exception $e) {
        	$error = 1;
            $output->writeln(sprintf('<error>Nao foi possivel executar o comando devido ao seguinte erro:</error>'));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        return $error;
    }
}