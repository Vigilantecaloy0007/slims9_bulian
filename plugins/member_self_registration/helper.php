<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2021-05-08 09:15:53
 * @modify date 2022-03-28 14:07:00
 * @desc [description]
 */

use Zein\Storage\Local\Upload;

require __DIR__ . '/vendor/autoload.php';

// Save Register
function saveRegister()
{
    global $dbs, $sysconf;

    // set meta
    $meta = $sysconf['selfRegistration']??[];

    // Set Table Attribute
    $table = (isset($meta['separateTable']) && (int)$meta['separateTable'] == 1) ? 'member_online': 'member';

    // load simbio dbop
    require_once SB.'simbio2'.DS.'simbio_DB'.DS.'simbio_dbop.inc.php';

    if (!\Volnix\CSRF\CSRF::validate($_POST)) {
        echo '<script type="text/javascript">';
        echo 'alert("Invalid login form!");';
        echo 'location.href = \'index.php?p=daftar_online\';';
        echo '</script>';
        exit();
    }

    # <!-- Captcha form processing - start -->
    if ($sysconf['captcha']['member']['enable']) {
        if ($sysconf['captcha']['member']['type'] == 'recaptcha') {
            require_once LIB . $sysconf['captcha']['member']['folder'] . '/' . $sysconf['captcha']['member']['incfile'];
            $privatekey = $sysconf['captcha']['member']['privatekey'];
            $resp = recaptcha_check_answer($privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["g-recaptcha-response"]);

            if (!$resp->is_valid) {
                // What happens when the CAPTCHA was entered incorrectly
                header("location:index.php?p=daftar_online&captchaInvalid=true");
                die();
            }
        } else if ($sysconf['captcha']['member']['type'] == 'others') {
            # other captchas here
        }
    }
    # <!-- Captcha form processing - end -->

    // set up data
    $map = [
            'memberID' => 'member_id',
            'memberName' => 'member_name', 'memberBirth' => 'birth_date', 
            'memberInst' => 'inst_name', 'memberSex' => 'gender',
            'memberBranch' => 'member_branch',
            'memberAddress' => 'member_address', 'memberPhone' => 'member_phone',
            'memberEmail' => 'member_email',
            'memberFacebook' => 'member_fax',
            'memberType' => 'member_type_id',
            'privacyAgreement' => 'privacy_agreement',
            'libAgreement' => 'lib_agreement',
            'memberDepartment' => 'member_department',
           
           ];

    $data = [];
    foreach ($map as $key => $column_name) {
        if (isset($_POST[$key]))
        {
            $data[$column_name] = $dbs->escape_string(str_replace(['"'], '', strip_tags($_POST[$key])));
        }
    }
    //userinput
    $name = $_POST['memberName'];
    $bday = $_POST['memberBirth'];
    $address = $_POST['memberAddress'];
    $phone = $_POST['memberPhone'];
    $email = $dbs->real_escape_string($_POST['memberEmail']);
    $fb = $_POST['memberFacebook'];
    $courseYear= $_POST['memberInst'];
    $gender = $_POST['memberSex'];
    $mtype =$_POST['memberType'];
    $password = $_POST['memberPassword1'];

    
    
    $mID = $_POST['memberID'];
    $inputID = "SELECT * FROM member_online WHERE member_id='$mID' LIMIT 1";
    $inputID2 = "SELECT * FROM member WHERE member_id='$mID' LIMIT 1";

    $idQuery = $dbs->query($inputID);
    $idQuery2 = $dbs->query($inputID2);

    if ($idQuery->num_rows > 0 OR $idQuery2->num_rows > 0) {
        header("location:index.php?p=daftar_online&register=idexist");
        exit();
    } else {
        $data['member_id'] = $mID;
    }
    
    if ((isset($_POST['memberPassword1']) && !empty($_POST['memberPassword1'])) && (isset($_POST['memberPassword2']) && !empty($_POST['memberPassword2'])))
    {
        // Given password
       


        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            header("location:index.php?p=daftar_online&register=invalidpw&name=$name&bday=$bday&address=$address&phone=$phone&email=$email&fb=$fb");

            // echo '<script type="text/javascript">';
            // echo 'alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");';
            // echo 'location.href = \'index.php?p=daftar_online=invalidpw\';';
            // echo '</script>';
            exit();
            // echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
        }else{
            if ($_POST['memberPassword2'] === $_POST['memberPassword1'])
            {
                
                $data['mpasswd'] = password_hash($_POST['memberPassword1'], PASSWORD_BCRYPT);
                
            }
            else
            {
                // echo '<script type="text/javascript">';
                // echo 'alert("Password cannot be empty");';
                // echo 'location.href = \'index.php?p=daftar_online\';';
                // echo '</script>';
                header("location:index.php?p=daftar_online&register=pwnotmatched&name=$name&bday=$bday&address=$address&phone=$phone&email=$email&fb=$fb");
                exit();
            }
        }

    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'alert("Password cannot be empty");';
        echo 'location.href = \'index.php?p=daftar_online\';';
        echo '</script>';
        exit();
    }

    // if (!isset($_POST['memberFacebook'])) {
    //     header("location:index.php?p=daftar_online&register=nofblink&name=$name&bday=$bday&address=$address&phone=$phone&email=$email");
    //     exit();
        
    // }else {
    //     $data['member_fax']  = $_POST['memberFacebook'];
    // }
    //check if ID is exist
  
    

    //check if email is exist
    $sql_string = "SELECT * FROM member_online WHERE member_email='$email' LIMIT 1";
    $sql_string2 = "SELECT * FROM member WHERE member_email='$email' LIMIT 1";

    // send query to database
    $query = $dbs->query($sql_string);
    $query2 = $dbs->query($sql_string2);

    if ($query->num_rows > 0 OR $query2->num_rows > 0) {
        header("location:index.php?p=daftar_online&register=emailexist");
        exit();
    
    } else {
         //Filter Laverdad Email
    $domain = explode('@', $email);
    switch (strtolower($domain[1])) {
        case 'student.laverdad.edu.ph':
        case 'laverdad.edu.ph':
            $data['member_email'] = $dbs->real_escape_string($_POST['memberEmail']);
            break;
        
        default:
            header("location:index.php?p=daftar_online&register=invalidemail");
            exit();
            break;
    }
    }
    
    // Date time
    $data['input_date'] = date('Y-m-d');
    $data['last_update'] = date('Y-m-d');

    


    if ($table === 'member' && (int)$meta['autoActive'] === 0)
    {
        $data['is_pending'] = 1;
    }

    // if ($table === 'member')
    // {
    //     $data['member_id'] = rand(1000000, 9000000);
    //     $data['expire_date'] = date('Y-m-d', strtotime("+1 year"));
    // }

    // Upload
    if (isset($meta['withImage']) && (bool)$meta['withImage'] === true)
    {
        $Upload = new Upload;
        $Upload->mahasiswa = SB . 'images/';
        $newFilename = hash('sha256', md5(date('this'))) . '.jpeg';

        $Upload
            ->streamFrom('photoprofil')
            ->limitSize('500KB')
            ->allowMime(['image/jpeg','image/png'])
            ->allowExt(['.png','.jpg','.jpeg'])
            ->storeToMahasiswa('persons')
            ->as($newFilename);

        if ($Upload->isSuccess())
        {
            $data['member_image'] = $newFilename;
        }
        else
        {
            // utility::jsAlert('Image was not uploaded successfully because : ' . $Upload->getError());
            header("location:index.php?p=daftar_online&register=imageerror");
            exit();
        }
    }
    if (!isset($_POST['privacyAgreement']) OR !isset($_POST['libAgreement'])) {
        header("location:index.php?p=daftar_online&register=notagree");
        exit();
        # code...
    }else {
        $data['privacy_agreement'] = 'I Agree';
        $data['lib_agreement'] = 'I Agree';
    }
    
        

    // do insert
    // initialise db operation
    $sql = new simbio_dbop($dbs);

    // setup for insert
    $insert = $sql->insert($table, $data);

    if ($insert)
    {
        // echo '<script type="text/javascript">';
        // echo 'alert("Registered successfully. '.$meta['regisInfo'].'");';
        // echo 'location.href = \'index.php?p=daftar_online\';';
        // echo '</script>';
        // exit();
        header("location:index.php?p=daftar_online&register=success");
        exit();
    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'alert("Failed to register, immediately contact the librarian, for further information '.$sql->error.'");';
        echo 'location.href = \'index.php?p=daftar_online\';';
        echo '</script>';
        exit();
    }

    // header("location:index.php?p=daftar_online");
    exit();
}

