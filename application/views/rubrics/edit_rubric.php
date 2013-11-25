<div class="span9 offset1">
    <?php echo $this->alert->display_alerts(); ?>
    <?php echo form_open('rubrics/update_rubric','',array('rubric_id' =>$rubric->idRubrics)); ?>
    <fieldset>
        <legend>New Rubric</legend>
        <label for="name">Name</label>
        <?php echo form_error('name', '<div class="alert alert-error">', '</div>'); ?>
        <input type="text" placeholder="Name" name="name" value="<?php echo set_value('name',$rubric->name);?>" />
        <label for="description">Description</label>
        <?php echo form_error('description', '<div class="alert alert-error">', '</div>'); ?>
        <textarea name="description"><?php echo set_value('description',$rubric->description);?></textarea>
        </fieldset>
        <input type="submit" class="btn btn-primary" />
    </form>
</div>