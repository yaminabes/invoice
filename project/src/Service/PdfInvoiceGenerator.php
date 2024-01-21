<?php

namespace App\Service;

use App\Entity\Entreprise;
use App\Entity\Activity;
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
    public function generateInvoice(\DateTimeInterface $startDate, \DateTimeInterface $endDate, Entreprise $clientEntreprise, Entreprise $currentEntreprise, array $activities): string
    {
        $data = [
            'activities'=> $activities,
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
        $clientEntreprise = $data['clientEntreprise'];
        $activities = $data['activities'];
        $totalHT=0;
        foreach ($activities as $activity) {
            $totalHT += $activity->calculateTotalCost();
        }
        $tva = $totalHT * 0.20;
        $totalTTC = $totalHT + $tva;


    
        // Start building the HTML content
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="fr">';
        $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $html .= '<title>Facture</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Arial, sans-serif; }';
        $html .= '.invoice-header { text-align: center; padding: 20px; background-color: #f0f0f0; }';
        $html .= '.invoice-details { margin: 20px 0; }';
        $html .= '.invoice-details table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
        $html .= '.invoice-details th, .invoice-details td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
        $html .= '.invoice-total { margin-top: 20px; text-align: right; }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';
    
        // Invoice Header
        $html .= '<div class="invoice-header">';
        $html .= '<h1>Facture</h1>';
        $html .= '</div>';
    
        // Invoice Details - Entreprise and Client
        $html .= '<div class="invoice-details">';
        $html .= '<table>';
        $html .= '<tr><th>Date de la facture</th><td>' . date('Y-m-d') . '</td></tr>';
        $html .= '<tr><th>Numéro de la facture</th><td>001</td></tr>';
        $html .= '<tr><th>Informations de l\'entreprise</th>';
        $html .= '<td>';
        $html .= 'Nom de votre entreprise: ' . $currentEntreprise->getDenominationSociale() . '<br>';
        $html .= 'Adresse du siège social: ' . $currentEntreprise->getAdresse() . '<br>';
        $html .= 'Adresse de facturation: ' . $currentEntreprise->getAdresseFacturation() . '<br>';
        $html .= 'Numéro de SIREN ou SIRET: ' . $currentEntreprise->getSiren() . '<br>';
        $html .= 'TVA intracommunautaire: (si applicable)<br>';
        $html .= '</td></tr>';
        $html .= '<tr><th>Informations du client</th>';
        $html .= '<td>';
        $html .= 'Nom du client: ' . $clientEntreprise->getDenominationSociale() . '<br>';
        $html .= 'Adresse du client: ' . $clientEntreprise->getAdresse() . '<br>';
        $html .= 'Adresse de livraison: ' . $clientEntreprise->getAdresseLivraison() . '<br>';
        $html .= 'Adresse de facturation: ' . $clientEntreprise->getAdresseFacturation() . '<br>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '</div>';
    
        // Invoice Details - Activities
        $html .= '<div class="invoice-details">';
        $html .= '<table>';
        $html .= '<tr><th>Désignation</th><th>Coût HT</th><th>Total HT</th></tr>';
        
        foreach ($activities as $activity) {
            $html .= '<tr>';
            $html .= '<td>' . $activity. '</td>';
            $html .= '<td>' . $activity->getUser()->getBasicCost() . '</td>';
            $html .= '<td>' . $activity->calculateTotalCost() . '</td>';
            $html .= '</tr>';
    
            // Add supplements for each activity
            foreach ($activity->getSupplements() as $supplement) {
                $html .= '<tr>';
                $html .= '<td>' . $supplement->getLabel() . '</td>';
                $html .= '<td>' . $supplement->getPercentage() . '</td>';
                $html .= '<td> </td> ';
                $html .= '</tr>';

            }
        }
        
        $html .= '</table>';
        $html .= '</div>';
    
        // Invoice Total
        $html .= '<div class="invoice-total">';
        $html .= '<p>Total HT :' .$totalHT. '€</p>';
        $html .= '<p>TVA (20%) : ' . $tva . '€</p>';
        $html .= '<p>Total TTC : ' . $totalTTC . '€</p>';
        $html .= '</div>';
    
        $html .= '</body>';
        $html .= '</html>';
    
        return $html;
    }
    


}