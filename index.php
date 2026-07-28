<?php

require_once '../users/init.php';
require_once 'includes/functions.php';  // load functions
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';  // load template

// check for user, and redirect to login if not logged in

// define input fields
$title = 'AI Advisor: Use Case Recommendation Tool';
$company = 'Customer Company Name';
$contactName = 'Customer Contact Name';
$contactEmail = 'Customer Contact Email';
$ae = 'Account Executive';

?>


<style>
    #formheader{
      width: 75%;
      margin: 0px auto;
      background: #2F5496;
      font-family: Georgia, "Times New Roman", Times, serif;
      font-size:2em;
      color: white;
      text-align: center;
      border-top-left-radius:20px;
      border-top-right-radius:20px;
    }
    #quote{
      width: 75%;
      background: #f4f7f8;
      margin: 0px auto;
      padding: 20px;
      border-bottom-right-radius:20px;
      border-bottom-left-radius: 20px;
      font-family: Georgia, "Times New Roman", Times, serif;
      filter: drop-shadow(0.80em 0.80em 0.4em);
    }
    #quote td{
      padding-right: 20px;
      padding-left: 10px;
      padding-top: 5px;
      padding-bottom: 5px;
    }
    #quote tr:nth-child(even){
      background-color: #FFFFFF;
    }
</style>


<br/>
<form action='index.php' enctype='multipart/form-data' method='post'>
    <table id='formheader'><tr><td colspan=2><?php echo e($title); ?></td></tr></table>
    <table id='quote'>
        <tr>
            <td><label for='company'><?php echo e($company); ?></label></td>
            <td><input type='text' id='company' name='company' placeholder="<?php echo e($company); ?>" size='50' required></td>
        </tr><tr>
            <td><label for='contact_name'><?php echo e($contactName); ?></label></td>
            <td><input type='text' id='contact_name' name='contact_name' placeholder="<?php echo e($contactName); ?>" size='50' required></td>
        </tr><tr>
            <td><label for='contact_email'><?php echo e($contactEmail); ?></label></td>
            <td><input type='email' id='contact_email' name='contact_email' placeholder="<?php echo e($contactEmail); ?>" size='50' required></td>
        </tr><tr>
            <td><label for='ae'><?php echo e($ae); ?></label></td>
            <td><input type='text' id='ae' name='ae' placeholder="<?php echo e($ae); ?>" size='50' required></td>
        </tr>
        <tr><td colspan=2 style='padding-bottom:35px;'></td></tr> <?php  // placeholder ?>
    </table>
</form>
<br/>