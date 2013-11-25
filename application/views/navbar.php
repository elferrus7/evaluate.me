<div class="row">
    <!-- NavBar Row -->
    <div class="span11">
        <!-- NavBar span -->
        <div class="navbar">
            <div class="navbar-inner">
                <?php //echo anchor('events/display_events','Home','class="brand"') ?>
                <ul class="nav">
                    <li>
                        <?php echo anchor('rubrics/display_rubrics','Rubrics') ?>
                    </li>
                    <li>
                        <?php echo anchor('events/display_events','Events') ?>
                    </li>
                    <li>
                        <?php echo anchor('users/display_users','Users') ?>
                    </li>
                    <li>
                        <?php echo anchor('roles/display_roles','Roles') ?>
                    </li>
                    <li>
                        <?php echo anchor('permissions/display_permissions','Permissions') ?>
                    </li>
                    <li>
                        <?php echo anchor('evaluations/events','Evaluations') ?>
                    </li>
                </ul>
            </div>
        </div>
    </div><!-- NavBar Span -->
</div><!-- NavBar Row -->
