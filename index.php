<?php
include "_header.php";
include_once "connection_database.php";
// session_start(); 

// if (!isset($_SESSION['email'])) {
//   $_SESSION['msg'] = "You must log in first";
//   header('location: login.php');
// }


?>
    <h1>Наші користувачі</h1>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Email</th>
      <th scope="col">IsLock</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sth = $dbh->prepare("SELECT Id, Email, IsLock FROM `tbl_users`");
    $sth->execute();
    
    while($result = $sth->fetch(PDO::FETCH_ASSOC))
    {
        echo '
        <tr>
            <th scope="row">'.$result["Id"].'</th>
            <td>'.$result["Email"].'</td>
            <td>'.$result["IsLock"].'</td>
        </tr>
        ';
    }
    ?>
  </tbody>
</table>

<?php
include "_footer.php";
?>