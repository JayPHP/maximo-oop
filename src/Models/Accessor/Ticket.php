<?php
/**
 * Ticket table accessor
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Models\Accessor;
use Jay\System\Adapter;

class Ticket
{
    private $db;

    public function __construct(Adapter $adapter) 
    {
        $this->db = $adapter->PDO;
    }

    public function insert($entity)
    {
        $query = 'INSERT INTO tickets';
        $query.= '(id, name, email, date, department, phone, building, room, description, ';
        $query.= 'priority, additional, sent)';
        $query.= 'VALUES (:id, :name, :email, :date, :department, :phone, :building, :room, ';
        $query.= ':description, :priority, :additional, :sent)';

        $st = $this->db->prepare($query);
        $st->execute((array) $entity);
        $entity->id = $this->db->lastInsertId();  
    }

    public function select($id)
    {
        $query = 'SELECT * FROM tickets WHERE id = :id';
        $st = $this->db->prepare($query);
        $st->execute(['id' => $id]);

        return $st->fetch();
    }

    public function update($id, $entity)
    {     
        $query = 'UPDATE tickets ';
        $query.= 'SET name = :name, email = :email, date = :date, department = :department, ';
        $query.= 'phone = :phone, building = :building, room = :room, description = :description, ';
        $query.= 'priority = :priority, additional = :additional, sent = :sent ';
        $query.= 'WHERE id = :id';
        $st = $this->db->prepare($query);

        $st->execute((array) $entity);
    }
}