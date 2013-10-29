<script type="text/javascript">
    $(document).ready( function(){
        var base_url = "<?php echo base_url(); ?>";
        var pl_ids = [];
        
        $('#pl_2').hide();
        $('#pl_3').hide();
        $('#pl_4').hide();
        $('#pl_5').hide();
        
        $('#insert_ec').click(function(){
            var percentage = $('input[name="percentage_ec"]').val();
            var description = $('textarea[name="description_ec"]').val();
            $.ajax({
                url: base_url + "index.php/rubrics/insert_ev",
                async: false,
                type: "POST",
                data: {'percentage':percentage,'description':description},
                success: function(resp){
                    console.log(jQuery.parseJSON(resp));
                    var jason = jQuery.parseJSON(resp);
                    var option = new Option(jason.description,jason.ev_id);
                    $('select[name="evaluation_criteria"]').append(option);
                    $('select[name="evaluation_criteria"]').val(jason.ev_id);
                    
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
                    var jason = jQuery.parseJSON(resp);
                    var option = new Option(jason.description,jason.pl_id);
                    $('.pl_dropdown').append(option);
                    //$('.pl_dropdown').val(jason.pl_id);
                    
                }
            });
        });
        
        $('#pl_1').change(function(){
            var pl_id = $('#pl_1 option:selected').val();
            pl_ids.push(pl_id);
            $.ajax({
                url: base_url + "index.php/rubrics/get_pl",
                async: false,
                type: "POST",
                data: {'pl_ids': pl_ids},
                success: function(resp){
                    var options = jQuery.parseJSON(resp);
                    $.each(options.pl_ids, function(i, item) {
                        var option = new Option(item,i);
                        $('#pl_2').append(option);
                        $('#pl_2').show('slow');
                    });
                }
            });
        });
        
        $('#pl_2').change(function(){
            var pl_id = $('#pl_2 option:selected').val();
            pl_ids.push(pl_id);
            $.ajax({
                url: base_url + "index.php/rubrics/get_pl",
                async: false,
                type: "POST",
                data: {'pl_ids': pl_ids},
                success: function(resp){
                    var options = jQuery.parseJSON(resp);
                    $.each(options.pl_ids, function(i, item) {
                        var option = new Option(item,i);
                        $('#pl_3').append(option);
                        $('#pl_3').show('slow');
                    });
                }
            });
        });
        
        $('#pl_3').change(function(){
            var pl_id = $('#pl_3 option:selected').val();
            pl_ids.push(pl_id);
            $.ajax({
                url: base_url + "index.php/rubrics/get_pl",
                async: false,
                type: "POST",
                data: {'pl_ids': pl_ids},
                success: function(resp){
                    var options = jQuery.parseJSON(resp);
                    $.each(options.pl_ids, function(i, item) {
                        var option = new Option(item,i);
                        $('#pl_4').append(option);
                        $('#pl_4').show('slow');
                    });
                }
            });
        });
        
        $('#pl_4').change(function(){
            var pl_id = $('#pl_4 option:selected').val();
            pl_ids.push(pl_id);
            $.ajax({
                url: base_url + "index.php/rubrics/get_pl",
                async: false,
                type: "POST",
                data: {'pl_ids': pl_ids},
                success: function(resp){
                    var options = jQuery.parseJSON(resp);
                    $.each(options.pl_ids, function(i, item) {
                        var option = new Option(item,i);
                        $('#pl_5').append(option);
                        $('#pl_5').show('slow');
                    });
                }
            });
        });
        
    });
</script>
<div class="span9 offset1">
    <h3><?php echo $rubric->name; ?></h3>
    <h4><?php echo '%'.$percentage; ?></h4>
    <?php echo form_open('rubrics/update_evaluation_criteria','',array('rubric' => $rubric_id,'ec_id' => $ec_id)); ?>
        <label for="evaluation_criteria">Evaluation Criteria</label>
        <?php echo form_dropdown('evaluation_criteria',$evaluation_criteria,'','class="drpdwnEvaluation" style="width:520px;"'); ?>
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
            <tr>
                <td>
                    <?php echo form_dropdown('performance_level_1',array('null' =>'Select a Performance') + $performance_levels,'','class ="pl_dropdown" id="pl_1" style="width:520px;"'); ?>
                </td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_2',array('null' =>'Select a Performance'),'','class ="pl_dropdown" id="pl_2" style="width:520px;"'); ?>
                </td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_3',array('null' =>'Select a Performance'),'','class ="pl_dropdown" id="pl_3" style="width:520px;"'); ?>
                </td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_4',array('null' =>'Select a Performance'),'','class ="pl_dropdown" id="pl_4" style="width:520px;"'); ?>
                </td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_5',array('null' =>'Select a Performance'),'','class ="pl_dropdown" id="pl_5" style="width:520px;"'); ?>
                </td>
            </tr>
        </table>
        <input class="btn" type="submit" style="margin-left: 260px;" value="Submit" name="submit" />
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