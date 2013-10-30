<script type="text/javascript">
    $(document).ready(function(){
       $('#success').hide();
       var base_url = "<?php echo base_url(); ?>";
       var event_id = <?php echo $event_id; ?>;
       $('#select_students').chosen({
           disable_search_threshold: 10,
           no_results_text: "Oops, nothing found!",
           placeholder_text_multiple: "Select Student",
           width: "95%",
           search_contains: false
       });
       
       $('#insert_project').click(function(){
           var students = [];
           $('.search-choice').each(function(){
               students.push($(this).data('id'));
           });
           var team_name = $('input[name="team_name"]').val();
           var project_name = $('input[name="project_name"]').val();
           var description = $('textarea[name="description"]').val();
           $.ajax({
                url: base_url + "index.php/events/insert_project",
                async: false,
                type: "POST",
                data:{'team_name': team_name,'project_name':project_name,'description': description,'students': students,'event_id': event_id},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        //window.location = base_url + 'index.php/events/details_event/' + jason.event_id;
                        $('#projects tr:last').after('<tr><td>' + team_name + '</td>'+project_name+'<td>'+ description + '</td></tr>');
                        $('#success').show('slow');
                    }
                }
            });
            students = [];
       });
        
        
        $('#select_rubric').click(function (){
           var rubric_id = $('select[name="rubric"]').val();
           $.ajax({
                url: base_url + "index.php/events/select_rubric",
                async: false,
                type: "POST",
                data:{'rubric_id': rubric_id,'event_id':event_id},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        console.log('simon');
                        //window.location = base_url + 'index.php/events/details_event/' + jason.event_id;
                        $('#success').show('slow');
                    }
                }
            });
        });
        
    });
</script>
<div class="span10 offset1">
    <div class="alert alert-success" id="success">Rubric Selected</div>
    <!-- Content span -->
    <?php if(!$event_rubric): ?>
    <p>Assign Rubric <a class="btn" href="#SelectRubric" role="button" data-toggle="modal" title="Assign Rubric">Select Rubric</a></p>
    <?php else: ?>
     <p>Rubric: <?php echo $event_rubric->name.' ' . anchor('rubrics/details_rubric/'.$event_rubric->idRubrics,'<i class="icon icon-zoom-in"></i>','title="Details Rubric"'); ?></p>
    <?php endif; ?>
    <?php echo $table; ?>
    <?php echo $table_judges; ?>
    <p>Add Project <a class="btn" href="#InsertProject" role="button" data-toggle="modal" title="Add new Project"><i class="icon-plus"></i></a></p>
    <?php echo $table_projects; ?>
</div><!-- Content span -->
<div id="InsertProject" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                ×
            </button>
            <h3 id="myModalLabel">Add Project</h3>
        </div>
        <div class="modal-body">
            <form>
                <label for="team_name">Team Name</label>
                <input type="text" name="team_name" placeholder="Team name" />
                <label for="team_name">Project Name</label>
                <input type="text" name="project_name" placeholder="Project name" />
                <label for="description">Description</label>
                <textarea name="description"></textarea>
                <?php echo form_dropdown('students',$students,'','id="select_students" multiple'); ?>
                <br />
                <button class="btn" type="reset" >Reset</button>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">
                Close
            </button>
            <a class="btn btn-primary" id="insert_project" data-dismiss="modal">Submit</a>
        </div>
    </div>
    
<div id="SelectRubric" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                ×
            </button>
            <h3 id="myModalLabel">Select Rubric</h3>
        </div>
        <div class="modal-body">
            <form>  
                <?php echo form_dropdown('rubric',$rubrics,''); ?>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">
                Close
            </button>
            <a class="btn btn-primary" id="select_rubric" data-dismiss="modal">Submit</a>
        </div>
    </div>