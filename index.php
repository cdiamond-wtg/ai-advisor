<?php

require_once '../users/init.php';
require_once __DIR__ . '/includes/functions.php'; 
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';  // load template

// check for user, and redirect to login if not logged in

// define input fields
$title = 'AI Advisor: Use Case Recommendation Tool';
$company = 'Customer Company Name';
$contactName = 'Customer Contact Name';
$contactEmail = 'Customer Contact Email';
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
    .questions fieldset {
        margin: 5px 45px;
    }
    .questions legend {
        font-size: 1em;
        font-weight: bold;
        margin-bottom: 0.25em;
    }
    .questions label {
        display: block;
        margin-bottom: 5px;
    }
    .questions input {
        margin-right: 10px;
    }
    .questions.collapsed > tr:not(:first-child) {
        display: none;
    }
    .questions > tr:first-child th {
        padding: 0.5rem 0.5rem 0 0.5rem;
    }
    .questions:last-of-type > tr:first-child th {
        padding-bottom: 0.5rem;
    }
    .questions > tr:first-child button {
        width: 100%;
        padding: 10px 20px;
        font-size: 1.25em;
        font-weight: bold;
        text-align: left;
        border: 0;
    }
    .questions > tr:first-child button::before {
        content: "▼";
        font-size: 0.5em;
        margin-right: 5px;
        vertical-align: middle;
        transform: translateY(-1px);
        display: inline-block;
    }
    .questions.collapsed > tr:first-child button::before {
        content: "▶";
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
        
        <?php foreach ($questions as $categoryIndex => $category): ?>         
            <tbody class='questions collapsed'>
                <tr><th colspan=2>
                    <button type='button' aria-expanded='false'><?php echo e($category['category']); ?></button>
                </th></tr>
                <?php foreach ($category['questions'] as $question): ?>
                    <?php 
                    $inputName = $question['name'] . ($question['type'] === 'checkbox' ? '[]' : ''); 
                    $isRequired = !empty($question['required']);
                    ?>
                    <tr>
                        <td colspan=2>
                            <fieldset>
                                <legend><?php echo e($question['question']); ?></legend>
                                <?php if ($question['type'] === 'text'): ?>
                                    <input 
                                        type='text'
                                        id="<?php echo e($inputName); ?>"
                                        name="<?php echo e($inputName); ?>"
                                        size=50
                                        <?php if ($isRequired): ?>required<?php endif; ?>
                                    >
                                <?php elseif ($question['type'] === 'text_group'): ?>
                                    <?php foreach ($question['fields'] as $field): ?>
                                        <?php 
                                        $fieldName = strtolower(str_replace(' ', '_', $field));
                                        $fieldId = $question['name'] . '_' . $fieldName; 
                                        ?>
                                        <label style="margin-bottom: 1px" for="<?php echo e($fieldId); ?>"><?php echo e($field); ?></label>
                                        <input style="margin-bottom: 3px"
                                            type='text'
                                            id="<?php echo e($fieldId); ?>"
                                            name="<?php echo e($question['name']); ?>[<?php echo e($fieldName); ?>]"
                                            <?php if ($isRequired): ?>required<?php endif; ?>
                                        >
                                    <?php endforeach; ?>
                                <?php elseif ($question['type'] === 'select'): ?>
                                    <select
                                        id="<?php echo e($inputName); ?>"
                                        name="<?php echo e($inputName); ?>"
                                        <?php if ($isRequired): ?>required<?php endif; ?> 
                                    ><option value=''></option>
                                    <?php foreach ($question['answers'] as $index => $answer): ?>
                                        <option value="<?php echo e('answer_' . ($index + 1)); ?>">
                                            <?php echo e($answer); ?>
                                        </option>
                                    <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <?php foreach ($question['answers'] as $index => $answer): ?>
                                        <label>
                                            <input 
                                                type="<?php echo e($question['type']); ?>"
                                                name="<?php echo e($inputName); ?>" 
                                                value="<?php echo e('answer_' . ($index + 1)); ?>" 
                                                <?php if ($isRequired && $index === 0): ?>required<?php endif; ?>
                                            ><?php echo e($answer); ?>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </fieldset>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php endforeach; ?>

    </table>
</form>
<br/><br/>


<script>
    document.querySelectorAll('.questions > tr:first-child button').forEach(function (button) {
        button.addEventListener('click', function () {
            const category = button.closest('.questions');
            const collapsed = category.classList.toggle('collapsed');
            button.setAttribute('aria-expanded', String(!collapsed));
        })  ;
    });
</script>