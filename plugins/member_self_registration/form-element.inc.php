<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2021-05-08 09:15:43
 * @modify date 2022-03-28 12:53:36
 * @desc [description]
 */

if ($_SESSION['uid'] > 1)
{
    echo '<div class="bg-danger p-2 text-white">';
    echo 'Only super-admin accounts can change this section';
    echo '</div>';
    exit;
}

// create new instance
$form = new simbio_form_table_AJAX('mainForm', $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], 'post');
$form->submit_button_attr = 'name="saveData" value="' . __('Save') . '" class="s-btn btn btn-default"';
// form table attributes
$form->table_attr = 'id="dataList" cellpadding="0" cellspacing="0"';
$form->table_header_attr = 'class="alterCell"';
$form->table_content_attr = 'class="alterCell2"';

/* Form Element(s) */
// Aktifkan Daftar Online
$form->addSelectList('selfRegistrationActive', 'Enable Online Registration?', [['0','No'],['1','Yes']], $meta['selfRegistrationActive'] ?? '', 'class="select2"', 'Activate or not');

// form title
$form->addTextField('text', 'title', 'Form Title' . '*', $meta['title'] ?? '', 'rows="1" class="form-control"', 'Form Title');

// Auto Active?
$form->addSelectList('autoActive', 'Automatic Membership On?', [['0','No'],['1','Yes']], $meta['autoActive'] ?? '', 'class="select2"', 'Activate or not');

// With Image?
$form->addSelectList('withImage', 'Upload an image?', [['0','No'],['1','Yes']], $meta['withImage'] ?? '', 'class="select2 withImage"', 'Yes or No');

// Memisahkan antara anggota aktif dengan anggota online?
$form->addSelectList('separateTable', 'Separate the table of active members and online members?', [['1','Yes'],['0','No']], $meta['separateTable'] ?? '', 'class="select2"', 'By separating, at least when a member is registered, it does not automatically log in and can borrow books.');

// Memisahkan antara anggota aktif dengan anggota online?
$form->addSelectList('editableData', 'Can the registered data be edited?', [['0','No'], ['1','Yes']], $meta['editableData'] ?? '', 'class="select2"', 'Userdata can be edited.');

// Menggunakan Re-Captcha Active?
$form->addSelectList('useRecaptcha', 'Use Google Re-Captcha?', [['0','No'],['1','Yes']], $meta['useRecaptcha'] ?? '', 'class="select2"', 'Using recaptcha to reduce spam attacks');

// Info setelah registrasi
$form->addTextField('textarea', 'regisInfo', 'Information and Contact regarding registration.' . '*', $meta['regisInfo'] ?? '', 'rows="1" class="form-control" style="margin-top: 0px; margin-bottom: 0px; height: 122px;"', 'Information and Contact');

// print out the form object
echo $form->printOut();
?>

<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Some text in the Modal..</p>
  </div>

</div>
<script>
    $('.withImage').change((e) => {
        let el = e.target;

        if (el.value === '1' && confirm('Are you sure? Uploading photos on online websites risks the occurrence of web phishing crimes. Reconsider!'))
        {
            return true;
        }
        else
        {
            el.value = '0';
        }
    })
    
</script>