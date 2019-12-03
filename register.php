<?php
$errors = array();
$email = '';
$password = '';
session_start();

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
    // if (isset($_POST['image']) and !empty($_POST['image'])) {
        //  $image = $_POST['image'];
    //     ;
    // } else {
    //     $errors["image"] = "Поле є обов'язковим";
    // }

//==============CAPTCHA=============
if($_POST['code'] == $_SESSION['rand_code']) {
    // send email
    $accept = "Thank you for contacting me.";
 
} else {
    $errors["captcha"] = "Please verify that you typed in the correct code.";
    $error = "Please verify that you typed in the correct code.";
 
}

    
    if (count($errors) == 0) {
// Add class User

class User { 
    public $email = ""; 
    public $password = ""; 
     
    
    function show() { 
        echo $this->email;
        echo $this->password;
      
    } 
    function toDB() { 
        include_once "connection_database.php";

        $email=strip_tags($this->email);//htmlentities($email);//$dbh->quote($email);
        $hash=password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO tbl_users (email, password) VALUES (?,?)";
        $stmt= $dbh->prepare($sql);
        $res = $stmt->execute([$email, $hash]);
        header('Location: /?g=' . $email);
         //mysqli_connect()
         exit;
      
    } 
} 

$user = new User(); 
$user->email=$email;
$user->password=$password;
//$user->show();
//$user->toDB();




        //==============================================
        $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/';
        $file_name= uniqid('300_').'.jpg';
        $file_save_path=$uploaddir.$file_name;
        my_image_resize(600,400,$file_save_path,'image');

        $_SESSION['image'] = $file_name;

        $imageSmall= resize_image($_FILES['image']['tmp_name'],600,400);
       if (move_uploaded_file($_FILES['image']['tmp_name'], $file_save_path)) {
           echo "Файл корректен и был успешно загружен.\n";
       } else {
           echo "Возможная атака с помощью файловой загрузки!\n";
       }
       include_once "connection_database.php";

       $email=strip_tags($email);//htmlentities($email);//$dbh->quote($email);
       $hash=password_hash($password, PASSWORD_DEFAULT);
       $sql = "INSERT INTO tbl_users (email, password, isLock,Image) VALUES (?,?,?,?)";
       $stmt= $dbh->prepare($sql);
       $res = $stmt->execute([$email, $hash, 0,$file_name]);
       header('Location: /?g=' . $email);
        //mysqli_connect()
        exit;
    }
}
function my_image_resize($width, $height, $path, $inputName) //32x32
{
    //Оригінал висота і ширина
    list($w,$h)= getimagesize($_FILES[$inputName]['tmp_name']); //204x247
    $maxSize=0;
    //Обчислюємо максмильан знечення або ширина або висота
    if(($w>$h) and ($width>$height)) //204>247 and 32>32
        $maxSize=$width;
    else
        $maxSize=$height; //32
    //MaxSize=32
    $width=$maxSize; //32
    $height=$maxSize; //32
    $ration_orig=$w/$h; //204/247=0.82
    if(1>$ration_orig) //1>0.82 вірно
    {
        $width=ceil($height*$ration_orig); /*32*0.82=26.24 = 27 */     //34- all //10- records page  ceil(3.4)
    }
    else//Хибно
    {
        $height=ceil($width/$ration_orig);
    }
    //27x32
    //Створюємо новий файл
    $imgString=file_get_contents($_FILES[$inputName]['tmp_name']);
    $image=imagecreatefromstring($imgString);
    $tmp=imagecreatetruecolor($width,$height); //розмір нового зображення 27x32
    imagecopyresampled($tmp,$image,
        0,0,
        0,0,
        $width, $height,
        $w,$h
    );
    //Збереження зображення
    switch($_FILES[$inputName]['type'])
    {
        case 'image/jpeg':
            imagejpeg($tmp,$path,30);
            break;
        case 'image/png':
            imagepng($tmp,$path,0);
            break;
        case 'image/gif':
            imagegif($tmp,$path);
            break;
        default:
            exit;
            break;
    }
    return $path;
    //Очисчаємо память
    imagedestroy($image);
    imagedestroy($tmp);
}
function resize_image($file, $w, $h, $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $dst;
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

                <!-- <p><textarea name="message"></textarea></p> -->
    <img src="captcha.php"/>
    <p><input type="text" name="code" /> Are you human?</p>

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