<?php


namespace App\Service;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

class WildMailer
{
    private $from;
    private $to;
    private $subject;
    private $content;
    private $mailer;

    public function __construct(string $appSender, string $appAdmin, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->from   = $appSender;
        $this->to     = $appAdmin;
    }

    public function sendMailNewProgram($name, $resume, $slug)
    {
        $this->setSubject('Une nouvelle série a été ajoutée');
        $email = (new TemplatedEmail())
            ->from($this->getFrom())
            ->to($this->getTo())
            ->subject($this->getSubject())
            ->htmlTemplate('email/mail.html.twig')
            ->context([
                'title'     => $name,
                'resume'    => $resume,
                'slug'      => $slug
            ]);

        $this->mailer->send($email);
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }



}
