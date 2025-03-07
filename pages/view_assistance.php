<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db_connect.php';

// Query for Assistance Type Distribution
$type_distribution_sql = "SELECT assistance_type, COUNT(*) AS count 
                          FROM assistance_records 
                          GROUP BY assistance_type";
$type_distribution_result = $conn->query($type_distribution_sql);

$assistance_types = [];
$type_counts = [];
while ($row = $type_distribution_result->fetch_assoc()) {
    $assistance_types[] = $row['assistance_type'];
    $type_counts[] = $row['count'];
}

// Query for Quantity Provided by Date
$quantity_by_date_sql = "SELECT date_provided, SUM(quantity) AS total_quantity 
                         FROM assistance_records 
                         GROUP BY date_provided 
                         ORDER BY date_provided";
$quantity_by_date_result = $conn->query($quantity_by_date_sql);

$dates = [];
$quantities = [];
while ($row = $quantity_by_date_result->fetch_assoc()) {
    $dates[] = $row['date_provided'];
    $quantities[] = $row['total_quantity'];
}

// Pagination and Sorting
$results_per_page = 10;
$count_sql = "SELECT COUNT(*) AS total FROM assistance_records";
$count_result = $conn->query($count_sql);
$row = $count_result->fetch_assoc();
$total_results = $row['total'];
$total_pages = ceil($total_results / $results_per_page);

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start_limit = ($page - 1) * $results_per_page;

$allowed_sort_columns = ['id', 'beneficiary_name', 'assistance_type', 'date_provided'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort_columns) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';
$next_order = $order === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assistance Records</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
<div class="navbar">
        <a href="index.php">Dashboard</a>
        <a href="register_beneficiary.php">Register Beneficiary</a>
        <a href="add_assistance.php">Add Assistance </a>
        <a href="view_assistance.php">View Assistance Records</a>
        <a href="view_beneficiaries.php">View Beneficiaries</a>
        <a href="logout.php">Logout</a>
    </div>


    <h2>Assistance Records</h2>

    <!-- Export Buttons -->
    <div class="export-buttons">
        <form method="GET" action="export_csv.php" style="display: inline;">
            <button type="submit" class="export-button">Export to CSV</button>
        </form>
        <form method="GET" action="export_pdf.php" style="display: inline;">
            <button type="submit" class="export-button">Export to PDF</button>
        </form>
    </div>

    <!-- Search Form -->
    <form method="GET" action="view_assistance.php">
        <label>Beneficiary Name:</label>
        <input type="text" name="beneficiary_name" placeholder="Enter name" value="<?php echo isset($_GET['beneficiary_name']) ? $_GET['beneficiary_name'] : ''; ?>">
        
        <label>Assistance Type:</label>
        <input type="text" name="assistance_type" placeholder="Enter type" value="<?php echo isset($_GET['assistance_type']) ? $_GET['assistance_type'] : ''; ?>">
        
        <label>Date Provided:</label>
        <input type="date" name="date_provided" value="<?php echo isset($_GET['date_provided']) ? $_GET['date_provided'] : ''; ?>">
        
        <label>Start Date:</label>
        <input type="date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">

        <label>End Date:</label>
        <input type="date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">

        <button type="submit" class= button>Search</button>
        <a href="view_assistance.php" class= reset-button>Reset</a>
    </form>

    <!-- Chart Container -->
    <div class="chart-container">
        <div>
            <h3>Assistance Type Distribution</h3>
            <canvas id="typeChart"></canvas>
        </div>
        <div>
            <h3>Quantity Provided by Date</h3>
            <canvas id="quantityChart"></canvas>
        </div>
    </div>

    <!-- Assistance Records Table -->
    <table>
        <tr>
            <th><a href="view_assistance.php?sort=id&order=<?php echo $next_order; ?>">ID</a></th>
            <th><a href="view_assistance.php?sort=beneficiary_name&order=<?php echo $next_order; ?>">Beneficiary</a></th>
            <th><a href="view_assistance.php?sort=assistance_type&order=<?php echo $next_order; ?>">Assistance Type</a></th>
            <th><a href="view_assistance.php?sort=date_provided&order=<?php echo $next_order; ?>">Date Provided</a></th>
            <th>Quantity</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>

        <?php
        // SQL query with filters, sorting, and pagination
        $sql = "SELECT a.id, b.name AS beneficiary_name, a.assistance_type, a.date_provided, a.quantity, a.notes
                FROM assistance_records a
                JOIN beneficiaries b ON a.beneficiary_id = b.id
                WHERE 1=1";

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

        if (!empty($_GET['start_date'])) {
            $start_date = $_GET['start_date'];
            $sql .= " AND a.date_provided >= '$start_date'";
        }
        if (!empty($_GET['end_date'])) {
            $end_date = $_GET['end_date'];
            $sql .= " AND a.date_provided <= '$end_date'";
        }

        $sql .= " ORDER BY $sort $order LIMIT $start_limit, $results_per_page";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["beneficiary_name"] . "</td>";
                echo "<td>" . $row["assistance_type"] . "</td>";
                echo "<td>" . $row["date_provided"] . "</td>";
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td>" . $row["notes"] . "</td>";
                echo "<td>
                        <a href='edit_assistance.php?id=" . $row["id"] . "'class='button'>Edit</a>|
                        <a href='delete_assistance.php?id=" . $row["id"] . "'class='delete-button' onclick=\"return confirm('Are you sure you want to delete this record?')\">Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No assistance records found</td></tr>";
        }
        ?>
    </table>

    <!-- Pagination Links -->
    <div>
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='view_assistance.php?page=$i'>$i</a> ";
            }
        }
        ?>
    </div>

</div>

<script>
    // Assistance Type Distribution - Pie Chart
    const typeChartCtx = document.getElementById('typeChart').getContext('2d');
    const typeChart = new Chart(typeChartCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($assistance_types); ?>,
            datasets: [{
                data: <?php echo json_encode($type_counts); ?>,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Assistance Type Distribution' }
            }
        }
    });

    // Quantity Provided by Date - Bar Chart
    const quantityChartCtx = document.getElementById('quantityChart').getContext('2d');
    const quantityChart = new Chart(quantityChartCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Total Quantity',
                data: <?php echo json_encode($quantities); ?>,
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Quantity Provided by Date' }
            },
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { title: { display: true, text: 'Quantity' }, beginAtZero: true }
            }
        }
    });
</script>

</body>
</html>
