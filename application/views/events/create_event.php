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
            var description = $("textarea[name='description']").val();
            var sd = new Date(date);
            var cd = new Date(close_date);
            if(sd.getTime() > cd.getTime()){
                $('#time').show('slow');
                return;
            }
            $.ajax({
                url: base_url + "index.php/events/insert_event",
                async: false,
                type: "POST",
                data: {'name':name,'date':date,'description':description,'judges': judges,'close_date':close_date},
                success: function(resp){
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        window.location = base_url + 'index.php/events/details_event/' + jason.event_id
                    }else {
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
        <legend>New Event</legend>
        <label for="name">Name</label>
        <?php echo form_error('name', '<div class="alert alert-error">', '</div>'); ?>
        <input type="text" placeholder="Name" name="name" value="<?php echo set_value('name');?>" />
        
        <label for="date">Start Date</label>
        <?php echo form_error('date', '<div class="alert alert-error">', '</div>'); ?>
        <input type="date" name="date" value="<?php echo set_value('date');?>" />
        <label for="close_date">Close Date</label>
        <input type="date" name="close_date" value="<?php echo set_value('close_date');?>" />
        <label for="description">Description</label>
        <?php echo form_error('description', '<div class="alert alert-error">', '</div>'); ?>
        <textarea name="description"><?php echo set_value('description');?></textarea>
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
        </ul>
    </section>
</div>