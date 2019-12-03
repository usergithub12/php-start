<?php 
 $invalid = '';
    include_once "connection_database.php";

    session_start();

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
            // if (isset($_POST['image']) and !empty($_POST['image'])) {
            //     //$password = $_POST['image'];
            //     ;
            // } else {
            //     $errors["image"] = "Поле є обов'язковим";
            // }
            if (count($errors) == 0) {
               
                
                // $password = md5($password);
                // include_once "connection_database.php";
                $query = $dbh->prepare('SELECT islock, password FROM tbl_users WHERE email = ?');
                $query->execute(array($email));
                if ($results = $query->fetch(PDO::FETCH_ASSOC)) {
                    if (password_verify($password, $results['password'])) {
                        echo $results['islock'];
                        $_SESSION["email"]= $email;
                        //header('Location: /?g=' . $email);
                        header('Location: /UserProfile.php');
                        exit;
                    }
                } else {
                    $invalid = "Не валідні дані";
                }
          
        }
    
}

    

        // header('Location: /?g='.$email);
        // exit;
    
?>

<?php
include "_header.php";
?>
        <div class="login-container">
            <div class="row">
                <div class="offset-md-3 col-md-6 login-form-1">
                    <h3>Login for Form 1</h3>
                    <form method="post">
                        <div class="form-group">
                            <input type="text" 
                                name="email"
                                class="form-control" 
                                placeholder="Your Email *" 
                                value="" />
                        </div>
                        <div class="form-group">
                            <input type="password" 
                                name="password"
                                class="form-control" 
                                placeholder="Your Password *" 
                                value="" />
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btnSubmit" value="Login" />
                        </div>
                        <div class="form-group">
                            <a href="#" class="ForgetPwd">Forget Password?</a>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>

<?php
include "_footer.php";
?>