<?php
session_start();
/*
  $connect = mysqli_connect("localhost", "root", "","onlinechat");

        // Check connection
        //echo "Connection successfull";
        if (!$connect) {
          die("Connection to database failed: " . mysqli_connect_error());
          } */
if(isset($_GET['logout'])){
$fp = fopen("dbexec.html", 'a');
fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has been logged out</i><br></div>");
fclose($fp);

session_destroy();
header("Location: index.php"); //Redirect the user
}



function loginForm(){
 
  
echo'
<div id="loginform">
<form action="index.php" method="post">
<p>Enter your login details </p><br>
<label for="name">Username: </label>
<input type="text" name="name" id="name" /><br><br>
<label for="pass">Password: </label>
<input type="password" name="pass" id="pass" /><br><br>
<input type="submit" name="enter" id="enter" value="Login" />


 
 

 



</form>
</div>
';
}

if(isset($_POST['enter'])){
       
 if(($_POST['name'] )!= "" && ($_POST['pass'] )!= "")
 {
     $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
     $_SESSION['pass'] = stripslashes(htmlspecialchars($_POST['pass']));
 
     $user = $_POST['name'];
    $pass = ($_POST['pass']);

     echo $user;
     echo $pass;


    if ($user&&$pass) 
    {
       $connect = mysqli_connect("localhost", "root", "", "onlinechat");

        // Check connection
        echo "Connection successfull";
        if (!$connect) {
          die("Connection failed: " . mysqli_connect_error());
          }
//connect to db
        mysqli_select_db($connect,"onlinechat") or die("unfortunately no db ");
        $query = mysqli_query($connect,"SELECT * FROM user WHERE username='$user'");

        $numrows = mysqli_num_rows($query);
         echo $numrows;

        if ($numrows!=0)
        {
          //while loop

            while ($row = mysqli_fetch_assoc($query))
            {
              $dbusername = $row['username'];
              $dbpassword = $row['password'];
              $dbloggedin = $row['logged_in'];
              echo $dbloggedin;
            }
            if ($dbloggedin==1)     //Checking online status so that same user cannot login from different browser 
            {
            echo "user already logged in";  
             header("Location: index.php");
             echo "user already logged in";
           }
 
            echo $dbpassword;
            echo $pass;
            /*if(($dbusername == $user)&&($dbpassword===$pass))
            {
              echo "Login successfully";
                //mysqli_query($connect,"UPDATE user SET logged_in = 1 WHERE username='$user'");

            }*/
             $query2 = mysqli_query($connect,"SELECT * FROM user WHERE username = '$user' AND password = '$pass'");

             //$res = $query2 or die ("Unable to verify user because " . mysql_error());

     
             $cnt = mysqli_num_rows($res);

     

              if ($cnt == 1)

              {

                 $_SESSION['loggedIn'] = 1;
                 echo "Login successfully";
              }

             else
            {
             echo "incorrect username/password!";
             header("Location: index.php");
            }
         }
        else
        {
          echo "user does not exist!";
          header("Location: index.php");
        }
    } 
 }
    else{
      echo '<span class="error">Not a valid login</span>';
    }
  

}


?>


<!DOCTYPE html>
<html>
<head>
<title>chat</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<?php
if(!isset($_SESSION['name'])){
loginForm();
}
else{
?>
<div id="wrapper">
<div id="menu">
<p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
<p class="logout"><a id="exit" href="#">Exit Chat</a></p>
<div style="clear:both"></div>
</div>

<div id="chatbox"></div>
<?php
if(file_exists("dbexec.html") && filesize("dbexec.html") > 0){
    $handle = fopen("dbexec.html", "r");
    $contents = fread($handle, filesize("dbexec.html"));
    fclose($handle);

    //echo $contents;
}
?>

<form name="message" action="">
<input name="usermsg" type="text" id="usermsg" size="63" />
<input name="submitmsg" type="submit"Â  id="submitmsg" value="Send" />
</form>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js">
</script>

<script type="text/javascript">

$(document).ready(function(){
  
   $("#exit").click(function(){
       var exit = confirm("Are you sure you want to end the session?");
       if(exit==true){window.location = 'index.php?logout=true';}
   });


   $("#submitmsg").click(function(){
      var clientmsg = $("#usermsg").val();
      $.post("post.php", {text: clientmsg});
      $("#usermsg").attr("value", "");
      return false;
   });
   
   setInterval (loadLog, 1500);
                              


function loadLog(){
    var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;

    $.ajax({ url: "dbexec.html",
             cache: false,
             success: function(html){
                $("#chatbox").html(html);
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                if(newscrollHeight > oldscrollHeight){
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
                }
             },
    });
}
});
</script>
<?php
}
?>


</body>
</html>