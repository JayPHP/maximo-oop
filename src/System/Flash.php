<?php
/**
 * Flash Message Interface
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System;

interface Flash
{
    public function success($message);
    public function error($message);
    public function warning($meessage);
    public function info($message);
    public function add($message, $type);
    public function render();
}