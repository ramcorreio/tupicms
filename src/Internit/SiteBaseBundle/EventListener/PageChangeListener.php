<?php
namespace Internit\SiteBaseBundle\EventListener;

use Tupi\AdminBundle\Twig\Template\ChangePageInterface;
use Tupi\AdminBundle\Twig\Template\ChangePageEvent;
use Tupi\AdminBundle\Twig\Template\TemplateItem;

class PageChangeListener implements ChangePageInterface
{
	public function onChange(ChangePageEvent $event)
	{
		$event->addTemplate(new TemplateItem('Home', 'InternitSiteBaseBundle:Default:index.html.twig'));
		$event->addTemplate(new TemplateItem('Página Notícia', 'InternitSiteBaseBundle:Default:noticia.html.twig'));
		$event->addTemplate(new TemplateItem('Lista Notícias', 'InternitSiteBaseBundle:Default:noticias.html.twig'));
		$event->addTemplate(new TemplateItem('Portfolio', 'InternitSiteBaseBundle:Default:portfolio.html.twig'));
		$event->addTemplate(new TemplateItem('Serviços', 'InternitSiteBaseBundle:Default:servicos.html.twig'));
		$event->addTemplate(new TemplateItem('Eventos', 'InternitSiteBaseBundle:Default:eventos.html.twig'));
		$event->addTemplate(new TemplateItem('Acompanhe', 'InternitSiteBaseBundle:Default:acompanhe.html.twig'));
		$event->addTemplate(new TemplateItem('Sobre', 'InternitSiteBaseBundle:Default:sobre.html.twig'));
		$event->addTemplate(new TemplateItem(
				'Newsletter',
				'InternitNewsletterBundle:Submit:newsletter'));
		$event->addTemplate(new TemplateItem(
			'Contato', 
			'InternitContactBundle:Submit:pergunta'
		));
	}
}