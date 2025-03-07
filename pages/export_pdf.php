<?php
include '../includes/db_connect.php';
require('../includes/fpdf/fpdf.php');

class PDF extends FPDF {
    // Page header
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Assistance Records', 0, 1, 'C');
        $this->Ln(10);
    }

    // Page footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Create a new PDF document
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);

// Column headers
$pdf->Cell(10, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Beneficiary', 1);
$pdf->Cell(40, 10, 'Assistance Type', 1);
$pdf->Cell(30, 10, 'Date Provided', 1);
$pdf->Cell(20, 10, 'Quantity', 1);
$pdf->Cell(50, 10, 'Notes', 1);
$pdf->Ln();



// SQL query with optional filters
$sql = "SELECT a.id, b.name AS beneficiary_name, a.assistance_type, a.date_provided, a.quantity, a.notes
        FROM assistance_records a
        JOIN beneficiaries b ON a.beneficiary_id = b.id
        WHERE 1=1";

// Apply filters from GET parameters if they exist
if (!empty($_GET['beneficiary_name'])) {
    $beneficiary_name = $_GET['beneficiary_name'];
    $sql .= " AND b.name LIKE '%$beneficiary_name%'";
}
if (!empty($_GET['assistance_type'])) {
    $assistance_type = $_GET['assistance_type'];
    $sql .= " AND a.assistance_type LIKE '%$assistance_type%'";
}
if (!empty($_GET['date_provided'])) {
    $date_provided = $_GET['date_provided'];
    $sql .= " AND a.date_provided = '$date_provided'";
}
// In both export_csv.php and export_pdf.php
if (!empty($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
    $sql .= " AND a.date_provided >= '$start_date'";
}
if (!empty($_GET['end_date'])) {
    $end_date = $_GET['end_date'];
    $sql .= " AND a.date_provided <= '$end_date'";
}

// Execute query and output rows to PDF
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pdf->SetFont('Arial', '', 10); // Reset font for data rows
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(10, 10, $row['id'], 1);
        $pdf->Cell(40, 10, $row['beneficiary_name'], 1);
        $pdf->Cell(40, 10, $row['assistance_type'], 1);
        $pdf->Cell(30, 10, $row['date_provided'], 1);
        $pdf->Cell(20, 10, $row['quantity'], 1);
        $pdf->Cell(50, 10, $row['notes'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No records found', 1, 1, 'C');
}

// Output the PDF for download
$pdf->Output('D', 'assistance_records.pdf');
exit();
