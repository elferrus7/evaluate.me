<script type="text/javascript">
    $(document).ready( function(){
        var base_url = "<?php echo base_url(); ?>";
        //var pl_ids = [];
        $('#pl_error').hide();
        $('#p_error').hide();
        $('.pl_dropdown').chosen();
        $('.drpdwnEvaluation').chosen();
        $('#insert_ec').click(function(){
            var percentage = $('input[name="percentage_ec"]').val();
            var description = $('textarea[name="description_ec"]').val();
            $.ajax({
                url: base_url + "index.php/rubrics/insert_ec",
                async: false,
                type: "POST",
                data: {'percentage':percentage,'description':description},
                success: function(resp){
                    //console.log(jQuery.parseJSON(resp));
                    //var jason = jQuery.parseJSON(resp);
                    location.assign('<?php echo base_url().'index.php/rubrics/create_evaluation_criteria/'.$rubric_id; ?>');
                    //$('select[name="evaluation_criteria"]').val(jason.ev_id);
                    
                }
            });
        });
        
        $('#insert_pl').click(function(){
            var percentage = $('input[name="percentage_pl"]').val();
            var description = $('textarea[name="description_pl"]').val();
            $.ajax({
                url: base_url + "index.php/rubrics/insert_pl",
                async: false,
                type: "POST",
                data: {'percentage':percentage,'description':description},
                success: function(resp){
                    /*var jason = jQuery.parseJSON(resp);
                    var option = new Option(jason.description,jason.pl_id);
                    $('.pl_dropdown').append(option);
                    //$('.pl_dropdown').val(jason.pl_id);*/
                   location.assign('<?php echo base_url().'index.php/rubrics/create_evaluation_criteria/'.$rubric_id; ?>');
                    
                }
            });
        });
        
        /*$('#pl_1_chosen').click(function(){
            var pl_id = $('#pl_1_chosen a.chosen-single');
            console.log(pl_id);
            $.ajax({
                url: base_url + "index.php/rubrics/get_pl",
                async: false,
                type: "POST",
                data: {'pl_ids': pl_ids},
                success: function(resp){
                    var options = jQuery.parseJSON(resp);
                    $.each(options.pl_ids, function(i, item) {
                        var option = new Option(item,i);
                        $('#pl_2_chosen').append(option);
                        //$('#pl_2').show('slow');
                    });
                }
           });
        });*/
        
        function get_ec(btn){
            var pls = [];
            var error = false
            $('table td div a.chosen-single').each(function(){
                if($.inArray($(this).data('id'),pls) != -1){
                    $('#pl_error').show('slow');
                    window.setInterval(function(){location.assign('<?php echo base_url().'index.php/rubrics/create_evaluation_criteria/'.$rubric_id; ?>');},3000);
                    error = true;
                }
                pls.push($(this).data('id'));
            });
            var ec = $('#ec_chosen').find('.chosen-single').data('id');
            var rubric = <?php echo $rubric_id; ?>;
            if(error){return;} 
            $.ajax({
                url: base_url + "index.php/rubrics/insert_evaluation_criteria",
                async: false,
                type: "POST",
                data: {'evaluation_criteria':ec,'rubric':rubric, 'pls':pls,'submit':btn},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        if(jason.redirect == 'next'){
                            location.assign('<?php echo base_url().'index.php/rubrics/create_evaluation_criteria/'.$rubric_id; ?>');
                        } else {
                            location.assign('<?php echo base_url().'index.php/rubrics/details_rubric/'.$rubric_id; ?>');
                        }    
                    } else {
                        $('#p_error').show('slow');
                        window.setInterval(function(){location.assign('<?php echo base_url().'index.php/rubrics/create_evaluation_criteria/'.$rubric_id; ?>');},3000);
                    }
                    
                }
            });
        }
        $('#next').click(function (){
            get_ec('next');
        });
        $('#submit').click(function (){
            get_ec('submit');
        });
        
    });
</script>
<div class="span9 offset1">
    <div class="alert alert-error" id="pl_error">You cannot use the same performance level twice</div>
    <div class="alert alert-error" id="p_error">The Rubric cannot be more of 100%</div>
    <?php echo $this->alert->display_alerts(); ?>
    <h3><?php echo $rubric->name; ?></h3>
    <h4><?php echo '%'.$percentage; ?></h4>
    <?php echo form_open('rubrics/insert_evaluation_criteria','',array('rubric' => $rubric_id)); ?>
        <label for="evaluation_criteria">Evaluation Criteria</label>
        <?php echo form_dropdown('evaluation_criteria',$evaluation_criteria,'','class="drpdwnEvaluation" style="width:520px;" id="ec" title="Evaluation Criteria"'); ?>
        <a class="btn" href="#InsertEvaluation" role="button" data-toggle="modal" title="Add new Evaluation Criteria"><i class="icon-plus"></i></a>
        <p>
            Description of the Evaluation Criteria
        </p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Performance Level <a class="btn" href="#InsertPerformance" role="button" data-toggle="modal" title="Add New Performance Level"><i class="icon-plus"></i></a></th>
                </tr>
            </thead>
            <?php for ($i=1; $i <= 5; $i++) :?>
            <tr>
                <td>
                    <?php echo form_dropdown('performance_level_'.$i,array('null' =>'Select a Performance') + $performance_levels,'','class ="pl_dropdown" id="pl_'.$i.'" style="width:520px;" title="Performance Level"'); ?>
                </td>
            </tr>
            <?php endfor; ?>
            
        </table>
        <!--<input class="btn btn-primary" type="submit" style="margin-left: 260px;" value="Next" name="submit" />-->
        <a class="btn btn-primary" id="next">Next</a>
        <a class="btn btn" id="submit" style="margin-left: 260px;">Submit</a>
        <!--<input class="btn" type="submit" style="margin-left: 260px;" value="Submit" name="submit" />-->
    </form>
    <div id="InsertEvaluation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                ×
            </button>
            <h3 id="myModalLabel">New Evaluation Criteria</h3>
        </div>
        <div class="modal-body">
            <form>
                <!--<label for="name">Name</label>
                <input type="text" placeholder="Evaluation Criteria Name" name="name_ec"/>-->
                <label for="percentage_ec">Percentage</label>
                <input type="number" placeholder="Evaluation Criteria Percentage" name="percentage_ec" step="10", min="0" max="100"/>
                <label for="description_ec">Description</label>
                <textarea name="description_ec"></textarea>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">
                Close
            </button>
            <a class="btn btn-primary" id="insert_ec" data-dismiss="modal">Save changes</a>
        </div>
    </div>
    <div id="InsertPerformance" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                ×
            </button>
            <h3 id="myModalLabel">New Performance Level</h3>
        </div>
        <div class="modal-body">
            <form>
                <!--<label for="name">Name</label>
                <input type="text" placeholder="Performance Level Name" name="name"/>-->
                <label for="percentage_pl">Percentage</label>
                <input type="number" placeholder="Performance Level Percentage" name="percentage_pl" step="10" step="10", min="0" max="100"/>
                <label for="description_pl">Description</label>
                <textarea name="description_pl"></textarea><br />
                <button class="btn" type="reset" >Reset</button>
            </form>
        </div>
        <div class="modal-footer">
            
            <button class="btn" data-dismiss="modal" aria-hidden="true">
                Close
            </button>
            <a class="btn btn-primary" id="insert_pl" data-dismiss="modal">Save changes</a>
        </div>
    </div>
</div>