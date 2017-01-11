<?php

function employee_list() {
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
  <style type="text/css">
      .swat{
      text-align: center;"
  }
  </style>
</head>
<body>
</br>
<div class="container">
  <table class="table" border="1">
    <thead>
      <tr>
        <th class='swat'>SNo</th>
        <th class="swat">Name</th>
        <th class='swat'>Role</th>
        <th class="swat">Contact</th>
      </tr>
    </thead>
    <tbody>
    <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'employee_list';
        $employees = $wpdb->get_results("SELECT id,name,contact,role from $table_name");
        foreach ($employees as $employee) {
            ?>
      <tr class="success">
        <td class='swat'><?= $employee->id; ?></td>
        <td class='swat'><?= $employee->name; ?></td>
        <td class='swat'><?= $employee->role; ?></td>
        <td class='swat'><?= $employee->contact; ?></td>
      </tr>  
       <?php } ?>    
    </tbody>
  </table>
</div>
<?php }
add_shortcode('short_employee_list', 'employee_list');
?>
</body>
</html>
    