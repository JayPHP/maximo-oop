<?php
/**
 * DataValidation Class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Database;

class DataValidation
{
    private $filters = [
        'email'     => FILTER_SANITIZE_EMAIL,
        'string'    => FILTER_SANITIZE_STRING,
        'url'       => FILTER_SANITIZE_URL
    ];

    public function sanitize($data, $type, $null = true)
    {
        $data = isset($this->filters[$type]) ? filter_var($data, $this->filters[$type]) : $data;

        if (empty($data) && $data !== 0 && $data !== '0') {
            if ($null) {
                return null;
            }

            throw new \Exception("is required");
        }
        
        return $data;
    }
}
