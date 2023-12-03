<Head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        <?php include('regstyle.css') ?>
        
    </style>
</Head>
<body>
<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2021-05-08 09:15:31
 * @modify date 2022-03-28 13:30:19
 * @desc [description]
 */ 

// set meta
$meta = $sysconf['selfRegistration']??[];

if ((int)($meta['selfRegistrationActive']??0) === 1)
{

    // set page title
    $page_title = $meta['title'];

    // Attribute
    $attr = [
        'action' => $_SERVER['PHP_SELF'] .'?p=daftar_online',
        'method' => 'POST',
        'enctype' => 'multipart/form-data'
    ];

    // require helper
    require SB.'plugins'.DS.'member_self_registration'.DS.'helper.php';

    if (isset($_POST['memberName']))
    {
        saveRegister();
    }

    // check dep
    if (!file_exists(SB.'plugins'.DS.'member_self_registration'.DS.'bs4formmaker.inc.php'))
    {
        echo '<div class="bg-danger p-2 text-white">';
        echo 'Folder <b>'.SB.'plugins'.DS.'member_self_registration'.DS.'bs4formmaker.inc.php</b> Make sure the folder is available.';
        echo '</div>';
    }
    else
    {
        // set key
        define('DR_INDEX_AUTH', '1');

        // require helper
        require SB.'plugins'.DS.'member_self_registration'.DS.'bs4formmaker.inc.php';
        echo '<div class="bg-white w-100 p-sm-5 shadow rounded-lg">';

        if (!isset($_GET['register'])){
            // header("location:index.php?p=daftar_online");
        }
        else {
            $registerCheck = $_GET['register'];
            if ($registerCheck == "invalidpw") {
                echo '<center class="alert alert-danger" role="alert">Password is invalid <br> <i>Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.</i></center>';  
            }
            elseif ($registerCheck == "pwnotmatched"){
                echo '<center class="alert alert-danger" role="alert">Password does not Matched</center>';
            }
            elseif ($registerCheck == "invalidemail") {
                echo '<center class="alert alert-danger" role="alert">Invalid Email, please use your given SPC Email</center>';
            }
            elseif ($registerCheck == "emailexist") {
                echo '<center class="alert alert-danger" role="alert">Email Already Registered</center>';
            }
            elseif ($registerCheck == "success") {
                echo '<center class="alert alert-success" role="alert">Thank you for registering! <br>'.$meta['regisInfo'].'</center>';
            }
            elseif ($registerCheck == "notagree") {
                echo '<center class="alert alert-danger" role="alert">Please Agree to the Privacy Policy and LVCS/LVCC Digital Library Resources Agreement!</center>';
            }

            elseif ($registerCheck == "emailexist") {
                echo '<center class="alert alert-danger" role="alert">SPC Email Already Registered</center>';
            }
            elseif ($registerCheck == "imageerror") {
                echo '<center class="alert alert-danger" role="alert">Image file size exceeds the maximum 500KB required file size, please resize your image not exceeding 500KB.</center>';
            }
            elseif ($registerCheck == "nofblink") {
                echo '<center class="alert alert-danger" role="alert">Facebook Link is Required</center>';
            }
            elseif ($registerCheck == "!couryear") {
                echo '<center class="alert alert-danger" role="alert">Gender, Course and Year, and Scholarship Status are empty. Make sure you filled it up.</center>';
            }
            elseif ($registerCheck == "idexist") {
                echo '<center class="alert alert-danger" role="alert">Student ID Already Registered</center>';
            }
        }

        echo '<center><img src="webicon.ico" style="height:10vw; width:10vw; padding: 2px;"/><br><h2>SAN PABLO COLLEGES LIBRARY REGISTRATION</h2></center><br><br><div style="border-bottom: 1px solid black;  width:100%"></div> <br><br>';
        // echo '<div style="border-bottom: 1px solid black; padding-bottom: 2vw;" ><p style="text-align: justify;" class="p-sm-3"><strong>PRIVACY NOTICE:</strong> In pursuant with the Data Privacy Act of 2012 (RA 10173), LA VERDAD CHRISTIAN SCHOOL/COLLEGE Apalit, and Caloocan branches adhere with its principles in processing and securing your information and as a data subject you have the right to be informed, to rectify, to object, to delete and to data portability. 
        // <br>By submitting your registration and ticking the box below signifies your agreement to the processing and disclosure of personal data, you agree to the collection, use, disclosure and processing of your personal data for legitimate purposes and in accordance with our mandate.
        // Rest assured that the data or information you share with us are safe and will be treated with the utmost confidentiality.</p></div>';
        
        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        // if (strpos($fullUrl, 'invalidpw=true')== true ) {
        //     echo '<center class="alert alert-danger" role="alert">Password is invalid <br> <i>Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.</i></center>';
        // }
        // elseif (strpos($fullUrl, 'doesnotmatch=true')== true ) {
        //     echo '<center class="alert alert-danger" role="alert">Password Does not Match</center>';
        // }
        // create form
        createForm($attr);

        // CSRF Token
        echo \Volnix\CSRF\CSRF::getHiddenInputString();
?>
    <!-- <strong>San Pablo Colleges Library Registration</strong><br>
    <div>
        <input style="margin-top: 2px;" type="checkbox" name="privacyAgreement" onclick="document.getElementById('id02').style.display='block'" required/>
            <label  style="margin-left: 7px;">
                <a class="text-primary" onclick="document.getElementById('id02').style.display='block'">DATA PRIVACY INFORMATION FOR LIBRARY USERS
            </a></label> <br>
        <input style="margin-top: 2px; " type="checkbox" name="libAgreement" onclick="document.getElementById('id01').style.display='block'" required/>
        <label style="margin-left: 7px;"><space><a class="text-primary" onclick="document.getElementById('id01').style.display='block'"> LVCS/LVCC Library Resources Agreement</a></label>
        </div> <br> -->
<?php 
            
        // Member ID
        createFormContent(__('<strong>Student/Staff ID</strong><i> (ID Given by the school)</i><br>Example: <i>18-0068JDA.</i>'), 'text', 'memberID', 'Fill in your School ID', true, '', true);
        // Member name
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            createFormContent(__('<strong>Name</strong><i> (Surname, First Name, M.I.)</i><br>Example: <i>Dela Cruz, Juan, A.</i>'), 'text', 'memberName', 'Fill in your name', true, $name, true);
        }
        else {
            createFormContent(__('<strong>Name</strong><i> (Surname, First Name, M.I.)</i><br>Example: <i>Dela Cruz, Juan, A.</i>'), 'text', 'memberName', 'Fill in your name', true, '', true);
        }
        
        // Birth Date  
        if (isset($_GET['bday'])) {
            $bday = $_GET['bday'];
            createFormContent(__('<strong>Birth Date</strong>'), 'date', 'memberBirth',' ', ' ', $bday);
        }
        else {
            createFormContent(__('<strong>Birth Date</strong>'), 'date', 'memberBirth');
        }

        // gender
        createSelect(__('<strong>Gender</strong>'), 'memberSex', [['label' => __('Male'), 'value' => 1],['label' => __('Female'), 'value' => 0]]);

        // Institution
        // createFormContent(__('Institution'), 'text', 'memberInst', 'Isikan institusi anda');

        createSelect(__('<strong>Grade & Year</strong>'), 'gradeYear', 
            [['label' => __('N/a'), 'value' => ''],
            ['label' => __('Nursery'), 'value' => 'Nursery'],
            ['label' => __('Kinder'), 'value' => 'Kinder'],
           //Grade School
            ['label' => __('Grade 1'), 'value' => 'Grade 1'],
            ['label' => __('Grade 2'), 'value' => 'Grade 2'],
            ['label' => __('Grade 3'), 'value' => 'Grade 3'],
            ['label' => __('Grade 4'), 'value' => 'Grade 4'],
            ['label' => __('Grade 5'), 'value' => 'Grade 5'],
            ['label' => __('Grade 6'), 'value' => 'Grade 6'],
            //JHS
            ['label' => __('Grade 7'), 'value' => 'Grade 7'],
            ['label' => __('Grade 8'), 'value' => 'Grade 8'],
            ['label' => __('Grade 9'), 'value' => 'Grade 9'],
            ['label' => __('Grade 10'), 'value' => 'Grade 10'],
            //SHS
            ['label' => __('Grade 11 STEM'), 'value' => 'Grade 11 STEM'],
            ['label' => __('Grade 11 ABM'), 'value' => 'Grade 11 ABM'],
            ['label' => __('Grade 11 HUMSS'), 'value' => 'Grade 11 HUMSS'],
            ['label' => __('Grade 11 GAS'), 'value' => 'Grade 11 GAS'],
            ['label' => __('Grade 11 ICT'), 'value' => 'Grade 11 ICT'],
            ['label' => __('Grade 11 HE'), 'value' => 'Grade 11 HE'],
            ['label' => __('Grade 11 ARTS & DESIGN'), 'value' => 'Grade 11 ARTS & DESIGN'],
            ['label' => __('Grade 11 ARTS & DESIGN'), 'value' => 'Grade 11 ARTS & DESIGN'],

            ['label' => __('Grade 12 STEM'), 'value' => 'Grade 12 STEM'],
            ['label' => __('Grade 12 ABM'), 'value' => 'Grade 12 ABM'],
            ['label' => __('Grade 12 HUMSS'), 'value' => 'Grade 12 HUMSS'],
            ['label' => __('Grade 12 GAS'), 'value' => 'Grade 12 GAS'],
            ['label' => __('Grade 12 ICT'), 'value' => 'Grade 12 ICT'],
            ['label' => __('Grade 12 HE'), 'value' => 'Grade 12 HE'],
            ['label' => __('Grade 12 ARTS & DESIGN'), 'value' => 'Grade 12 ARTS & DESIGN'],
            ['label' => __('Grade 12 ARTS & DESIGN'), 'value' => 'Grade 12 ARTS & DESIGN'],
            //College
            ['label' => __('1st Year College'), 'value' => '1st Year College'],
            ['label' => __('2nd Year College'), 'value' => '2nd Year College'],
            ['label' => __('3rd Year College'), 'value' => '3rd Year College'],
            ['label' => __('4th Year College'), 'value' => '4th Year College'],
            ['label' => __('5th Year College'), 'value' => '5th Year College'],

            ['label' => __('1st Year Graduate'), 'value' => '1st Year Graduate'],
            ['label' => __('2nd Year Graduate'), 'value' => '2nd Year Graduate'],
            ['label' => __('3rd Year Graduate'), 'value' => '3rd Year Graduate'],
            ['label' => __('4th Year Graduate'), 'value' => '4th Year Graduate'],
            ['label' => __('5th Year Graduate'), 'value' => '5th Year Graduate'],
            
            ]);

        createSelect(__('<strong>College & Graduate</strong> <br><i>For College & Graduate</i>'), 'collegeGrad', 
            [['label' => __('N/A'), 'value' => ''],
            ['label' => __('Bachelor of Arts'), 'value' => 'Bachelor of Arts'],
            ['label' => __('Bachelor of Arts in Communication'), 'value' => 'Bachelor of Arts in Communication'],
            ['label' => __('Bachelor of Science in Psychology'), 'value' => 'Bachelor of Science in Psychology'],
            ['label' => __('Bachelor of Science in Radiologic Technology'), 'value' => 'Bachelor of Science in Radiologic Technology'],
            ['label' => __('Bachelor of Science in Business Administration'), 'value' => 'Bachelor of Science in Business Administration'],
            ['label' => __('Bachelor of Science in Hospitality Management'), 'value' => 'Bachelor of Science in Hospitality Management'],
            ['label' => __('Bachelor of Science in Accountancy'), 'value' => 'Bachelor of Science in Accountancy'],
            ['label' => __('Bachelor of Secondary Education'), 'value' => 'Bachelor of Secondary Education'],
            ['label' => __('Bachelor of Physical Education'), 'value' => 'Bachelor of Physical Education'],
            ['label' => __('Bachelor of Elementary Education'), 'value' => 'Bachelor of Elementary Education'],
            ['label' => __('Bachelor of Special Needs Education'), 'value' => 'Bachelor of Special Needs Education'],
            ['label' => __('Bachelor of Early Childhood Education'), 'value' => 'Bachelor of Early Childhood Education'],
            ['label' => __('Bachelor of Technology and Livelihood Education'), 'value' => 'Bachelor of Technology and Livelihood Education'],
            ['label' => __('Bachelor of Science in Real Estate Management'), 'value' => 'Bachelor of Science in Real Estate Management'],
            ['label' => __('Bachelor of Science in Computer Science'), 'value' => 'Bachelor of Science in Computer Science'],
            ['label' => __('Bachelor of Science in Information Technology'), 'value' => 'Bachelor of Science in Information Technology'],
            ['label' => __('Bachelor of Science in Nursing'), 'value' => 'Bachelor of Science in Nursing'],
            ['label' => __('Bachelor of Science in Physical Therapy'), 'value' => 'Bachelor of Science in Physical Therapy'],
            ['label' => __('Associate in Hotel and Restaurant Management'), 'value' => 'Associate in Hotel and Restaurant Management'],
            ['label' => __('Associate in Computer Technology'), 'value' => 'Associate in Computer Technology'],
            ['label' => __('Associate in Hospitality Management'), 'value' => 'Associate in Hospitality Management '],
            ['label' => __('Doctor in Business Administration'), 'value' => 'Doctor in Business Administration'],
            ['label' => __('Doctor of Education with specialization in Educational Management'), 'value' => 'Doctor of Education with specialization in Educational Management'],
            ['label' => __('Master in Business Administration'), 'value' => 'Master in Business Administration'],
            ['label' => __('Master of Arts in Educational Management'), 'value' => 'Master of Arts in Educational Management'],
            ['label' => __('Master of Arts in Guidance and Counseling'), 'value' => 'Master of Arts in Guidance and Counseling'],
            ['label' => __('Master of Arts in Nursing'), 'value' => 'Master of Arts in Nursing'],
            ['label' => __('Juris Doctor'), 'value' => 'Juris Doctor'],

        ]);

        createSelect(__('<strong>Department</strong> <br><i>For SPC personnel only. Choose N/a if not applicable</i>'), 'department', 
            [['label' => __('N/A'), 'value' => ''],
            ['label' => __('SPC board of trustees'), 'value' => 'SPC board of trustees'],
            ['label' => __('College of Accountancy & College of Business Administration'), 'value' => 'College of Accountancy & College of Business Administration'],
            ['label' => __('College of Arts & Sciences'), 'value' => 'College of Arts & Sciences'],
            ['label' => __('College of Radiologic Technology'), 'value' => 'College of Radiologic Technology'],
            ['label' => __('LAW Office'), 'value' => 'LAW Office'],
            ['label' => __('Junior High School'), 'value' => 'Junior High School'],
            ['label' => __('Senior High School'), 'value' => 'Senior High School'],
            ['label' => __('Careers'), 'value' => 'Careers'],
            ['label' => __('Accounting and Finance Office'), 'value' => 'Accounting and Finance Office'],
            ['label' => __('Accounting Office'), 'value' => 'Accounting Office'],
            ['label' => __('Budget Office'), 'value' => 'Budget Office'],
            ['label' => __('Assessment Office'), 'value' => 'Assessment Office'],
            ['label' => __('Cashier Office'), 'value' => 'Cashier Office'],
            ['label' => __('SPC Alumni'), 'value' => 'SPC Alumni'],
            ['label' => __('Office of the Corporate Secretary San Pablo Colleges & SPC Agri-business Corp'), 'value' => 'Office of the Corporate Secretary San Pablo Colleges & SPC Agri-business Corp'],
            ['label' => __('Payroll Office'), 'value' => 'Payroll Office'],
            ['label' => __('Registrar Office'), 'value' => 'Registrar Office'],
            ['label' => __('Strategic Management & Business Development Office'), 'value' => 'Strategic Management & Business Development Office'],
            ['label' => __('Office of Research, Evaluation and Publication'), 'value' => 'Office of Research, Evaluation and Publication'],
            ['label' => __('Library'), 'value' => 'Library'],


        ]);

        //School Branch
        // createSelect(__('<strong>School Branch</strong> <br> <i>Choose School Branch where you currently Enrolled </i>'), 'memberBranch', 
        // [['label' => __('Apalit'), 'value' => 'Apalit'],['label' => __('Caloocan'), 'value' => 'Caloocan']]);

        // Member type
        $list = [];
        foreach (membershipApi::getMembershipType($dbs) as $id => $data) {
            $list[] = [
                'label' => $data['member_type_name'],
                'value' => $id,
            ];
        }

        createSelect(__('<strong>Membership Type</strong>'), 'memberType', $list);
        
        
         // Member Address
        if (isset($_GET['address'])) {
            $address = $_GET['address'];
            createFormContent(__('<strong>Address</strong><br><i>House# St. Village/Brgy. City/Town, Province, Country, Zip code</i>'), 'textarea', 'memberAddress', 'Enter your address', '',$address,'',true);
        }
        else {
            createFormContent(__('<strong>Address</strong><br><i>House# St. Village/Brgy. City/Town, Province, Country, Zipcode</i>'), 'textarea', 'memberAddress', 'Enter your address','','',true);
        }

        // Member Phone
        if (isset($_GET['phone'])) {
            $phone = $_GET['phone'];
            createFormContent(__('<strong>Phone Number</strong>'), 'tel', 'memberPhone', 'Fill in your telephone/mobile number', '', $phone, '',true);
        }
        else {
            createFormContent(__('<strong>Phone Number</strong>'), 'tel', 'memberPhone', 'Fill in your telephone/mobile number','','',true);
        }
        
      

        // Photo Profile
        if (isset($meta['withImage']) && (bool)$meta['withImage'] === true){
            //createUploadArea('<strong>Profile Picture</strong> <br>Upload 2x2 recent Formal Picture', 'photoprofil', 'Choose file');
            echo '
            <div class="form-group">
                <label for="imgInp"><strong>Profile Picture</strong> <br>Upload 2x2 recent Formal Picture <br> <i>Maximum File size: 500KB</i></label>
            <div class="input-group">
                <input name="photoprofil" accept="image/*" type="file" id="imgInp" class="imgUpload" required />  
            </div>
                <div class="img">
                <img id="blah" src="#" alt="Your Image preview"/>
                </div>
            </div>
            ';
        }

        //facebook
        if (isset($_GET['fb'])) {
            $email = $_GET['fb'];
            createFormContent(__('<strong>Facebook Link</strong><br><i>Copy and Paste your Facebook Link</i>'), 'text', 'memberFb', 'Enter your Facebook link','', $email,'', true);
        }
        else {
            createFormContent(__('<strong>Facebook Link</strong><br><i>Copy and Paste your Facebook Link</i>'), 'text', 'memberFb', 'Enter your Facebook link', '','', true);
        }
        //email
        if (isset($_GET['email'])) {
            $email = $_GET['email'];
            createFormContent(__('<strong>E-mail</strong><br><i>Use your SPC Email provided by the School</i>'), 'email', 'memberEmail', 'Enter your SPC Email','', $email);
        }
        else {
            createFormContent(__('<strong>E-mail</strong><br><i>Use your SPC Email provided by the School</i>'), 'email', 'memberEmail', 'Enter your SPC Verdad Email');
        }
        

        // Member Password
        createPasswordShow([
            '<strong>Password</strong><br>1. Password should be at least 8 characters<br>2. Includes at least one uppercase letter<br>3. Must have numbers <i>e.g: 0-9</i> <br>4. Must have at least one special characters <i>e.g: !@#$%^&*</i>',
            '<strong>Confirm Password</strong>'
        ], ['memberPassword1', 'memberPassword2'], function(){
            echo <<<HTML
                <input type="checkbox" id="showPassword"/>  <label class="fa fa-eye"></label> Show Password
                <script>
                    document.querySelector('#showPassword').onclick = function () {
                        if(document.querySelector('#showPassword').checked) {
                            document.querySelectorAll('input[name="memberPassword1"], input[name="memberPassword2"]').forEach(el => {
                                    el.setAttribute('type', 'text');
                            })
                        } else {
                            document.querySelectorAll('input[name="memberPassword1"], input[name="memberPassword2"]').forEach(el => {
                                el.setAttribute('type', 'password');
                            })
                        }
                    }
                </script>
            HTML;
        });

      

      

        // captcha
        if ((int)$meta['useRecaptcha'] === 1 && $sysconf['captcha']['member']['enable'])
        {
            // require captcha
            require_once LIB . $sysconf['captcha']['member']['folder'] . DS . $sysconf['captcha']['member']['incfile'];

            // public key
            $publickey = $sysconf['captcha']['member']['publickey'];

            createAnything("Press I'm Not a Robot", '<div class="captchaMember">'.recaptcha_get_html($publickey).'</div>');
        } ?>
      

        <?php
        // Button
        echo '<div class=:"flex ">';
        
        createFormButton('Register', 'submit', 'register');
        echo '<p class="float-right p-1"><a href="index.php?p=member" class="text-primary">Already have an account?</a></p>';
        echo '</div>';
        // Iframe
        createBlindIframe('blindIframe');

        // close tag
        closeTag('div');
        closeTag('div');
        closeTag('form');
    }
}
else
{
    echo '<div class="bg-danger p-2 text-white">';
    echo 'The form is currently not accepting registrants';
    echo '</div>';
}