// update register
function updateRegister()
{
    global $dbs, $sysconf;

    if (isset($_POST['updateRecordID']) && isset($_POST['saveDataMember']))
    {
        // set meta
        $meta = $sysconf['selfRegistration'];

        // Set Table Attribute
        $table = (isset($meta['separateTable']) && (int)$meta['separateTable'] == 1) ? 'member_online': 'member';

        // load simbio dbop
        require_once SB.'simbio2'.DS.'simbio_DB'.DS.'simbio_dbop.inc.php';

        // initialise db operation
        $sql = new simbio_dbop($dbs);
        $updateRecId = $dbs->escape_string($_POST['updateRecordID']);

        if ($table === 'member_online')
        {
            // select data
            $dataQuery = $dbs->query('select * from member_online where id = \''.$updateRecId.'\'');

            // $memberId == ['member_id'];
            $dataResult = ($dataQuery->num_rows > 0) ? $dataQuery->fetch_assoc() : [];


            // check status
            if ((int)$meta['editableData'] === 0 && count($dataResult) > 0)
            {
                // unset id
                unset($dataResult['id']);
                // merge data
                $dataOnline = array_merge([ 'expire_date' => date('Y-m-d', strtotime("+1 year"))], $dataResult);
                // prepare to insert
                $insert = $sql->insert('member', $dataOnline);

                if ($insert)
                {
                    $sql->delete('member_online', "id='$updateRecId'");
                    utility::jsToastr('Self Register Form', 'Successfully save data', 'success');
                    echo '<script>parent.$("#mainContent").simbioAJAX("'.MWB.'membership/index.php")</script>';
                    exit;
                }
                else
                {
                    utility::jsAlert($sql->error);
                    utility::jsToastr('Self Register Form', 'Failed to save data 1', 'error');
                    exit;
                }
            }
            else
            {
                // set up data
                $map = [
                        'memberID' => 'member_id',
                        'memberName' => 'member_name', 'memberBirth' => 'birth_date', 
                        'memberInst' => 'inst_name', 'memberSex' => 'gender',
                        'memberAddress' => 'member_address', 'memberPhone' => 'member_phone',
                        'memberEmail' => 'member_email',
                        'memberFacebook' => 'member_fax',
                        'memberBranch' => 'member_branch',
                        'privacyAgreement' => 'privacy_agreement',
                        'libAgreement' => 'lib_agreement',
                        'memberDepartment' => 'member_department',

                    ];

                $data = [];
                foreach ($map as $key => $column_name) {
                    if (isset($_POST[$key]))
                    {
                        $data[$column_name] = $dbs->escape_string(str_replace(['"'], '', strip_tags($_POST[$key])));
                    }
                }
                
                if (isset($meta['withImage']) && (bool)$meta['withImage'] === true)
                {
                    $data['member_image'] = $dataResult['member_image'];
                }

                
                $data['mpasswd'] = (isset($dataResult['mpasswd'])) ? $dataResult['mpasswd'] : 'No Password';
                $data['member_type_id'] = (isset($dataResult['member_type_id'])) ? $dataResult['member_type_id'] : 0;
                $data['input_date'] = (isset($dataResult['input_date'])) ? $dataResult['input_date'] : date('Y-m-d');
                $data['last_update'] = date('Y-m-d');
                $data['expire_date'] = date('Y-m-d', strtotime("+1 year"));
                $data['privacy_agreement'] = (isset($dataResult['privacy_agreement'])) ? $dataResult['privacy_agreement'] : 0;
                $data['lib_agreement'] = (isset($dataResult['lib_agreement'])) ? $dataResult['lib_agreement'] : 0;

                $insert = $sql->insert('member', $data);

                if ($insert)
                {
                    $sql->delete('member_online', "id='$updateRecId'");
                    utility::jsToastr('Self Register Form', 'Successfully save data', 'success');
                    echo '<script>parent.$("#mainContent").simbioAJAX("'.MWB.'membership/index.php")</script>';
                    exit;
                }
                else
                {
                    utility::jsToastr('Self Register Form', 'Failed to save data 2', 'error');
                    exit;
                }
            }
        }
        else
        {
            $update = $sql->update('member', ['member_id' => $updateRecId, 'isPending' => (int)$_POST['isPending']], "member_id = '$updateRecId'");

            if ($update)
            {
                utility::jsToastr('Self Register Form', 'Successfully save data', 'success');
                echo '<script>parent.$("#mainContent").simbioAJAX("'.MWB.'membership/index.php")</script>';
                exit;
            }
            else
            {
                utility::jsToastr('Self Register Form', 'Failed to save data 3', 'error');
                exit;
            }
        }
        exit;
    }
}

