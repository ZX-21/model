<body>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php 

    session_start();
    require_once 'config.php';

    if (isset($_POST['signup'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $c_password = $_POST['c_password'];
        $urole = 'user';

        if (empty($firstname)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณากรอกชื่อ',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else if (empty($lastname)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณากรอกนามสกุล',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else if (empty($email)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณากรอกอีเมล',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'รูปแบบอีเมลไม่ถูกต้อง',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else if (empty($password)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณากรอกรหัสผ่าน',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else if (empty($c_password)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณายืนยันรหัสผ่าน',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else if ($password != $c_password) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'รหัสผ่านไม่ตรงกัน',
                }).then(() => {
                    window.location = 'index.php';
                });
            </script>";
        } else {
            try {

                $check_email = $conn->prepare("SELECT email FROM users WHERE email = :email");
                $check_email->bindParam(":email", $email);
                $check_email->execute();
                $row = $check_email->fetch(PDO::FETCH_ASSOC);
                
                // ตรวจสอบว่ามีผลลัพธ์ใน $row หรือไม่
                if ($row && $row['email'] == $email) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'คำเตือน',
                            html: 'มีอีเมลนี้อยู่ในระบบแล้ว <a href=\"signin.php\">คลิ๊กที่นี่</a> เพื่อเข้าสู่ระบบ',
                        }).then(() => {
                            window.location = 'index.php';
                        });
                    </script>";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users(firstname, lastname, email, password, urole) 
                                            VALUES(:firstname, :lastname, :email, :password, :urole)");
                    $stmt->bindParam(":firstname", $firstname);
                    $stmt->bindParam(":lastname", $lastname);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":password", $passwordHash);
                    $stmt->bindParam(":urole", $urole);
                    $stmt->execute();
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            html: 'สมัครสมาชิกเรียบร้อยแล้ว! <a href=\"signin.php\">คลิ๊กที่นี่</a> เพื่อเข้าสู่ระบบ',
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
