<?php 
$edit_data		=	$this->db->get_where('student_withdraw' , array(
	'student_id' => $param2))->result_array();
foreach ($edit_data as $row):
	
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_student');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/withdraw_student/do_update/'.$row['student_id'], array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
                
                	<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>
                        
						<div class="col-sm-5">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="<?php echo $this->crud_model->get_image_url('student' , $row['student_id']);?>" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div><b><?php echo $this->db->get_where('student',array('student_id' => $row['student_id']))->row()->name;?> </b></div>
							</div>
						</div>
					</div>
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('reason');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="reason" value="<?php echo $row['reason']?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('prepared_by');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="prepared_by" value="<?php echo $row['prepared_by'];?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('checked_by');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="checked_by" value="<?php echo $row['check_by'];?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('remarks');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="remarks" value="<?php echo $row['remarks'];?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        <div class="col-sm-5">
							<input type="text" class="form-control" name="class" disabled
								value="<?php echo $this->db->get_where('class' , array('class_id' => $row['withdraw_class']))->row()->name; ?>">
						</div>
					</div>

					
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('withdraw_date');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control datepicker" name="withdraw_date" 
								value="<?php echo $this->db->get_where('student_withdraw' , array('student_id' => $param2))->row()->withdraw_date;?>" 
									data-start-view="2">
						</div> 
					</div>
                     <div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('dues');?></label>
                        <div class="col-sm-5">
							<input type="text" class="form-control" name="dues" value="<?php echo $row['dues'];?>"> 
						</div>
					</div>    
					
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class= "btn btn-info"><?php echo get_phrase('edit_student');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>

