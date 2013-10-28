<script type="text/javascript">
    $(document).ready( function(){
        $('#insert_ec').click(function(){
            var base_url = "<?php echo base_url(); ?>";
            
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
            var base_url = "<?php echo base_url(); ?>";
            
            var percentage = $('input[name="percentage_pl"]').val();
            var description = $('textarea[name="description_pl"]').val();
            $.ajax({
                url: base_url + "index.php/rubrics/insert_pl",
                async: false,
                type: "POST",
                data: {'percentage':percentage,'description':description},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    var option = new Option(jason.description,jason.pl_id);
                    $('.pl_dropdown').append(option);
                    $('.pl_dropdown').val(jason.pl_id);
                    
                }
            });
        });
        
    });
</script>
<div class="span9 offset1">
    <h3><?php echo $rubric->name; ?></h3>
    <?php echo form_open('rubrics/insert_evaluation_criteria','',array('rubric' => $rubric_id)); ?>
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
                    <?php echo form_dropdown('performance_level_1',$performance_levels,'','class ="pl_dropdown" style="width:520px;"'); ?>
                <p>
                    Description of the performance level
                </p></td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_2',$performance_levels,'','class ="pl_dropdown" style="width:520px;"'); ?>
                <p>
                    Description of the performance level
                </p></td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_3',$performance_levels,'','class ="pl_dropdown" style="width:520px;"'); ?>
                <p>
                    Description of the performance level
                </p></td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_4',$performance_levels,'','class ="pl_dropdown" style="width:520px;"'); ?>
                <p>
                    Description of the performance level
                </p></td>
            </tr>
            <tr>
                <td>
                <?php echo form_dropdown('performance_level_5',$performance_levels,'','class ="pl_dropdown" style="width:520px;"'); ?>
                <p>
                    Description of the performance level
                </p></td>
            </tr>
        </table>
        <input class="btn btn-primary" type="submit" style="margin-left: 260px;" value="Next" name="submit" />
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
                <input type="number" placeholder="Evaluation Criteria Percentage" name="percentage_ec" step="any"/>
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
                <input type="number" placeholder="Performance Level Percentage" name="percentage_pl" step="any"/>
                <label for="description_pl">Description</label>
                <textarea name="description_pl"></textarea>
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