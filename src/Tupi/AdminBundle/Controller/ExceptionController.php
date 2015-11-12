<?php
namespace Tupi\AdminBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;

class ExceptionController extends BaseExceptionController
{
	protected $dir;
	
	public function __construct(\Twig_Environment $twig, $debug, $dir)
	{
		$this->twig = $twig;
		$this->debug = $debug;
		$this->dir = $dir;
	}
	
	public function showException(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {        
    	$template = "";
        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $code = $exception->getStatusCode();
        $session = new \stdClass(); 
        $session->url = "home";

        if($this->debug)
        {
        	$template = (string) $this->findTemplate($request, $request->getRequestFormat(), $code, $this->debug);
        }else {
        	$template = 'InternitSiteBaseBundle:Exception:error.html.twig';
        	if(!$this->templateExists($template))
        	{
        		$template = (string) $this->findTemplate($request, $request->getRequestFormat(), $code, $this->debug);
        	}
        }
                
        $logger->pushHandler(new RotatingFileHandler($this->dir.'/logs/app.log', 0, Logger::DEBUG));
        $logger->addError($code, array('message' => $exception->getMessage(), 'class' => $exception->getClass(), 'file' => $exception->getFile(), 'line' => $exception->getLine()));;
                
        return new Response($this->twig->render(
        	$template,
        	array(
        			'status_code'    => $code,
        			'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
        			'exception'      => $exception,
        			'logger'         => $logger,
        			'currentContent' => $currentContent,
        			'session' => $session
        	)
        ), $code);
	}
	
	private function saveLog()
	{
		
	}
}
