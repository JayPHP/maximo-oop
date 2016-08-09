<?php
/**
 * Tickets table class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Tables;
use Jay\System\Adapter;
use Jay\System\Database\DataValidation;

class Tickets
{
    private $db;
    private $validator;

    public $columns = [
        'name'          => ['type' => 'string', 'null' => true],
        'email'         => ['type' => 'email', 'null' => true],
        'date'          => ['type' => 'date', 'null' => false],
        'department'    => ['type' => 'string', 'null' => true],
        'phone'         => ['type' => 'string', 'null' => true],
        'building'      => ['type' => 'string', 'null' => false],
        'room'          => ['type' => 'string', 'null' => false],
        'description'   => ['type' => 'string', 'null' => false],
        'priority'      => ['type' => 'string', 'null' => true],
        'additional'    => ['type' => 'string', 'null' => true],
        'sent'          => ['type' => 'int', 'null' => false]
    ];

    public function __construct(Adapter $adapter, DataValidation $validator) 
    {
        $this->db = $adapter->PDO;
        $this->validator = $validator;
    }

    public function save($entity)
    {
        if ($this->validation($entity)) {
            $colNames = array_keys($this->columns);
            $cols = implode(', ', $colNames);
            $vals = implode(', ', preg_filter('/^/', ':', $colNames));

            $query = "INSERT INTO tickets ($cols) VALUES ($vals)";
            $st = $this->db->prepare($query);
            $st->execute((array) $entity);

            $entity->id = $this->db->lastInsertId();
            return true;
        }

        return false;
    }

    public function update($id, $entity)
    {
        if ($this->validation($entity)) {
            foreach (array_keys($this->columns) as $name) {
                $sets[] = $name.' = :'.$name;
            }
            
            $sets = implode(', ', $sets);
            $entity->id = $id;
            $query = "UPDATE tickets SET $sets WHERE id = :id";
            $st = $this->db->prepare($query);
            $st->execute((array) $entity);

            return true;            
        }

        return false;
    }

    public function get($id)
    {
        $query = 'SELECT * FROM tickets WHERE id = :id';
        $st = $this->db->prepare($query);
        $st->execute(['id' => $id]);

        return $this->createEntity($st->fetch());
    }

    // I need to work on this. Needs separating and it's only sanitizing atm. 
    private function validation($entity)
    {
        $clean = true;
        foreach ($entity as $name => $value) {
            if (is_array($value)) { 
                continue; 
            }

            if (!isset($this->columns[$name])) {
                $entity->errors[$name] = 'Column does not exist in table class';
                $clean = false;
                continue;
            }

            try {
                $entity->$name = $this->validator->sanitize(
                    $value, $this->columns[$name]['type'], $this->columns[$name]['null']
                );
            } catch (\Exception $e) {
                $entity->errors[$name] = $e->getMessage();
                $clean = false;
            }
        }

        return $clean;
    }

    public function createEntity($data = [])
    {
        $entity = new \StdClass;
        foreach (array_keys($this->columns) as $name) {
            $entity->$name = array_key_exists($name, $data) ? $data[$name] : null;
        }
        
        return $entity;
    }

    public function updateEntity($entity, $data)
    {
        foreach (array_keys($this->columns) as $name) {
            if (isset($data[$name]) && !empty($data[$name])) {
                $entity->$name = $data[$name];
            }
        }
    }
}
