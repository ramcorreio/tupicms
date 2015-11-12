<?php
namespace Internit\RandomChatBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\NoResultException;

use Tupi\SecurityBundle\Command\BaseCommand;
use Internit\RandomChatBundle\Entity\RandomChatTurn;
use Internit\RandomChatBundle\Controller\InsertChatController;
use Internit\RandomChatBundle\Controller\RandomChatController;
use Internit\RandomChatBundle\Entity\RandomChatLinks;

class TurnRandomCommand extends BaseCommand
{
	protected function configure()
    {
        $this
            ->setName('chat:create:turn')
            ->setDescription('Criar turno 0 para chat randômico.');
    }
    
    private function createTurnRandom()
    {
    	$repo = $this->getRepository('InternitRandomChatBundle:RandomChatTurn');
    	
    	$turnRandom = null;
    	
    	try {
    		$turnRandom = $repo->getChatTurn();
    	} catch (NoResultException $e) {
    		$turnRandom = new RandomChatTurn();
    	}
    	
    	$turnRandom->setTurn(0);
    	
    	$action = '';
    	if(!is_null($turnRandom->getId()))
    	{
    		$repo->update($turnRandom);
    		$action = 'atualizado';
    	}
    	else
    	{
    		$repo->persist($turnRandom);
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
	    	$action = $this->createTurnRandom();
    		$output->writeln(sprintf('<info>Random Chat pronto para funcionamento!</info>',     	$action));
        } catch (\Exception $e) {
        	$error = 1;
            $output->writeln(sprintf('<error>Nao foi possivel executar o comando devido ao seguinte erro:</error>'));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        return $error;
    }
}