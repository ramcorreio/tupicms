<?php 
namespace Tupi\AdminBundle\EventListener;

use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Symfony\Component\Templating\EngineInterface;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Monolog\Handler\RotatingFileHandler;
use Symfony\Component\HttpFoundation\Response;
use Tupi\ContentBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Kernel exception listener
 */
class ExceptionListener extends ExceptionHandler
{
	/**
	 * The template engine
	 *
	 * @var EngineInterface
	 */
	private $templateEngine;
	private $logger;
	private $isDebug;
	private $teste;
	private $kernel;
	private $errorCode;
	private $logPath;
	
	/**
	 * Constructor.
	 *
	 * @param EngineInterface $templateEngine The template engine
	 */
	public function __construct(EngineInterface $templateEngine, Logger $logger, $isDebug, $kernel)
	{
		$this->templateEngine = $templateEngine;
		$this->logger = $logger;
		$this->isDebug = $isDebug;
		$this->kernel = $kernel;
		$this->logPath = $kernel->getRootDir();
	}
	
	
	/**
	 * Handles a kernel exception and returns a relevant response.
	 *
	 * Aims to deliver content to the user that explains the exception, rather than falling
	 * back on symfony's exception handler which displays a less verbose error message.
	 *
	 * @param GetResponseForExceptionEvent $event The exception event
	 */
	//Trata os staus code
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		//Captura as configurações em tupi_admin -> error
		$pathTemplate = $this->kernel->getContainer()->getParameter('tupi_admin.error');
		//verifica se esta em prod
		if($this->isProd()){
			//verifica se existe algum exception
			if($exception = $event->getException()){
				//verifica se existe o metodo getStatusCode
				//geralmente só aparece no retorno do status code 404
				if(method_exists($exception, "getStatusCode")){
					$code = $exception->getStatusCode();
					if($code == 404){
						//informa o tipo do erro
						$errorType = 'notFound';
						$params = array(
								'status_code' => $code,
								'session' => new Page(),
								'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : ''
						);
					}

				}else{
					//caso nao seja o status code 404, gera o arquivo de log
					$this->errorCode = $this->getErrorCode();
					$errorlog = array(
							'message' => $exception->getMessage(),
							'file' => $exception->getFile(),
							'line' => $exception->getLine()
					);
					$event->stopPropagation();
					//$event->setResponse("");
					$this->setErrorLog($errorlog);
					//informa o tipo do erro
					$errorType = 'fatalError';
					$params = array(
							'errorCode' => $this->errorCode,
							'session' => new Page()
						);
				}
				
				//verifica se o template realmente existe
				if($this->templateEngine->exists($pathTemplate['template'][$errorType])){
					$response = $this->templateEngine->render(
							$pathTemplate['template'][$errorType],
							$params
					);
					$event->setResponse(new Response($response));
				}
			}
		}
	}

	public function onKernelRequest(GetResponseEvent $event)
	{

	}
	
	/**
	 * verifica em qual modo o symfony se encontra
	 * PROD ou DEV
	 * @return TRUE | FALSE
	 */
	
	private function isProd(){
		//Verifica se esta em prod
		return "prod" == $this->kernel->getEnvironment();
	}
	/**
	 * Gera nome unico para o arquivo de log
	 */
	private function getErrorCode(){
		//gera um nome unico para o arquivo de log
		return date("YmdHis");
	}
	
	/**
	 * Cria o arquivo de log e injeta as informacoes
	 * do erro
	 * @param array $errorlog
	 */
	
	private function setErrorLog(array $errorlog){
		//Cria o arquivo de log
		$this->logger->pushHandler(new RotatingFileHandler($this->logPath.'/logs/'.$this->errorCode.'.log', 0, Logger::DEBUG));
		//insere o log no arquivo
		$this->logger->addError('',$errorlog);
	}
	
}