<script type="text/javascript">
    $(document).ready(function (){
        $('#alert').hide();
        $('#time').hide();
        $('#submit').click(function (){
            var judges = [];
            $('#selected .judge').each(function (){
                  judges.push($(this).data('id'));
            });
            //console.log(judges);
            var base_url = "<?php echo base_url(); ?>";
            var name = $("input[name='name']").val();
            var date = $("input[name='date']").val();
            var close_date = $("input[name='close_date']").val();
            var event_id = <?php echo $event->idEvents; ?>;
            var description = $("textarea[name='description']").val();
            var sd = new Date(date);
            var cd = new Date(close_date);
            if(sd.getTime() > cd.getTime()){
                $('#time').show('slow');
                return;
            }
            console.log('name '+ name);
            console.log('date '+ date);
            console.log('close_date ' +close_date);
            console.log('event_id '+ event_id);
            console.log('description ' + description);
            console.log(judges);
            $.ajax({
                url: base_url + "index.php/events/update_event",
                async: false,
                type: "POST",
                data: {'name':name,'date':date,'close_date':close_date,'description':description,'judges': judges,'event_id':event_id},
                success: function(resp){
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        window.location = base_url + 'index.php/events/details_event/' + jason.event_id
                    } else {
                        $('#alert').show('slow');
                    }
                }
            });
        });
    });
</script>
<div class="span4 offset1">
    <div class="alert alert-danger" id="alert">Please fill all the fields</div>
    <div class="alert alert-danger" id="time">The start date should be berfore the close date</div>
    <!-- Content span -->
    <?php echo form_open('events/create_event'); ?>
    <fieldset>
        <legend>Edit Event</legend>
        <label for="name">Name</label>
        <?php echo form_error('name', '<div class="alert alert-error">', '</div>'); ?>
        <input type="text" placeholder="Name" name="name" value="<?php echo set_value('name',$event->name);?>" />
        
        <label for="date">Start Date</label>
        <?php echo form_error('date', '<div class="alert alert-error">', '</div>'); ?>
        <input type="date" name="date" value="<?php echo set_value('date', $event->date);?>" />
        <label for="close_date">Close Date</label>
        <input type="date" name="close_date" value="<?php echo set_value('date', $event->close_date);?>" />
        <label for="description">Description</label>
        <?php echo form_error('description', '<div class="alert alert-error">', '</div>'); ?>
        <textarea name="description"><?php echo set_value('description',$event->description);?></textarea>
    </fieldset>
    <!--<input type="submit" value="Submit" class="btn" id="submit"/>-->
    <a class="btn" id= "submit">Submit</a>
    </form>
</div><!-- Content span -->
<div class="span5 ">
    <section id="connected">
        <ul class="connected list">
            <li class="disabled">Judges available</li>
            <?php echo $judges; ?>
        </ul>
        <ul class="connected list no2" id = "selected">
            <li class="disabled">
                Judges selected
            </li>
            <?php echo $event_judges; ?>
        </ul>
    </section>
</div>