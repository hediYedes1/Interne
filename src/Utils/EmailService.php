<?php

namespace App\Utils;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;
use App\Entity\Interview;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger,
        private ParameterBagInterface $params
    ) {}

    public function sendInterviewAssignmentEmail(string $recipient, Interview $interview): bool
    {
        try {
            $logoPath = $this->params->get('kernel.project_dir').'/public/img/logoIn.png';
            $logoCid = 'logoRH360';

            if (!file_exists($logoPath)) {
                $this->logger->warning('Logo file not found: '.$logoPath);
                $logoPath = null;
            }

            $email = (new Email())
                ->from('no-reply@votre-domaine.com')
                ->to($recipient)
                ->subject("Nouvel interview programmé - {$interview->getTitreoffre()}")
                ->html($this->generateInterviewAssignmentContent($interview, $logoCid));

            if ($logoPath) {
                $email->embedFromPath($logoPath, $logoCid);
            }

            $this->mailer->send($email);
            return true;
        } catch (\Exception $e) {
            $this->logger->error('Erreur envoi email interview: ' . $e->getMessage(), [
                'exception' => $e,
                'recipient' => $recipient
            ]);
            return false;
        }
    }

    private function generateInterviewAssignmentContent(Interview $interview, string $logoCid): string
    {
        $date = $interview->getDateinterview()->format('d/m/Y');
        $heure = $interview->getTimeinterview() ? $interview->getTimeinterview()->format('H:i') : 'heure non définie';
        $type = $interview->getTypeinterview()->value;
        $localisation = $interview->getLocalisation();
        $lienMeet = $interview->getLienmeet();

        $content = <<<HTML
        <html>
            <body style="font-family: Arial, sans-serif;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="cid:$logoCid" alt="Logo RH360" style="max-height: 100px;" />
                </div>
                <h2 style="color: #2c3e50;">Nouvel interview programmé</h2>
                <p>Bonjour,</p>
                <p>Un nouvel interview vous a été assigné :</p>
        HTML;

        if ($type === 'En ligne' && $lienMeet) {
            $content .= "<p><strong>Lien Meet:</strong> <a href=\"$lienMeet\">$lienMeet</a></p>";
        } elseif ($type === 'En personne') {
            $content .= "<p><strong>Localisation:</strong> $localisation</p>";
        }

        $content .= <<<HTML
                </div>
                <p>Merci de vous préparer pour cet entretien.</p>
                <p>Cordialement,<br>L'équipe RH</p>
            </body>
        </html>
        HTML;

        return $content;
    }
}