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

    public function generateInvoice(\DateTimeInterface $startDate, \DateTimeInterface $endDate): string
    {
        // Implement logic to fetch activities, calculate total, and generate PDF
        // ...

        // Example code using Dompdf
        $data = ['test'];
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
        // // Extract data from the array (modify according to your actual data structure)
        // $user = $data['user'];
        // $activities = $data['activities'];
    
        // // Start building the HTML content
        // $html = '<html>';
        // $html .= '<head><title>Invoice</title></head>';
        // $html .= '<body>';
        // $html .= '<h1>Invoice for ' . $user->getFirstName() . ' ' . $user->getLastName() . '</h1>';
    
        // // Iterate through activities and display details
        // foreach ($activities as $activity) {
        //     $html .= '<p>Date: ' . $activity->getDate()->format('Y-m-d') . '</p>';
        //     $html .= '<p>Status: ' . ($activity->isStatus() ? 'Active' : 'Inactive') . '</p>';
        //     // Add more details as needed
        // }
    
        // $html .= '</body>';
        // $html .= '</html>';

        

        $html = $html = <<<HTML
        <head>
            <meta charset="UTF-8">
            <title>Facture José Roux</title>
            <style>
                    * {
            box-sizing: border-box;
        }

        html {
            height: 100%;
        }

        body {
            min-height: 100%;
            margin: 0;
            display: flex;
            flex-flow: column nowrap;
            justify-content: center;
            align-items: stretch;
            font: 12pt/1.5 'Raleway','Cambria', sans-serif;
            font-weight: 300;
            background: #fff;
            color: #666;
            -webkit-print-color-adjust: exact;
        }

        header {
            padding: 16px;
            position: relative;
            color: #888;
        }

        h1, h2 {
            font-weight: 200;
            margin: 0;
        }

        h1 {
            font-size: 27pt;
            letter-spacing: 4px;
        }

        body > * {
            width: 100%;
            max-width: 7in;
            margin: 3px auto;
            background: #F0f0f0;
            text-align: center;
        }

        footer {
            padding: 16px;
        }

        p {
            font-size: 9pt;
            margin: 0;
            font-family: 'Nunito';
            color: #777;
        }

        section,
        table {
            padding: 8px 0;
            position: relative;
        }

        dl {
            margin: 0;
            letter-spacing: -4px;
        }

        dt, dd {
            letter-spacing: normal;
            display: inline-block;
            margin: 0;
            padding: 0px 6px;
            vertical-align: top;
        }

        dl.bloc > dt,
        dl:not(.bloc) dt:not(:last-of-type),
        dl:not(.bloc) dd:not(:last-of-type) {
            border-bottom: 1px solid #ddd;
        }

        dl:not(.bloc) dt {
            border-right: 1px solid #ddd;
        }

        dt {
            width: 49%;
            text-align: right;
            letter-spacing: 1px !important;
            overflow: hidden;
        }

        dd {
            width: 49%;
            text-align: left;
        }

        dd,
        tr > td {
            font-family: 'Nunito';
        }

        section.flex {
            display: flex;
            flex-flow: row wrap;
            padding: 8px 16px;
            justify-content: space-around;
        }

        dl.bloc {
            padding: 0;
            flex: 1;
            vertical-align: top;
            min-width: 240px;
            margin: 0 8px 8px;
        }

        dl.bloc > dt {
            text-align: left;
            width: 100%;
            margin-top: 12px;
        }

        dl.bloc > dd {
            text-align: left;
            width: 100%;
            padding: 8px 0 5px 16px;
            line-height: 1.25;
        }

        dl.bloc > dd > dl dt {
            width: 33%;
        }

        dl.bloc > dd > dl dd {
            width: 60%;
        }

        dl.bloc dl {
            margin-top: 12px;
        }

        dl.bloc dd {
            font-size: 11pt;
        }

        table {
            width: 100%;
            padding: 0;
            border-spacing: 0px;
        }

        tr {
            margin: 0;
            padding: 0;
            background: #fdfdfd;
            border-right: 1px solid #ddd;
            width: 100%;
        }

        td, th {
            border: 1px solid #e3e3e3;
            border-top: 1px solid white;
            border-left-color: #fff;
            font-size: 11pt;
            background: #fdfdfd;
        }

        thead th {
            background: #E9E9E9;
            background: linear-gradient(to bottom, #f9f9f9, #e9e9e9) !important;
            font-weight: 300;
            letter-spacing: 1px;
            padding: 15px 0 5px;
        }

        tbody tr:last-child td {
            border-bottom: 1px solid #ddd;
        }

        td {
            min-width: 75px;
            padding: 3px 6px;
            line-height: 1.25;
        }

        tfoot tr td {
            height: 40px;
            padding: 6px 0 0;
            color: #000;
            text-shadow: 0 0 1px rgba(0,0,0,.25);
            font-family: 'Cambria','Raleway', sans-serif;
            font-weight: 400;
            letter-spacing: 1px;
        }

        tfoot tr td:first-child {
            font-style: italic;
            color: #997B7B;
        }

        a {
            color: #992C2C;
        }

        a:hover {
            color: #BB0000;
        }

        @page {
            margin: .5cm;
        }

        html, body {
            background: #333231;
        }

        header:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            border-top: 12px solid #333;
            border-left: 12px solid #ddd;
            width: 0;
            box-shadow: 1px 1px 2px rgba(0,0,0,.18);
        }</style>
            <link href="https://fonts.googleapis.com/css?family=Nunito:300|Raleway:200,300" rel="stylesheet" type="text/css">
        </head>
        
        <body>
            <header>
                <h1>FACTURE</h1>
                <h2>José Roux − Interactive Design</h2>
            </header>
        
            <section class="flex">
                <dl>
                    <dt>Facture #</dt>
                    <dd>20140603</dd>
                    <dt>Date de facturation</dt>
                    <dd>03.06.2014</dd>
                </dl>
            </section>
        
            <section class="flex">
                <dl class="bloc">
                    <dt>Facturé à:</dt>
                    <dd>
                        Company X &amp; Son Inc.<br>
                        2789 Some street,<br>
                        Big City, Québec, J3X 1J1
                        <dl>
                            <dt>Attn</dt>
                            <dd>Le Big Boss</dd>
                            <dt>Téléphone</dt>
                            <dd>(450) 555-2663</dd>
                            <dt>Courriel</dt>
                            <dd>bigboss@bigcompanylonglongemail.com</dd>
                        </dl>
                    </dd>
                </dl>
        
                <dl class="bloc">
                    <dt>Description de service:</dt>
                    <dd>Développement AIR</dd>
                    <dt>Période totale:</dt>
                    <dd>24 Mai au 2 Juin 2014</dd>
                </dl>
            </section>
        
            <table>
                <thead>
                    <tr>
                        <th>Période</th>
                        <th>Description</th>
                        <th>Heures</th>
                        <th>Taux</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>24 Mai au 2 Juin</td>
                        <td>Développement du jeu Tomatina</td>
                        <td>24&#8202;h</td>
                        <td>20&#8202;$/h</td>
                        <td>480&#8202;$</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">− Faire les chèques payable au nom de moi −</td>
                        <td>Total:</td>
                        <td>480&#8202;$</td>
                    </tr>
                </tfoot>
            </table>
        
            <footer>
                <p>Moi – Informatique − Développement WEB | <a href="http://joseroux.com">joseroux.com</a></p>
                <p>1777 some street in the woods, Wentworth-Nord, Qc, J0T 1Y0 | Tél. 450-555-1000 | <a href="mailto:mail@me.com">mail@me.com</a></p>
            </footer>
        </body>
        HTML;
        return $html;
    }


}
