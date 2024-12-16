

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesform.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   
</head>
<body>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require_once 'config.php';

if (isset($_REQUEST["btn_insert"])) {
    $name = $_REQUEST['txt_name'];
    $student_id = $_REQUEST['txt_studentid'];
    $phone = $_REQUEST['txt_phone'];

    if (empty($name)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter name!',
                confirmButtonText: 'OK'
            });
        </script>";
    } else if (empty($student_id)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please enter student ID!',
                confirmButtonText: 'OK'
            });
        </script>";
    } else if (empty($phone)) {
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
            // Insert data using prepared statements
            $insert_stmt = $conn->prepare("INSERT INTO data (name, student_id ,phone) VALUES (:name, :student_id, :phone)");
            $insert_stmt->bindParam(':name', $name);
            $insert_stmt->bindParam(':student_id', $student_id);
            $insert_stmt->bindParam(':phone', $phone);

            if ($insert_stmt->execute()) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Insert Successfully',
                        text: 'Data has been added successfully!',
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
                    <h3 class="text-center mb-4">Add Data</h3>
                    <form method="post" class="p-4 border rounded shadow-sm">
                        <?php if (isset($errorMsg)): ?>
                            <div class="alert alert-danger">
                                <strong>Error! <?php echo htmlspecialchars($errorMsg); ?></strong>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="txt_name" id="name" placeholder="Enter Name" required>
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" name="txt_studentid" id="student_id" placeholder="Enter Student ID" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="txt_phone" id="phone" placeholder="Enter Phone" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="btn_insert" class="btn btn-success btn btn-primary">Insert</button>
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

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
