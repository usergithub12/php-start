<?php
include "_header.php";
include_once "input-helper.php";

$errors = array();
$country="";
$file = 'countries.txt';
$dictionary="dictionary.txt";
?>
 <div class="row mt-3">
        <div class="offset-md-3 col-md-6">
            <h3>Save to File</h3>
            <form method="post" >
                <?php create_input("country", "Country", "text", $errors); ?>
               
               <input type="submit" class="btn btn-dark w-100" value="SEND">
                </form>

                <select name="" id="">
<?php
 

   // Открываем файл для получения существующего содержимого
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if(isset($_POST["country"])){
           //if exists in file
           $country=$_POST["country"];
           $data = file_get_contents($file, true);
        if (!strstr($data, $country)) {

            $dictionary_data = file_get_contents($dictionary, true);
            if (strstr($dictionary_data, $country)) {
            $myfile = fopen($file, "a") or die("Unable to open file!");
            fwrite($myfile, $country."\n");
            fclose($myfile);
            }
        }
    
            //read
            $handle = fopen($file, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    // process the line read.
                    echo '<option value="' . $line . '">' .$line .
                   
                     '</option>';
                }
            
                fclose($handle);
            } else {
                // error opening the file.
            } 
        

       }
       
   
   
   
   
   /// READ FROM FILE
   // $readfile = file_get_contents('./'.$file.txt, true);
   
   }
   ?>

           
                </select>
                </div>
                </div>



  
<?php
include "_footer.php"
?>