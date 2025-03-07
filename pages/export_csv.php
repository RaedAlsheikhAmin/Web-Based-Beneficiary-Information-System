<?php
include '../includes/db_connect.php';

// Set headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=assistance_records.csv');

$output = fopen("php://output", "w");

// Column headers
fputcsv($output, array('ID', 'Beneficiary', 'Assistance Type', 'Date Provided', 'Quantity', 'Notes'));

// SQL query with optional filters
$sql = "SELECT a.id, b.name AS beneficiary_name, a.assistance_type, a.date_provided, a.quantity, a.notes
        FROM assistance_records a
        JOIN beneficiaries b ON a.beneficiary_id = b.id
        WHERE 1=1";

// Apply filters from GET parameters (if any)
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


// Execute query and output rows to CSV
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}
fclose($output);
exit();
