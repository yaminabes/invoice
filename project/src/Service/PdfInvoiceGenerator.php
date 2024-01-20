<?php

namespace App\Service;

use App\Entity\Entreprise;
use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use App\Repository\SupplementRepository;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfInvoiceGenerator
{
    private $activityRepository;
    private $userRepository;
    private $supplementRepository;
    private $pdfLibrary;

    public function __construct(
        ActivityRepository $activityRepository,
        UserRepository $userRepository,
        SupplementRepository $supplementRepository
    ) {
        $this->activityRepository = $activityRepository;
        $this->userRepository = $userRepository;
        $this->supplementRepository = $supplementRepository;

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $this->pdfLibrary = new Dompdf($options);
    }

    public function generateInvoice(\DateTimeInterface $startDate, \DateTimeInterface $endDate, Entreprise $clientEntreprise, Entreprise $currentEntreprise): string
    {
        $data = [
            'test',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'clientEntreprise' => $clientEntreprise,
            'currentEntreprise' => $currentEntreprise,
        ];
        $html = $this->renderInvoiceHtml($data);

        $this->pdfLibrary->loadHtml($html);
        $this->pdfLibrary->setPaper('A4', 'portrait');
        $this->pdfLibrary->render();

        $output = $this->pdfLibrary->output();

        // Save the PDF to a file (you can customize the path and filename)
        $pdfPath = '/invoices/invoice.pdf';
        $this->pdfLibrary->stream($pdfPath);
        // file_put_contents($pdfPath, $output);

        return $pdfPath;
    }
    private function renderInvoiceHtml(array $data): string
    {
        $currentEntreprise = $data['currentEntreprise'];

        // Accédez à la dénomination sociale de l'entreprise
        $denominationSociale = $currentEntreprise->getDenominationSociale();

        // Start building the HTML content
        $html = '<html>';
        $html .= '<head><title>Invoice</title></head>';
        $html .= '<body>';
        $html .= '<h1>Invoice for ' . $denominationSociale . '</h1>';
        $html .= '<p>Start Date: ' . $data['startDate']->format('Y-m-d') . '</p>';
        $html .= '<p>End Date: ' . $data['endDate']->format('Y-m-d') . '</p>';
        // Add more details as needed

        $html .= '</body>';
        $html .= '</html>';
        return $html;
    }


}