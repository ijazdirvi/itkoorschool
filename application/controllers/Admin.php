<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* 	
 * 	@author 	: Joyonto Roy
 * 	date		: 27 september, 2014
 * 	Ekattor School Management System Pro
 * 	http://codecanyon.net/user/Creativeitem
 * 	support@creativeitem.com
 */

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
         

        /* cache control */
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    /*     * *default functin, redirects to login page if no admin logged in yet** */

    public function index() {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }

    /*     * *ADMIN DASHBOARD** */

    function dashboard() {



        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name'] = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
    }

    /*     * **MANAGE STUDENTS CLASSWISE**** */

    function student_add() {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name'] = 'student_add';
        $page_data['page_title'] = get_phrase('add_student');
        $this->load->view('backend/index', $page_data);
    }

    /*     * **ADMIT STUDENT ON EXCEL**** */


    /*     * **ADMIT STUDENT ON EXCEL**** */

    function student_excel_add($param1 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'import_excel') {
            set_time_limit(0);
            move_uploaded_file($_FILES['userfile']['tmp_name'], APPPATH . '/uploads/student_import.xlsx');


            // Importing excel sheet for bulk student uploads
//            include 'simplexlsx.class.php';
//
//            $xlsx = new SimpleXLSX('uploads/student_import.xlsx');

            $file = APPPATH . '/uploads/student_import.xlsx';
            $this->load->library('excel');

//read file from path
            $objPHPExcel = PHPExcel_IOFactory::load($file);

            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

//  Loop through each row of the worksheet in turn
            for ($row = 2; $row <= $highestRow; $row++) {
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);



                foreach ($rowData as $r) {
                    $data = array();
                    $data2 = array();
                    $data3 = array();
                    $data4 = array();

                    $data['admission_date'] = gmdate("Y-m-d", ($r[0] - 25569) * 86400);

                    $data['name'] = $r[2];
                    $data['birthday'] = gmdate("Y-m-d", ($r[4] - 25569) * 86400);
                    $data['dob_in_words'] = $r[5];
                    $data['caste'] = $r[6];
                    $data['sex'] = $r[14];
                    $data['address'] = $r[8];
                    $data['admit_class'] = $r[9];
                    $data['phone'] = $r[15];
                    $data['email'] = strtolower(str_replace(" ", "", $r[2])) . "" . $r[1] . "@icadir.com";
                    $data['status'] = $r[10] !== 0 ? 1 : 0;
                    $data['password'] = sha1(strtolower(str_replace(" ", "", $r[2])) . "" . $r[1]);


                    /*
                     * Enroll data
                     */
                    if ($this->input->post('section_id') != '') {
                        $data2['section_id'] = $this->input->post('section_id');
                    }
                    $data2['roll'] = $r[1];
                    $data2['status'] = $r[10] !== 0 ? 1 : 0;

                    //parent data ....
                    $data3['name'] = $r[3];
                    $data3['profession'] = $r[7];
                    $data3['address'] = $r[8];
                    $data3['email'] = strtolower(str_replace(" ", "", $r[3])) . "" . $r[1] . "@icadir.com";
                    $data3['password'] = sha1(strtolower(str_replace(" ", "", $r[3])) . "" . $r[1]);
                    $data3['phone'] = $r[15];

                    //withdraw date
                    if ($r[10] != '') {
                        $data4['reg_no'] = $r[1];
                        $data4['dues'] = $r[12];
                        $data4['withdraw_class'] = $r[10];
                        $data4['withdraw_date'] = gmdate("Y-m-d", ($r[11] - 25569) * 86400);
                        $data4['remarks'] = $r[13];
                    }
                }


                $this->db->insert('parent', $data3);

                $parent_id = $this->db->insert_id();

                $data['parent_id'] = $parent_id;
                $this->db->insert('student', $data);
                //print_r($data);

                $student_id = $this->db->insert_id();

                $data2['enroll_code'] = substr(md5(rand(0, 1000000)), 0, 7);
                $data2['student_id'] = $student_id;
                $data2['class_id'] = $this->input->post('class_id');
                if ($this->input->post('section_id') != '') {
                    $data2['section_id'] = $this->input->post('section_id');
                }
                //$data2['roll']          =   $rolls[$i];
                $data2['date_added'] = strtotime(date("Y-m-d H:i:s"));
                $data2['year'] = $this->db->get_where('settings', array(
                            'type' => 'running_year'
                        ))->row()->description;

                $this->db->insert('enroll', $data2);

                if (!empty($data4)) {
                    $data4['student_id'] = $student_id;
                    $this->db->insert('student_withdraw', $data4);
                }
            }

            redirect(base_url() . 'index.php?admin/student_information/' . $this->input->post('class_id'), 'refresh');
        }
        $page_data['page_name'] = 'student_excel_add';
        $page_data['page_title'] = get_phrase('add_excel_student');
        $this->load->view('backend/index', $page_data);
    }

    function student_bulk_add($param1 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'add_bulk_student') {

            $names = $this->input->post('name');
            $rolls = $this->input->post('roll');
            $emails = $this->input->post('email');
            $passwords = $this->input->post('password');
            $phones = $this->input->post('phone');
            $addresses = $this->input->post('address');
            $genders = $this->input->post('sex');

            $student_entries = sizeof($names);
            for ($i = 0; $i < $student_entries; $i++) {
                $data['status'] = 1;
                $data['name'] = $names[$i];
                $data['email'] = $emails[$i];
                $data['password'] = sha1($passwords[$i]);
                $data['phone'] = $phones[$i];
                $data['address'] = $addresses[$i];
                $data['sex'] = $genders[$i];

                //validate here, if the row(name, email, password) is empty or not
                if ($data['name'] == '' || $data['email'] == '' || $data['password'] == '')
                    continue;

                $this->db->insert('student', $data);
                $student_id = $this->db->insert_id();

                $data2['enroll_code'] = substr(md5(rand(0, 1000000)), 0, 7);
                $data2['student_id'] = $student_id;
                $data2['class_id'] = $this->input->post('class_id');
                if ($this->input->post('section_id') != '') {
                    $data2['section_id'] = $this->input->post('section_id');
                }
                $data2['roll'] = $rolls[$i];
                $data2['status'] = 1;
                $data2['date_added'] = strtotime(date("Y-m-d H:i:s"));
                $data2['year'] = $this->db->get_where('settings', array(
                            'type' => 'running_year'
                        ))->row()->description;

                $this->db->insert('enroll', $data2);
            }
            $this->session->set_flashdata('flash_message', get_phrase('students_added'));
            redirect(base_url() . 'index.php?admin/student_information/' . $this->input->post('class_id'), 'refresh');
        }

        $page_data['page_name'] = 'student_bulk_add';
        $page_data['page_title'] = get_phrase('add_bulk_student');
        $this->load->view('backend/index', $page_data);
    }

    function get_sections($class_id) {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/student_bulk_add_sections', $page_data);
    }

    function get_level_class($level_id) {
        $classes = $this->db->get_where('class', array('level_id' => $level_id))->result_array();
        echo '<option> Select Class</option>';
        foreach ($classes as $row) {
            echo '<option value="' . $row['class_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function student_information($class_id = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $page_data['page_name'] = 'student_information';
        $page_data['page_title'] = get_phrase('student_information') . " - " . get_phrase('class') . " : " .
                $this->crud_model->get_class_name($class_id);
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function student_marksheet($student_id = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $class_id = $this->db->get_where('enroll', array(
                    'student_id' => $student_id, 'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
                ))->row()->class_id;
        $student_name = $this->db->get_where('student', array('student_id' => $student_id))->row()->name;
        $class_name = $this->db->get_where('class', array('class_id' => $class_id))->row()->name;
        $page_data['page_name'] = 'student_marksheet';
        $page_data['page_title'] = get_phrase('marksheet_for') . ' ' . $student_name . ' (' . get_phrase('class') . ' ' . $class_name . ')';
        $page_data['student_id'] = $student_id;
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function student_marksheet_print_view($student_id, $exam_id) {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $class_id = $this->db->get_where('enroll', array(
                    'student_id' => $student_id, 'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
                ))->row()->class_id;
        $class_name = $this->db->get_where('class', array('class_id' => $class_id))->row()->name;

        $page_data['student_id'] = $student_id;
        $page_data['class_id'] = $class_id;
        $page_data['exam_id'] = $exam_id;
        $this->load->view('backend/admin/dmcprint', $page_data);
    }

    function student($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $running_year = $this->db->get_where('settings', array(
                    'type' => 'running_year'
                ))->row()->description;

        if ($param1 == 'create') {
            $status = 1;
            $data['name'] = $this->input->post('name');
            $data['birthday'] = $this->input->post('birthday');
            $data['sex'] = $this->input->post('sex');
            $data['address'] = $this->input->post('address');
            $data['phone'] = $this->input->post('phone');
            $data['email'] = $this->input->post('email');
            $data['password'] = sha1($this->input->post('password'));
            $data['parent_id'] = $this->input->post('parent_id');
            $data['dormitory_id'] = $this->input->post('dormitory_id');
            $data['transport_id'] = $this->input->post('transport_id');
            $data['admission_date'] = date("Y-m-d", strtotime($this->input->post('admission_date')));
            $data['dob_in_words'] = $this->input->post('dob_inwords');
            $data['status'] = $status;
            $this->db->insert('student', $data);
            $student_id = $this->db->insert_id();

            $data2['student_id'] = $student_id;
            $data2['enroll_code'] = substr(md5(rand(0, 1000000)), 0, 7);
            $data2['class_id'] = $this->input->post('class_id');
            $data2['status'] = $status;
            if ($this->input->post('section_id') != '') {
                $data2['section_id'] = $this->input->post('section_id');
            }

            $data2['roll'] = $this->input->post('roll');
            $data2['date_added'] = strtotime(date("Y-m-d H:i:s"));
            $data2['year'] = $running_year;
            $this->db->insert('enroll', $data2);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $student_id . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/student_add/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name'] = $this->input->post('name');
            $data['birthday'] = $this->input->post('birthday');
            $data['sex'] = $this->input->post('sex');
            $data['address'] = $this->input->post('address');
            $data['phone'] = $this->input->post('phone');
            $data['email'] = $this->input->post('email');
            $data['admission_date'] = date("Y-m-d", strtotime($this->input->post('admission_date')));
            $data['parent_id'] = $this->input->post('parent_id');
            $data['dormitory_id'] = $this->input->post('dormitory_id');
            $data['transport_id'] = $this->input->post('transport_id');

            $this->db->where('student_id', $param2);
            $this->db->update('student', $data);

            $data2['section_id'] = $this->input->post('section_id');
            $data2['roll'] = $this->input->post('roll');

            $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
            $this->db->where('student_id', $param2);
            $this->db->where('year', $running_year);
            $this->db->update('enroll', array(
                'section_id' => $data2['section_id'], 'roll' => $data2['roll']
            ));

            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $param2 . '.jpg');
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param3, 'refresh');
        }

        if ($param2 == 'delete') {
            $this->db->where('student_id', $param3);
            $parent_id = $this->db->get_where('student', array('student_id' => $param3))->row()->parent_id;
            $this->db->delete('parent', array('parent_id' => $parent_id));
            $this->db->delete('enroll', array('student_id' => $param3));
            $this->db->delete('student', array('student_id' => $param3));
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
        }
        if ($param1 == 'withdraw') {


            $status = 0;
            $data['student_id'] = $param2;
            $data['file_no'] = 123123;
            $data['reg_no'] = $this->input->post('roll');
            $data['reason'] = $this->input->post('description');
            $data['prepared_by'] = $this->input->post('prepared_by');
            $data['check_by'] = $this->input->post('check_by');
            $data['remarks'] = $this->input->post('remark');

            // $data0['withdraw_date']   = $this->input->post('withdraw');
            $data['withdraw_class'] = $param3;
            $data['withdraw_date'] = date('Y-m-d', strtotime($this->input->post('withdraw')));
            $data['dues'] = $this->input->post('dues');

            $this->db->where('student_id', $param2);

            //$this->db->update('student',$data);
            $this->db->update('student', array('status' => $status));
            $this->db->where('student_id', $param2);
            $this->db->update('enroll', array('status' => $status, 'withdraw_date' => $data['withdraw_date']));



            $this->db->insert('student_withdraw', $data);

            $this->session->set_flashdata('flash_message', get_phrase('Student withdraw Successfully'));
            //$page_data1['page_title'] = get_phrase('Withdraw_Student');
            //$page_data1['page_name'] = 'withdraw_certificate';
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
            //$this->load->view('backend/index', $page_data1);
//redirect(base_url() . 'index.php?admin/withdraw_certificate/' . $param3, 'refresh');
        }
        /*         * **STUDENT CERTIFICATES*** */
        if ($param1 == 'certificate') {
            $student_id = $param2;
            $class_id = $this->db->get_where('enroll', array(
                        'student_id' => $student_id, 'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
                    ))->row()->class_id;
            $student_name = $this->db->get_where('student', array('student_id' => $student_id))->row()->name;
            $class_name = $this->db->get_where('class', array('class_id' => $class_id))->row()->name;
            $page_data['page_name'] = 'certificate';
            $page_data['page_title'] = get_phrase('Admission_Certificate_for') . ' ' . $student_name . ' (' . get_phrase('class') . ' ' . $class_name . ')';
            $page_data['student_id'] = $student_id;
            $page_data['class_id'] = $class_id;
            $this->load->view('backend/index', $page_data);
        }
    }

    //STUDENT CERTIFICATE PRINT VIEW
    function student_certificate_print_view($student_id) {

        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['student_id'] = $student_id;
        $this->load->view('backend/admin/student_certificate_print_view', $page_data);
    }

    //STUDENT CERTIFICATE PRINT VIEW
    function withdraw_certificate_print_view($student_id) {

        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['student_id'] = $student_id;
        $page_data['page_title'] = 'withdraw_certificate';
        $this->load->view('backend/admin/withdraw_certificate_print_view', $page_data);
    }

    // STUDENT PROMOTION
    function student_promotion($param1 = '', $param2 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'promote') {
            $running_year = $this->input->post('running_year');
            $from_class_id = $this->input->post('promotion_from_class_id');
            $students_of_promotion_class = $this->db->get_where('enroll', array(
                        'class_id' => $from_class_id, 'year' => $running_year
                    ))->result_array();
            foreach ($students_of_promotion_class as $row) {
                $enroll_data['enroll_code'] = substr(md5(rand(0, 1000000)), 0, 7);
                $enroll_data['student_id'] = $row['student_id'];
                $enroll_data['section_id'] = $this->db->get_where('section', array(
                            'class_id' => $this->input->post('promotion_status_' . $row['student_id'])
                        ))->row()->section_id;
                $enroll_data['roll'] = $row['roll'];
                $enroll_data['class_id'] = $this->input->post('promotion_status_' . $row['student_id']);
                $enroll_data['year'] = $this->input->post('promotion_year');
                $enroll_data['date_added'] = strtotime(date("Y-m-d H:i:s"));
                $enroll_data['status'] = 1;
                $this->db->insert('enroll', $enroll_data);
            }
            $this->session->set_flashdata('flash_message', get_phrase('new_enrollment_successfull'));
            redirect(base_url() . 'index.php?admin/student_promotion', 'refresh');
        }

        $page_data['page_title'] = get_phrase('student_promotion');
        $page_data['page_name'] = 'student_promotion';
        $this->load->view('backend/index', $page_data);
    }

    function get_students_to_promote($class_id_from, $class_id_to, $running_year, $promotion_year) {
        $page_data['class_id_from'] = $class_id_from;
        $page_data['class_id_to'] = $class_id_to;
        $page_data['running_year'] = $running_year;
        $page_data['promotion_year'] = $promotion_year;
        $this->load->view('backend/admin/student_promotion_selector', $page_data);
    }

    /*     * **MANAGE PARENTS CLASSWISE**** */

    function parent($param1 = '', $param2 = '', $param3 = '')
    {
    if($this->  session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');
        $data['password'] = sha1($this->input->post('password'));
        $data['phone'] = $this->input->post('phone');
        $data['address'] = $this->input->post('address');
        $data['profession'] = $this->input->post('profession');
        $this->db->insert('parent', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        $this->email_model->account_opening_email('parent', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
        redirect(base_url() . 'index.php?admin/parent/', 'refresh');
    }
    if ($param1 == 'edit') {
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');
        $data['phone'] = $this->input->post('phone');
        $data['address'] = $this->input->post('address');
        $data['profession'] = $this->input->post('profession');
        $this->db->where('parent_id', $param2);
        $this->db->update('parent', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/parent/', 'refresh');
    }
    if ($param1 == 'delete') {
        $this->db->where('parent_id', $param2);
        $this->db->delete('parent');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/parent/', 'refresh');
    }
    $page_data['page_title'] = get_phrase('all_parents');
    $page_data['page_name'] = 'parent';
    $this->load->view('backend/index', $page_data);
}

/* * **MANAGE TEACHERS**** */

function teacher($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['birthday'] = $this->input->post('birthday');
        $data['sex'] = $this->input->post('sex');
        $data['address'] = $this->input->post('address');
        $data['phone'] = $this->input->post('phone');
        $data['email'] = $this->input->post('email');
        $data['password'] = sha1($this->input->post('password'));
        $this->db->insert('teacher', $data);
        $teacher_id = $this->db->insert_id();
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $teacher_id . '.jpg');
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        $this->email_model->account_opening_email('teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
        redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['birthday'] = $this->input->post('birthday');
        $data['sex'] = $this->input->post('sex');
        $data['address'] = $this->input->post('address');
        $data['phone'] = $this->input->post('phone');
        $data['email'] = $this->input->post('email');

        $this->db->where('teacher_id', $param2);
        $this->db->update('teacher', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
    } else if ($param1 == 'personal_profile') {
        $page_data['personal_profile'] = true;
        $page_data['current_teacher_id'] = $param2;
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('teacher', array(
                    'teacher_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('teacher_id', $param2);
        $this->db->delete('teacher');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
    }
    $page_data['teachers'] = $this->db->get('teacher')->result_array();
    $page_data['page_name'] = 'teacher';
    $page_data['page_title'] = get_phrase('manage_teacher');
    $this->load->view('backend/index', $page_data);
}

/* * **MANAGE SUBJECTS**** */

function subject($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['class_id'] = $this->input->post('class_id');
        $data['teacher_id'] = $this->input->post('teacher_id');
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        $this->db->insert('subject', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/subject/' . $data['class_id'], 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['class_id'] = $this->input->post('class_id');
        $data['teacher_id'] = $this->input->post('teacher_id');
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

        $this->db->where('subject_id', $param2);
        $this->db->update('subject', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/subject/' . $data['class_id'], 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('subject', array(
                    'subject_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('subject_id', $param2);
        $this->db->delete('subject');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/subject/' . $param3, 'refresh');
    }
    $page_data['class_id'] = $param1;
    if(!empty($param1))
    {
    $page_data['subjects'] = $this->db->get_where('subject', array('class_id' => $param1))->result_array();
    }
    $page_data['page_name'] = 'subject';
    $page_data['page_title'] = get_phrase('manage_subject');
    $this->load->view('backend/index', $page_data);
}

/* * **MANAGE CLASSES**** */

function classes($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['name_numeric'] = $this->input->post('name_numeric');
        $data['teacher_id'] = $this->input->post('teacher_id');
        $data['level_id'] = $this->input->post('education_level');
        $this->db->insert('class', $data);
        $class_id = $this->db->insert_id();
        //create a section by default
        $data2['class_id'] = $class_id;
        $data2['name'] = 'A';
        $this->db->insert('section', $data2);

        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/classes/', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['name_numeric'] = $this->input->post('name_numeric');
        $data['teacher_id'] = $this->input->post('teacher_id');
        $data['level_id'] = $this->input->post('education_level');
        $this->db->where('class_id', $param2);
        $this->db->update('class', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/classes/', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('class', array(
                    'class_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('class_id', $param2);
        $this->db->delete('class');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/classes/', 'refresh');
    }
    $page_data['classes'] = $this->db->get('class')->result_array();
    $page_data['page_name'] = 'class';
    $page_data['page_title'] = get_phrase('manage_class');
    $this->load->view('backend/index', $page_data);
}

// ACADEMIC SYLLABUS
function academic_syllabus($class_id = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    // detect the first class
    if ($class_id == '')
        $class_id = $this->db->get('class')->first_row()->class_id;

    $page_data['page_name'] = 'academic_syllabus';
    $page_data['page_title'] = get_phrase('academic_syllabus');
    $page_data['class_id'] = $class_id;
    $this->load->view('backend/index', $page_data);
}

function upload_academic_syllabus() {
    $data['academic_syllabus_code'] = substr(md5(rand(0, 1000000)), 0, 7);
    $data['title'] = $this->input->post('title');
    $data['description'] = $this->input->post('description');
    $data['class_id'] = $this->input->post('class_id');
    $data['uploader_type'] = $this->session->userdata('login_type');
    $data['uploader_id'] = $this->session->userdata('login_user_id');
    $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
    $data['timestamp'] = strtotime(date("Y-m-d H:i:s"));
    //uploading file using codeigniter upload library
    $files = $_FILES['file_name'];
    $this->load->library('upload');
    $config['upload_path'] = 'uploads/syllabus/';
    $config['allowed_types'] = '*';
    $_FILES['file_name']['name'] = $files['name'];
    $_FILES['file_name']['type'] = $files['type'];
    $_FILES['file_name']['tmp_name'] = $files['tmp_name'];
    $_FILES['file_name']['size'] = $files['size'];
    $this->upload->initialize($config);
    $this->upload->do_upload('file_name');

    $data['file_name'] = $_FILES['file_name']['name'];

    $this->db->insert('academic_syllabus', $data);
    $this->session->set_flashdata('flash_message', get_phrase('syllabus_uploaded'));
    redirect(base_url() . 'index.php?admin/academic_syllabus/' . $data['class_id'], 'refresh');
}

function download_academic_syllabus($academic_syllabus_code) {
    $file_name = $this->db->get_where('academic_syllabus', array(
                'academic_syllabus_code' => $academic_syllabus_code
            ))->row()->file_name;
    $this->load->helper('download');
    $data = file_get_contents("uploads/syllabus/" . $file_name);
    $name = $file_name;

    force_download($name, $data);
}

/* * **MANAGE SECTIONS**** */

function section($class_id = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    // detect the first class
    if ($class_id == '')
        $class_id = $this->db->get('class')->first_row()->class_id;

    $page_data['page_name'] = 'section';
    $page_data['page_title'] = get_phrase('manage_sections');
    $page_data['class_id'] = $class_id;
    $this->load->view('backend/index', $page_data);
}

function sections($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['nick_name'] = $this->input->post('nick_name');
        $data['class_id'] = $this->input->post('class_id');
        $data['teacher_id'] = $this->input->post('teacher_id');
        $this->db->insert('section', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/section/' . $data['class_id'], 'refresh');
    }

    if ($param1 == 'edit') {
        $data['name'] = $this->input->post('name');
        $data['nick_name'] = $this->input->post('nick_name');
        $data['class_id'] = $this->input->post('class_id');
        $data['teacher_id'] = $this->input->post('teacher_id');
        $this->db->where('section_id', $param2);
        $this->db->update('section', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/section/' . $data['class_id'], 'refresh');
    }

    if ($param1 == 'delete') {
        $this->db->where('section_id', $param2);
        $this->db->delete('section');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/section', 'refresh');
    }
}

function get_class_section($class_id) {
    $sections = $this->db->get_where('section', array(
                'class_id' => $class_id
            ))->result_array();
    foreach ($sections as $row) {
        echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
    }
}

function get_class_subject($class_id) {
    $subjects = $this->db->get_where('subject', array(
                'class_id' => $class_id
            ))->result_array();
    foreach ($subjects as $row) {
        echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
    }
}

function get_class_students($class_id) {
    $students = $this->db->get_where('enroll', array(
                'class_id' => $class_id, 'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
            ))->result_array();
    foreach ($students as $row) {
        $name = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name;
        echo '<option value="' . $row['student_id'] . '">' . $name . '</option>';
    }
}

function get_class_students_mass($class_id) {
    $students = $this->db->get_where('enroll', array(
                'class_id' => $class_id, 'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
            ))->result_array();
    echo '<div class="form-group">
                <label class="col-sm-3 control-label">' . get_phrase('students') . '</label>
                <div class="col-sm-9">';
    foreach ($students as $row) {
        $name = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name;
        echo '<div class="checkbox">
                    <label><input type="checkbox" class="check" name="student_id[]" value="' . $row['student_id'] . '">' . $name . '</label>
                </div>';
    }
    echo '<br><button type="button" class="btn btn-default" onClick="select()">' . get_phrase('select_all') . '</button>';
    echo '<button style="margin-left: 5px;" type="button" class="btn btn-default" onClick="unselect()"> ' . get_phrase('select_none') . ' </button>';
    echo '</div></div>';
}

/* * **MANAGE EXAMS**** */

function exam($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['date'] = $this->input->post('date');
        $data['comment'] = $this->input->post('comment');
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        $this->db->insert('exam', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/exam/', 'refresh');
    }
    if ($param1 == 'edit' && $param2 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['date'] = $this->input->post('date');
        $data['comment'] = $this->input->post('comment');
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

        $this->db->where('exam_id', $param3);
        $this->db->update('exam', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/exam/', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('exam', array(
                    'exam_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('exam_id', $param2);
        $this->db->delete('exam');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/exam/', 'refresh');
    }
    $page_data['exams'] = $this->db->get('exam')->result_array();
    $page_data['page_name'] = 'exam';
    $page_data['page_title'] = get_phrase('manage_exam');
    $this->load->view('backend/index', $page_data);
}

/* * **** SEND EXAM MARKS VIA SMS ******* */

function exam_marks_sms($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');


    if ($param1 == 'send_sms') {

        $exam_id = $this->input->post('exam_id');
        $class_id = $this->input->post('class_id');
        $receiver = $this->input->post('receiver');

        // get all the students of the selected class
        $students = $this->db->get_where('enroll', array(
                    'class_id' => $class_id,
                    'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
                ))->result_array();
        // get the marks of the student for selected exam
        foreach ($students as $row) {
            if ($receiver == 'student')
                $receiver_phone = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->phone;
            if ($receiver == 'parent') {
                $parent_id = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->parent_id;
                if ($parent_id != '') {
                    $receiver_phone = $this->db->get_where('parent', array('parent_id' => $row['parent_id']))->row()->phone;
                }
            }


            $this->db->where('exam_id', $exam_id);
            $this->db->where('student_id', $row['student_id']);
            $marks = $this->db->get_where('mark', array('year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description))->result_array();
            $message = '';
            foreach ($marks as $row2) {
                $subject = $this->db->get_where('subject', array('subject_id' => $row2['subject_id']))->row()->name;
                $mark_obtained = $row2['mark_obtained'];
                $message .=$subject . ' : ' . $mark_obtained . ' , ';
            }
            // send sms
//            $this->sms_model->send_sms($message, $receiver_phone);

            $phone['phone'] = $receiver_phone;
            $this->db->insert('sms_numbers', $phone);
            $this->sendPushNotification($message);
//            sleep(0.001);
        }
        $this->session->set_flashdata('flash_message', get_phrase('message_sent'));
        redirect(base_url() . 'index.php?admin/exam_marks_sms', 'refresh');
    }

    $page_data['page_name'] = 'exam_marks_sms';
    $page_data['page_title'] = get_phrase('send_marks_by_sms');
    $this->load->view('backend/index', $page_data);
}

/* * **MANAGE EXAM MARKS**** */

function marks2($exam_id = '', $class_id = '', $subject_id = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    if ($this->input->post('operation') == 'selection') {
        $page_data['exam_id'] = $this->input->post('exam_id');
        $page_data['class_id'] = $this->input->post('class_id');
        $page_data['subject_id'] = $this->input->post('subject_id');

        if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
            redirect(base_url() . 'index.php?admin/marks2/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['subject_id'], 'refresh');
        } else {
            $this->session->set_flashdata('mark_message', 'Choose exam, class and subject');
            redirect(base_url() . 'index.php?admin/marks2/', 'refresh');
        }
    }
    if ($this->input->post('operation') == 'update') {
        $students = $this->db->get_where('enroll', array('class_id' => $class_id, 'year' => $running_year))->result_array();
        foreach ($students as $row) {
            $data['mark_obtained'] = $this->input->post('mark_obtained_' . $row['student_id']);
            $data['comment'] = $this->input->post('comment_' . $row['student_id']);

            $this->db->where('mark_id', $this->input->post('mark_id_' . $row['student_id']));
            $this->db->update('mark', array('mark_obtained' => $data['mark_obtained'], 'comment' => $data['comment']));
        }
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/marks2/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id'), 'refresh');
    }
    $page_data['exam_id'] = $exam_id;
    $page_data['class_id'] = $class_id;
    $page_data['subject_id'] = $subject_id;

    $page_data['page_info'] = 'Exam marks';

    $page_data['page_name'] = 'marks2';
    $page_data['page_title'] = get_phrase('manage_exam_marks');
    $this->load->view('backend/index', $page_data);
}

function marks_manage() {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $page_data['page_name'] = 'marks_manage';
    $page_data['page_title'] = get_phrase('manage_exam_marks');
    $this->load->view('backend/index', $page_data);
}

function marks_manage_view($exam_id = '', $class_id = '', $section_id = '', $subject_id = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $page_data['exam_id'] = $exam_id;
    $page_data['class_id'] = $class_id;
    $page_data['subject_id'] = $subject_id;
    $page_data['section_id'] = $section_id;
    $page_data['page_name'] = 'marks_manage_view';
    $page_data['page_title'] = get_phrase('manage_exam_marks');
    $this->load->view('backend/index', $page_data);
}

function marks_selector() {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    $data['exam_id'] = $this->input->post('exam_id');
    $data['class_id'] = $this->input->post('class_id');
    $data['section_id'] = $this->input->post('section_id');
    $data['subject_id'] = $this->input->post('subject_id');
    $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
    $query = $this->db->get_where('mark', array(
        'exam_id' => $data['exam_id'],
        'class_id' => $data['class_id'],
        'section_id' => $data['section_id'],
        'subject_id' => $data['subject_id'],
        'year' => $data['year']
    ));
    if ($query->num_rows() < 1) {
        $students = $this->db->get_where('enroll', array(
                    'class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'year' => $data['year']
                ))->result_array();
        foreach ($students as $row) {
            $data['student_id'] = $row['student_id'];
            $this->db->insert('mark', $data);
        }
    }
    redirect(base_url() . 'index.php?admin/marks_manage_view/' . $data['exam_id'] . '/' . $data['class_id'] . '/' . $data['section_id'] . '/' . $data['subject_id'], 'refresh');
}

function marks_update($exam_id = '', $class_id = '', $section_id = '', $subject_id = '') {
    $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
    $marks_of_students = $this->db->get_where('mark', array(
                'exam_id' => $exam_id,
                'class_id' => $class_id,
                'section_id' => $section_id,
                'year' => $running_year,
                'subject_id' => $subject_id
            ))->result_array();
    foreach ($marks_of_students as $row) {
        $obtained_marks = $this->input->post('marks_obtained_' . $row['mark_id']);
        $comment = $this->input->post('comment_' . $row['mark_id']);
        $this->db->where('mark_id', $row['mark_id']);
             $mark_total = $this->input->post('mark_total');
        $this->db->update('mark', array('mark_obtained' => $obtained_marks, 'comment' => $comment,'mark_total'=>$mark_total));
    }
    $this->session->set_flashdata('flash_message', get_phrase('marks_updated'));
    redirect(base_url() . 'index.php?admin/marks_manage_view/' . $exam_id . '/' . $class_id . '/' . $section_id . '/' . $subject_id, 'refresh');
}

function marks_get_subject($class_id) {
    $page_data['class_id'] = $class_id;
    $this->load->view('backend/admin/marks_get_subject', $page_data);
}

// TABULATION SHEET
function tabulation_sheet($class_id = '', $exam_id = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    if ($this->input->post('operation') == 'selection') {
        $page_data['exam_id'] = $this->input->post('exam_id');
        $page_data['class_id'] = $this->input->post('class_id');

        if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0) {
            redirect(base_url() . 'index.php?admin/tabulation_sheet/' . $page_data['class_id'] . '/' . $page_data['exam_id'], 'refresh');
        } else {
            $this->session->set_flashdata('mark_message', 'Choose class and exam');
            redirect(base_url() . 'index.php?admin/tabulation_sheet/', 'refresh');
        }
    }
    $page_data['exam_id'] = $exam_id;
    $page_data['class_id'] = $class_id;

    $page_data['page_info'] = 'Exam marks';

    $page_data['page_name'] = 'tabulation_sheet';
    $page_data['page_title'] = get_phrase('tabulation_sheet');
    $this->load->view('backend/index', $page_data);
}

function tabulation_sheet_print_view($class_id, $exam_id) {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $page_data['class_id'] = $class_id;
    $page_data['exam_id'] = $exam_id;
    $this->load->view('backend/admin/tabulation_sheet_print_view', $page_data);
}

/* * **MANAGE GRADES**** */

function grade($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['grade_point'] = $this->input->post('grade_point');
        $data['mark_from'] = $this->input->post('mark_from');
        $data['mark_upto'] = $this->input->post('mark_upto');
        $data['comment'] = $this->input->post('comment');
        $this->db->insert('grade', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/grade/', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['grade_point'] = $this->input->post('grade_point');
        $data['mark_from'] = $this->input->post('mark_from');
        $data['mark_upto'] = $this->input->post('mark_upto');
        $data['comment'] = $this->input->post('comment');

        $this->db->where('grade_id', $param2);
        $this->db->update('grade', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/grade/', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('grade', array(
                    'grade_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('grade_id', $param2);
        $this->db->delete('grade');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/grade/', 'refresh');
    }
    $page_data['grades'] = $this->db->get('grade')->result_array();
    $page_data['page_name'] = 'grade';
    $page_data['page_title'] = get_phrase('manage_grade');
    $this->load->view('backend/index', $page_data);
}

/* * ********MANAGING CLASS ROUTINE***************** */

function class_routine($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    if ($param1 == 'create') {
        $data['class_id'] = $this->input->post('class_id');
        if ($this->input->post('section_id') != '') {
            $data['section_id'] = $this->input->post('section_id');
        }
        $data['subject_id'] = $this->input->post('subject_id');
        $data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
        $data['time_end'] = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
        $data['time_start_min'] = $this->input->post('time_start_min');
        $data['time_end_min'] = $this->input->post('time_end_min');
        $data['day'] = $this->input->post('day');
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        $this->db->insert('class_routine', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/class_routine_add/', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['class_id'] = $this->input->post('class_id');
        if ($this->input->post('section_id') != '') {
            $data['section_id'] = $this->input->post('section_id');
        }
        $data['subject_id'] = $this->input->post('subject_id');
        $data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
        $data['time_end'] = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
        $data['time_start_min'] = $this->input->post('time_start_min');
        $data['time_end_min'] = $this->input->post('time_end_min');
        $data['day'] = $this->input->post('day');
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

        $this->db->where('class_routine_id', $param2);
        $this->db->update('class_routine', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/class_routine_view/' . $data['class_id'], 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('class_routine', array(
                    'class_routine_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $class_id = $this->db->get_where('class_routine', array('class_routine_id' => $param2))->row()->class_id;
        $this->db->where('class_routine_id', $param2);
        $this->db->delete('class_routine');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/class_routine_view/' . $class_id, 'refresh');
    }
}

function class_routine_add() {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $page_data['page_name'] = 'class_routine_add';
    $page_data['page_title'] = get_phrase('add_class_routine');
    $this->load->view('backend/index', $page_data);
}

function class_routine_view($class_id) {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $page_data['page_name'] = 'class_routine_view';
    $page_data['class_id'] = $class_id;
    $page_data['page_title'] = get_phrase('class_routine');
    $this->load->view('backend/index', $page_data);
}

function class_routine_print_view($class_id, $section_id) {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    $page_data['class_id'] = $class_id;
    $page_data['section_id'] = $section_id;
    $this->load->view('backend/admin/class_routine_print_view', $page_data);
}

function get_class_section_subject($class_id) {
    $page_data['class_id'] = $class_id;
    $this->load->view('backend/admin/class_routine_section_subject_selector', $page_data);
}

function section_subject_edit($class_id, $class_routine_id) {
    $page_data['class_id'] = $class_id;
    $page_data['class_routine_id'] = $class_routine_id;
    $this->load->view('backend/admin/class_routine_section_subject_edit', $page_data);
}

function manage_attendance() {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    $page_data['page_name'] = 'manage_attendance';
    $page_data['page_title'] = get_phrase('manage_attendance_of_class');
    $this->load->view('backend/index', $page_data);
}

function manage_attendance_view($class_id = '', $section_id = '', $timestamp = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $class_name = $this->db->get_where('class', array(
                'class_id' => $class_id
            ))->row()->name;
    $page_data['class_id'] = $class_id;
    $page_data['timestamp'] = $timestamp;
    $page_data['page_name'] = 'manage_attendance_view';
    $section_name = $this->db->get_where('section', array(
                'section_id' => $section_id
            ))->row()->name;
    $page_data['section_id'] = $section_id;
    $page_data['page_title'] = get_phrase('manage_attendance_of_class') . ' ' . $class_name . ' : ' . get_phrase('section') . ' ' . $section_name;
    $this->load->view('backend/index', $page_data);
}

function get_section($class_id) {
    $page_data['class_id'] = $class_id;
    $this->load->view('backend/admin/manage_attendance_section_holder', $page_data);
}

function attendance_selector() {
    $data['class_id'] = $this->input->post('class_id');
    $data['year'] = $this->input->post('year');
    $data['timestamp'] = strtotime($this->input->post('timestamp'));
    $data['section_id'] = $this->input->post('section_id');
    $query = $this->db->get_where('attendance', array(
        'class_id' => $data['class_id'],
        'section_id' => $data['section_id'],
        'year' => $data['year'],
        'timestamp' => $data['timestamp']
    ));
    if ($query->num_rows() < 1) {
        $students = $this->db->get_where('enroll', array(
                    'class_id' => $data['class_id'], 'section_id' => $data['section_id'], 'year' => $data['year'], 'status' => 1
                ))->result_array();

        foreach ($students as $row) {
            $attn_data['class_id'] = $data['class_id'];
            $attn_data['year'] = $data['year'];
            $attn_data['timestamp'] = $data['timestamp'];
            $attn_data['section_id'] = $data['section_id'];
            $attn_data['student_id'] = $row['student_id'];
            $this->db->insert('attendance', $attn_data);
        }
    }
    redirect(base_url() . 'index.php?admin/manage_attendance_view/' . $data['class_id'] . '/' . $data['section_id'] . '/' . $data['timestamp'], 'refresh');
}

function attendance_update($class_id = '', $section_id = '', $timestamp = '') {

    $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

    $active_sms_service = $this->db->get_where('settings', array('type' => 'active_sms_service'))->row()->description;

    $attendance_of_students = $this->db->get_where('attendance', array(
                'class_id' => $class_id, 'section_id' => $section_id, 'year' => $running_year, 'timestamp' => $timestamp
            ))->result_array();

    foreach ($attendance_of_students as $row) {
        $student_id = $row['student_id'];
        $attendance_status_last = '';
        $attendance_status = $this->input->post('status_first' . $row['attendance_id']);
        if ($this->input->post("attendance_day") == "full") {
            $attendance_status_last = $this->input->post('status_last' . $row['attendance_id']);
        }
        $this->db->where('attendance_id', $row['attendance_id']);

        $this->db->update('attendance', array('status_first' => $attendance_status, 'status_last' => $attendance_status_last));


        $present_first = $this->getattendancevalue("status_first ", 1, $student_id, $running_year, date("m", $timestamp), $class_id);
        $present_last = $this->getattendancevalue("status_last ", 1, $student_id, $running_year, date("m", $timestamp), $class_id);

        $absent_first = $this->getattendancevalue("status_first ", 2, $student_id, $running_year, date("m", $timestamp), $class_id);
        $absent_last = $this->getattendancevalue("status_last ", 2, $student_id, $running_year, date("m", $timestamp), $class_id);

        $sick_first = $this->getattendancevalue("status_first ", 3, $student_id, $running_year, date("m", $timestamp), $class_id);
        $sick_last = $this->getattendancevalue("status_last ", 3, $student_id, $running_year, date("m", $timestamp), $class_id);

        $leave_first = $this->getattendancevalue("status_first ", 4, $student_id, $running_year, date("m", $timestamp), $class_id);
        $leave_last = $this->getattendancevalue("status_last ", 4, $student_id, $running_year, date("m", $timestamp), $class_id);

        $month = date("m", $timestamp);
        /*
         * find all presnt and leave


          echo "SELECT   COUNT( status_first ) AS totalCounnt
          FROM attendance  WHERE student_id =$student_id  AND class_id=$class_id
          AND status_first =1 AND year='$running_year' AND  MONTH(FROM_UNIXTIME(timestamp))=$month ";
         */
        /*
          $present_first_query = $this->db->query("SELECT   COUNT( status_first ) AS totalCounnt
          FROM attendance  WHERE student_id =$student_id  AND class_id=$class_id
          AND status_first =1 AND year='$running_year' AND  MONTH(FROM_UNIXTIME(timestamp))=$month ");
          $present_first = ($present_first_query->num_rows() > 0) ? $present_first_query->row()->totalCounnt : 0;


          $present_last_query = $this->db->query("SELECT   COUNT( status_last ) AS totalCounnt
          FROM attendance  WHERE student_id =$student_id  AND class_id=$class_id
          AND status_last =1 AND year='$running_year' AND  MONTH(FROM_UNIXTIME(timestamp))=$month ");
          $present_last = ($present_last_query->num_rows() > 0) ? $present_last_query->row()->totalCounnt : 0;
         */
        /*
         * // attendance_count
         */



        if ($attendance_status == 2 || $attendance_status_last == 2) {

            $student_name = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name;
            $phone['phone'] = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->phone;
//            $receiver_phone = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->phone;
            $message = 'Your child   is absent today.  ' . $this->db->get_where('settings', array('type' => 'absent_message'))->row()->description;
            $this->db->insert('sms_numbers', $phone);
            $this->sendPushNotification($message);
//            sleep(1);
        }



        $query = $this->db->get_where('attendance_count', array(
            'class_id' => $class_id,
            'student_id' => $student_id,
            'year' => $running_year,
            'month' => date("m", $timestamp),
        ));

        if ($query->num_rows() < 1) {
            $this->db->insert('attendance_count', array(
                'class_id' => $class_id,
                'student_id' => $student_id,
                'year' => $running_year,
                'month' => date("m", $timestamp),
                'total_atd' => (int) $present_first + (int) $present_last,
                'total_absent' => (int) $absent_first + (int) $absent_last,
                'total_leave' => (int) $leave_first + (int) $leave_last + (int) $sick_first + (int) $sick_last,
            ));
        } else {
            $this->db->where('id', $query->row()->id);
            $this->db->update('attendance_count', array(
                'total_atd' => (int) $present_first + (int) $present_last,
                'total_absent' => (int) $absent_first + (int) $absent_last,
                'total_leave' => (int) $leave_first + (int) $leave_last + (int) $sick_first + (int) $sick_last,
            ));
        }
    }
    $this->session->set_flashdata('flash_message', get_phrase('attendance_updated'));
    redirect(base_url() . 'index.php?admin/manage_attendance_view/' . $class_id . '/' . $section_id . '/' . $timestamp, 'refresh');
}

/* * **** DAILY ATTENDANCE **************** */

function manage_attendance2($date = '', $month = '', $year = '', $class_id = '', $section_id = '', $session = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    $active_sms_service = $this->db->get_where('settings', array('type' => 'active_sms_service'))->row()->description;
    $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;


    if ($_POST) {
        // Loop all the students of $class_id
        $this->db->where('class_id', $class_id);
        if ($section_id != '') {
            $this->db->where('section_id', $section_id);
        }
        //$session = base64_decode( urldecode( $session ) );
        $this->db->where('year', $session);
        $students = $this->db->get('enroll')->result_array();
        foreach ($students as $row) {
            $attendance_status = $this->input->post('status_' . $row['student_id']);

            $this->db->where('student_id', $row['student_id']);
            $this->db->where('date', $date);
            $this->db->where('year', $year);
            $this->db->where('class_id', $row['class_id']);
            if ($row['section_id'] != '' && $row['section_id'] != 0) {
                $this->db->where('section_id', $row['section_id']);
            }
            $this->db->where('session', $session);

            $this->db->update('attendance', array('status' => $attendance_status));

            if ($attendance_status == 2) {

                if ($active_sms_service != '' || $active_sms_service != 'disabled') {
                    $student_name = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name;
                    $parent_id = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->parent_id;
                    $receiver_phone = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->phone;
                    $message = 'Your child is absent today' . $this->db->get_where('settings', array('type' => 'absent_message'))->row()->description;
                    $this->sms_model->send_sms($message, $receiver_phone);
                }
            }
        }

        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/manage_attendance/' . $date . '/' . $month . '/' . $year . '/' . $class_id . '/' . $section_id . '/' . $session, 'refresh');
    }
    $page_data['date'] = $date;
    $page_data['month'] = $month;
    $page_data['year'] = $year;
    $page_data['class_id'] = $class_id;
    $page_data['section_id'] = $section_id;
    $page_data['session'] = $session;

    $page_data['page_name'] = 'manage_attendance';
    $page_data['page_title'] = get_phrase('manage_daily_attendance');
    $this->load->view('backend/index', $page_data);
}

function attendance_selector2() {
    //$session = $this->input->post('session');
    //$encoded_session = urlencode( base64_encode( $session ) );
    redirect(base_url() . 'index.php?admin/manage_attendance/' . $this->input->post('date') . '/' .
            $this->input->post('month') . '/' .
            $this->input->post('year') . '/' .
            $this->input->post('class_id') . '/' .
            $this->input->post('section_id') . '/' .
            $this->input->post('session'), 'refresh');
}

///////ATTENDANCE REPORT /////
function attendance_report() {
    $page_data['month'] = date('m');
    $page_data['page_name'] = 'attendance_report';
    $page_data['page_title'] = get_phrase('attendance_report');
    $this->load->view('backend/index', $page_data);
}

function attendance_report_view($class_id = '', $section_id = '', $month = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $class_name = $this->db->get_where('class', array(
                'class_id' => $class_id
            ))->row()->name;
    $page_data['class_id'] = $class_id;
    $page_data['month'] = $month;
    $page_data['page_name'] = 'attendance_report_view';
    $section_name = $this->db->get_where('section', array(
                'section_id' => $section_id
            ))->row()->name;
    $page_data['section_id'] = $section_id;
    $page_data['page_title'] = get_phrase('attendance_report_of_class') . ' ' . $class_name . ' : ' . get_phrase('section') . ' ' . $section_name;
    $this->load->view('backend/index', $page_data);
}

function attendance_report_print_view($class_id = '', $section_id = '', $month = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $page_data['class_id'] = $class_id;
    $page_data['section_id'] = $section_id;
    $page_data['month'] = $month;
    $this->load->view('backend/admin/attendance_report_print_view', $page_data);
}

function attendance_report_selector() {
    $data['class_id'] = $this->input->post('class_id');
    $data['year'] = $this->input->post('year');
    $data['month'] = $this->input->post('month');
    $data['section_id'] = $this->input->post('section_id');
    redirect(base_url() . 'index.php?admin/attendance_report_view/' . $data['class_id'] . '/' . $data['section_id'] . '/' . $data['month'], 'refresh');
}

/* * ****MANAGE BILLING / INVOICES WITH STATUS**** */

function invoice($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    if ($param1 == 'create') {
        $data['student_id'] = $this->input->post('student_id');
        $data['title'] = $this->input->post('title');
        $data['description'] = $this->input->post('description');
        $data['amount'] = $this->input->post('amount');
        $data['amount_paid'] = $this->input->post('amount_paid');
        $data['due'] = $data['amount'] - $data['amount_paid'];
        $data['status'] = $this->input->post('status');
        $data['creation_timestamp'] = strtotime($this->input->post('date'));
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

        $this->db->insert('invoice', $data);
        $invoice_id = $this->db->insert_id();

        $data2['invoice_id'] = $invoice_id;
        $data2['student_id'] = $this->input->post('student_id');
        $data2['title'] = $this->input->post('title');
        $data2['description'] = $this->input->post('description');
        $data2['payment_type'] = 'income';
        $data2['method'] = $this->input->post('method');
        $data2['amount'] = $this->input->post('amount_paid');
        $data2['timestamp'] = strtotime($this->input->post('date'));
        $data2['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

        $this->db->insert('payment', $data2);

        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/student_payment', 'refresh');
    }

    if ($param1 == 'create_mass_invoice') {
        foreach ($this->input->post('student_id') as $id) {

            $data['student_id'] = $id;
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
            $data['amount'] = $this->input->post('amount');
            $data['amount_paid'] = $this->input->post('amount_paid');
            $data['due'] = $data['amount'] - $data['amount_paid'];
            $data['status'] = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

            $this->db->insert('invoice', $data);
            $invoice_id = $this->db->insert_id();

            $data2['invoice_id'] = $invoice_id;
            $data2['student_id'] = $id;
            $data2['title'] = $this->input->post('title');
            $data2['description'] = $this->input->post('description');
            $data2['payment_type'] = 'income';
            $data2['method'] = $this->input->post('method');
            $data2['amount'] = $this->input->post('amount_paid');
            $data2['timestamp'] = strtotime($this->input->post('date'));
            $data2['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

            $this->db->insert('payment', $data2);
        }

        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/student_payment', 'refresh');
    }

    if ($param1 == 'do_update') {
        $data['student_id'] = $this->input->post('student_id');
        $data['title'] = $this->input->post('title');
        $data['description'] = $this->input->post('description');
        $data['amount'] = $this->input->post('amount');
        $data['status'] = $this->input->post('status');
        $data['creation_timestamp'] = strtotime($this->input->post('date'));

        $this->db->where('invoice_id', $param2);
        $this->db->update('invoice', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/invoice', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('invoice', array(
                    'invoice_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'take_payment') {
        $data['invoice_id'] = $this->input->post('invoice_id');
        $data['student_id'] = $this->input->post('student_id');
        $data['title'] = $this->input->post('title');
        $data['description'] = $this->input->post('description');
        $data['payment_type'] = 'income';
        $data['method'] = $this->input->post('method');
        $data['amount'] = $this->input->post('amount');
        $data['timestamp'] = strtotime($this->input->post('timestamp'));
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        $this->db->insert('payment', $data);

        $status['status'] = $this->input->post('status');
        $this->db->where('invoice_id', $param2);
        $this->db->update('invoice', array('status' => $status['status']));

        $data2['amount_paid'] = $this->input->post('amount');
        $data2['status'] = $this->input->post('status');
        $this->db->where('invoice_id', $param2);
        $this->db->set('amount_paid', 'amount_paid + ' . $data2['amount_paid'], FALSE);
        $this->db->set('due', 'due - ' . $data2['amount_paid'], FALSE);
        $this->db->update('invoice');

        $this->session->set_flashdata('flash_message', get_phrase('payment_successfull'));
        redirect(base_url() . 'index.php?admin/income/', 'refresh');
    }

    if ($param1 == 'delete') {
        $this->db->where('invoice_id', $param2);
        $this->db->delete('invoice');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/income', 'refresh');
    }
    $page_data['page_name'] = 'invoice';
    $page_data['page_title'] = get_phrase('manage_invoice/payment');
    $this->db->order_by('creation_timestamp', 'desc');
    $page_data['invoices'] = $this->db->get('invoice')->result_array();
    $this->load->view('backend/index', $page_data);
}

/* * ******Class Voice Payment********** */

function ClassVoice_payment($class_id = '', $running_year = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    $page_data['page_name'] = 'class_voicepayment';
    $page_data['page_title'] = get_phrase('Student_payment') . " - " . get_phrase('class') . " : " . $this->crud_model->get_class_name($class_id);
    $page_data['class_id'] = $class_id;
    // $running_session=$running_year;
    // echo $running_session;

    $page_data['data'] = $this->crud_model->get_invoice_payment($class_id, $running_year);

    $this->load->view('backend/index', $page_data);
}

/* * ********ACCOUNTING******************* */

function income($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    // SELECT sum(amount) as invisment, Month(FROM_UNIXTIME(timestamp)) as monthh from payment where year="2016-2017" GROUP by Month(FROM_UNIXTIME(timestamp))

    $page_data['page_name'] = 'income';
    $page_data['page_title'] = get_phrase('income');

    $this->load->view('backend/index', $page_data);
}

function student_payment($param1 = '', $param2 = '', $param3 = '') {

    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    $page_data['page_name'] = 'student_payment';
    $page_data['page_title'] = get_phrase('create_student_payment');
    $this->load->view('backend/index', $page_data);
}

function expense($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    if ($param1 == 'create') {
        $data['title'] = $this->input->post('title');
        $data['expense_category_id'] = $this->input->post('expense_category_id');
        $data['description'] = $this->input->post('description');
        $data['payment_type'] = 'expense';
        $data['method'] = $this->input->post('method');
        $data['amount'] = $this->input->post('amount');
        $data['timestamp'] = strtotime($this->input->post('timestamp'));
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        $this->db->insert('payment', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/expense', 'refresh');
    }

    if ($param1 == 'edit') {
        $data['title'] = $this->input->post('title');
        $data['expense_category_id'] = $this->input->post('expense_category_id');
        $data['description'] = $this->input->post('description');
        $data['payment_type'] = 'expense';
        $data['method'] = $this->input->post('method');
        $data['amount'] = $this->input->post('amount');
        $data['timestamp'] = strtotime($this->input->post('timestamp'));
        $data['year'] = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        $this->db->where('payment_id', $param2);
        $this->db->update('payment', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/expense', 'refresh');
    }

    if ($param1 == 'delete') {
        $this->db->where('payment_id', $param2);
        $this->db->delete('payment');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/expense', 'refresh');
    }

    $page_data['page_name'] = 'expense';
    $page_data['page_title'] = get_phrase('expenses');
    $this->load->view('backend/index', $page_data);
}

function expense_category($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $this->db->insert('expense_category', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/expense_category');
    }
    if ($param1 == 'edit') {
        $data['name'] = $this->input->post('name');
        $this->db->where('expense_category_id', $param2);
        $this->db->update('expense_category', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/expense_category');
    }
    if ($param1 == 'delete') {
        $this->db->where('expense_category_id', $param2);
        $this->db->delete('expense_category');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/expense_category');
    }

    $page_data['page_name'] = 'expense_category';
    $page_data['page_title'] = get_phrase('expense_category');
    $this->load->view('backend/index', $page_data);
}

/* * ********MANAGE LIBRARY / BOOKS******************* */

function book($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['description'] = $this->input->post('description');
        $data['price'] = $this->input->post('price');
        $data['author'] = $this->input->post('author');
        $data['class_id'] = $this->input->post('class_id');
        $data['status'] = $this->input->post('status');
        $this->db->insert('book', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/book', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['description'] = $this->input->post('description');
        $data['price'] = $this->input->post('price');
        $data['author'] = $this->input->post('author');
        $data['class_id'] = $this->input->post('class_id');
        $data['status'] = $this->input->post('status');

        $this->db->where('book_id', $param2);
        $this->db->update('book', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/book', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('book', array(
                    'book_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('book_id', $param2);
        $this->db->delete('book');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/book', 'refresh');
    }
    $page_data['books'] = $this->db->get('book')->result_array();
    $page_data['page_name'] = 'book';
    $page_data['page_title'] = get_phrase('manage_library_books');
    $this->load->view('backend/index', $page_data);
}

/* * ********MANAGE TRANSPORT / VEHICLES / ROUTES******************* */

function transport($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    if ($param1 == 'create') {
        $data['route_name'] = $this->input->post('route_name');
        $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
        $data['description'] = $this->input->post('description');
        $data['route_fare'] = $this->input->post('route_fare');
        $this->db->insert('transport', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/transport', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['route_name'] = $this->input->post('route_name');
        $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
        $data['description'] = $this->input->post('description');
        $data['route_fare'] = $this->input->post('route_fare');

        $this->db->where('transport_id', $param2);
        $this->db->update('transport', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/transport', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('transport', array(
                    'transport_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('transport_id', $param2);
        $this->db->delete('transport');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/transport', 'refresh');
    }
    $page_data['transports'] = $this->db->get('transport')->result_array();
    $page_data['page_name'] = 'transport';
    $page_data['page_title'] = get_phrase('manage_transport');
    $this->load->view('backend/index', $page_data);
}

/* * ********MANAGE DORMITORY / HOSTELS / ROOMS ******************* */

function dormitory($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['number_of_room'] = $this->input->post('number_of_room');
        $data['description'] = $this->input->post('description');
        $this->db->insert('dormitory', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['number_of_room'] = $this->input->post('number_of_room');
        $data['description'] = $this->input->post('description');

        $this->db->where('dormitory_id', $param2);
        $this->db->update('dormitory', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('dormitory', array(
                    'dormitory_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('dormitory_id', $param2);
        $this->db->delete('dormitory');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
    }
    $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
    $page_data['page_name'] = 'dormitory';
    $page_data['page_title'] = get_phrase('manage_dormitory');
    $this->load->view('backend/index', $page_data);
}

/* * *MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD* */

function noticeboard($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    if ($param1 == 'create') {
        $data['notice_title'] = $this->input->post('notice_title');
        $data['notice'] = $this->input->post('notice');
        $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
        $this->db->insert('noticeboard', $data);

        $this->sendPushNotification($this->input->post('notice'));



        $check_sms_send = $this->input->post('check_sms');

        if ($check_sms_send == 1) {
            // sms sending configurations

            $students = $this->db->get('student')->result_array();
            $date = $this->input->post('create_timestamp');
            $message = $data['notice'] . ' on ' . $date;

            foreach ($students as $row) {
                $phone['phone'] = $row['phone'];
                $this->db->insert('sms_numbers', $phone);
            }

            $this->sendPushNotification($this->input->post($message));
        }

        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
    }

    if ($param1 == 'do_update') {
        $data['notice_title'] = $this->input->post('notice_title');
        $data['notice'] = $this->input->post('notice');
        $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
        $this->db->where('notice_id', $param2);
        $this->db->update('noticeboard', $data);
        $this->sendPushNotification($this->input->post('notice'));

        $check_sms_send = $this->input->post('check_sms');

        if ($check_sms_send == 1) {
            // sms sending configurations


            $students = $this->db->get('student')->result_array();
            $date = $this->input->post('create_timestamp');
            $message = $data['notice_title'] . ' ';
            $message .= get_phrase('on') . ' ' . $date;

            foreach ($students as $row) {
                $phone['phone'] = $row['phone'];
                $this->db->insert('sms_numbers', $phone);
            }
        }

        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('noticeboard', array(
                    'notice_id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('notice_id', $param2);
        $this->db->delete('noticeboard');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
    }
    $page_data['page_name'] = 'noticeboard';
    $page_data['page_title'] = get_phrase('manage_noticeboard');
    $page_data['notices'] = $this->db->get('noticeboard')->result_array();
    $this->load->view('backend/index', $page_data);
}

/* private messaging */

function message($param1 = 'message_home', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    if ($param1 == 'send_new') {
        $message_thread_code = $this->crud_model->send_new_private_message();
        $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
        redirect(base_url() . 'index.php?admin/message/message_read/' . $message_thread_code, 'refresh');
    }

    if ($param1 == 'send_reply') {
        $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
        $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
        redirect(base_url() . 'index.php?admin/message/message_read/' . $param2, 'refresh');
    }

    if ($param1 == 'message_read') {
        $page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
        $this->crud_model->mark_thread_messages_read($param2);
    }

    $page_data['message_inner_page_name'] = $param1;
    $page_data['page_name'] = 'message';
    $page_data['page_title'] = get_phrase('private_messaging');
    $this->load->view('backend/index', $page_data);
}

/* * ***SITE/SYSTEM SETTINGS******** */

function system_settings($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url() . 'index.php?login', 'refresh');

    if ($param1 == 'do_update') {

        $data['description'] = $this->input->post('system_name');
        $this->db->where('type', 'system_name');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('system_title');
        $this->db->where('type', 'system_title');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('address');
        $this->db->where('type', 'address');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('phone');
        $this->db->where('type', 'phone');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('paypal_email');
        $this->db->where('type', 'paypal_email');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('currency');
        $this->db->where('type', 'currency');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('system_email');
        $this->db->where('type', 'system_email');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('system_name');
        $this->db->where('type', 'system_name');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('language');
        $this->db->where('type', 'language');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('text_align');
        $this->db->where('type', 'text_align');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('running_year');
        $this->db->where('type', 'running_year');
        $this->db->update('settings', $data);

        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
    }
    if ($param1 == 'upload_logo') {
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
        $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
        redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
    }
    if ($param1 == 'change_skin') {
        $data['description'] = $param2;
        $this->db->where('type', 'skin_colour');
        $this->db->update('settings', $data);
        $this->session->set_flashdata('flash_message', get_phrase('theme_selected'));
        redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
    }
    $page_data['page_name'] = 'system_settings';
    $page_data['page_title'] = get_phrase('system_settings');
    $page_data['settings'] = $this->db->get('settings')->result_array();
    $this->load->view('backend/index', $page_data);
}

function get_session_changer() {
    $this->load->view('backend/admin/change_session');
}

function change_session() {
    $data['description'] = $this->input->post('running_year');
    $this->db->where('type', 'running_year');
    $this->db->update('settings', $data);
    $this->session->set_flashdata('flash_message', get_phrase('session_changed'));
    redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
}

/* * *** UPDATE PRODUCT **** */

function update($task = '', $purchase_code = '') {

    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    // Create update directory.
    $dir = 'update';
    if (!is_dir($dir))
        mkdir($dir, 0777, true);

    $zipped_file_name = $_FILES["file_name"]["name"];
    $path = 'update/' . $zipped_file_name;

    move_uploaded_file($_FILES["file_name"]["tmp_name"], $path);

    // Unzip uploaded update file and remove zip file.
    $zip = new ZipArchive;
    $res = $zip->open($path);
    if ($res === TRUE) {
        $zip->extractTo('update');
        $zip->close();
        unlink($path);
    }

    $unzipped_file_name = substr($zipped_file_name, 0, -4);
    $str = file_get_contents('./update/' . $unzipped_file_name . '/update_config.json');
    $json = json_decode($str, true);



    // Run php modifications
    require './update/' . $unzipped_file_name . '/update_script.php';

    // Create new directories.
    if (!empty($json['directory'])) {
        foreach ($json['directory'] as $directory) {
            if (!is_dir($directory['name']))
                mkdir($directory['name'], 0777, true);
        }
    }

    // Create/Replace new files.
    if (!empty($json['files'])) {
        foreach ($json['files'] as $file)
            copy($file['root_directory'], $file['update_directory']);
    }

    $this->session->set_flashdata('flash_message', get_phrase('product_updated_successfully'));
    redirect(base_url() . 'index.php?admin/system_settings');
}

/* * ***SMS SETTINGS******** */

function sms_settings($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url() . 'index.php?login', 'refresh');
    if ($param1 == 'clickatell') {

        $data['description'] = $this->input->post('clickatell_user');
        $this->db->where('type', 'clickatell_user');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('clickatell_password');
        $this->db->where('type', 'clickatell_password');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('clickatell_api_id');
        $this->db->where('type', 'clickatell_api_id');
        $this->db->update('settings', $data);

        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
    }

    if ($param1 == 'twilio') {

        $data['description'] = $this->input->post('twilio_account_sid');
        $this->db->where('type', 'twilio_account_sid');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('twilio_auth_token');
        $this->db->where('type', 'twilio_auth_token');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('twilio_sender_phone_number');
        $this->db->where('type', 'twilio_sender_phone_number');
        $this->db->update('settings', $data);

        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
    }

    if ($param1 == 'active_service') {

        $data['description'] = $this->input->post('active_sms_service');
        $this->db->where('type', 'active_sms_service');
        $this->db->update('settings', $data);

        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
    }

    $page_data['page_name'] = 'sms_settings';
    $page_data['page_title'] = get_phrase('sms_settings');
    $page_data['settings'] = $this->db->get('settings')->result_array();
    $this->load->view('backend/index', $page_data);
}

/* * ***LANGUAGE SETTINGS******** */

function manage_language($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url() . 'index.php?login', 'refresh');

    if ($param1 == 'edit_phrase') {
        $page_data['edit_profile'] = $param2;
    }
    if ($param1 == 'update_phrase') {
        $language = $param2;
        $total_phrase = $this->input->post('total_phrase');
        for ($i = 1; $i < $total_phrase; $i++) {
            //$data[$language]	=	$this->input->post('phrase').$i;
            $this->db->where('phrase_id', $i);
            $this->db->update('language', array($language => $this->input->post('phrase' . $i)));
        }
        redirect(base_url() . 'index.php?admin/manage_language/edit_phrase/' . $language, 'refresh');
    }
    if ($param1 == 'do_update') {
        $language = $this->input->post('language');
        $data[$language] = $this->input->post('phrase');
        $this->db->where('phrase_id', $param2);
        $this->db->update('language', $data);
        $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
        redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
    }
    if ($param1 == 'add_phrase') {
        $data['phrase'] = $this->input->post('phrase');
        $this->db->insert('language', $data);
        $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
        redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
    }
    if ($param1 == 'add_language') {
        $language = $this->input->post('language');
        $this->load->dbforge();
        $fields = array(
            $language => array(
                'type' => 'LONGTEXT'
            )
        );
        $this->dbforge->add_column('language', $fields);

        $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
        redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
    }
    if ($param1 == 'delete_language') {
        $language = $param2;
        $this->load->dbforge();
        $this->dbforge->drop_column('language', $language);
        $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));

        redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
    }
    $page_data['page_name'] = 'manage_language';
    $page_data['page_title'] = get_phrase('manage_language');
    //$page_data['language_phrases'] = $this->db->get('language')->result_array();
    $this->load->view('backend/index', $page_data);
}

/* * ***BACKUP / RESTORE / DELETE DATA PAGE********* */

function backup_restore($operation = '', $type = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    if ($operation == 'create') {
        $this->crud_model->create_backup($type);
    }
    if ($operation == 'restore') {
        $this->crud_model->restore_backup();
        $this->session->set_flashdata('backup_message', 'Backup Restored');
        redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
    }
    if ($operation == 'delete') {
        $this->crud_model->truncate($type);
        $this->session->set_flashdata('backup_message', 'Data removed');
        redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
    }

    $page_data['page_info'] = 'Create backup / restore from backup';
    $page_data['page_name'] = 'backup_restore';
    $page_data['page_title'] = get_phrase('manage_backup_restore');
    $this->load->view('backend/index', $page_data);
}

/* * ****MANAGE OWN PROFILE AND CHANGE PASSWORD** */

function manage_profile($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url() . 'index.php?login', 'refresh');
    if ($param1 == 'update_profile_info') {
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');

        $this->db->where('admin_id', $this->session->userdata('admin_id'));
        $this->db->update('admin', $data);
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/admin_image/' . $this->session->userdata('admin_id') . '.jpg');
        $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
        redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
    }
    if ($param1 == 'change_password') {
        $data['password'] = sha1($this->input->post('password'));
        $data['new_password'] = sha1($this->input->post('new_password'));
        $data['confirm_new_password'] = sha1($this->input->post('confirm_new_password'));

        $current_password = $this->db->get_where('admin', array(
                    'admin_id' => $this->session->userdata('admin_id')
                ))->row()->password;
        if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
            $this->db->where('admin_id', $this->session->userdata('admin_id'));
            $this->db->update('admin', array(
                'password' => $data['new_password']
            ));
            $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
        } else {
            $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
        }
        redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
    }
    $page_data['page_name'] = 'manage_profile';
    $page_data['page_title'] = get_phrase('manage_profile');
    $page_data['edit_data'] = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->result_array();
    $this->load->view('backend/index', $page_data);
}

/* * ******WITDRAW STUDNET******* */

function withdraw_student($session = '' , $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url() . 'index.php?login', 'refresh');

    $page_data['page_title'] = get_phrase('Withdraw_Students');
    $page_data['page_name'] = 'withdraw_student';
    $page_data['session'] = '';
    if (isset($_POST['running_year']) && !empty($_POST['running_year'])) {

        $page_data['session'] = $_POST['running_year'];
        $page_data['page_title'] = get_phrase('Withdraw_Students') . " - " . get_phrase('session') . " : " . $_POST['running_year'];
    }
    if($session == 'do_update'){
        $data['reason'] = $this->input->post('reason');
        $data['prepared_by'] = $this->input->post('prepared_by');
        $data['check_by'] = $this->input->post('checked_by');
        $data['remarks'] = $this->input->post('remarks');
        $data['dues'] = $this->input->post('dues');
        $data['withdraw_date'] = $this->input->post('withdraw_date');
        $this->db->where('student_id' , $param2);
        $this->db->update('student_withdraw', $data);

        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/withdraw_student/', 'refresh');

    }
    if ($session == 'delete') {
        
        $withdraw_id = $this->db->get_where('student_withdraw', array('student_id' => $param2))->row()->withdraw_id;
        $this->db->where('withdraw_id', $withdraw_id);
        $this->db->delete('student_withdraw');

        //update enroll status to 1
        $this->db->where('student_id', $param2);
         $this->db->update('enroll', array('status' => '1'));

         //update student status to 1
         $this->db->where('student_id',$param2);
         $this->db->update('student', array('status' => '1'));
         
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/withdraw_student/', 'refresh');
    }
    $this->load->view('backend/index', $page_data);
}

function sendPushNotification($pushMessage) {
    // $registration_ids = "dQKKyyOB1n4:APA91bFe0EmMK3SAycb0BvNsyVzxuK3f-15KiX4Y5QRHcu5XlMvp1mTP-3H4ltvC_pLuHUn7zquOXPd2RIVHLj7HN8lc1wOootFo7CW6An1G8l6ToQ6uxb-MGBnmtAZ_MBPUdzx3lMwX";
    $registration_ids = "c_yiN97ynyo:APA91bGB7NRRR_bM6YDflp_jv4gIaGCtGUBA2NP8wsQmu4yLndlx_nTp29qsUqAdO1CeYI--3ySz0BYeH-Mf9vg5kmYbPzpodB19qzuUZIsF7astkVMxE5VHcbp8xaVUu-Hm2r6goaJc";

    define('GOOGLE_API_KEY', 'AIzaSyBIxvyansMrS5S69ExxLfMOxKMMAtfaZ1w'); //Replace with your Key
    $gcmRegIds = array();
    array_push($gcmRegIds, $registration_ids);
    $message = array('message' => $pushMessage);

    $pushStatus = '0';
    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';
    $fields = array(
        'registration_ids' => $gcmRegIds,
        'data' => $message,
    );
    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    // Close connection
    curl_close($ch);
//print($result);
    return $result;
}

function getPhoneNumber() {
    define('hostname', 'localhost');

    define('user', 'icadirco_school');

    define('password', '968Axwn9uD');

    define('databaseName', 'icadirco_school');



    $connect = mysqli_connect(hostname, user, password, databaseName);
    $query = " Select * FROM sms_numbers; ";

    $result = mysqli_query($connect, $query);
    $number_of_rows = mysqli_num_rows($result);

    $temp_array = array();

    if ($number_of_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $temp_array[] = $row;
        }
    }
    mysqli_query($connect, "TRUNCATE TABLE sms_numbers");
    header('Content-Type: application/json');
    echo json_encode(array("students" => $temp_array));
}

function withdraw_certificate($param1 = '') {
    $page_data['page_name'] = 'withdraw_certificate';
    $page_data['page_title'] = get_phrase('withdraw_certificate');
    $page_data['student_id'] = $param1;
    $this->load->view('backend/index', $page_data);
}

function getattendancevalue($status, $status_value, $student_id, $year, $month, $class_id) {

    //$this->getattendancevalue("status_first ",1, $row['student_id'], $running_year, date("m",$timestamp), $class_id);
//    $query = "SELECT 
//    COUNT( $status ) AS totalCounnt 
//    FROM attendance  
//    WHERE student_id =$student_id 
//        AND class_id=$class_id 
//            AND $status =$status_value AND year='$year' 
//            AND  MONTH(FROM_UNIXTIME(timestamp))=$month";
    /*

      $present_last_query = $this->db->query("SELECT   COUNT( status_last ) AS totalCounnt
      FROM attendance  WHERE student_id =$student_id  AND class_id=$class_id
      AND status_last =1 AND year='$running_year' AND  MONTH(FROM_UNIXTIME(timestamp))=$month ");
      echo     $present_last = ($present_last_query->num_rows() > 0) ? $present_last_query->row()->totalCounnt : 0;

     */

    $query = $this->db->query("SELECT   COUNT( $status ) AS totalCounnt 
    FROM attendance  WHERE student_id =$student_id  AND class_id=$class_id 
            AND $status =$status_value AND year='$year' AND  MONTH(FROM_UNIXTIME(timestamp))=$month ");

    return ($query->num_rows() > 0) ? $query->row()->totalCounnt : 0;
}

/**
 * 
 * Holidays
 */
function holidays($param1 = '', $param2 = '') {


    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');


    if ($param1 == 'create') {

        $data['title'] = $this->input->post('title');

        $data['from_date'] = date("Y-m-d", strtotime($this->input->post('from_date')));
        $data['to_date'] = date("Y-m-d", strtotime($this->input->post('to_date')));
//        $datediff = floor((abs(strtotime($this->input->post('from_date'))-strtotime($this->input->post('to_date')) ))/(60*60*24));

        $data['session'] = $this->db->get_where('settings', array(
                    'type' => 'running_year'
                ))->row()->description;


        $this->db->insert('holidays', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/holidays', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $this->db->where('id', $param2);
        $this->db->update('fee_types', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/feetypes', 'refresh');
    }
    if ($param1 == 'delete') {
        $this->db->where('id', $param2);
        $this->db->delete('holidays');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/holidays', 'refresh');
    }

    $page_data['holidays'] = $this->db->get_where('holidays')->result_array();
    $page_data['page_name'] = 'holidays';
    $page_data['page_title'] = get_phrase('holidays');
    $this->load->view('backend/index', $page_data);
}

/*
 * 
 * monthly_fee_structure
 */

function monthly_fee_structure($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    $year = $this->db->get_where('settings', array(
                'type' => 'running_year'
            ))->row()->description;


    if ($param1 == 'create') {
        $data = array();
        $data['class_id'] = $this->input->post('class_id');
        $data['amount'] = $this->input->post('amount');
        $data['session'] = $year;
        ;
        $this->db->insert('monthly_fee_structure', $data);

        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/monthly_fee_structure/', 'refresh');
    }
    $page_data['monthly_fee_structure']=$this->db->get_where('monthly_fee_structure', array(
                    "session" => $year
                ))->result_array();
     $page_data['page_name'] = 'monthly_fee_structure';
    $page_data['page_title'] = get_phrase('monthly_fee_structure');
    $this->load->view('backend/index', $page_data);
}

function student_fee_slip($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    $year = $this->db->get_where('settings', array(
                'type' => 'running_year'
            ))->row()->description;

    if ($param1 == 'create') {
        $data = array();


        $class_id = $this->input->post('class_id');
        $query = $this->db->get_where('enroll', array('class_id' => $class_id, "year" => $year));
        $students = $query->result_array();
        foreach ($students as $row):
            $student_id = $row['student_id'];

            foreach ($this->input->post('amount') as $key => $value) {
//                echo "$key  =>  $value";

                $data = array(
                    'class_id' => $class_id,
                    'student_id' => $student_id,
                    'session' => $year,
                    'fee_type_id' => $key,
                    'amount' => $value,
                    'created_date' => date("Y-m-d H:i:s"),
                    'status' => 1
                );
                $this->db->insert('student_fee_list', $data);
            }
        endforeach;


        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/student_fee_slip', 'refresh');
    }

    /*
      studentcreate
     * 
     */
    if ($param1 == "studentcreate") {
        $data = array();

        $class_id = $this->input->post('class_id');
        $student_id = $this->input->post('student_id');

        foreach ($this->input->post('amount') as $key => $value) {

            $data = array(
                'class_id' => $class_id,
                'student_id' => $student_id,
                'session' => $year,
                'fee_type_id' => $key,
                'amount' => $value,
                'created_date' => date("Y-m-d H:i:s"),
                'status' => 1
            );
            $this->db->insert('student_fee_list', $data);
        }


        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/student_fee_slip', 'refresh');
    }

    /*
     * studentedit
     */ if ($param1 == 'student_fee_edit') {

        $student_id = $this->db->get_where('student_fee_list', array(
                    'id' => $param2
                ))->row()->student_id;
        $class_id = $this->db->get_where('student_fee_list', array(
                    'id' => $param2
                ))->row()->class_id;



        foreach ($this->input->post('amount') as $key => $value) {


            $id = $this->db->get_where('student_fee_list', array(
                        'session' => $year,
                        'student_id' => $student_id,
                        'class_id' => $class_id,
                        'fee_type_id' => $key,
                    ))->row()->id;

            $data = array(
                'fee_type_id' => $key,
                'amount' => $value,
            );

            $this->db->where('id', $id);
            $this->db->update('student_fee_list', $data);
        }

        redirect(base_url() . 'index.php?admin/student_fee_slip', 'refresh');
    }

    if ($param1 == 'delete') {

        $student_id = $this->db->get_where('student_fee_list', array(
                    'id' => $param2
                ))->row()->student_id;

        $delete_student = $this->db->get_where('student_fee_list', array(
                    'student_id' => $student_id
                ))->result_array();

        foreach ($delete_student as $value) {
            $id = $value['id'];
            $this->db->where('id', $id);
            $this->db->delete('student_fee_list');
        }

        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/student_fee_slip', 'refresh');
    }

    $page_data['student_fee_list'] = $this->db
            ->group_by('student_id')
            ->get_where('student_fee_list', array('session' => $year))
            ->result_array();
    $page_data['page_name'] = 'student_fee_slip';
    $page_data['page_title'] = get_phrase('student_fee_slip');
    $this->load->view('backend/index', $page_data);
}

function feetypes($param1 = '', $param2 = '') {


    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');


    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');

        $this->db->insert('fee_types', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/feetypes', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $this->db->where('id', $param2);
        $this->db->update('fee_types', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
        redirect(base_url() . 'index.php?admin/feetypes', 'refresh');
    } else if ($param1 == 'edit') {
        $page_data['edit_data'] = $this->db->get_where('fee_types', array(
                    'id' => $param2
                ))->result_array();
    }
    if ($param1 == 'delete') {
        $this->db->where('id', $param2);
        $this->db->delete('fee_types');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/feetypes', 'refresh');
    }

    $page_data['fee_types'] = $this->db->get_where('fee_types')->result_array();
    $page_data['page_name'] = 'fee_types';
    $page_data['page_title'] = get_phrase('fee_types');
    $this->load->view('backend/index', $page_data);


//    $page_data['page_title'] = get_phrase('fee_types');
//    $page_data['page_name'] = 'fee_types';
//    $this->load->view('backend/index', $page_data);
}

function student_transaction($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');

    $year = $this->db->get_where('settings', array(
                'type' => 'running_year'
            ))->row()->description;

    if ($param1 == 'create') {
        $student_id = $this->input->post('student_id');
        $data['class_id'] = $this->input->post('class_id');
        $data['student_id'] = $this->input->post('student_id');
        $data['due_payment'] = $this->input->post('due_payment');
        $data['payment_type'] = $this->input->post('fee_type');
        $data['Concession'] = $this->input->post('Concession');
        $data['month'] = $this->input->post('month');
        $data['due_date'] = date("Y-m-d", strtotime($this->input->post('due_date')));
        $data['session'] = $year;
        $data['created_by'] = $this->input->post('created_by');
        $data['remarks'] = $this->input->post('remarks');
        $data['checked_by'] = $this->input->post('checked_by');

        $this->db->insert('student_transaction', $data);
        $phone = array();

        $receiver_phone = $this->db->get_where('student', array('student_id' => $student_id))->row()->phone;
        $name = $this->db->get_where('student', array('student_id' => $student_id))->row()->name;
        $phone['phone'] = $receiver_phone;
        $this->db->insert('sms_numbers', $phone);
        $message = "$name  has submitted his fees: Rs" . $this->input->post('payment') . "  From ICA Dir";
        $this->sendPushNotification($message);


        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/student_transaction', 'refresh');
    }

    /*
      studentcreate
     * 
     */
    if ($param1 == "update") {
        $data['payment'] = $this->input->post('payment');
        $data['due_date'] = date("Y-m-d", strtotime($this->input->post('due_date')));
        $data['created_by'] = $this->input->post('created_by');
        $data['remarks'] = $this->input->post('remarks');
        $data['checked_by'] = $this->input->post('checked_by');


        $this->db->where('id', $param2);
        $this->db->update('student_transaction', $data);

        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/student_transaction', 'refresh');
    }



    if ($param1 == 'delete') {
        $this->db->where('id', $param2);
        $this->db->delete('student_transaction');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/student_transaction', 'refresh');
    }

    $page_data['student_transaction'] = $this->db
            ->group_by('student_id')
            ->get_where('student_transaction', array('session' => $year))
            ->result_array();

    $page_data['page_name'] = 'student_transaction';
    $page_data['page_title'] = get_phrase('student_transaction');
    $this->load->view('backend/index', $page_data);
}

function create_invoice($param1 = '', $param2 = '') {

    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');


    $page_data['student_id'] = '';
    $page_data['class_id'] = '';
    $page_data['class_id2'] = '';

    if ($this->input->post('class_id') != '' && $this->input->post('student_id') != '') {
        $page_data['class_id'] = $this->input->post('class_id');
        $page_data['student_id'] = $this->input->post('student_id');
        $page_data['class_id2'] = '';
    } else if ($this->input->post('class_id2') != '') {
        $page_data['class_id2'] = $this->input->post('class_id2');
        $page_data['student_id'] = '';
        $page_data['class_id'] = '';
    } else {
        $page_data['student_id'] = '';
        $page_data['class_id'] = '';
        $page_data['class_id2'] = '';
    }

    $page_data['page_name'] = 'create_invoice';
    $page_data['page_title'] = get_phrase('create_invoice');
    $this->load->view('backend/index', $page_data);
}

function time_table($param1 = '', $param2 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
    $running_year = $this->db->get_where('settings', array(
                'type' => 'running_year'
            ))->row()->description;

    if ($param1 == 'create') {
        $data['class_id'] = $this->input->post('class');
        $data['section_id'] = $this->input->post('section_id');
        $data['exam_id'] = $this->input->post('exam');
        $first_subject_id = $this->input->post('subject_first');
        $second_subject_id = $this->input->post('subject_second');
        $data['first_subject'] = $this->db->get_where('subject', array('subject_id' => $first_subject_id))->row()->name;
        $data['second_subject'] = $this->db->get_where('subject', array('subject_id' => $second_subject_id))->row()->name;
        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $data['first_time'] = $this->input->post('first_time');
        $data['second_time'] = $this->input->post('second_time');
        $data['year'] = $running_year;

        $this->db->insert('time_table', $data);
        $this->session->set_flashdata('flash_message', get_phrase('Data Added Successfully'));
        redirect(base_url() . 'index.php?admin/time_table/', 'refresh');
    }
    if ($this->input->post('section_id') != '' && $this->input->post('exam') != '') {

        $page_data['class_id'] = $this->input->post('class');
        $page_data['section_id'] = $this->input->post('section_id');
        $page_data['exam_id'] = $this->input->post('exam');
    }

    $page_data['page_name'] = 'time_table';
    $page_data['page_title'] = 'Date Sheet';
    $this->load->view('backend/index', $page_data);
}

function time_table_view($class_id = '', $exam_id = '') {
    $data['class_id'] = $this->input->post('class');
    $data['section_id'] = $this->input->post('section_id');
    $data['exam_id'] = $this->input->post('exam');


    $this->load->view('backend/admin/time_table_view', $data);
}

function student_roll_slip($param = '') {


    $page_data['student_id'] = '';
    $page_data['class_id'] = '';
    $page_data['exam_id'] = '';
    $page_data['section_id'] = '';
    $year = $this->db->get_where('settings', array(
                'type' => 'running_year'
            ))->row()->description;

    $page_data['class_id2'] = '';

    if ($param == 'create_roll_no' && $this->input->post('exam') != '') {
        //Array ( [exam] => [sorting] => ascending [starting] => 100 )
        $exam_id = $this->input->post('exam');
        $sorting = trim($this->input->post('sorting'));
        $starting = $this->input->post('starting');
        /*
         * SELECT C.class_id,C.name_numeric,E.* FROM `class` as C INNER JOIN 
         *  enroll as E ON E.class_id=C.class_id  and E.year='2016-2017' and E.status=1 ORDER BY C.name_numeric ASC
         */
        $this->db->select(' C.class_id as class_idd,C.name_numeric,E.* ');
        $this->db->from('class as C');
        $this->db->join('enroll as E', 'E.class_id=C.class_id');
        $this->db->where("E.year='$year' and E.status=1");

        if ($sorting == "descending") {

            $this->db->order_by('C.name_numeric', 'DESC');

            $student_list = $this->db->get()->result_array();
//            $student_list = $this->db->order_by('class_id', 'DESC')->get_where('enroll', array(
//                        'year' => $year,
//                        'status' => 1,
//                    ))->result_array();

            foreach ($student_list as $student) {

                $data['exam_id'] = $exam_id;
                $data['session'] = $year;
                $data['student_id'] = $student['student_id'];
                $data['class_id'] = $student['class_idd'];
                $data['roll_no'] = $starting;
                $this->db->insert('roll_numbers', $data);
                $starting++;
            }
        } else {


            // SELECT * FROM `enroll` where year="2016-2017" ORDER BY class_id, student_id DESC
            $this->db->order_by('C.name_numeric', 'ASC');

            $student_list = $this->db->get()->result_array();

            foreach ($student_list as $student) {

                $data['exam_id'] = $exam_id;
                $data['session'] = $year;
                $data['student_id'] = $student['student_id'];
                $data['class_id'] = $student['class_idd'];
                $data['roll_no'] = $starting;
                $this->db->insert('roll_numbers', $data);
                $starting++;
            }
        }


        $this->session->set_flashdata('flash_message', get_phrase('Data Added Successfully'));
        redirect(base_url() . 'index.php?admin/student_roll_slip/', 'refresh');
    }


//  if ($this->input->post('section_id') != '' && $this->input->post('exam') != '') {
    if ($this->input->post('class_id') != '' && $this->input->post('exam') != '') {

        $page_data['class_id'] = $this->input->post('class_id');
        $page_data['student_id'] = $this->input->post('student_id');
        $page_data['exam_id'] = $this->input->post('exam');
        $page_data['class_id2'] = '';
    } else if ($this->input->post('class_id2') != '') {

        $page_data['class_id2'] = $this->input->post('class_id2');
        $page_data['exam_id'] = $this->input->post('exam2');
        $page_data['student_id'] = '';
        $page_data['class_id'] = '';
    } else {
        $page_data['student_id'] = '';
        $page_data['class_id'] = '';
        $page_data['exam_id'] = '';
        $page_data['section_id'] = '';
        $page_data['class_id2'] = '';
    }

    $page_data['page_name'] = "student_roll_slip";
    $page_data['page_title'] = "Student Roll No";
    $this->load->view('backend/index', $page_data);
}

function get_class_students_parents($class_id) {
    $students = $this->db->get_where('enroll', array(
                'class_id' => $class_id, 'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description
            ))->result_array();

    foreach ($students as $row) {
        $name = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name;
        $parent_id = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->parent_id;
        $parent_name = $this->db->get_where('parent', array('parent_id' => $parent_id))->row()->name;
        echo '<option value="' . $row['student_id'] . '">' . $name . ' / ' . $parent_name . '</option>';
    }
}

function general_sms_directory($param1 = '', $param2 = '', $param3 = '') {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url() . 'index.php?login', 'refresh');
    $page_data['page_title'] = 'Messages Directory';
    $page_data['page_name'] = 'general_sms_directory';

    if ($param1 == 'create') {
        $data['name'] = $this->input->post('name');
        $data['address'] = $this->input->post('address');
        $data['phone'] = $this->input->post('phone');
        $data['relation'] = $this->input->post('relation');

        $this->db->insert('phone_directory', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/general_sms_directory/', 'refresh');
    }

    if ($param1 == 'delete') {
        $this->db->where('phone_directory_id', $param2);
        $this->db->delete('phone_directory');
        $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
        redirect(base_url() . 'index.php?admin/general_sms_directory/', 'refresh');
    }
    if ($param1 == 'do_update') {
        $data['name'] = $this->input->post('name');
        $data['phone'] = $this->input->post('phone');
        $data['address'] = $this->input->post('address');
        $data['relation'] = $this->input->post('relation');
        $this->db->where('phone_directory_id', $param2);
        $this->db->update('phone_directory', $data);
        $this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
        redirect(base_url() . 'index.php?admin/general_sms_directory/', 'refresh');
    }
    $page_data['phone_directory'] = $this->db->get('phone_directory')->result_array();
    $this->load->view('backend/index', $page_data);
}

function send_general_sms() {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');
    $page_data['page_title'] = 'Send Messages ';
    $page_data['page_name'] = 'send_general_sms';
    $this->load->view('backend/index', $page_data);
}

/* General messaging */

function sending_general_sms() {
    if ($this->session->userdata('admin_login') != 1)
        redirect(base_url(), 'refresh');

    $value = $this->input->post('relation');

    $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

    $message = $this->input->post('general_message');

    switch ($value) {
        case 'class_wise':
            $classes = $this->input->post('class_wise');

            foreach ($classes as $class_id) {

                $students = $this->db->get_where('enroll', array('class_id' => $class_id, 'year' => $running_year))->result_array();
                foreach ($students as $row) {
                    $phone['phone'] = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->phone;

                    $this->db->insert('sms_numbers', $phone);
                }
            }

            break;

        case 'all_teacher' :

            $all_teacher = $this->db->get('teacher')->result_array();
            foreach ($all_teacher as $row) {
                $phone['phone'] = $this->db->get_where('teacher', array('teacher_id' => $row['teacher_id']))->row()->phone;
                $this->db->insert('sms_numbers', $phone);
            }
            break;

        case 'specific_teacher':
            $teacher_id = $this->input->post('specific_teacher');
            //$message = $this->input->post('general_message');

            foreach ($teacher_id as $result) {
                $phone['phone'] = $this->db->get_where('teacher', array('teacher_id' => $result))->row()->phone;
                $this->db->insert('sms_numbers', $phone);
            }
            break;

        case 'all_student':
            $students = $this->db->get_where('enroll', array('year' => $running_year))->result_array();
            foreach ($students as $row) {
                $phone['phone'] = $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->phone;
                $this->db->insert('sms_numbers', $phone);
            }

            break;

        case 'specific_student' :
            $classes = $this->input->post('class_id') . '<br>';
            $students = $this->input->post('student_id');
            foreach ($students as $row) {
                $phone['phone'] = $this->db->get_where('student', array('student_id' => $row))->row()->phone;
                $this->db->insert('sms_numbers', $phone);
            }
            break;
        case "all_directory":
            $phone_directory = $this->db->get('phone_directory')->result_array();
            foreach ($phone_directory as $row) {
                $phone['phone'] = $row['phone'];
                $this->db->insert('sms_numbers', $phone);
            }
            break;
        case "specific_directory":
            $phone_directory = $this->db->get('phone_directory')->result_array();
            foreach ($phone_directory as $row) {
                $phone['phone'] = $row['phone'];
                $this->db->insert('sms_numbers', $phone);
            }
            break;
    }


    $this->sendPushNotification($message);

    $this->session->set_flashdata('flash_message', get_phrase('message_send_successfully'));
    redirect(base_url() . 'index.php?admin/send_general_sms/', 'refresh');
}

function award_list($param1 = '', $param2 = '') {

    if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');


    $page_data['exam_id'] = '';
    $page_data['class_id'] = '';
    $page_data['subject_id'] = '';
    if ($this->input->post('class_id') != '' && $this->input->post('exam_id') != '' && $this->input->post('subject_id') != '') {
        $page_data['class_id'] = $this->input->post('class_id');
        $page_data['exam_id'] = $this->input->post('exam_id');
        $page_data['subject_id'] = $this->input->post('subject_id');
        $page_data['time'] = $this->input->post('time') != '' ? $this->input->post('time') : '3 Hr';
//        print_r($this->input->post());
    } else {
        $page_data['exam_id'] = '';
        $page_data['class_id'] = '';
        $page_data['subject_id'] = '';
    }

    $page_data['page_name'] = 'award_list';
    $page_data['page_title'] = get_phrase('award_list');
    $this->load->view('backend/index', $page_data);
}
function print_marksheets(){
	if ($this->session->userdata('admin_login') != 1)
        redirect('login', 'refresh');
        $page_data['page_name'] = 'print_marksheets';
    	$page_data['page_title'] = get_phrase('Marksheets');
    $this->load->view('backend/index', $page_data);
}
function print_all_marksheets(){
    if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['class_id'] = $this->input->post('class_id');
        $page_data['section_id'] = $this->input->post('section_id');
        $page_data['exam_id'] = $this->input->post('exam_id');
       
        $class_name = $this->db->get_where('class', array('class_id' => $page_data['class_id']))->row()->name;
    
        $section_name = $this->db->get_where('section', array('section_id'=>$page_data['section_id']))->row()->name;
    
        $exam_name = $this->db->get_where('exam', array('exam_id'=>$page_data['exam_id'], 'year' => $this->db->get_where('settings', array('type' => 'running_year'))->row()->description))->row()->name;
        
    
        $page_data['page_name'] = 'class_student_marksheet';
        $page_data['page_title'] = get_phrase('marksheet_for').' '.get_phrase('class').' ' .$class_name .' ' .get_phrase('Section ').' '.$section_name. ' (' .$exam_name. ' )' ;

        $this->load->view('backend/index', $page_data);
}
 function pdf_file(){
     $this->load->library("pdf");
     $this->load->view('backend/admin/pdf_file');
     
    
 }
     /* * ***GEERAL DOCUMENTS ********* */
     function general_documnets($param1='', $param2=''){
         if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        
         if ($param1 == 'create') {
        
            $data['name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['file_type'] = $this->input->post('file_type');
             //$file_name  =  $FILES['upload_file']['name'];
            //$file_name  = time().$file_name;
            
            $data['file_name'] = $_FILES['file_name']['name'];
            move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/general_files/". $_FILES["file_name"]["name"]);

        $this->db->insert('general_documnet', $data);
        
        $this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
        redirect(base_url() . 'index.php?admin/general_documnets/', 'refresh');
        }
         if($param1 == 'update'){
            $data['name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $this->db->where('file_id', $param2);
            $this->db->update('general_documnet', $data);
            
            $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/general_documnets', 'refresh');
             
         }
        
         if($param1 == 'delete'){
            $this->db->where('file_id', $param2);
            $this->db->delete('general_documnet');
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/general_documnets', 'refresh');
         }
         
         
        $page_data['page_name'] = 'upload_general_documnets';
        $page_data['page_title'] = 'Upload_General_Documents';
        $this->load->view('backend/index', $page_data);
         
     }


}
