<div class="span10 offset1">
    <!-- Content span -->
    <p><b>Project name</b>: <?php echo $project->project_name; ?></p>
    <p><?php echo $ec->description; ?></p>
    <p><b><?php echo $ec->percentage; ?> Points</b></p>
    <?php echo form_open('evaluations/insert_evaluation','',array('rubric_id'=>$rubric->idRubrics,'user_id'=>$user,'project_id'=>$project->idProjects,'evaluation_criteria_id'=>$ec->idEvaluation_criteria)); ?>
    <table class="table table-striped">
        <tbody>
        <?php foreach($pls as $pl): ?>
            <tr>
                <td><?php echo $pl->description; ?></td>
                <td>%<?php echo $pl->percentage; ?></td>
                <td><?php echo form_radio(array('name'=>'pls','value'=>$pl->idPerformance_levels)); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input class="btn btn-primary" type="submit"  value="Next" />
    <?php echo form_close(); ?>
</div><!-- Content span -->