// save Setting
function saveSetting($self)
{
    global $dbs;

    // load simbio dbop
    require_once SB.'simbio2'.DS.'simbio_DB'.DS.'simbio_dbop.inc.php';

    // action
    if (isset($_POST['saveData']))
    {
        // save into serialize data
        $allowData = ['selfRegistrationActive','title','autoActive','separateTable','useRecaptcha','regisInfo','editableData','withImage'];

        // loop for filter
        foreach ($_POST as $key => $value) {
            if (in_array($key, $allowData))
            {
                $_POST[$key] = $dbs->escape_string($value);
            }
            else
            {
                unset($_POST[$key]);
            }
        }

        // copy template
        // copyTemplate($_POST);
        
        // serialize data
        $data = serialize($_POST);

        // initialise db operation
        $sql = new simbio_dbop($dbs);

        // Delete data
        $sql->delete('setting', 'setting_name = "selfRegistration"');

        // setup for insert
        $insert = $sql->insert('setting', ['setting_name' => 'selfRegistration', 'setting_value' => $data]);

        if ($insert)
        {
            // if ((int)$_POST['separateTable'] === 1 )
            // {
            //     createTable();
            // }

            // set alert
            utility::jsToastr('Self Register Form', 'Successfully save data', 'success');
            echo '<script>parent.$("#mainContent").simbioAJAX("'.$self.'")</script>';
        }
        else
        {
            utility::jsToastr('Self Register Form', 'Failed to save data'.$sql->error, 'error');
        }
        exit;
    }
} 

