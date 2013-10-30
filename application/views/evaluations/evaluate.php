<div class="span10 offset1">
    <!-- Content span -->
    <p><b>Project name</b>: <?php echo $project->project_name; ?></p>
    <p><?php echo $ec->description; ?></p>
    <p><b>20 Points</b></p>
    <?php echo form_open('evaluation/create_evaluation'); ?>
    <table class="table table-striped">
        <tbody>
        <?php foreach($pls as $pl): ?>
            <tr>
                <td><?php echo $pl->description; ?></td>
                <td>%<?php echo $pl->percentage; ?></td>
                <td><?php echo form_radio(array('name'=>'pls[]','value'=>$pl->idPerformance_levels)); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input class="btn btn-primary" type="submit"  value="Next" />
    <?php echo form_close(); ?>
</div><!-- Content span -->