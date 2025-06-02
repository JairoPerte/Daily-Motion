<?php

namespace App\Shared\Infrastructure\Mailer;

use Mailjet\Client;
use Mailjet\Resources;
use App\User\Domain\Entity\User;
use App\Authentication\Domain\Entity\Session;
use App\Shared\Domain\Exception\MailException;
use App\Shared\Domain\Mailer\AppMailerInterface;

class MailjetMailer implements AppMailerInterface
{
    private Client $client;

    public function __construct(
        string $apiKey,
        string $apiSecret,
        private string $fromEmail,
        private string $fromName
    ) {
        $this->client = new Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);
    }

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendEmailCode(User $user): void
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->fromEmail,
                        'Name'  => $this->fromName
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail()->getString(),
                            'Name'  => $user->getUserName()->getString()
                        ]
                    ],
                    'Subject'  => "Tu código de verificación",
                    'HTMLPart' => "<h3>Hola {$user->getUserName()->getString()}</h3><p>Tu código de verificación es: <strong>{$user->getEmail()->getEmailCode()}</strong></p><p>Si no has solicitado este código, ignora este mensaje.</p>",
                ]
            ]
        ];

        $response = $this->client->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            throw new MailException("Ha habido un error al enviar el mail");
        }
    }

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendLogInEmail(User $user, Session $session): void
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->fromEmail,
                        'Name'  => $this->fromName
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail()->getString(),
                            'Name'  => $user->getUserName()->getString()
                        ]
                    ],
                    'Subject'  => "Un nuevo inicio de sesión en tu cuenta",
                    'HTMLPart' => "<h3>Hola {$user->getUserName()->getString()}</h3><p>Se ha iniciado sesión en tu cuenta desde un nuevo dispositivo.</p><h2>Detalles de la sesión:</h2><p>Fecha y hora: {$session->getSessionTimeStamp()->getCreatedAt()->format('Y-m-d H:i:s')}</p><p>Dispositivo: {$session->getSessionUserAgent()->getString()}</p>",
                ]
            ]
        ];

        $response = $this->client->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            throw new MailException("Ha habido un error al enviar el mail");
        }
    }

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendFriendRequest(User $user): void
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->fromEmail,
                        'Name'  => $this->fromName
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail()->getString(),
                            'Name'  => $user->getUserName()->getString()
                        ]
                    ],
                    'Subject'  => "Has recibido una nueva solicitud de amistad",
                    'HTMLPart' => "<h3>Hola {$user->getUserName()->getString()}</h3><p>Tienes una nueva solicitud de amistad. Acepta o rechaza la solicitud desde tu perfil.</p>",
                ]
            ]
        ];

        $response = $this->client->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            throw new MailException("Ha habido un error al enviar el mail");
        }
    }
}
