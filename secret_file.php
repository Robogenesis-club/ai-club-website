<?php
include 'config.php'; // This now handles session_start() and both $conn and $conn_member

// Optional: Only allow logged-in users to access
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // redirect to login if not logged in
    exit();
}

if (!isset($conn_member) || !$conn_member) {
    // Member DB not connected — show friendly message and log the issue
    error_log('robophp: $conn_member is null in secret_file.php');
    echo '<h2>Administration area temporarily unavailable</h2><p>Member database is not connected. Please check your database setup (create the `member` database and import the SQL).</p>';
    exit();
}

// Fetch members from 'personal' table
$sql = "SELECT * FROM personal ORDER BY bennettid";
$result = $conn_member->query($sql);

// Stats
$total_members = 0;
$hosteller_count = 0;

if ($result) { // Check if query was successful
    $total_members = $result->num_rows;
    if ($result->num_rows > 0) {
        $result->data_seek(0); // Reset pointer to the beginning
        while($row = $result->fetch_assoc()) {
            if (strtolower($row['accomodation']) === 'hosteller') $hosteller_count++;
        }
        $result->data_seek(0); // Reset pointer again for table display
    }
} else {
    error_log('robophp: Error fetching members: ' . $conn_member->error);
    // You might want to display an error message to the admin here
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - RoboGenesis</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --bg: #0a0b10; --card: #121528; --text: #e6ecff;
        --muted: #9aa3b2; --primary: #6ea8ff; --stroke: rgba(255,255,255,.08);
        --radius: 18px;
    }
    * { box-sizing: border-box; margin:0; padding:0; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); padding:20px; }
    .container { max-width: 1400px; margin:0 auto; }
    .admin-nav { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; padding:15px; background:var(--card); border-radius:var(--radius); }
    .admin-actions a { margin-left:10px; text-decoration:none; padding:8px 12px; border-radius:6px; color:white; }
    .btn-secondary { background:#9aa3b2; } .btn-danger { background:#e74c3c; }
    .header { text-align:center; margin-bottom:20px; }
    .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; margin-bottom:20px; }
    .stat-card { background:var(--card); padding:20px; border-radius:var(--radius); text-align:center; }
    .stat-number { font-size:2rem; color:var(--primary); margin-bottom:5px; }
    .stat-label { color:var(--muted); font-size:0.9rem; }
    .table-container { overflow-x:auto; background:var(--card); border-radius:var(--radius); padding:10px; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:12px; text-align:left; border-bottom:1px solid var(--stroke); }
    th { background:#0f1221; color:var(--primary); position:sticky; top:0; }
    tr:hover { background: rgba(255,255,255,0.03); }
    .action-buttons button { margin-right:5px; padding:6px 10px; border:none; border-radius:5px; cursor:pointer; }
    .btn-primary { background: var(--primary); color:#fff; }
    .btn-danger { background: #e74c3c; color:#fff; }
</style>
</head>
<body>
<div class="container">
    <div class="admin-nav">
        <div>Admin: <?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
        <div class="admin-actions">
            <a href="index.php" class="btn-secondary"><i class="fas fa-home"></i> Home</a>
            <a href="logout.php" class="btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="header">
        <h1>Admin Dashboard</h1>
        <p>Manage all members of RoboGenesis Club</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_members; ?></div>
            <div class="stat-label">Total Members</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $hosteller_count; ?></div>
            <div class="stat-label">Hostellers</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $total_members - $hosteller_count; ?></div>
            <div class="stat-label">Day Scholars</div>
        </div>
    </div>

    <div class="table-container">
        <?php if ($result && $result->num_rows > 0): // Check if $result is valid and has rows ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Bennett ID</th>
                    <th>Contact</th>
                    <th>Accommodation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr id="row-<?php echo $row['bennettid']; ?>">
                    <td><?php echo htmlspecialchars($row['Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['bennettid']); ?></td>
                    <td><?php echo htmlspecialchars($row['number']); ?></td>
                    <td style="color: <?php echo strtolower($row['accomodation'])==='hosteller'?'#6ea8ff':'#ff7a59'; ?>">
                        <?php echo htmlspecialchars($row['accomodation']); ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-primary" onclick="viewMember('<?php echo $row['bennettid']; ?>')"><i class="fas fa-eye"></i> View</button>
                            <button class="btn-danger" onclick="deleteMember('<?php echo $row['bennettid']; ?>')"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align:center; color:#aaa;">No members found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function viewMember(id) {
    alert('View member with ID: ' + id);
}

function deleteMember(id) {
    if(confirm('Delete member with ID: ' + id + '?')){
        fetch('delete_member.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                alert('Member deleted!');
                // remove row from table without reload
                const row = document.getElementById('row-' + id);
                if(row) row.remove();
                // Optionally, update stats without full reload
                // You'd need to fetch new counts or decrement existing ones
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(err => alert('Error: ' + err));
    }
}
</script>
</body>
</html>

<?php
// Close connections at the end of the script
if ($conn) $conn->close();
if ($conn_member) $conn_member->close();
?>
