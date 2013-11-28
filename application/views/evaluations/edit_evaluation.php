<div class="span10 offset1">
    <!-- Content span -->
    <p><b>Project name</b>: <?php echo $project->project_name; ?></p>
    <p><?php echo $ec->description; ?></p>
    <p><b><?php echo $ec->percentage; ?> Points</b></p>
    <?php echo form_open('evaluations/update_evaluation','',
                        array('rubric_id'=>$rubric->idRubrics,
                              'user_id'=>$user,
                              'project_id'=>$project->idProjects,
                              'evaluation_criteria_id'=>$ec->idEvaluation_criteria,
                              'evaluation_id'=>$evaluation->idEvaluations)); ?>
    <table class="table table-striped">
        <tbody>
        <?php foreach($pls as $pl): ?>
            <tr>
                <td><?php echo $pl['pl']->description; ?></td>
                <td>%<?php echo $pl['percentage']; ?></td>
                <td><?php
                        $data = array('name'=>'pls','value'=>$pl['pl']->idPerformance_levels);
                        if($evaluation->Performance_levels_idPerformance_levels == $pl['pl']->idPerformance_levels) $data['checked'] = TRUE; 
                        echo form_radio($data); 
                     ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php 
        if(isset($e_id)) echo anchor('evaluations/edit_evaluation/'.$e_id,'Back','class = "btn"'); 
    ?>
    <input class="btn btn-primary" type="submit"  value="Next" />
    <?php echo form_close(); ?>
</div><!-- Content span -->