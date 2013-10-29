<script type="text/javascript">
    $(document).ready(function (){
        $('#success').hide();
        $('#submit').click(function (){
            var permissions = [];
            $('#selected .permission').each(function (){
                  permissions.push($(this).data('id'));
            });
            //console.log(judges);
            var base_url = "<?php echo base_url(); ?>";
            var name = $("input[name='name']").val();
            var description = $("textarea[name='description']").val();
            var role_id = <?php echo $role->idRoles; ?>;
            $.ajax({
                url: base_url + "index.php/roles/update_role",
                async: false,
                type: "POST",
                data: {'name':name,'description':description,'permissions':permissions,'role_id':role_id},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        $('#success').show('slow');
                        window.location = base_url + "index.php/roles/details_role/" +jason.role_id 
                    }
                }
            });
        });
    });
</script>
<div class="span4 offset1">
    <!-- Content span -->
    <?php echo form_open('roles/insert_role'); ?>
    <fieldset>
        <legend>Edit Role</legend>
        <label for="name">Role</label>
        <input type="text" name="name" placeholder="Role Name" value="<?php echo set_value('name',$role->name); ?>" />
        <label for="name">Description</label>
        <textarea name="description"><?php echo set_value('description',$role->description); ?></textarea>
    </fieldset>
    <a class="btn" id= "submit">Submit</a>
</div><!-- Content span -->
<div class="span5 ">
    <section id="connected">
        <h5>Permissions Available</h5>
        <ul class="connected list">
            <li class="disabled"></li>
            <?php echo $role_permissions; ?>
        </ul>
        <h5>Permissions in this Role</h5>
        <ul class="connected list no2" id = "selected">
            <li class="disabled">
                Select Permissions
            </li>
            <?php echo $permissions; ?>
        </ul>
    </section>
</div>