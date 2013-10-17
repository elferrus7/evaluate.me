<script type="text/javascript">
    $(document).ready(function (){
        $('#submit').click(function (){
            var judges = [];
            $('#selected > li').each(function (){
                  judges.push($(this).data('id'));
            });
            //console.log(judges);
            var base_url = "<?php echo base_url(); ?>"
            $.ajax({
                url: base_url + "index.php/events/test_ajax",
                async: false,
                type: "POST",
                data: {'name': 'Fernando', 'judges': judges},
                success: function(resp){
                    console.log('exito');
                    console.log(resp);
                }
            });
        });
    });
</script>

<div class="span4 offset1">
    <!-- Content span -->
    <?php echo form_open('events/create_event'); ?>
    <fieldset>
        <legend>New Event</legend>
        <label for="name">Name</label>
        <input type="text" placeholder="Name" name="name" />
        <label for="password">Date</label>
        <input type="date" name="date" />
        <label for="description">Description</label>
        <textarea name="description"></textarea>
    </fieldset>
    <input type="submit" value="Submit" class="btn" id="submit"/>
    </form>
</div><!-- Content span -->
<div class="span5 ">
    <section id="connected">
        <h5>Judges Available</h5>
        <ul class="connected list">
            <li data-id="2">
                Ricardo Cortez
            </li>
            <li data-id="3">
                Jorge Torres
            </li>
            <li data-id="4">
                Gabriel Hermosillo
            </li>
        </ul>
        <h5>Judges in the event</h5>
        <ul class="connected list no2" id = "selected">
            <li class="highlight" data-id="1">
                Pedro Perez
            </li>
        </ul>
    </section>
</div>
<script>
            $(function() {
                $('.connected').sortable({
                    connectWith: '.connected'
                });
            });
        </script>