<div class="nav-bar menu-bar">
    <p class="nav-application-name">GTAMS</p>

    <div class="container-right">
        <ul>
            <li><a href="register.php">REGISTER</a></li>
            <li><a href="login.php">LOGIN</a></li>
        </ul>
    </div>
</div>

<?php
$page_title = "Login";
require "header.php";
?>

<?php
require "footer.php";
?>

<?php
if(!empty($_POST))
{
  //Selects admins to check if the person logging in is admin.

  $sth = $dbh->prepare("SELECT * FROM system_admins");
  $sth->execute();
  $result = $sth->fetchAll();

  $registered = 0;
    //Test if email/password is in Admin table. Set 1 if yes, 5 is no.
  foreach($result as $r)
  {
    if($r['email'] == $_POST['email'] && $r['password_digest'] == $_POST['password'])
    {
        $_SESSION["type"] = "admin";
        $_SESSION["email"] = $_POST['email'];
        $_SESSION["id"] = $r['id'];
        $registered = 1;
        break;
    }
    else
    {
      $registered = 5;
    }
  }

  //If the person logging in isn't an admin, check if it's a gc_member.
  if($registered == 5 || $registered == 0)
  {
    $sth = $dbh->prepare("SELECT * FROM gc_members");
    $sth->execute();
    $result = $sth->fetchAll();

    foreach($result as $r)
    {
      if($r['email'] == $_POST['email'] && $r['password_digest'] == $_POST['password'])
      {
        $_SESSION["type"] = "gc";
        $_SESSION["email"] = $_POST['email'];
        $_SESSION["id"] = $r['id'];
          $registered = 2;
          break;
      }
      else
      {
        $registered = 5;
      }
    }
  }
  //registered values can be: 1=admin, 2=gc member, 3 and above = failed to login.
  if($registered == 1)
  {
    header('Location: admin-dashboard.php');
  }
  else if($registered == 2)
  {
    header('Location: dashboard.php');
  }
  else if($registered > 2)
  {
    echo "Invalid Username or Password <br>";
  }
}
?>

<?php
require "forms/login-form.php";
?>
