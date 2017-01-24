

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
            
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->


        <!-- DASHBOARD -->
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/dashboard">
                <i class="entypo-gauge"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>

        <!-- STUDENT -->
         <li class="<?php
            if (    $page_name == 'student_information' ||
                    $page_name == 'student_marksheet'   )
                echo 'opened active has-sub';
            ?> ">
                <a href="#">
                    <i class="fa fa-group"></i>
                    <span><?php echo get_phrase('student'); ?></span>
                </a>
                <ul>
                    <!-- STUDENT INFORMATION -->
                    <li class= "<?php if ($page_name == 'student_information' || $page_name == 'student_marksheet') echo 'opened active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/student_information/">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('student_information'); ?></span>
                        </a> 
                    </li>
                    
                </ul>
            </li>

        <!-- TEACHER -->
        <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/teacher_list">
                <i class="entypo-users"></i>
                <span><?php echo get_phrase('teacher'); ?></span>
            </a>
        </li>
        <!-- CLASS -->
            <li class="<?php
            if (    $page_name == 'academic_syllabus' ||
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
                    <li class="<?php if ($page_name == 'academic_syllabus') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?teacher/academic_syllabus">
                            <span><i class="entypo-dot"></i> <?php echo get_phrase('academic_syllabus'); ?></span>
                        </a>
                    </li>
                    <li class="<?php if ($page_name == 'subject') echo 'opened active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?teacher/subject">
                            <i class="entypo-docs"></i>
                            <span><?php echo get_phrase('subject'); ?></span>
                        </a>
                        
                    </li>
                    <!-- CLASS ROUTINE -->
                    <li class="<?php
                    if ($page_name == 'class_routine_view')
                        echo 'opened active';
                    ?> ">
                        <a href="<?php echo base_url(); ?>index.php?teacher/class_routine_view">
                            <i class="entypo-target"></i>
                            <span><?php echo get_phrase('class_routine'); ?></span>
                        </a> 
                    </li>
                </ul>


            </li>

        
		<!-- STUDY MATERIAL -->
        <li class="<?php if ($page_name == 'study_material') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/study_material">
                <i class="entypo-book-open"></i>
                <span><?php echo get_phrase('study_material'); ?></span>
            </a>
        </li>

        <!-- ACADEMIC SYLLABUS -->
        <li class="<?php if ($page_name == 'academic_syllabus') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?teacher/academic_syllabus">
                <i class="entypo-doc"></i>
                <span><?php echo get_phrase('academic_syllabus'); ?></span>
            </a>
        </li>

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
                        <a href="<?php echo base_url(); ?>index.php?teacher/manage_attendance">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('daily_atendance'); ?></span>
                        </a>
                    </li>

               

                    <li class="<?php if (( $page_name == 'attendance_report' || $page_name == 'attendance_report_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?teacher/attendance_report">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('attendance_report'); ?></span>
                        </a>
                    </li>

                </ul>
            </li>

        <!-- EXAMS -->
        <li class="<?php if ($page_name == 'marks_manage' || $page_name == 'marks_manage_view') echo 'opened active';?> ">
            <a href="#">
                <i class="entypo-graduation-cap"></i>
                <span><?php echo get_phrase('exam'); ?></span>
            </a>
            <ul>

                <li class="<?php if ($page_name == 'marks_manage' || $page_name == 'marks_manage_view') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?teacher/marks_manage">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_marks'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="<?php
            if (
                    $page_name == 'book' ||
                    $page_name == 'noticeboard' ||
                    $page_name == 'message' 
            )
                echo 'opened active';
            ?> ">
                <a href="#">
                    <i class="entypo-location"></i>
                    <span><?php echo get_phrase('activity'); ?></span>
                </a>
                <ul>

                    <!-- LIBRARY -->
                    <li class="<?php if ($page_name == 'book') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/book">
                            <i class="entypo-book"></i>
                            <span><?php echo get_phrase('library'); ?></span>
                        </a>
                    </li>

                   <!-- NOTICEBOARD -->
                    <li class="<?php if ($page_name == 'noticeboard') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/noticeboard">
                            <i class="entypo-doc-text-inv"></i>
                        <span><?php echo get_phrase('noticeboard'); ?></span>
                        </a>
                    </li>

                <!-- MESSAGE -->
                    <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/message">
                            <i class="entypo-mail"></i>
                            <span><?php echo get_phrase('message'); ?></span>
                        </a>
                    </li>

                </ul>
            </li>
        

        <!-- ACCOUNT -->
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/manage_profile">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
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