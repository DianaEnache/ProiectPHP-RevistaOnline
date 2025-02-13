<?php
require '../connect.php'; 

// CSRF Protection
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// viziatori totali(unici)
$total_visitors_query = "SELECT COUNT(DISTINCT ip_address) AS total FROM analytics";
$total_visitors = mysqli_fetch_assoc(mysqli_query($connect, $total_visitors_query))['total'];

// vizitatorii(unici curenti)
$today_visitors_query = "SELECT COUNT(DISTINCT ip_address) AS total FROM analytics WHERE visit_date >= CURDATE()";
$today_visitors = mysqli_fetch_assoc(mysqli_query($connect, $today_visitors_query))['total'];

// total vizualizari pagina
$total_views_query = "SELECT COUNT(*) AS views FROM analytics";
$total_views = mysqli_fetch_assoc(mysqli_query($connect, $total_views_query))['views'];

// cele mai vizitate pagini
$popular_pages_query = mysqli_query($connect, "
    SELECT page, COUNT(*) AS visits 
    FROM analytics 
    GROUP BY page 
    ORDER BY visits DESC 
    LIMIT 5
");

$popular_pages = [];
while ($row = mysqli_fetch_assoc($popular_pages_query)) {
    $popular_pages[] = $row;
}

$page_labels = array_column($popular_pages, 'page');
$page_visits = array_column($popular_pages, 'visits');

?>



<!-- mesaj after stergere analytics -->
<?php if (isset($_GET['reset']) && $_GET['reset'] === 'success') : ?>
    <div class="alert alert-success">Analytics data has been reset successfully!</div>
<?php endif; ?>

<!--  button Reset Analytics -->
<form method="POST" action="reset_analytics.php" onsubmit="return confirm('Are you sure you want to reset all analytics data?');">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <button type="submit" class="btn btn-danger mb-3">Reset Analytics</button>
</form>


<div class="row">
    <div class="col-md-3">
        <div class="card text-white p-3" style="background-color:rgb(43, 71, 211);">
            <h4>Total Visitors</h4>
            <p><?= $total_visitors ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white p-3" style="background-color:rgb(100, 49, 212);">
            <h4>Today's Visitors</h4>
            <p><?= $today_visitors ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white p-3" style="background-color:rgb(167, 38, 200);">
            <h4>Total Page Views</h4>
            <p><?= $total_views ?></p>
        </div>
    </div>
</div>


<!-- Cele mai vizitate pagini -->
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card bg-dark text-white p-3" >
            <h4>Most Visited Pages</h4>
            <ul class="list-group">
                <?php foreach ($popular_pages as $page) : ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?= htmlspecialchars($page['page']) ?>
                        <span class="badge bg-info"><?= $page['visits'] ?> views</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Chart -->
    <div class="col-md-6">
        <canvas id="pageViewsChart"></canvas>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('pageViewsChart').getContext('2d');
    var pageViewsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($page_labels) ?>,
            datasets: [{
                label: 'Page Views',
                data: <?= json_encode($page_visits) ?>,
                backgroundColor: 'rgba(133, 54, 235, 0.6)'
            }]
        }
    });
</script>
