<body>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php 

    session_start();
    require_once 'config.php';

    if (isset($_POST['signin'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณากรอกอีเมล'
                }).then(() => {
                    window.location = 'signin.php';
                });
            </script>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'รูปแบบอีเมลไม่ถูกต้อง'
                }).then(() => {
                    window.location = 'signin.php';
                });
            </script>";
        } else if (empty($password)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณากรอกรหัสผ่าน'
                }).then(() => {
                    window.location = 'signin.php';
                });
            </script>";
        } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร'
                }).then(() => {
                    window.location = 'signin.php';
                });
            </script>";
        }
         else {
            try {

                $check_data = $conn->prepare("SELECT * FROM users WHERE email = :email");
                $check_data->bindParam(":email", $email);
                $check_data->execute();
                $row = $check_data->fetch(PDO::FETCH_ASSOC);

                if ($check_data->rowCount() > 0) {

                    if ($email == $row['email']) {
                        if (password_verify($password, $row['password'])) {
                            if ($row['urole'] == 'admin') {
                                $_SESSION['admin_login'] = $row['id'];
                                echo "<script>Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'เข้าสู่ระบบในฐานะผู้ดูแลระบบ', showConfirmButton: false, timer: 1500 }); setTimeout(() => { window.location.href = 'admin.php'; }, 1600);</script>";
                            } else {
                                $_SESSION['user_login'] = $row['id'];
                                echo "<script>Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'เข้าสู่ระบบสำเร็จ', showConfirmButton: false, timer: 1500 }); setTimeout(() => { window.location.href = 'user.php'; }, 1600);</script>";
                            }
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'รหัสผ่านผิด'
                                }).then(() => {
                                    window.location = 'signin.php';
                                });
                            </script>";
                        }
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'อีเมลผิด'
                                }).then(() => {
                                    window.location = 'signin.php';
                                });
                            </script>";
                        }
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่มีข้อมูลในระบบ'
                                }).then(() => {
                                    window.location = 'index.php';
                                });
                            </script>";
                        }
                        

            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

?>

</body>
