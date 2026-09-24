<?php

# Filetransport class, taken from https://www.mugo.ca/Blog/Creating-a-file-mail-transport-for-Symfony
namespace Wabue\Elgg\FileTransport;

use DateTimeImmutable;
use RuntimeException;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

readonly class FileMailer implements MailerInterface
{
    public function __construct(private string $directory)
    {
        if (
            !is_dir($this->directory)
            && !mkdir($this->directory, 0775, true)
            && !is_dir($this->directory)
        ) {
            throw new RuntimeException(
                sprintf('Cannot create mail dump directory "%s".', $this->directory)
            );
        }
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): void
    {
        $filename = sprintf(
            '%s/%s-%s.eml',
            rtrim($this->directory, '/'),
            (new DateTimeImmutable())->format('Ymd-His'),
            bin2hex(random_bytes(4)),
        );

        file_put_contents($filename, $message->toString());
    }
}