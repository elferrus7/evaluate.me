<script type="text/javascript">
    $(document).ready(function (){
        $('#success').hide();
        $('#submit').click(function (){
            var judges = [];
            $('#selected .judge').each(function (){
                  judges.push($(this).data('id'));
            });
            //console.log(judges);
            var base_url = "<?php echo base_url(); ?>";
            var name = $("input[name='name']").val();
            var date = $("input[name='date']").val();
            var event_id = <?php echo $event->idEvents; ?>;
            var description = $("textarea[name='description']").val();
            console.log(judges);
            $.ajax({
                url: base_url + "index.php/events/update_event",
                async: false,
                type: "POST",
                data: {'name':name,'date':date,'description':description,'judges': judges,'event_id':event_id},
                success: function(resp){
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        window.location = base_url + 'index.php/events/details_event/' + jason.event_id
                        //$('#success').show('slow');
                    }
                }
            });
        });
    });
</script>
<div class="alert alert-success" id="success">Event Created</div>
<div class="span4 offset1">
    <!-- Content span -->
    <?php echo form_open('events/create_event'); ?>
    <fieldset>
        <legend>Edit Event</legend>
        <label for="name">Name</label>
        <?php echo form_error('name', '<div class="alert alert-error">', '</div>'); ?>
        <input type="text" placeholder="Name" name="name" value="<?php echo set_value('name',$event->name);?>" />
        
        <label for="date">Date</label>
        <?php echo form_error('date', '<div class="alert alert-error">', '</div>'); ?>
        <input type="date" name="date" value="<?php echo set_value('date', $event->date);?>" />
        
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
        <h5>Judges Available</h5>
        <ul class="connected list">
            <?php echo $judges; ?>
        </ul>
        <h5>Judges in the event</h5>
        <ul class="connected list no2" id = "selected">
            <li class="highlight">
                Select Judges
            </li>
            <?php echo $event_judges; ?>
        </ul>
    </section>
</div>