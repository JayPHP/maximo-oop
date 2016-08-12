<?php
/**
 * Tickets Table
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Models\Tables;

use Jay\System\Database\DataValidation;
use Jay\Models\Accessor\Ticket as TicketAccessor;
use Jay\Models\Entities\Ticket as TicketEntity;

class Ticket
{
    private $accessor;

    public function __construct(TicketAccessor $accessor)
    {
        $this->accessor = $accessor;
    }

    private function validation($entity)
    {
        $validator = new DataValidation($entity);

        $validator->check('name', [
            'type' => 'string',
            'null' => true
        ]);

        $validator->check('email', [
            'type' => 'email',
            'null' => true
        ]);

        $validator->check('date', [
            'type' => 'date',
            'null' => false
        ]);

        $validator->check('department', [
            'type' => 'string',
            'null' => true
        ]);

        $validator->check('phone', [
            'type' => 'integer',
            'null' => true
        ]);

        $validator->check('building', [
            'type' => 'string',
            'null' => false
        ]);

        $validator->check('room', [
            'type' => 'string',
            'null' => false
        ]);

        $validator->check('description', [
            'type' => 'string',
            'null' => false
        ]);

        $validator->check('priority', [
            'type' => 'string',
            'null' => true
        ]);

        $validator->check('additional', [
            'type' => 'string',
            'null' => true
        ]);

        $validator->check('sent', [
            'type' => 'boolean',
            'null' => false
        ]);

        if (property_exists($entity, 'errors')) {
            return false;
        }

        return true;
    }

    public function create(TicketEntity $entity)
    {
        if ($this->validation($entity)) {
            $this->accessor->insert($entity);
            return true;
        }

        return false;
    }

    public function get($id)
    {
        $data = $this->accessor->select($id);
        
        $ticket = new TicketEntity;
        $this->mergeEntity($ticket, $data);

        return $ticket;
    }

    public function update($id, $entity)
    {
        if ($this->validation($entity)) {
            $this->accessor->update($id, $entity);
            return true;
        }

        return false;
    }

    public function mergeEntity($entity, $data)
    {
        foreach ($data as $name => $value) {
            if (property_exists($entity, $name)) {
                $entity->$name = $value;
            }
        }
    }
}