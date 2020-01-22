
<form action="<?php echo $_SERVER['SCRIPT_URL'] ?>" method="post">
    <label for="pass">Enter your password (6 chars or more please):</label>
    <input type="text" name="pass" />
    <input type="submit" value="go" />
</form>

<?php
if(@isset($_POST['pass'])){
    $pass = $_POST['pass'];
    echo 'Your password encrypted is: <strong>'.hash_password($pass).'<strong>';
}