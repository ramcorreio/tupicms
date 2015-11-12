<?php

namespace Tupi\SecurityBundle\Composer;

use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as SensioScriptHandler;
use Composer\Script\CommandEvent;
use Composer\Script\Event;

class ScriptHandler extends SensioScriptHandler
{
	public static function updateSchema(CommandEvent $event)
	{
		$options = self::getOptions($event);
		$consoleDir = self::getConsoleDir($event, 'create schema');

		static::executeCommand($event, $consoleDir, 'doctrine:schema:update --force', $options['process-timeout']);
	}
	
	public static function installMenuRoot(CommandEvent $event)
	{
		$options = self::getOptions($event);
		$consoleDir = self::getConsoleDir($event, 'install menu');
	
		static::executeCommand($event, $consoleDir, 'admin:menu:root', $options['process-timeout']);
	}
	
	public static function createUserAdmin(CommandEvent $event)
	{
		$options = self::getOptions($event);
		$consoleDir = self::getConsoleDir($event, 'create user admin');
		
		$value = $event->getIO()->ask('Informar a senha para o usuario: ', "");
	
		static::executeCommand($event, $consoleDir, 'admin:create:user '.$value, $options['process-timeout']);
	}
}