<hr />
<?php
    $child_of_parent = $this->db->get_where('student' , array(
        'student_id' => $student_id
    ))->result_array();
    foreach ($child_of_parent as $row):
?>

<div class="label label-primary pull-right" style="font-size: 14px;">
    <i class="entypo-user"></i> <?php echo $row['name'];?>
</div>
<div class="row">
    <div class="col-md-12">

        <!------CONTROL TABS START------>
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
                    <?php echo get_phrase('student_transaction_list'); ?>
                </a></li>
       </ul>
        <!------CONTROL TABS END------>
        <div class="tab-content">
            <br>            
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">

                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                           
                            <th><div><?php echo get_phrase('student'); ?></div></th> 
                            <th><div><?php echo get_phrase('Parent'); ?></div></th> 
                            <th><div><?php echo get_phrase('class'); ?></div></th> 
                            <th><div><?php echo get_phrase('payment'); ?></div></th>  
                            <th><div><?php echo get_phrase('Action'); ?></div></th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        foreach ($student_transaction as $row):
                            ?>
                            <tr>
                                <td><?php
                                    echo $this->db->get_where('student', array(
                                        'student_id' => $row['student_id']
                                    ))->row()->name;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $parent_id = $this->db->get_where('student', array(
                                                'student_id' => $row['student_id']
                                            ))->row()->parent_id;
                                    echo $this->db->get_where('parent', array(
                                        'parent_id' => $parent_id
                                    ))->row()->name;
                                    ?>
                                </td>
                                <td><?php
                                    echo $this->db->get_where('class', array(
                                        'class_id' => $row['class_id']
                                    ))->row()->name;
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    $year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
                                    $this->db->select_sum('payment');
                                    $this->db->from('student_transaction');
                                    $this->db->where(array(
                                        'class_id' => $row['class_id'],
                                        'student_id' => $row['student_id'],
                                        'session' => "$year"
                                    ));
                                    $query = $this->db->get();
                                    echo $query->row()->payment;

//                                 
                                    ?>
                                </td>


                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-mini btn-black"
                                           onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_view_student_transaction/<?php echo $row['id']; ?>');">
                                            <i class="entypo-eye"></i>
                                            <?php echo get_phrase('detail'); ?>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>



            </div>




        </div>
    </div>
</div>
<?php endforeach;?>

<script type="text/javascript">

    jQuery(document).ready(function ($)
    {

        var datatable = $("#table_export").dataTable({
            "sPaginationType": "bootstrap",
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends": "xls",
                        "mColumns": [0, 1, 2, 3, 4]
                    },
                    {
                        "sExtends": "pdf",
                        "mColumns": [0, 1, 2, 3, 4]
                    },
                    {
                        "sExtends": "print",
                        "fnSetText": "Press 'esc' to return",
                        "sMessage": "<center> <h3>Student Transaction List</h3></center>  ",
                        "fnClick": function (nButton, oConfig) {
//							datatable.fnSetColumnVis(1, false);
                            datatable.fnSetColumnVis(5, false);

                            this.fnPrint(true, oConfig);

                            window.print();

                            $(window).keyup(function (e) {
                                if (e.which == 27) {
//									  datatable.fnSetColumnVis(1, true);
                                    datatable.fnSetColumnVis(5, true);
                                }
                            });
                        },
                    },
                ]
            },
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });



        var datatable = $("#table_export").dataTable();

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });


    function get_class_students(class_id) {

        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/get_class_students_parents/' + class_id,
            success: function (response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });

    }


















</script> 