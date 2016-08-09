<?php
/**
 * Flash Messages
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Components;

use Jay\System\Flash;

class FlashMessage implements Flash
{
    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
    }

    public function info($message)
    {
        $this->add($message, 'info');
    }

    public function warning($message)
    {
        $this->add($message, 'warning');
    }

    public function error($message)
    {
        $this->add($message, 'error');
    }

    public function success($message)
    {
        $this->add($message, 'success');
    }

    public function add($message, $type)
    {
        $_SESSION['flash'][$type][] = $message;
    }

    public function render()
    {
        if (empty($_SESSION['flash'])) {
            return false;
        }

        $button =   '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>';

        foreach ($_SESSION['flash'] as $type => $messages) {
            $title = ucfirst($type);
            foreach ($messages as $message) {
                echo    "<div class='alert alert-dismissible alert-$type' role='alert'>
                            $button 
                            <h4>$title!</h4> $message
                        </div>";
            }
        }

        unset($_SESSION['flash']);

    }
}