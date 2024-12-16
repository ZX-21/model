<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require_once 'config.php';

if (isset($_REQUEST["update_id"])) {
    try {
        $id = $_REQUEST["update_id"];
        $select_stmt = $conn->prepare("SELECT * FROM data WHERE id = :aid");
        $select_stmt->bindParam(":aid", $id);
        $select_stmt->execute();
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $name = $row['name'];
            $student_id = $row['student_id'];
            $phone = $row['phone'];
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if (isset($_POST["btn_update"])) {
    $name_up = $_POST["txt_name"];
    $student_id_up = $_POST["txt_studentid"];
    $phone_up = $_POST["txt_phone"];

    if (empty($name_up)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter name!',
                confirmButtonText: 'OK'
            });
        </script>";
    } else if (empty($student_id_up)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter student ID!',
                confirmButtonText: 'OK'
            });
        </script>";
    } else if (empty($phone_up)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter phone!',
                confirmButtonText: 'OK'
            });
        </script>";
    } else {
        try {
            $id_up = $_REQUEST["update_id"]; 
            $update_stmt = $conn->prepare("UPDATE data SET name = :name, student_id = :student_id, phone = :phone WHERE id = :id");
            $update_stmt->bindParam(':name', $name_up);
            $update_stmt->bindParam(':student_id', $student_id_up);
            $update_stmt->bindParam(':phone', $phone_up);
            $update_stmt->bindParam(':id', $id_up);

            if ($update_stmt->execute()) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Update Successfully',
                        text: 'Data has been updated successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location = 'user.php';
                    });
                </script>";
            }
        } catch(PDOException $e) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an error: " . $e->getMessage() . "',
                    showConfirmButton: true
                });
            </script>";
        }
    }
}
?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="user.php">Data Info</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h3 class="text-center mb-4">Update Data</h3>
                    <form method="post" class="p-4 border rounded shadow-sm">
                        <?php if (isset($errorMsg)): ?>
                            <div class="alert alert-danger">
                                <strong>Error! <?php echo htmlspecialchars($errorMsg); ?></strong>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="txt_name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" name="txt_studentid" id="student_id" value="<?php echo htmlspecialchars($student_id); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="txt_phone" id="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="btn_update" class="btn btn-success btn-primary">Update</button>
                        </div>
                        <div class="text-center mt-3">
                            <a href="user.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-3 bg-light mt-4">
        <p>&copy; 2024 Room Reservation System. All rights reserved.</p>
    </footer>

</body>
</html>
