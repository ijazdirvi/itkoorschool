
<div class="row">
    <div class="col-sm-10 col-sm-offset-1 ">
        <?php echo form_open(base_url() . 'index.php?admin/print_all_marksheets');?>
            
        <div class="col-sm-3">
            <select name="class_id" class="form-control" data-validate="required" id="class_id" 	
                        data-message-required="<?php echo get_phrase('value_required');?>" onchange="return get_class_sections(this.value)">                             
                    <option value=""><?php echo get_phrase('select');?></option>                             
                        <?php 								
                        $classes = $this->db->get('class')->result_array();								
                        foreach($classes as $row):									
                        ?>                            		
                    <option value="<?php echo $row['class_id'];?>">											
                        <?php echo $row['name'];?>                                            
                    </option>                               
                        <?php								
                        endforeach;							  
                        ?>                        
                </select>
        </div>
        <div class="col-sm-3">
           <select name="section_id" class="form-control" id="section_selector_holder">		                            
             <option value=""><?php echo get_phrase('select_class_first');?></option>			                        			                    
           </select>
        </div>
        <div class="col-sm-3">
            <select name="exam_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" >                             
                    <option value=""><?php echo get_phrase('select exam');?></option>                             
                        <?php 								
                        $exam = $this->db->get('exam')->result_array();								
                        foreach($exam as $row):									
                        ?>                            		
                    <option value="<?php echo $row['exam_id'];?>">											
                        <?php echo $row['name'];?>                                            
                    </option>                               
                        <?php								
                        endforeach;							  
                        ?>                        
                </select>
        </div>
         <div class="col-sm-3">
            <a href="<?php echo base_url(); ?>index.php?admin/" class="btn btn-primary pull-right">     
                <i class="entypo-plus-circled"></i> <?php echo get_phrase('Print'); ?> 
            </a> 
        </div>   
       <?php echo form_close();?>
    </div>
        
</div>



<script type="text/javascript">
    
    function get_class_sections(class_id) {   
        
        $.ajax({          
            
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,   
            
            success: function(response)      
    
    {                jQuery('#section_selector_holder').html(response);   
    
    }        });  

}

</script>	

