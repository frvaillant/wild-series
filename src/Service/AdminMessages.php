<?php


namespace App\Service;


class AdminMessages
{
    private $messageReceived;
    private $action;
    private $entity;
    private $name;
    private $message;
    const MESSAGES = [
        'category' => [
            'gender' => 'f',
            'article' => 'la',
            'label' =>'catégorie',
        ]
    ];


    public function __construct($message)
    {
        $this->messageReceived = $message;
        if ($message!='') {
            $params = explode('_', $message);
            $this->action = $params[0];
            $this->entity = $params[1];
            $this->name = $params[2];
        }
    }

    public function getName() {
        return str_replace('-', '_', $this->name);
    }

    public function getAction()
    {
        switch ($this->action) {
            case 'add' :
                $this->message = 'ajouté';
                $this->message .=  ($this->getGender() === 'f') ? 'e' : '';
                break;
            case 'update' :
                $this->message = 'modifié';
                $this->message .=  ($this->getGender() === 'f') ? 'e' : '';
                break;
        }
        return $this->message;
    }

    public function getGender() {
        return self::MESSAGES[$this->entity]['gender'];
    }

    public function getArticle() {
        return self::MESSAGES[$this->entity]['article'];
    }

    public function getLabel() {
        return self::MESSAGES[$this->entity]['label'];
    }

    public function makeMessage() {
        if ($this->messageReceived != '') {
            $message = $this->getArticle() . ' ' .
                $this->getLabel() . ' ' .
                $this->getName() . ' a bien été ' .
                $this->getAction();
        } else {
            $message = '';
        }
        return $message;
    }
    
    public static function prepareMessage(string $message): string
    {
        $actionMessage = str_replace(' ', '-', $message);
        $actionMessage = str_replace('\'', '-', $actionMessage);
        return $actionMessage;
    }
}