// delete item
function deleteItem($self)
{
    global $dbs,$meta;

    if ((isset($_POST['itemID']) AND !empty($_POST['itemID']) AND isset($_POST['itemAction'])))
    {
        // Set Table Attribute
        $table = (isset($meta['separateTable']) && (int)$meta['separateTable'] == 1) ? ['member_online', "id = '{id}'"] : ['member', "member_id = '{id}'"];

        // load simbio dbop
        require_once SB.'simbio2'.DS.'simbio_DB'.DS.'simbio_dbop.inc.php';

        // process delete
        // initialise db operation
        $sql = new simbio_dbop($dbs);
        
        $fail = 0;
        foreach ($_POST['itemID'] as $itemID) {
            $delete = $sql->delete($table[0], str_replace('{id}', $dbs->escape_string($itemID), $table[1]));

            if (!$delete)
            {
                $fail++;
            }
        }
        

        if (!$fail)
        {
            utility::jsToastr('Register Member Online', 'Successfully deleted data.', 'success');
            echo '<script>parent.$("#mainContent").simbioAJAX("'.$self.'")</script>';
        }
        else
        {
            utility::jsToastr('Register Member Online', 'Failed to delete data', 'error');
        }
        exit;
    }
}

// copy template
function copyTemplate($data)
{
    if ((int)$data['selfRegistrationActive'] === 1 && !file_exists(SB.'lib'.DS.'contents'.DS.'daftar_online.inc.php'))
    {
        copy(__DIR__.DS.'daftar_online.inc.php', SB.'lib'.DS.'contents'.DS.'daftar_online.inc.php');
    }
}

// Creating Table
function createTable()
{
    global $dbs;

    // setup query
    @$dbs->query("CREATE TABLE IF NOT EXISTS `member_online` (
        `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `member_name` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
        `birth_date` date DEFAULT NULL,
        `inst_name` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
        `gender` int(1) NOT NULL,
        `member_address` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
        `member_phone` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
        `member_email` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
        `member_fax` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
        `mpasswd` varchar(64) COLLATE utf8mb4_bin DEFAULT NULL,
        `input_date` date DEFAULT NULL,
        `last_update` date DEFAULT NULL
      ) ENGINE='MyISAM';");
    
}

// compose Url
function getCurrentUrl($query = [])
{
    
    return $_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge(['mod' => $_GET['mod'], 'id' => $_GET['id']], $query));
}

// premission check
function dirCheckPermission()
{
    $msg = '';
    if (!is_writable(SB.'lib'.DS.'contents'.DS))
    {
        $msg = 'Directory : <b>'.SB.'lib'.DS.'contents'.DS.'</b> Can not be written!. Please change the permissions on the folder.';
    }

    return $msg;
}
