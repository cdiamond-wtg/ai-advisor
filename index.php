<?php

require_once '../users/init.php';
require_once __DIR__ . '/includes/functions.php'; 
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';  // load template

// check for user, and redirect to login if not logged in

// define input fields
$title = 'AI Advisor';
$company = 'Customer Company Name';
$ae = 'Account Executive';

// get questions
$questions = getQuestions();

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
      padding-left: 5px;
      padding-top: 5px;
      padding-bottom: 5px;
    }
    #quote tr:nth-child(even){
      background-color: #FFFFFF;
    }
    .questions {
        --fieldset-padding: 25px;
    }
    .questions fieldset {
      padding: var(--fieldset-padding);
    }
    .questions legend {
      font-size: 1em;
      font-weight: bold;
    }
    .questions label {
      display: block;
      margin-bottom: 5px;
    }
    .questions input {
      margin-right: 10px;
    }
    .questions span {
        display: block;
        margin-left: var(--fieldset-padding);
        font-size: 0.75em;
        color: #555;
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
            <td><label for='ae'><?php echo e($ae); ?></label></td>
            <td><input type='text' id='ae' name='ae' placeholder="<?php echo e($ae); ?>" size='50' required></td>
        </tr>
        <tbody class='questions'>
        <?php foreach ($questions as $question): ?>
            <?php $inputName = $question['name'] . ($question['type'] === 'checkbox' ? '[]' : ''); ?>
            <tr>
                <td colspan=2>
                    <fieldset>
                        <legend><?php echo e($question['question']); ?></legend>
                        <?php foreach ($question['answers'] as $index => $answer): ?>
                            <?php 
                            $value = 'answer_' . ($index + 1);
                            $subfield = $question['subfields'][$index] ?? null;
                            ?>
                            <label>
                                <input 
                                    type="<?php echo e($question['type']); ?>"
                                    name="<?php echo e($inputName); ?>" 
                                    value="<?php echo e($value); ?>" 
                                    required
                                ><?php echo e($answer); ?>
                                <?php if (isset($subfield)): ?>
                                    <span><?php echo e($subfield); ?></span>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    </fieldset>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</form>
<br/>