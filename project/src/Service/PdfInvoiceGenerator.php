<?php

namespace App\Service;

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

    public function generateInvoice(\DateTimeInterface $startDate, \DateTimeInterface $endDate, int $userId): string
    {
        // Implement logic to fetch activities, calculate total, and generate PDF
        // ...

        // Example code using Dompdf
        $html = $this->renderInvoiceHtml($data);

        $this->pdfLibrary->loadHtml($html);
        $this->pdfLibrary->setPaper('A4', 'portrait');
        $this->pdfLibrary->render();

        $output = $this->pdfLibrary->output();

        // Save the PDF to a file (you can customize the path and filename)
        $pdfPath = '/path/to/save/invoice.pdf';
        file_put_contents($pdfPath, $output);

        return $pdfPath;
    }
    private function renderInvoiceHtml(array $data): string
    {
        // Extract data from the array (modify according to your actual data structure)
        $user = $data['user'];
        $activities = $data['activities'];
    
        // Start building the HTML content
        $html = '<html>';
        $html .= '<head><title>Invoice</title></head>';
        $html .= '<body>';
        $html .= '<h1>Invoice for ' . $user->getFirstName() . ' ' . $user->getLastName() . '</h1>';
    
        // Iterate through activities and display details
        foreach ($activities as $activity) {
            $html .= '<p>Date: ' . $activity->getDate()->format('Y-m-d') . '</p>';
            $html .= '<p>Status: ' . ($activity->isStatus() ? 'Active' : 'Inactive') . '</p>';
            // Add more details as needed
        }
    
        $html .= '</body>';
        $html .= '</html>';
    
        return $html;
    }


}
