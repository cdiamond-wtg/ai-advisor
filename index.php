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
    #profile_button{
        margin-top: 15px;
        margin-left: 20px;
    }
    #profile_response{
        margin-top: 10px;
        margin-left: 20px;
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

                <?php if ($category['category'] === 'Company Profile'): ?>
                    <button type='button' id='profile_button'>Load Profile</button>
                    <pre id='profile_response'></pre>
                <?php endif; ?>

                <?php foreach ($category['questions'] as $question): ?>
                    <?php $isRequired = !empty($question['required']); ?>
                    <?php $isCheckbox = $question['type'] === 'checkbox'; ?>
                    <?php $hasMax = $isCheckbox && isset($question['max_selections']); ?>
                    
                    <fieldset 
                        <?php if ($hasMax): ?>
                            data-max="<?php echo (int) $question['max_selections']; ?>"
                        <?php endif; ?> 

                        <?php if (isset($question['show_if'])): ?>
                            data-show-if-question="<?php echo e($question['show_if']['question']); ?>"
                            data-show-if-value="<?php echo e($question['show_if']['value']); ?>"
                            hidden
                        <?php endif; ?>
                    >

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
// populate dropdown menus
function populateSelect(selectId, choices) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.replaceChildren(new Option('', ''));
    let entries = Object.entries(choices);
    if (selectId === 'industry') {
        entries.sort(([, labelA], [, labelB]) => labelA.localeCompare(labelB));
    }
    entries.forEach(([id, label]) => {select.add(new Option(label, id));});

    const existingLabels = Array.from(select.options).map(
        option => option.text.trim().toLowerCase());
    if (!existingLabels.includes('other')) select.add(new Option('Other', 'other'));
    if (!existingLabels.includes('unknown')) select.add(new Option('Unknown', 'unknown'));
}

// load choices
async function loadChoices(column) {
    const formData = new FormData();
    formData.append('action', 'load_choices');
    formData.append('column', column);

    const response = await fetch('crm.php', {method: 'POST', body: formData});
    const result = await response.json();
    populateSelect(column, result.success ? result.choices : {});
}

loadChoices('relationship');
loadChoices('industry');

// restrict number of checkbox selections to max
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

// show or hide conditional questions based on checkbox values
function updateConditionalQuestions() {
    document.querySelectorAll('[data-show-if-question]').forEach(fieldset => {
        const question = fieldset.dataset.showIfQuestion;
        const value = fieldset.dataset.showIfValue;
        const answer = document.querySelector(
            `[name="${question}[]"][value="${value}"]`
        );
        fieldset.hidden = !answer?.checked;
    });
}
document.addEventListener('change', updateConditionalQuestions);
updateConditionalQuestions();

// load profile data from crm upon button click
function fillProfileFields(profile) {
    Object.entries(profile).forEach(([fieldId, value]) => {
        if (typeof value === 'object' && value !== null) {
            Object.entries(value).forEach(([subfieldId, subfieldValue]) => {
                const field = document.getElementById(`${fieldId}_${subfieldId}`);
                if (field) field.value = subfieldValue ?? '';
            });
            return;
        }
        const field = document.getElementById(fieldId);
        if (field) field.value = value ?? '';
    });
}

const profileButton = document.getElementById('profile_button');
const profileResponse = document.getElementById('profile_response');
const companyInput = document.getElementById('company');

profileButton.addEventListener('click', async function () {
    const company = companyInput.value.trim();
    const requestData = new FormData();

    requestData.append('action', 'load_profile');
    requestData.append('company', company);

    profileButton.disabled = true;
    profileButton.textContent = 'Loading profile...';
    profileResponse.textContent = '';

    try {
        const response = await fetch('crm.php', {method: 'POST', body: requestData});
        const result = await response.json();
        profileResponse.textContent = result.message ?? '';
        if (!result.profile_found || !result.profile) return;
        fillProfileFields(result.profile);
    }
    catch (error) {
        profileResponse.textContent = 'Unable to send CRM request.';
    }
    finally {
        profileButton.disabled = false;
        profileButton.textContent = 'Load Profile';
    }
});
</script>
