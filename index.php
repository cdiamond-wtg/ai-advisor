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
$categories = getQuestions();

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
      border-bottom-right-radius:20px;
      border-bottom-left-radius: 20px;
      font-family: Georgia, "Times New Roman", Times, serif;
      filter: drop-shadow(0.80em 0.80em 0.4em);
      padding-bottom: 25px;
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
    #quote table{
      width: 100%;
    }
    #quote details{
        border: 2px solid #2F5496;
        border-radius: 5px;
        margin: 5px 25px;
        padding: 10px 25px;
    }
    #quote details[open]{
        background-color: white;
    }
    #quote summary{
        font-weight: bold;
        text-transform: uppercase;
    }
    #quote fieldset{
        border: 0;
        border-left: 4px solid #2F5496;
        margin: 15px 0;
        padding: 10px 15px;
        background-color: #f4f7f8;
    }
    #quote legend{
        font-size: 1em;
        margin-bottom: 0.25em;
    }
    #quote label{
        display: block;
        margin-bottom: 5px;
        font-size: 0.9em;
    }
    #quote input{
        margin-right: 5px;
    }
    #quote select{
        width: 100%;
        max-width: 250px;
        box-sizing: border-box;
    }
    #quote .text-group{
        display: flex;
        gap: 15px;
    }
    #quote .text-group-field{
        flex: 1;
    }
    #quote .subtext{
        font-style: italic;
        font-size: 0.95em;
        margin: 0 0 8px;
    }
    #quote legend:has(+ .subtext){
        margin-bottom: 0px;
    }
</style>


<br/>
<form action='index.php' enctype='multipart/form-data' method='post'>
    <table id='formheader'><tr><td colspan=2><?php echo e($title); ?></td></tr></table>
    <div id='quote'>
        <table>
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
            </tr><tr><td colspan=2 style='padding-bottom:20px;'></td></tr>
        </table>

        <?php foreach ($categories as $category): ?>
            <details name='categories'>
                <summary><?php echo e($category['category']); ?></summary>

                <?php foreach ($category['questions'] as $question): ?>
                    <?php $isRequired = !empty($question['required']); ?>
                    <?php $isCheckbox = $question['type'] === 'checkbox'; ?>
                    <?php $hasMax = $isCheckbox && isset($question['max_selections']); ?>
                    
                    <fieldset <?php if ($hasMax): ?>
                        data-max="<?php echo (int) $question['max_selections']; ?>"
                    <?php endif; ?> >

                        <legend><?php echo e($question['question']); ?></legend>

                        <?php if ($hasMax): ?>
                            <p class='subtext'>Select up to <?php echo (int) $question['max_selections']; ?> options.</p>
                        <?php elseif ($isCheckbox): ?>
                            <p class='subtext'>Select all applicable options.</p>
                        <?php endif; ?>

                        <?php if (in_array($question['type'], ['text', 'url'], true)): ?>
                            <input
                                type="<?php echo e($question['type']); ?>"
                                id="<?php echo e($question['name']); ?>"
                                name="<?php echo e($question['name']); ?>"
                                size='75'
                                <?php if ($isRequired): ?>required<?php endif; ?>
                            >
                        <?php elseif ($question['type'] === 'text_group'): ?>
                            <div class='text-group'>
                            <?php foreach ($question['answers'] as $value => $label): ?>
                                <?php $fieldId = $question['name'] . '_' . $value; ?>
                                <div class='text-group-field'>
                                <label for="<?php echo e($fieldId); ?>"><?php echo e($label); ?></label>
                                <input
                                    type='text'
                                    id="<?php echo e($fieldId); ?>"
                                    name="<?php echo e($question['name']); ?>[<?php echo e($value); ?>]"
                                    <?php if ($isRequired): ?>required<?php endif; ?>
                                >
                                </div>
                            <?php endforeach; ?>
                            </div>
                        <?php elseif ($question['type'] === 'textarea'): ?>
                            <textarea
                                id="<?php echo e($question['name']); ?>"
                                name="<?php echo e($question['name']); ?>"
                                rows=4
                                cols=75
                                <?php if ($isRequired): ?>required<?php endif; ?>
                            ></textarea>
                        <?php elseif ($question['type'] === 'radio'): ?>
                            <?php foreach ($question['answers'] as $value => $label): ?>
                                <label>
                                    <input
                                        type='radio'
                                        name="<?php echo e($question['name']); ?>"
                                        value="<?php echo e($value); ?>"
                                        <?php if ($isRequired): ?>required<?php endif; ?>
                                    ><?php echo e($label); ?>
                                </label>
                            <?php endforeach; ?>
                        <?php elseif ($question['type'] === 'checkbox'): ?>
                            <?php foreach ($question['answers'] as $value => $label): ?>
                                <label>
                                    <input
                                        type='checkbox'
                                        name="<?php echo e($question['name']); ?>[]"
                                        value="<?php echo e($value); ?>"
                                    ><?php echo e($label); ?>
                                </label>
                            <?php endforeach; ?>
                        <?php elseif ($question['type'] === 'select'): ?>
                            <select
                                id="<?php echo e($question['name']); ?>"
                                name="<?php echo e($question['name']); ?>"
                                <?php if ($isRequired): ?>required<?php endif; ?>
                            ><option value=''></option>
                            <?php foreach ($question['answers'] as $value => $label): ?>
                                <option value="<?php echo e($value); ?>">
                                    <?php echo e($label); ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                        <?php endif; ?>

                    </fieldset>
                <?php endforeach; ?>

            </details>
        <?php endforeach; ?>

    </div>
</form>
<br/>


<script>
document.addEventListener('change', function (event) {
    if (!event.target.matches('fieldset[data-max] input[type="checkbox"]')) {
        return;
    }

    const group = event.target.closest('fieldset');
    const selected = group.querySelectorAll('input:checked').length;
    const maximum = Number(group.dataset.max);

    if (selected > maximum) {
        event.target.checked = false;
        alert('You can select up to ' + maximum + ' options.');
    }
});
</script>
