
<?php
include_once "connection_database.php";

$errors = array();
$email = '';
$password = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$email = $_POST["email"];
    //echo "POST REQUEST = " . $email;
    if (isset($_POST['email']) and !empty($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $errors["email"] = "Поле є обов'язковим";
    }
    if (isset($_POST['password']) and !empty($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        $errors["password"] = "Поле є обов'язковим";
    }
    if (isset($_POST['image']) and !empty($_POST['image'])) {
        //$password = $_POST['image'];
        ;
    } else {
        $errors["image"] = "Поле є обов'язковим";
    }

    if (count($errors) == 0) {
        
try {
    // $dbh = new PDO("mysql:host=$hostname;dbname=college",$username,$password);
    
    // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
    $sql = "INSERT INTO tbl_users (email,password)
    VALUES ('".$_POST["email"]."','".$_POST["password"]."')";
    if ($dbh->query($sql)) {
    echo "<script type= 'text/javascript'>alert('New Record Inserted Successfully');</script>";
    // $sth = $dbh->prepare($sql);
    // $sth->execute();
}
    else{
    echo "<script type= 'text/javascript'>alert('Data not successfully Inserted.');</script>";
    }
    
    $dbh = null;
    }
    catch(PDOException $e)
    {
    echo $e->getMessage();
    }

        header('Location: /?g=' . $email);
        exit;
    }




}

?>






<?php
include "_header.php";
include_once "input-helper.php" ?>

    <div class="row mt-3">
        <div class="offset-md-3 col-md-6">
            <h3>Реєстрація</h3>
            <form method="post" id="form_register">
                <?php create_input("email", "Електронна пошта", "email", $errors); ?>

                <?php create_input("password", "Пароль", "password", $errors); ?>

                <?php create_input("image", "Фото", "file", $errors); ?>

                <img id="prev"/>

                <div class="form-group">
                    <input type="submit" class="btnSubmit" value="Register"/>
                </div>
                <div class="form-group">
                    <a href="#" class="ForgetPwd">Forget Password?</a>
                </div>
            </form>
        </div>

    </div>


<?php
include "_scripts.php";
?>

    <script>
        $(function () {
            $('#form_register input[type=email]').on('input', function () {
                valid_hide($(this));
            });
            $('#form_register input[type=password]').on('input', function () {
                valid_hide($(this));
            });
            $('#form_register #image').on('input', function () {
                valid_hide($(this));
                readURL(this);
            });

            function valid_hide(child) {
                if (child.is(".is-invalid")) {
                    child.removeClass("is-invalid");
                    child.parent().find('.invalid-feedback')[0].remove();
                }
            }
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        //$(this).parent().append("<img src='"+e.target.result+"'/>");
                        $('#prev').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
<?php
include "_footer.php";
?>