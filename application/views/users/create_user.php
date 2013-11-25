<script type="text/javascript">
    $(document).ready(function (){
        $('#alert').hide();
        $('#submit').click(function (){
            var roles = [];
            $('#selected .role').each(function (){
                  roles.push($(this).data('id'));
            });
            //console.log(judges);
            var base_url = "<?php echo base_url(); ?>";
            var email = $("input[name='email']").val();
            var password = $("input[name='password']").val();
            var first_name = $("input[name='first_name']").val();
            var last_name = $("input[name='last_name']").val();
            $.ajax({
                url: base_url + "index.php/users/insert_user",
                async: false,
                type: "POST",
                data: {'email': email, 'password':password, 'first_name':first_name, 'last_name':last_name,'roles':roles},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        window.location = base_url + "index.php/users/details_user/" +jason.user_id 
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
    <?php echo form_open('users/insert_user'); ?>
    <fieldset>
        <legend>New User</legend>
        <label for="email">Email</label>
        <input type="email" placeholder="email@email.com" name="email" value="<?php echo set_value('email');?>" />
        <label for="password">Password</label>
        <input type="password" name="password" />
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name'); ?>" />
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" placeholder="Last Name" value="<?php echo set_value('last_name'); ?>" />
    </fieldset>
    <a class="btn" id= "submit">Submit</a>
</div><!-- Content span -->
<div class="span5 ">
    <section id="connected">
        <!--<h5>Roles Available</h5>-->
        <ul class="connected list">
            <li class="disabled">Roles available</li>
            <?php echo $roles; ?>
        </ul>
        <!--<h5>Roles in this user</h5>-->
        <ul class="connected list no2" id = "selected">
            <li class="disabled">
                Roles selected
            </li>
        </ul>
    </section>
</div>