<?php
// core/PDFHelper.php

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

class PDFHelper extends FPDF
{
    public function __construct()
    {
        parent::__construct();
        $this->SetTextColor(53, 61, 80);
        $this->SetDrawColor(221, 227, 236);
        $this->SetFillColor(245, 248, 252);
        $this->SetLineWidth(0.2);
    }

    public function Header()
    {
        $this->SetY(8);
        $this->SetDrawColor(221, 227, 236);
        $this->SetLineWidth(0.25);
        $this->Line(18, 12, 192, 12);
        $this->SetLineWidth(0.2);
    }

    public function Footer()
    {
        $this->SetY(-14);
        $this->SetFont('Helvetica', 'I', 6);
        $this->SetTextColor(122, 130, 148);
        $this->Cell(0, 6, 'Documento generado automáticamente | Página ' . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }
}