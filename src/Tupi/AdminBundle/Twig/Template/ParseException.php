<?php
namespace Tupi\AdminBundle\Twig\Template;

use Exception;
use Symfony\Component\Validator\Exception\ExceptionInterface;

/**
 * ParseException é lançada quando o formato da string para função unserialize estiver errada.
 *
 *
 * @author Internit LTDA <admin@internit.com.br>
 */
class ParseException extends Exception implements ExceptionInterface
{
}