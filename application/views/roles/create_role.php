<script type="text/javascript">
    $(document).ready(function (){
        $('#alert').hide();
        $('#submit').click(function (){
            var permissions = [];
            $('#selected .permission').each(function (){
                  permissions.push($(this).data('id'));
            });
            //console.log(judges);
            var base_url = "<?php echo base_url(); ?>";
            var name = $("input[name='name']").val();
            var description = $("textarea[name='description']").val();
            $.ajax({
                url: base_url + "index.php/roles/insert_role",
                async: false,
                type: "POST",
                data: {'name':name,'description':description,'permissions':permissions},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        //$('#success').show('slow');
                        window.location = base_url + "index.php/roles/details_role/" +jason.role_id 
                    } else {
                        $('#alert').show('slow');
                    }
                }
            });
        });
    });
</script>
<div class="span4 offset1">
    <!-- Content span -->
    <div class="alert alert-danger" id="alert">Please fill all the fields</div>
    <?php echo form_open('roles/insert_role'); ?>
    <fieldset>
        <legend>New Role</legend>
        <label for="name">Role</label>
        <input type="text" name="name" placeholder="Role Name" value="<?php echo set_value('name'); ?>" />
        <label for="name">Description</label>
        <textarea name="description"><?php echo set_value('description'); ?></textarea>
    </fieldset>
    <a class="btn" id= "submit">Submit</a>
</div><!-- Content span -->
<div class="span5 ">
    <section id="connected">
        <h5>Permissions Available</h5>
        <ul class="connected list">
            <li class="disabled"></li>
            <?php echo $permissions; ?>
        </ul>
        <h5>Permissions in this Role</h5>
        <ul class="connected list no2" id = "selected">
            <li class="disabled">
                Select Permissions
            </li>
        </ul>
    </section>
</div>