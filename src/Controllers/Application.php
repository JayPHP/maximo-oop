<?php
/**
 * Application Controller
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Jay\System\Template;
use Jay\System\Flash;

class Application
{
    protected $request;
    protected $template;
    protected $flash;

    public function __construct(
        Request $request,
        Template $template,
        Flash $flash
    ) {
        $this->request = $request;
        $this->template = $template;
        $this->flash = $flash;
    }

    public function index()
    {
        $html = $this->template->render('homepage');
    }

    public function redirect($route = false, $var = false)
    {
        $base = $this->request->isSecure() ? 'https://' : 'http://';
        $base .= "{$_SERVER['HTTP_HOST']}/";
        $base .= (!BASE) ? '' : BASE;
        $base .= (!$route) ? '' : "/$route/";
        $base .= (!$var) ? '' : urlencode($var);

        header("Location: $base");    
        exit;
    }
}
