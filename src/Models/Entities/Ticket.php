<?php
/**
 * Tickets Entity
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Models\Entities;

class Ticket
{
    public $id;

    public $name;

    public $email; 

    public $date;

    public $department;

    public $phone;

    public $building;

    public $room;

    public $description;

    public $priority;

    public $additional;

    public $sent;

    public function __construct()
    {
        $this->setDate();
        $this->setSent();
    }

    public function setDate()
    {
        $this->date = date('Y-m-d');
    }

    public function setSent()
    {
        $this->sent = (int) false;
    }
}