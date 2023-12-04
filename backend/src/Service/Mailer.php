<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


readonly class Mailer
{
    public function __construct(private MailerInterface $mailer, private LoggerInterface $logger)
    {
    }

    /**
     * @param string $email
     * @param string $subject
     * @param string $template
     * @param array $context
     * @param bool $important
     * @return void
     * @throws \Exception
     */
    public function send(string $email, string $subject, string $template, array $context, bool $important = false): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from('no-replay@ams-mantes.fr')
                ->to(new Address($email))
                ->subject($subject)
                ->htmlTemplate($template)
                ->context($context);
            if ($important) {
                $email = $email->priority(Email::PRIORITY_HIGH);
            }
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('mail not send : '.$e->getMessage());
            throw new \Exception('Erreur d\'envoi demail');
        }
    }
}