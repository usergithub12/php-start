
<?php
include_once "connection_database.php";

$errors = array();
$email = '';
$password = '';


function compressImage($source, $destination, $quality) {
      
    $info = getimagesize($source);
  
    if ($info['mime'] == 'image/jpeg') 
      $image = imagecreatefromjpeg($source);
  
    elseif ($info['mime'] == 'image/gif') 
      $image = imagecreatefromgif($source);
  
    elseif ($info['mime'] == 'image/png') 
      $image = imagecreatefrompng($source);
  
    imagejpeg($image, $destination, $quality);
  
  } 
// $image='';
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
   

    if (count($errors) == 0) {
        $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
        $file_name= uniqid('300_').'.jpg';
        $file_save_path=$uploaddir.$file_name;
        //$tmpfname = tempnam("/uploads", "300_");
       //$uploadfile = $uploaddir . $tmpfname;//basename($_FILES['image']['name']);
       if (move_uploaded_file($_FILES['image']['tmp_name'], $file_save_path)) {
           echo "Файл корректен и был успешно загружен.\n";
       } else {
           echo "Возможная атака с помощью файловой загрузки!\n";
       }

       // SAVE PHOTO [COMPRESSED] ============>
    
        // Getting file name
        // $filename = $_FILES['imagefile']['name'];
        // Valid extension
        $valid_ext = array('png','jpeg','jpg');
        // Location
        // $location = "images/".$filename;
        // file extension
        $file_extension = pathinfo($file_save_path, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        // Check extension
        if(in_array($file_extension,$valid_ext)){
          // Compress Image
          
          compressImage($file_save_path,$uploaddir."_10__".$file_name,10);
          compressImage($file_save_path,$uploaddir."_50__".$file_name,50);
          compressImage($file_save_path,$uploaddir."_100__".$file_name,100);
        }else{
          echo "Invalid file type.";
        }
      }
 

    include_once "connection_database.php";
    $email=strip_tags($email);//htmlentities($email);//$dbh->quote($email);
    $hash=password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO tbl_users (email, password, isLock) VALUES (?,?,?)";
    $stmt= $dbh->prepare($sql);
    $res = $stmt->execute([$email, $hash, 0]);
    header('Location: /?g=' . $email);
 
    exit;
}
          






?>






<?php
include "_header.php";
include_once "input-helper.php" ?>

    <div class="row mt-3">
        <div class="offset-md-3 col-md-6">
            <h3>Реєстрація</h3>
            <form method="post" id="form_register" enctype="multipart/form-data">
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