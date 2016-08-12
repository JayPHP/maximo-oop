<?php
/**
 * DataValidation Class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Database;

class DataValidation
{
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function check($name, $options = null)
    {
        if (!property_exists($this->entity, $name)) {
            throw new \Exception("$$name does not exist in the Entity");
        }

        if (!is_scalar($this->entity->$name)) {
            throw new \Exception("$$name is not a scalar");
        }

        if (isset($options['type'])) {
            $this->sanitizeAndValidate($name, $options);
        }

        return true;
    }

    private function sanitizeAndValidate($name, $options)
    {
        if (!method_exists($this, "validate{$options['type']}")) {
            throw new \Exception("Type $type for $$name is unrecognised");
        }

        if ($this->validateNotNull($this->entity->$name)) {
            $this->entity->$name = $this->{"validate{$options['type']}"}($name);
            return;
        }

        if (!$options['null']) {
            $this->entity->errors[$name] = 'Cannot be null';
        }
    }

    private function validateString($name)
    {
        $sanitize = filter_var($this->entity->$name, FILTER_SANITIZE_STRING);

        return $sanitize;
    }

    private function validateEmail($name)
    {
        $sanitize = filter_var($this->entity->$name, FILTER_SANITIZE_EMAIL);

        if (!filter_var($sanitize, FILTER_VALIDATE_EMAIL)) {
            $this->entity->errors[$name] = 'is not a valid email';
        }

        return $sanitize;
    }

    private function validateDate($name)
    {
        // Validation to be added
        return $this->entity->$name;
    }

    private function validateBoolean($name)
    {
        // validation to be added
        return $this->entity->$name;
    }

    private function validateInteger($name)
    {
        $sanitize = (int) filter_var($this->entity->$name, FILTER_SANITIZE_NUMBER_INT);

        if (!filter_var($sanitize, FILTER_VALIDATE_INT)) {
            $this->entity->errors[$name] = 'is not a valid integer';
        }

        return $sanitize;        
    }

    private function validateNotNull($value)
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            return false;
        }

        return true;
    }
}
