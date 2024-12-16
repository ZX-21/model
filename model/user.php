<?php 

    session_start();
    require_once 'config.php';
    if (!isset($_SESSION['user_login'])) {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
        header('location: signin.php');
    }

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Room Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<?php 

    if (isset($_REQUEST["delete_id"])) {
        $id = $_REQUEST["delete_id"];
        $select_stmt = $conn->prepare("SELECT * FROM data WHERE id = :id");
        $select_stmt->bindparam(":id", $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    
        $delete_stmt = $conn->prepare("DELETE FROM data WHERE id = :id");
        $delete_stmt->bindparam(":id", $id);
        $delete_stmt->execute();
    
        header("Location:user.php");
       }

?>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a href="#" class="navbar-brand">Data Info</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user_login'])): ?>
                            <?php 
                                $user_id = $_SESSION['user_login'];
                                $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <li class="nav-item">
                                <span class="nav-link text-white">Welcome, <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></span>
                            </li>
                            <li class="nav-item">
                                <a href="logout.php" class="btn btn-danger">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a href="signin.php" class="btn btn-success">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

   

    <main class="container my-5">
        <h2 class="text-center mb-4">Data Information</h2>
        <div class="table-responsive">

        <a href="add.php" class="btn btn-outline-primary btn-sm mb-3">
          <i class="bi bi-plus-circle"></i> Add New Data
        </a>

        <table class="table table-bordered table-striped">
    <thead>
        <tr class="table-primary">
            <th>ID</th>
            <th>Name</th>
            <th>Student ID</th>
            <th>Phone</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
           $select_stmt = $conn->prepare("SELECT * FROM data");
           $select_stmt->execute();
           while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row["id"]); ?></td>
            <td><?php echo htmlspecialchars($row["name"]); ?></td>
            <td><?php echo htmlspecialchars($row["student_id"]); ?></td>
            <td><?php echo htmlspecialchars($row["phone"]); ?></td>
            <td>
                <a href="edit.php?update_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
            </td>
            <td>

                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo htmlspecialchars($row['id']); ?>">
                    <i class="bi bi-trash"></i> Delete
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const deleteId = this.getAttribute('data-id'); 


            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {

                    window.location.href = 'user.php?delete_id=' + deleteId;
                }
            });
        });
    });
</script>




        </div>
    </main>

    <footer class="bg-light py-3">
        <div class="container text-center">
            <p>&copy; 2024 Room Reservation System. All rights reserved.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: 'ยินดีต้อนรับ',
                text: 'ยินดีต้อนรับ <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?>!',
                confirmButtonText: 'ตกลง'
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
