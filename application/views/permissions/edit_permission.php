<script type="text/javascript">
    $(document).ready(function (){
        $('#alert').hide();
        $('#submit').click(function (){
            var operations = [];
            $('#selected .operation').each(function (){
                  operations.push($(this).data('id'));
            });
            //console.log(judges);
            var base_url = "<?php echo base_url(); ?>";
            var name = $("input[name='name']").val();
            var description = $("textarea[name='description']").val();
            var permission_id = <?php echo $permission->idPermissions; ?>;
            $.ajax({
                url: base_url + "index.php/permissions/update_permission",
                async: false,
                type: "POST",
                data: {'name':name,'description':description,'operations':operations,'permission_id':permission_id},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        window.location = base_url + "index.php/permissions/details_permission/" +jason.permission_id 
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
    <?php echo form_open('permissions/insert_permission'); ?>
    <fieldset>
        <legend>Edit permission</legend>
        <label for="name">permission</label>
        <input type="text" name="name" placeholder="permission Name" value="<?php echo set_value('name',$permission->name); ?>" />
        <label for="name">Description</label>
        <textarea name="description"><?php echo set_value('description',$permission->description); ?></textarea>
    </fieldset>
    <a class="btn" id= "submit">Submit</a>
</div><!-- Content span -->
<div class="span5 ">
    <section id="connected">
        <h5>Operations Available</h5>
        <ul class="connected list">
            <li class="disabled"></li>
            <?php echo $permission_operations; ?>
        </ul>
        <h5>Operations in this Permission</h5>
        <ul class="connected list no2" id = "selected">
            <li class="disabled">
                Select Operations
            </li>
            <?php echo $operations; ?>
        </ul>
    </section>
</div>