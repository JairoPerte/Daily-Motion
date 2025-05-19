<?php

namespace App\Shared\Infrastructure\Mailer;

use Throwable;
use DateTimeImmutable;
use Symfony\Component\Mime\Email;
use App\User\Domain\ValueObject\UserEmail;
use App\Authentication\Domain\Entity\Session;
use Symfony\Component\Mailer\MailerInterface;
use App\Shared\Domain\Exception\MailException;
use App\Shared\Domain\Mailer\AppMailerInterface;

class MailjetMailer implements AppMailerInterface
{
    public function __construct(private MailerInterface $mailer) {}

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendEmailCode(UserEmail $userEmail): void
    {
        try {
            $email = (new Email())
                ->from('daily.motion.app.contact@gmail.com')
                ->to($userEmail->getString())
                ->subject('Welcome!')
                ->text('Welcome to our app!')
                ->html("<p>{$userEmail->getEmailCode()}</p>");

            $this->mailer->send($email);
        } catch (Throwable $e) {
            throw new MailException("Ha habido un error al enviar el mail");
        }
    }

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendLogInEmail(UserEmail $userEmail, Session $session): void
    {
        try {
            $now = new DateTimeImmutable();
            $email = (new Email())
                ->from('daily.motion.app.contact@gmail.com')
                ->to($userEmail->getString())
                ->subject('A new Login From Your Account')
                ->text('There is a new Login in your account')
                ->html("<p>Login at: {$now->format('Y-m-d H:i:s')}</p><p>Device: {$session->getSessionUserAgent()->getString()}</p>");

            $this->mailer->send($email);
        } catch (Throwable $e) {
            throw new MailException("Ha habido un error al enviar el mail");
        }
    }

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendFriendRequest(UserEmail $userEmail): void
    {
        try {
            $now = new DateTimeImmutable();
            $email = (new Email())
                ->from('daily.motion.app.contact@gmail.com')
                ->to($userEmail->getString())
                ->subject('You have a new Friend Request')
                ->text('There is a new Friend Request')
                ->html("<p>You have a new Friend Request accept it or deny it</p>");

            $this->mailer->send($email);
        } catch (Throwable $e) {
            throw new MailException("Ha habido un error al enviar el mail");
        }
    }
}
