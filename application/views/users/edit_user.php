<script type="text/javascript">
    $(document).ready(function (){
        $('#success').hide();
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
            var user_id = <?php echo $user->idUsers; ?>;
            $.ajax({
                url: base_url + "index.php/users/update_user",
                async: false,
                type: "POST",
                data: {'email': email, 'password':password, 'first_name':first_name, 'last_name':last_name,'roles':roles,'user_id':user_id},
                success: function(resp){
                    console.log(resp);
                    var jason = jQuery.parseJSON(resp);
                    if(jason.stat){
                        $('#success').show('slow');
                        window.location = base_url + "index.php/users/details_user/" +jason.user_id 
                    }
                }
            });
        });
    });
</script>
<div class="span4 offset1">
    <!-- Content span -->
    <?php echo form_open('rubrics/update_rubric'); ?>
    <fieldset>
        <legend>New User</legend>
        <label for="email">Email</label>
        <input type="email" placeholder="email@email.com" name="email" value="<?php echo set_value('email',$user->email);?>" />
        <label for="password">Password</label>
        <input type="password" name="password" />
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name',$user->first_name); ?>" />
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" placeholder="Last Name" value="<?php echo set_value('last_name',$user->last_name); ?>" />
    </fieldset>
    <a class="btn" id= "submit">Submit</a>
</div><!-- Content span -->
<div class="span5 ">
    <section id="connected">
        <h5>Roles Available</h5>
        <ul class="connected list">
            <li class="disabled"></li>
            <?php echo $user_roles; ?>
        </ul>
        <h5>Roles for this user</h5>
        <ul class="connected list no2" id = "selected">
            <li class="disabled">
            </li>
            <?php echo $roles; ?>
        </ul>
    </section>
</div>