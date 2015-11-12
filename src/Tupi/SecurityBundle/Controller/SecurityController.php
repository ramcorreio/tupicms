<?php
namespace Tupi\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Util\SecureRandom;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tupi\SecurityBundle\Form\RecoveryPass;
use InvalidArgumentException;

class SecurityController extends Controller
{
	public function esqueciAction() 
	{
		$mensagem = null;
		
		$form = new RecoveryPass();
		$form = $this->createForm($form);
		
		$fail = true;
		if($this->getRequest()->isMethod('POST'))
		{
			$form->handleRequest($this->getRequest());			
			if(!$form->isValid()) 
			{
				$errors = $this->get('validator')->validate($form);
				$mensagem = $errors[0]->getMessage();
			}
			else
			{
				try {
					$repo = $this->container->get('doctrine')->getRepository(UserController::REPOSITORY_NAME);
					$values = $form->getData();
					$user = $repo->loadUserByEmail($values['email']);
					
					$generator = new SecureRandom();
					$repo->setContainer($this->container);
					
					$pass = bin2hex($generator->nextBytes(4));
					$user->setPassword($pass);
					$repo->encryptPass($user);
					
					$repo->update($user);
					
					//disparo da mensagem
					$message = $this->get('admin.mail.service')->createMessage();
					$message->setSubject('Recuperação de senha.')
					->setFrom('admin@internit.com.br')
					->setTo($user->getEmail(), $user->getName())
					->setBody(
						$this->renderView('TupiAdminBundle:Template:email.txt.twig', array(
							'name' => $user->getName(),
							'pass' => $pass
								
						)
					));
					
					$this->get('admin.mail.service')->send($message);
					
					$mensagem = "Foi enviado um email com sua nova senha.";
					$repo->flush();
					$fail = false;
				}
				catch (InvalidArgumentException $e)
				{
					$mensagem = $e->getMessage();
				}
			}
		}
		
		return $this->render('TupiSecurityBundle::esqueci.html.twig', array(
			'form' => $form->createView(),
			'mensagem' => $mensagem,
			'fail' => $fail
		));
	}
	
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        // pegar o erro de login se existir um
        $error = null;
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
        	
        	$error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
        	
        	$error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        	$session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
			$error = "Acesso negado.";
        }
        
        // último login inserido pelo usuário
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);
        
        
        return $this->render('TupiSecurityBundle::login.html.twig', array(

        	'last_username' => $lastUsername,
        	'error'         => $error
        ));
    }   
}