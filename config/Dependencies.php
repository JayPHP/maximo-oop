<?php
/**
 * Dependencies
 *
 * This application uses Auryn. You can find docuentation here
 * https://github.com/rdlowrey/Auryn.
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

$injector = new \Auryn\Injector;

$injector->alias('Jay\System\Template', 'Jay\System\Template\TwigRenderer');
$injector->alias('Jay\System\Adapter', 'Jay\System\Database\PDOAdapter');
$injector->alias('Jay\System\Flash', 'Jay\System\Components\FlashMessage');
$injector->alias('Jay\System\HtmlHelper', 'Jay\System\Components\HtmlHelper');

$injector->share('Symfony\Component\HttpFoundation\Request');
$injector->share('Symfony\Component\HttpFoundation\Response');
$injector->share('Jay\System\Flash');

$injector->define('Symfony\Component\HttpFoundation\Request', [
        $_GET, 
        $_POST,
        array(), 
        $_COOKIE,
        $_FILES, 
        $_SERVER
    ]);

$injector->define('PDO', [
        "mysql:host={$config['host']};dbname={$config['database']};charset{$config['charset']}",
        $config['username'],
        $config['password']
    ]);

$injector->delegate('Twig_Environment', function() use ($injector) {
    $loader = new Twig_Loader_Filesystem(dirname(__DIR__) . '/src/views');
    $twig = new Twig_Environment($loader);
    return $twig;
});

return $injector;