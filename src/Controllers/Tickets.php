<?php
/**
 * Tickets Controller
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\Controllers;

use Jay\Models\Tables\Ticket as Table;
use Jay\Models\Entities\Ticket;
use Symfony\Component\HttpFoundation\Request;
use Jay\System\Template;
use Jay\System\Flash;

class Tickets extends Application
{
    private $tickets;

    public function __construct(Request $request, Template $template, Flash $flash, Table $table)
    {
        parent::__construct($request, $template, $flash);
        $this->tickets = $table;
    }

    public function index()
    {
        $this->template->render('homepage');
    }

    public function create()
    {
        $ticket = new Ticket;
        $this->tickets->mergeEntity($ticket, $this->request->request->all());

        if ($this->tickets->create($ticket)) {
            $this->flash->info('Please review your ticket and submit');
            $this->redirect('ticket/review', $ticket->id);
        }

        $this->outputErrors($ticket);
        $this->redirect();
    }

    public function review($params) 
    {
        $ticket = $this->tickets->get($params['id']);

        $this->isEditable($ticket);
        $this->template->render('review', (array) $ticket);
    }


    public function update($params)
    {
        $ticket = $this->tickets->get($params['id']);
        
        $this->isEditable($ticket);
        $this->tickets->mergeEntity($ticket, $this->request->request->all());

        if ($this->tickets->update($params['id'], $ticket)) {
            $this->flash->success('Your ticket has been updated');
            $this->redirect('ticket/review', $params['id']);
        }

        $this->outputErrors($ticket);
        $this->redirect('ticket/review', $params['id']);
    }

    public function complete($params)
    {
        $ticket = $this->tickets->get($params['id']);
        $this->isEditable($ticket);

        if ($this->sendEmail($ticket)) {
            $ticket->sent = (int) true;
            $this->tickets->update($params['id'], $ticket);

            $this->flash->success('Your ticket has been sent');
            $this->redirect();
        }

        $this->flash->error('There was an error submitting the ticket, please contact IT');
        $this->redirect('ticket/review', $params['id']);
    }

    private function isEditable($ticket)
    {
        $expiry = date('Y-m-d', strtotime($ticket->date. ' + 1 days'));
        if ($ticket->sent || strtotime(date('Y-m-d')) > strtotime($expiry)) {
            $this->flash->error('Sorry, this URL is no longer valid');
            $this->redirect();
        }
    }

    private function outputErrors($ticket)
    {
        foreach ($ticket->errors as $field => $error) {
            $this->flash->error($field.' '.$error);
        }        
    }

    private function sendEmail($ticket)
    {
        $email = "Maximo plain-text email template here";

        $to = 'exmaple@exmaple.com';
        $subject = 'Maximo Subject';
        $from = 'From: example@example.com';

        if (mail($to, $subject, $email, $from)) {
            return true;
        }

        return false;
    }
}
