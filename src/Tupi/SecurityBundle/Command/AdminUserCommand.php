<?php
namespace Tupi\SecurityBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Tupi\SecurityBundle\Entity\User;
use Tupi\SecurityBundle\Controller\UserController;

class AdminUserCommand extends BaseCommand
{
	protected function configure()
    {
        $this
            ->setName('admin:create:user')
            ->setDescription('Criar o usuario administrador na base de dados')
            ->addArgument('password', InputArgument::OPTIONAL, 'password?')
            //->addOption('doctrine', null, InputOption::VALUE_REQUIRED, 'A conexao para usar neste comando.')
            //->addArgument('password', InputArgument::REQUIRED, 'Informar a senha para o usuario.')
            //->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }
    
    private function createUser($password)
    {
    	$repo = $this->getRepository(UserController::REPOSITORY_NAME);
    	
    	$login = "admin";
    	try {
    		$user = $repo->loadUserByUsername($login);
    	} catch (UsernameNotFoundException $e) {
    		$user = new User();
    	}
    	
    	$user->setLogin($login);
    	 
    	//criptrografando a senha
    	$encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($user);
    	$user->setPassword($encoder->encodePassword($password, $user->getSalt()));
    	$user->setName("Administrador");
    	$user->setEmail("admin@internit.com.br");
    	$user->setRole("ROLE_ADMIN");
    	$user->setBirthDate(new \DateTime());
    	$user->setActive(true);

    	$action = '';
    	if(!is_null($user->getId())) 
    	{
    		$repo->update($user);
    		$action = 'atualizado';
    	}
    	else 
    	{
    		$repo->persist($user);
    		$action = 'criado';
    	}
    	
    	$repo->flush();
    	return $action;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$error = false;
    	$dialog = $this->getDialogHelper();
    	$password = $input->getArgument('password');
    	
    	if(empty($password))
    	{
    		$password = $dialog->askAndValidate($output, $dialog->getQuestion('Informar a senha para o usuario', "", ":"), array('Tupi\SecurityBundle\Validator\Constraints\PasswordValidator', 'password'));
    	}
    	
    	try {
    		$action = $this->createUser($password);
    		$output->writeln(sprintf('<info>Usuario admin %s com sucesso!</info>', $action));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Nao foi possivel processar o comando devido ao erro a seguir:</error>'));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $error = true;
        }

        return $error ? 1 : 0;
    }
}