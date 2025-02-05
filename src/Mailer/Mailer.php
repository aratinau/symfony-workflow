<?php

namespace App\Mailer;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class Mailer
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
    ) {
    }

    public function send(User $user, $subject, $template, $datas = [])
    {
        try {
            $message = (new Email())
                ->from("symfony@workflow.dev")
                ->subject($subject)
                ->to($user->getEmail())
                ->html(
                    $this->twig->render($template, $datas),
                    'text/html; charset=UTF-8'
                )
            ;

            $this->mailer->send($message);
        } catch (\Exception $e) {
            // Log the error or handle email sending failure
            // You might want to use a logger or throw a custom exception
            throw $e;
        }
    }
}
