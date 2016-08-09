<?php
/**
 * Routing
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

return [
    ['GET', '/', ['Jay\Controllers\Tickets', 'index']],
    ['POST', '/', ['Jay\Controllers\Tickets', 'create']],
    ['GET', '/ticket/review/{id:\d+}', ['Jay\Controllers\Tickets', 'review']],
    ['POST', '/ticket/review/{id:\d+}', ['Jay\Controllers\Tickets', 'update']],
    ['GET', '/ticket/complete/{id:\d+}', ['Jay\Controllers\Tickets', 'complete']]
];