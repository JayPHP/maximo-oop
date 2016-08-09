<?php
/**
 * TwigRenderer
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Template;

use Jay\System\Template;
use Twig_Environment;
use Symfony\Component\HttpFoundation\Response;
use Jay\System\Flash;
use Jay\System\HtmlHelper;

class TwigRenderer implements Template
{

    public function __construct(
        Twig_Environment $template, 
        Response $response, 
        Flash $flash, 
        HtmlHelper $html)
    {
        $this->template = $template;
        $this->response = $response;
        $this->flash = $flash;
        $this->html = $html;
    }

    public function render($template, $data = [], $layout = 'default')
    {
        if (isset($data['app_layout_name'])) {
            throw new \Exception("app_layout_name is a reserved value");
        }

        $data['app_layout_name'] = "$layout.html";

        $functions = [
            'asset' => ['html', 'asset'],
            'flash' => ['flash', 'render'],
            'link' => ['html', 'link']
        ];

        $this->defineFunctions($functions);    
        
        $this->response->setContent(
            $this->template->render("$template.html", $data)
        );
    }

    public function defineFunctions($functions)
    {
        foreach ($functions as $name => $function) {
            $func = new \Twig_SimpleFunction($name, [$this->$function[0], $function[1]]);
            $this->template->addFunction($func);
        }
    }
}