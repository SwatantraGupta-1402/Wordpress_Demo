<?php

function add_new_employee() {

    if (isset($_POST['insert'])) {

        global $wpdb;
        $name = $_POST['name'];
        $role = $_POST['role'];
        $contact = $_POST['contact'];
//        var_dump($_POST);

        $table_name = $wpdb->prefix . 'employee_list';
        $wpdb->insert(
                "$table_name", //table
                array('name' => $name, 'role' => $role, 'contact' => $contact), //data
                array('%s', '%s', '%s') //data format
        );

        $msg = "Employee Added Successfully...";
    }
    ?>
    <!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
 </br>
<div class="alert alert-success fade in">
<a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
  <strong ><?php echo $msg; ?></strong>
</div>
  <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <div class="form-group col-md-4">
      <label for="usr">Name:</label>
     <input type="text" class="form-control" placeholder="Enter Your Name" name="name" required="required" />
    </div>
    <div class="form-group col-md-4">
      <label for="pwd">Role</label>
     <input type="text" placeholder="Enter Your Role" class="form-control" name="role" required="required" />
    </div>
     <div class="form-group col-md-4">
      <label for="pwd">Contact</label>
     <input type="text" placeholder="Enter Contact No" class="form-control" name="contact" required="required" />
    </div>
     <div class="form-group">
    <button type="submit" name="insert" class="btn btn-primary active">Success</button>
    </div>
  </form>
</div>
 <?php
}
add_shortcode('short_employee_list', 'employee_list');
?>
</body>
</html>
    
   