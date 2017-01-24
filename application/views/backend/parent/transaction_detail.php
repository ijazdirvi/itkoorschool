 <?php
 
                if ($student_id != ''):
                    $year = $this->db->get_where('settings', array(
                                'type' => 'running_year'
                            ))->row()->description;

                    $studentName = $this->db->get_where('student', array('student_id' => $student_id))->row()->name;

                    $parent_id = $this->db->get_where('student', array(
                                'student_id' => $student_id
                            ))->row()->parent_id;
                    $parentName = $this->db->get_where('parent', array(
                                'parent_id' => $parent_id
                            ))->row()->name;
                    $fileid = date("dmy") . $student_id;
                    ?>
                    <span class="pull-right">
                        <a onClick="PrintElem('#studentwisepanel')" class="btn btn-default btn-icon icon-left hidden-print pull-right">
                            Print  Payment  Detail 
                            <i class="entypo-print"></i>
                        </a>
                    </span>
                    <div id="studentwisepanel">
                        <div  style="font-size:7px; color: black">
                            <style type="text/css">
                                td{
                                    padding: 1px !important;
                                }
                                th{
                                    padding: 1px !important;
                                }
                            </style>


                            <div class="text-uppercase text-center">
                                <span class="pull-left">
                                    <?php echo $fileid ?>
                                </span>
                                <span class="bold ">
                                    Payment Detail of 
                                    <b><?php echo ucfirst($studentName) . " S/O " . ucfirst($parentName) ?> 

                                        &nbsp; &nbsp; &nbsp; Class: <?php echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name; ?>
                                        &nbsp; &nbsp; &nbsp;  Session: <?php echo $year; ?>
                                    </b>
                                </span>  
                            </div>




                            <div class="row" style="border-bottom: 1px black dashed">

                                <div class="col-sm-3 col-xs-3">
                                    <?php
                                    $fee_types = $this->db->get('fee_types')->result_array();
                                    ?>

                                    <table class="table table-bordered table-condensed"  style="font-size:7px; color: black">

                                        <tr class="bg-primary text-center">
                                            <td colspan="3"> Fees Detail </td>
                                        </tr>
                                        <tr>
                                            <th> Sr# </th>
                                            <th> <?php echo get_phrase('type'); ?> </th>
                                            <th> <?php echo get_phrase('amount'); ?> </th> 

                                        </tr>

                                        <tbody>

                                            <?php
                                            $total_fees = 0;
                                            $count = 1;
                                            foreach ($fee_types as $rowtypes):
                                                ?>
                                                <tr>
                                                    <td><?php
                                                        echo $count;
                                                        $count++;
                                                        ?> </td>

                                                    <td> <?php echo $rowtypes ['name'] ?></td>

                                                    <td> <?php
                                                        $amount = $this->db->get_where(' student_fee_list', array(
                                                                    'class_id' => $class_id,
                                                                    'student_id' => $student_id,
                                                                    'session' => "$year",
                                                                    'fee_type_id' => $rowtypes['id'],
                                                                ))->row()->amount;
                                                        echo $amount;
                                                        $total_fees +=$amount;
                                                        ?> 
                                                    </td>
                                                </tr>
                                                <?php
                                            endforeach;
                                            ?> 
                                            <tr class="bg-primary">
                                                <td colspan="2"><b> Total </b></td> 
                                                <td><?php echo $total_fees ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    Signature : ______________
                                </div>


                                <div class="col-sm-4 col-xs-4">

                                    <?php
                                    $student_transaction = $this->db
                                                    ->get_where('student_transaction', array(
                                                        'class_id' => $class_id,
                                                        'student_id' => $student_id,
                                                        'session' => $year
                                                    ))->result_array();
                                    ?>

                                    <table class = "table table-bordered table-condensed" style="font-size:7px; color: black" >

                                        <tr class="bg-primary text-center">
                                            <td colspan="4">
                                                Transaction Detail  
                                            </td>
                                        </tr>

                                        <tr>
                                            <th> Sr#</th>

                                            <th><?php echo get_phrase('due_date'); ?></th>
                                            <th><?php echo get_phrase('payment'); ?></th>
                                            <th><?php echo get_phrase('remarks'); ?></th> 

                                        </tr>

                                        <tbody>

                                            <?php
                                            $total_paid = 0;
                                            $count = 1;
                                            foreach ($student_transaction as $payment):
                                                ?>
                                                <tr>
                                                    <td> <div> <?php
                                                            echo $count;
                                                            $count++;
                                                            ?> </div> </td>
                                                    <td> <?php echo date("d-m-Y", strtotime($payment ['due_date'])) ?>       </td>
                                                    <td> <?php
                                                        echo $payment ['payment'];
                                                        $total_paid +=$payment ['payment'];
                                                        ?>        
                                                    </td>

                                                    <td> <?php echo $payment['remarks'] ?>       </td> 
                                                </tr>
                                                <?php
                                            endforeach;
                                            ?>  
                                            <tr class="bg-primary text-center text-capitalize">
                                                <td colspan="2"> Total</td>
                                                <td colspan="2"><?php echo $total_paid ?></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo $count; ?>  </td>
                                                <td>  </td> <td>  </td> <td>  </td>

                                            </tr>
                                        </tbody>
                                    </table>


                                    Stamp: ______________
                                </div>


                                <div class="col-sm-2 col-xs-2">

                                    <h4 class="text-center">Summery </h4>

                                    <table class = "table table-bordered  table-condensed" style="font-size:7px; color: black">
                                        <tr>
                                            <td> Total Amount </td>
                                            <td> <?php echo $total_fees ?> </td>
                                        </tr>
                                        <tr>
                                            <td> Paid Amount </td>
                                            <td> <?php echo $total_paid ?> </td>
                                        </tr>
                                        <tr class="bg-primary">
                                            <td> Remaining </td>
                                            <td> <?php echo $total_fees - $total_paid ?> </td>
                                        </tr>
                                    </table>

                                    Remaining :______________
                                </div>


                                <div class="col-sm-3 col-xs-3" style="border-left:  1px black dashed">

                                    <b class="text-center"><?php echo $fileid ?>   &nbsp;For Office Only </b> <br/>
                                    <?php echo ucfirst($studentName) . " S/O " . ucfirst($parentName) ?> 

                                    &nbsp; &nbsp; &nbsp; Class: <?php echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name; ?>
                                    &nbsp; &nbsp; &nbsp;  Session: <?php echo $year; ?>

                                    <table class = "table table-bordered  table-condensed" style="font-size:7px; color: black">
                                        <tr>
                                            <td> Total </td>
                                            <td> <?php echo $total_fees ?> </td>
                                        </tr>
                                        <tr>
                                            <td> Paid </td>
                                            <td> <?php echo $total_paid ?> </td>
                                        </tr>
                                        <tr class="bg-primary">
                                            <td> Remaining </td>
                                            <td> <?php echo $total_fees - $total_paid ?> </td>
                                        </tr>
                                        <tr class="">
                                            <td> Due Date </td>
                                            <td>   </td>
                                        </tr>
                                        <tr class="">
                                            <td> Payment </td>
                                            <td>   </td>
                                        </tr>
                                        <tr class="">
                                            <td> Remarks </td>
                                            <td>   </td>
                                        </tr>
                                    </table>


                                </div>
                            </div>


                        </div>
                    </div>
                    <?php
                endif;
                ?>