?>


  

  <!-- <div id="id01" class="w3-modal">
    <div class="w3-modal-content" style="border-radius: 10px;">
      <div class="w3-container">
        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
        <center>
            <img src="webicon.ico" alt="logo" style="height:150px; width:150px; padding: 10px; margin-top: 50px;"><br>
            <h3><strong> LVCC LIBRARY RESOURCES AGREEMENT </strong></h3> <br>
            <div style="border-bottom: 1px solid black"></div>
        </center>
        <br>
     
        <p style="text-align:justify; padding: 10px;"><strong>Under Republic Act No. 8293, also known as the Intellectual Property Code of the
            Philippines, LVCC Library adheres to its policies and regulations in protecting and
            securing the exclusive rights or copyrights of every book author, publisher, and
            publishing company, and as such, we strictly implement the following agreement.</strong>
            </p>
        <br>

        <p style="text-align:justify; padding: 10px;"><strong>LIBRARY RESOURCES AGREEMENT.</strong> </p>
        <p style="padding: 10px;">1. Access to LVCS/LVCC Digital Library Resources is restricted to La Verdad Christian
            College/School students only. </p><br>
        <p style="padding: 10px;">2. The number of digital textbooks/Manual may vary according to his/her subjects per semester. </p><br>
        <p style="padding: 10px;">3. Students’ access to digital textbooks/manuals will expire after the Academic school year. </p><br>
        <p style="padding: 10px;">4. Do not download, print, or share any material on your digital library website. </p><br>
        <p style="padding: 10px;">5. Students will be penalized for the Digital textbook/manual that is downloaded,
                                    reproduced, printed, or posted on social media without permission.</p><br>
         <p style="padding: 10px;">6. Do not use works protected by copyright law without permission for a usage where
                                    such permission is required. </p><br>
        <p style="padding: 10px;">7. Avoid Plagiarism or presenting another author&#39;s language, thoughts, ideas, or
                                    expressions as one&#39;s original work. It is considered academic dishonesty and a
                                    breach of journalistic ethics. </p><br>
        
        
        

      </div>
    </div>
  </div>

  <div id="id02" class="w3-modal">
    <div class="w3-modal-content" style="border-radius: 10px;">
      <div class="w3-container">
        <span onclick="document.getElementById('id02').style.display='none'" class="w3-button w3-display-topright">&times;</span>
        <center>
            <img src="webicon.ico" alt="logo" style="height:150px; width:150px; padding: 10px; margin-top: 50px;"><br>
            <h3><strong> DATA PRIVACY INFORMATION FOR LIBRARY USERS </strong></h3> <br>
            <div style="border-bottom: 1px solid black"></div>
        </center>
        <br>    
       
        <div style="padding: 10px;">
        <p>
            <strong>Introduction</strong>  <br>
            The LVCC Library is keen on maintaining the confidentiality and privacy of its patrons. 
            Therefore, it is the LVCC Library's policy to only store, maintain, and use information obtained 
            rom its stakeholders and users for pertinent, essential, and clearly defined purposes. 
            This information is also to be monitored and protected in accordance with the law to ensure 
            that personal privacy is protected. <br>
            We kindly request that you take a moment to read through the key provisions of this privacy statement in order to comply with data protection laws (RA 10173). You agree to the use of your data in accordance with this privacy statement if you use any of the LVCC Library's services.
        </p> <br>
        <p>
            <strong>Information We Collect</strong>  <br>
            The goal of the LVCC Library is to identify its users by collecting only the necessary personal information, which includes the following: a) Name b) Birthday c) Gender d) Grade & Section, Year & Course, Faculty/College/Institute/Department affiliation e) Scholarship Status f) Home address g) Email address h) Contact number i) Fb Account.
        </p><br>
        <p>
            <strong>
                Why We Proces your Data
            </strong>
            To receive library privileges, the LVCC Library must have access to your personal information. The information you provide will only be used to: a) create/update a library account; b) access library resources and services; c) communicate library-related matters; and d) generate statistical reports
        </p><br>
        <p>
            <Strong>How We Share Information</Strong> <br>
            The LVCC Library will only use, disclose, or share your personal information for library-related purposes, such as a) informing the Office of the Dean, Finance Office, and Office of the Registrar about library accountability; b) promoting the library and improving its services, and c) generating reports for library benefactors. We may also share your information if required by regulatory agencies or local and international accrediting bodies.
        </p><br>
        <p>
            <strong>Your Choices and Responsibilities</strong> <br> 
            You may opt not to grant us to use your personal information for library related purposes by unticking the option below. By doing so, you may not be able to maximize the use of the LVCC library services. You are responsible for ensuring the completeness and accuracy of all information provided to the LVCC Library. If you provide false information on purpose, you may be denied library privileges.
        </p><br>
        <p>
            <strong>Other Important Information</strong> <br>
            The LVCC Library conducts regular monitoring to ensure compliance with the principles outlined in this policy. Library users who have questions or concerns regarding this privacy policy may contact the Data Protection Officer at <a href="mailto:dpo@laverdad.edu.ph" style="color:blue;">dpo@laverdad.edu.ph</a> or the Head Librarian at <a href= "mailto:library.apalit@laverdad.edu.ph" style="color:blue;">library.apalit@laverdad.edu.ph</a>
        </p>
        </div>
       
      </div>
    </div>
  </div> -->

</div>
</body>
<script>
        imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
        }
        }
        
      </script>
</html>