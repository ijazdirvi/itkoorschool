
<header class="navbar navbar-fixed-top"><!-- set fixed position by adding class "navbar-fixed-top" -->

    <div class="navbar-inner">

        <!-- logo -->
        <div class="navbar-brand">
            <a href="<?php echo base_url(); ?>">
                <img src="uploads/logo.png" width="50" alt="" />
            </a>
        </div>


        <!-- main menu -->

        <!-- main menu -->

        <ul class="navbar-nav">


            <!-- DASHBOARD -->
            <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
                <a href="<?php echo base_url(); ?>index.php?admin/dashboard">
                    <i class="entypo-gauge"></i>
                    <span><?php echo get_phrase('dashboard'); ?></span>
                </a>
            </li>

            <!-- STUDENT -->
            <li class="<?php
            if ($page_name == 'student_add' ||
                    $page_name == 'student_bulk_add' ||
                    $page_name == 'student_information' ||
                    $page_name == 'student_marksheet' ||
                    $page_name == 'student_promotion' || 
                    $page_name == 'withdraw_student'  )
                echo 'opened active has-sub';
            ?> ">
                <a href="#">
                    <i class="fa fa-group"></i>
                    <span><?php echo get_phrase('student'); ?></span>
                </a>
                <ul>
                    <!-- STUDENT INFORMATION -->
                    <li class= "<?php if ($page_name == 'student_information' || $page_name == 'student_marksheet') echo 'opened active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_information">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('student_information'); ?></span>
                        </a> 
                    </li>
                    <!-- STUDENT ADMISSION -->
                    <li class="<?php if ($page_name == 'student_add') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_add">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('admit_student'); ?></span>
                        </a>
                    </li>
                    <!-- STUDENT EXCEL ADMISSION -->
                    <li class="<?php if ($page_name == 'student_excel_add') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_excel_add">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('admit_excel_student'); ?></span>
                        </a>
                    </li>

                    <!-- STUDENT BULK ADMISSION -->
                    <li class="<?php if ($page_name == 'student_bulk_add') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_bulk_add">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('admit_bulk_student'); ?></span>
                        </a>
                    </li>



                    <!-- STUDENT PROMOTION -->
                    <li class="<?php if ($page_name == 'student_promotion') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_promotion">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('student_promotion'); ?></span>
                        </a>
                    </li>


                    <li class="<?php if ($page_name == 'withdraw_student') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/withdraw_student/ ">  
                            <i class="entypo-gauge"></i>
                            <span><?php echo get_phrase('withdraw_student'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- TEACHER -->
            <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
                <a href="<?php echo base_url(); ?>index.php?admin/teacher">
                    <i class="entypo-users"></i>
                    <span><?php echo get_phrase('teacher'); ?></span>
                </a>
            </li>

            <!-- PARENTS -->
            <li class="<?php if ($page_name == 'parent') echo 'active'; ?> ">
                <a href="<?php echo base_url(); ?>index.php?admin/parent">
                    <i class="entypo-user"></i>
                    <span><?php echo get_phrase('parents'); ?></span>
                </a>
            </li>

            <!-- CLASS -->
            <li class="<?php
            if (
                    $page_name == 'class' ||
                    $page_name == 'section' ||
                    $page_name == 'academic_syllabus' ||
                    $page_name == 'subject'  ||
                    $page_name == 'class_routine_view' ||
                            $page_name == 'class_routine_add'
                    )
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-flow-tree"></i>
                    <span><?php echo get_phrase('class'); ?></span>
                </a>
                <ul>
                    <li class="<?php if ($page_name == 'class') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/classes">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_classes'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'section') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/section">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_sections'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'academic_syllabus') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/academic_syllabus">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('academic_syllabus'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'subject') echo 'opened active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/subject">
                            <i class="entypo-docs"></i>
                            <span><?php echo get_phrase('subject'); ?></span>
                        </a>
                        
                    </li>
                    <!-- CLASS ROUTINE -->
                    <li class="<?php
                    if ($page_name == 'class_routine_view' ||
                            $page_name == 'class_routine_add')
                        echo 'opened active';
                    ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/class_routine_view">
                            <i class="entypo-target"></i>
                            <span><?php echo get_phrase('class_routine'); ?></span>
                        </a> 
                    </li>
                </ul>


            </li>

            <!-- SUBJECT -->




            <!-- DAILY ATTENDANCE -->
            <li class="<?php
            if (
                    $page_name == 'manage_attendance' ||
                    $page_name == 'manage_attendance_view' ||
                    $page_name == 'attendance_report' ||
                    $page_name == 'holidays' ||
                    $page_name == 'attendance_report_view'
            )
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-chart-area"></i>
                    <span><?php echo get_phrase('daily_attendance'); ?></span>
                </a>
                <ul>
                   
                    <li class="<?php if (($page_name == 'manage_attendance' || $page_name == 'manage_attendance_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/manage_attendance">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('daily_atendance'); ?></span>
                        </a>
                    </li>

               

                    <li class="<?php if (( $page_name == 'attendance_report' || $page_name == 'attendance_report_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/attendance_report">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('attendance_report'); ?></span>
                        </a>
                    </li>

                

                    <li class="<?php if (( $page_name == 'holidays')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/holidays">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('holidays'); ?></span>
                        </a>
                    </li>

                </ul>
            </li>


            <!-- EXAMS -->
            <li class="<?php
            if ($page_name == 'exam' ||
                    $page_name == 'grade' ||
                    $page_name == 'marks_manage' ||
                    $page_name == 'exam_marks_sms' ||
                    $page_name == 'tabulation_sheet' ||
                    $page_name == 'time_table' ||
                    $page_name == 'student_roll_slip' || $page_name == 'award_list' ||
                    $page_name == 'marks_manage_view')
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-graduation-cap"></i>
                    <span><?php echo get_phrase('exam'); ?></span>
                </a>
                <ul>
                    <li class="<?php if ($page_name == 'exam') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/exam">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_list'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'grade') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/grade">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_grades'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'marks_manage' || $page_name == 'marks_manage_view') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/marks_manage">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_marks'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'exam_marks_sms') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/exam_marks_sms">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('send_marks_by_sms'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'tabulation_sheet') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/tabulation_sheet">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('tabulation_sheet'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'student_roll_slip') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_roll_slip">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('student_roll_no'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'time_table') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/time_table">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('date_sheet'); ?></span>
                        </a>
                    </li>

                    <li class="<?php if ($page_name == 'award_list') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/award_list">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('award_list'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'print_marksheets') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/print_marksheets">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('print_marksheets'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- ACCOUNTING -->
            <li class="<?php
            if ($page_name == 'income' || $page_name == 'fee_types' || $page_name == 'student_fee_slip' ||
                    $page_name == 'expense' || $page_name == 'student_transaction' ||
                    $page_name == 'expense_category' || $page_name == 'create_invoice' ||
                    $page_name == 'student_payment')
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-suitcase"></i>
                    <span><?php echo get_phrase('accounting'); ?></span>
                </a>

                <ul>
                    
                     <li class="<?php if ($page_name == 'monthly_fee_structure') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/monthly_fee_structure">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('monthly_fee_structure'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'fee_types') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/feetypes">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('fee_types'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'student_fee_slip') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_fee_slip">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('student_fee'); ?></span>
                        </a>
                    </li>	
                    <li class="<?php if ($page_name == 'student_transaction') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/student_transaction">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('student_transaction'); ?></span>
                        </a>
                    </li>		

                    <li class="<?php if ($page_name == 'create_invoice') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/create_invoice">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('create_invoice'); ?></span>
                        </a>
                    </li>


                    <li class="<?php if ($page_name == 'expense') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/expense">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('expense'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'expense_category') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/expense_category">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('expense_category'); ?></span>
                        </a>
                    </li>

                    <li class="<?php if ($page_name == 'income') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/income">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('income'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>


        <!-- SCHOOL ACTIVITY -->
            <li class="">
                <a href="#"> <i class="entypo-location"></i>
                    <span><?php echo get_phrase('activity'); ?></span>
                </a>
                <ul>
                    <!-- LIBRARY -->
                    <li class="<?php if ($page_name == 'book') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/book">
                            <i class="entypo-book"></i>
                            <span><?php echo get_phrase('library'); ?></span>
                        </a>
                    </li>

                    <!-- TRANSPORT -->
                    <li class="<?php if ($page_name == 'transport') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/transport">
                            <i class="entypo-location"></i>
                            <span><?php echo get_phrase('transport'); ?></span>
                        </a>
                    </li>
                    <!-- NOTICEBOARD -->
                    <li class="<?php if ($page_name == 'noticeboard') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/noticeboard">
                            <i class="entypo-doc-text-inv"></i>
                            <span><?php echo get_phrase('noticeboard'); ?></span>
                        </a>
                    </li>
                    <!-- UPLOAD GENERAL DOCUMENTS  -->
                    <li class="<?php if ($page_name == 'upload_general_documnets') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/general_documnets">
                            <i class="entypo-book"></i>
                            <span><?php echo get_phrase('Upload_documnents'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>



            <!-- MESSAGE -->
            <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
                <a href="#">
                    <i class="entypo-mail"></i>
                    <span><?php echo get_phrase('message'); ?></span>
                </a>


                <ul>
                    <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/message">
                            <i class="entypo-mail"></i>
                            <span><?php echo get_phrase('email_message'); ?></span>
                        </a>
                    </li>
                    <li class="<?php
                    if ($page_name == 'general_sms_directory' ||
                            $page_name == 'send_general_sms')
                        echo 'opened active';
                    ?> ">
                        <a href="#">
                            <i class="entypo-mail"></i>
                            <span><?php echo get_phrase('General message'); ?></span>
                        </a>

                        <ul>
                            <li class="<?php if ($page_name == 'general_sms_directory') echo 'active' ?>">
                                <a href="<?php echo base_url(); ?>index.php?admin/general_sms_directory">
                                    <i class="entypo-phone"></i>
                                    <span><?php echo get_phrase('Phone_directory') ?></span>
                                </a>
                            </li>
                            <li class="<?php if ($page_name == 'send_general_sms') echo 'active' ?>">
                                <a href="<?php echo base_url(); ?>index.php?admin/send_general_sms">
                                    <i class="entypo-chat"></i>
                                    <span><?php echo get_phrase('Send SMS') ?></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>


            <!-- SETTINGS -->
            <li class="<?php
            if ($page_name == 'system_settings' ||
                    $page_name == 'manage_language' ||
                    $page_name == 'sms_settings')
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-lifebuoy"></i>
                    <span><?php echo get_phrase('settings'); ?></span>
                </a>
                <ul>
                    <li class="<?php if ($page_name == 'system_settings') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/system_settings">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('general_settings'); ?></span>
                        </a>
                    </li>

                    <li class="<?php if ($page_name == 'manage_language') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/manage_language">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('language_settings'); ?></span>
                        </a>
                    </li>
                    <!-- ACCOUNT -->
                    <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?admin/manage_profile">
                            <i class="entypo-lock"></i>
                            <span><?php echo get_phrase('account'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>  
        </ul> 



        <!-- notifications and other links -->
        <ul class="nav navbar-right pull-right">


            <!-- mobile only -->
            <li class="visible-xs">	

                <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                <div class="horizontal-mobile-menu visible-xs">
                    <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>

            </li>

        </ul> 
    </div>

</header>




<!--


<div class="sidebar-menu">
    <header class="logo-env" >

         logo 
        <div class="logo" style="">
            <a href="<?php echo base_url(); ?>">
                <img src="uploads/logo.png"  style="max-height:60px;"/>
            </a>
        </div>

         logo collapse icon 
        <div class="sidebar-collapse" style="">
            <a href="#" class="sidebar-collapse-icon with-animation">

                <i class="entypo-menu"></i>
            </a>
        </div>

         open/close menu icon (do not remove if you want to enable menu on mobile devices) 
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
            </a>
        </div>
    </header>
        
    <div class="sidebar-menu-inner">
    <ul id="main-menu" class="main-menu">
         add class "multiple-expanded" to allow multiple submenus to open 
         class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" 


         DASHBOARD 
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/dashboard">
                <i class="entypo-gauge"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>

         STUDENT 
        <li class="<?php
if (
        $page_name == 'student_add' ||
        $page_name == 'student_bulk_add' ||
        $page_name == 'student_information' ||
        $page_name == 'student_marksheet' ||
        $page_name == 'student_promotion' ||
        $page_name == 'student_excel_add'
)
    echo 'opened active';
?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/student_information/">
                <i class="fa fa-group"></i>
                <span><?php echo get_phrase('student'); ?></span>
            </a>

        </li>

         TEACHER 
        <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/teacher">
                <i class="entypo-users"></i>
                <span><?php echo get_phrase('teacher'); ?></span>
            </a>
        </li>

         PARENTS 
        <li class="<?php if ($page_name == 'parent') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/parent">
                <i class="entypo-user"></i>
                <span><?php echo get_phrase('parents'); ?></span>
            </a>
        </li>

         CLASS 
        <li class="<?php
if ($page_name == 'class' ||
        $page_name == 'section' ||
        $page_name == 'academic_syllabus')
    echo 'opened active';
?> ">
            <a href="#">
                <i class="entypo-flow-tree"></i>
                <span><?php echo get_phrase('class'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'class') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/classes">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_classes'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'section') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/section">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_sections'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'academic_syllabus') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/academic_syllabus">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('academic_syllabus'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

         SUBJECT 
        <li class="<?php if ($page_name == 'subject') echo 'opened active'; ?> ">
            <a href="#">
                <i class="entypo-docs"></i>
                <span><?php echo get_phrase('subject'); ?></span>
            </a>
            <ul>
<?php
$classes = $this->db->get('class')->result_array();
foreach ($classes as $row):
    ?>
                                                    <li class="<?php if ($page_name == 'subject' && $class_id == $row['class_id']) echo 'active'; ?>">
                                                        <a href="<?php echo base_url(); ?>index.php?admin/subject/<?php echo $row['class_id']; ?>">
                                                            <span><?php echo get_phrase('class'); ?> <?php echo $row['name']; ?></span>
                                                        </a>
                                                    </li>
<?php endforeach; ?>
            </ul>
        </li>

         CLASS ROUTINE 
        <li class="<?php
if ($page_name == 'class_routine_view' ||
        $page_name == 'class_routine_add')
    echo 'opened active';
?> ">
            <a href="#">
                <i class="entypo-target"></i>
                <span><?php echo get_phrase('class_routine'); ?></span>
            </a>
            <ul>
<?php
$classes = $this->db->get('class')->result_array();
foreach ($classes as $row):
    ?>
                                                    <li class="<?php if ($page_name == 'class_routine_view' && $class_id == $row['class_id']) echo 'active'; ?>">
                                                        <a href="<?php echo base_url(); ?>index.php?admin/class_routine_view/<?php echo $row['class_id']; ?>">
                                                            <span><?php echo get_phrase('class'); ?> <?php echo $row['name']; ?></span>
                                                        </a>
                                                    </li>
<?php endforeach; ?>
            </ul>
        </li>

         DAILY ATTENDANCE 
        <li class="<?php
if (
        $page_name == 'manage_attendance' ||
        $page_name == 'manage_attendance_view' ||
        $page_name == 'attendance_report' ||
        $page_name == 'holidays' ||
        $page_name == 'attendance_report_view'
)
    echo 'opened active';
?> ">
            <a href="#">
                <i class="entypo-chart-area"></i>
                <span><?php echo get_phrase('daily_attendance'); ?></span>
            </a>
            <ul>

                <li class="<?php if (($page_name == 'manage_attendance' || $page_name == 'manage_attendance_view')) echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_attendance">
                        <span><i class="entypo-dot"></i><?php echo get_phrase('daily_atendance'); ?></span>
                    </a>
                </li>

            </ul>
            <ul>

                <li class="<?php if (( $page_name == 'attendance_report' || $page_name == 'attendance_report_view')) echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/attendance_report">
                        <span><i class="entypo-dot"></i><?php echo get_phrase('attendance_report'); ?></span>
                    </a>
                </li>

            </ul>

            <ul>

                <li class="<?php if (( $page_name == 'holidays')) echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/holidays">
                        <span><i class="entypo-dot"></i><?php echo get_phrase('holidays'); ?></span>
                    </a>
                </li>

            </ul>
        </li>


         EXAMS 
        <li class="<?php
if ($page_name == 'exam' ||
        $page_name == 'grade' ||
        $page_name == 'marks_manage' ||
        $page_name == 'exam_marks_sms' ||
        $page_name == 'tabulation_sheet' ||
        $page_name == 'time_table' ||
        $page_name == 'student_roll_slip' ||
        $page_name == 'marks_manage_view')
    echo 'opened active';
?> ">
            <a href="#">
                <i class="entypo-graduation-cap"></i>
                <span><?php echo get_phrase('exam'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'exam') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/exam">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_list'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'grade') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/grade">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_grades'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'marks_manage' || $page_name == 'marks_manage_view') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/marks_manage">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_marks'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'exam_marks_sms') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/exam_marks_sms">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('send_marks_by_sms'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'tabulation_sheet') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/tabulation_sheet">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('tabulation_sheet'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'student_roll_slip') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_roll_slip">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_roll_no'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'time_table') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/time_table">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_time_table'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

         PAYMENT 
         <li class="<?php //if ($page_name == 'invoice') echo 'active';            ?> ">
            <a href="<?php //echo base_url();            ?>index.php?admin/invoice">
                <i class="entypo-credit-card"></i>
                <span><?php //echo get_phrase('payment');            ?></span>
            </a>
        </li> 

         ACCOUNTING 
         ACCOUNTING 
        <li class="<?php
if ($page_name == 'income' || $page_name == 'fee_types' || $page_name == 'student_fee_slip' ||
        $page_name == 'expense' || $page_name == 'student_transaction' ||
        $page_name == 'expense_category' || $page_name == 'create_invoice' ||
        $page_name == 'student_payment')
    echo 'opened active';
?> ">
            <a href="#">
                <i class="entypo-suitcase"></i>
                <span><?php echo get_phrase('accounting'); ?></span>
            </a>

            <ul>
                <li class="<?php if ($page_name == 'fee_types') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/feetypes">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('fee_types'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'student_fee_slip') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_fee_slip">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_fee'); ?></span>
                    </a>
                </li>	
                <li class="<?php if ($page_name == 'student_transaction') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_transaction">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_transaction'); ?></span>
                    </a>
                </li>		

                <li class="<?php if ($page_name == 'create_invoice') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/create_invoice">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('create_invoice'); ?></span>
                    </a>
                </li>


                <li class="<?php if ($page_name == 'expense') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/expense">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('expense'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'expense_category') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/expense_category">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('expense_category'); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'income') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/income">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('income'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

         LIBRARY 
        <li class="<?php if ($page_name == 'book') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/book">
                <i class="entypo-book"></i>
                <span><?php echo get_phrase('library'); ?></span>
            </a>
        </li>

         TRANSPORT 
        <li class="<?php if ($page_name == 'transport') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/transport">
                <i class="entypo-location"></i>
                <span><?php echo get_phrase('transport'); ?></span>
            </a>
        </li>

         DORMITORY 
        <li class="<?php if ($page_name == 'dormitory') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/dormitory">
                <i class="entypo-home"></i>
                <span><?php echo get_phrase('dormitory'); ?></span>
            </a>
        </li>

         NOTICEBOARD 
        <li class="<?php if ($page_name == 'noticeboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/noticeboard">
                <i class="entypo-doc-text-inv"></i>
                <span><?php echo get_phrase('noticeboard'); ?></span>
            </a>
        </li>

         MESSAGE 
        <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/message">
                <i class="entypo-mail"></i>
                <span><?php echo get_phrase('message'); ?></span>
            </a>
        </li>

        <li class="<?php
if ($page_name == 'general_sms_directory' ||
        $page_name == 'send_general_sms')
    echo 'opened active';
?> ">
            <a href="#">
                <i class="entypo-mail"></i>
                <span><?php echo get_phrase('General message'); ?></span>
            </a>

            <ul>
                <li class="<?php if ($page_name == 'general_sms_directory') echo 'active' ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/general_sms_directory">
                        <i class="entypo-phone"></i>
                        <span><?php echo get_phrase('Phone_directory') ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'send_general_sms') echo 'active' ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/send_general_sms">
                        <i class="entypo-chat"></i>
                        <span><?php echo get_phrase('Send SMS') ?></span>
                    </a>
                </li>
            </ul>
        </li>
         SETTINGS 
        <li class="<?php
if ($page_name == 'system_settings' ||
        $page_name == 'manage_language' ||
        $page_name == 'sms_settings')
    echo 'opened active';
?> ">
            <a href="#">
                <i class="entypo-lifebuoy"></i>
                <span><?php echo get_phrase('settings'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'system_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/system_settings">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('general_settings'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'sms_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/sms_settings">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('sms_settings'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'manage_language') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_language">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('language_settings'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

         ACCOUNT 
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/manage_profile">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
        </li>


        <li class="<?php if ($page_name == 'withdraw_student') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/withdraw_student/ ">  
                <i class="entypo-gauge"></i>
                <span><?php echo get_phrase('withdraw_student'); ?></span>
            </a>
        </li>
    </ul>
</div>
</div>-->
