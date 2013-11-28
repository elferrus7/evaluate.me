<div class="span10 offset1">
    <!-- Content span -->
    <p><?php echo anchor('evaluations/projects/'.$project->Events_idEvents,'<i class="icon-arrow-left"></i>','class="btn"'); ?></p>
    <p><b>Project name</b>: <?php echo $project->project_name; ?></p>
    <h3>Total: <?php echo $grade; ?></h3>
    <?php echo $table; ?>
    
</div><!-- Content span -->