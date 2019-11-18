<html>
  <head>
    <title>ToyzRUs Ecommerce | Login</title>
  </head>
  <body>
    <form action="login.php" method="post">
      <h2>Login:</h2>
      <input type="text" name="username" placeholder="Username"><br />
      <input type="text" name="password" placeholder="Password"><br />
      <input type="text" name="address" placeholder="Address"><br />
      <label for="registration">Have you been previously registered? </label><input type="checkbox" name="isRegistered" id="registration"><br />
      <small>Check box for login. Leave unchecked to register as a new user.</small><br />
      <!--
      <label for="staff">Are you a member of the staff? </label><input type="checkbox" name="isStaff" id="staff"><br />
      <label for="manager">Are you a manager within the staff? </label><input type="checkbox" name="isManager" id="manager"><br />
      -->
      <input type="submit" value="LOGIN">
    </form>
  </body>
</html>