<?php session_start(); 
if(isset($_POST['killUser'])){
    if($_POST['killUser'] == "Exit"){
        unset($_SESSION['u']); //Exit from session
    }
}
?>
<?php
//Connect to database
require_once("connect.php");
//Authorization
//Get name and password from form if they are not empty
if(isset($_POST['uName']) && isset($_POST['uPswd'])){
    if(!empty($_POST['uName']) && !empty($_POST['uPswd'])){
        //Get list of users from table
        $userTable = mysqli_query($link, "SELECT * FROM best_users");
        //If table has rows
        if(mysqli_num_rows($userTable) > 0){
            //Read every row
            while($oneUser = mysqli_fetch_assoc($userTable)){
                //If name and password from form are equals to name and password from table
                if($_POST['uName'] == $oneUser['login'] && md5($_POST['uPswd']) == $oneUser['password']){
                    //Create new session
                    $_SESSION['u'] = $oneUser;
                }
            }
        }
    }
}
//Registration
if(isset($_POST['nName']) && isset($_POST['nPswd'])){
    if(!empty($_POST['nName']) && !empty($_POST['nPswd'])){
        $userTable = mysqli_query($link, "SELECT * FROM best_users");
        $name = $_POST['nName'];
        //Hide password by md5
        $pswd = md5($_POST['nPswd']);
        //Query for adding new user
        $addUser = "INSERT INTO best_users (login, password) VALUES ('$name', '$pswd')";
        if(mysqli_num_rows($userTable) > 0){
            //Let's think that it is new user
            $newUser = true;
            while($oneUser = mysqli_fetch_assoc($userTable)){
                if($_POST['nName'] == $oneUser['login'] && md5($_POST['nPswd']) == $oneUser['password']){
                    //User already exists in table -> change value and do nothing
                    $newUser = false;
                }
            }
            //If it is new user
            if($newUser == true){
                //Send query
                mysqli_query($link, $addUser);
            }
        //If table is empty, then add user
        }else{
            mysqli_query($link, $addUser);
        }
    }
}
?>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <?php if(!isset($_SESSION['u'])): //If there is no session, then show form ?>
    <button class="button" id="swapButton">Registration</button>
    <form id="log" action="" method="post">
        user name:<input type="text" name="uName"><br>
        password:<input type="text" name="uPswd"><br>
        <input type="submit" value="Done">
    </form>
    <form id="reg" hidden="true" action="" method="post">
        user name:<input type="text" name="nName"><br>
        password:<input type="text" name="nPswd"><br>
        <input type="submit" value="Done">
    </form>
    <?php else: //If session started, then show some info ?>
    <h2>Welcome <?php echo $_SESSION['u']['login']; ?>!</h2>
    <form action="" method="post">
        <input type="submit" name="killUser" value="Exit">
    </form>
    <?php endif; //End of "if statement"?>
</body>
<script>
    //Add click event to button "Registration"/"Authorization"
    document.getElementById("swapButton").addEventListener("click", function() {
        //If current button's text is "Registration"
        if(document.getElementById("swapButton").innerHTML == "Registration"){
            //Change button's text
            document.getElementById("swapButton").innerHTML = "Authorization";
            //Hide one form
            document.getElementById("log").hidden = true;
            //Unhide another form
            document.getElementById("reg").hidden = false;
        }else{
            document.getElementById("swapButton").innerHTML = "Registration";
            document.getElementById("log").hidden = false;
            document.getElementById("reg").hidden = true;
        }
    }, false);
</script>
</html>
