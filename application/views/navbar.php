<div class="row">
    <!-- NavBar Row -->
    <div class="span11">
        <!-- NavBar span -->
        <div class="navbar">
            <div class="navbar-inner">
                <?php echo anchor('events/display_events','Home','class="brand"') ?>
                <ul class="nav">
                    <li class="active">
                        <?php echo anchor('rubrics/display_rubrics','Rubrics') ?>
                    </li>
                    <li>
                        <?php echo anchor('events/display_events','Events') ?>
                    </li>
                    <li>
                        <?php echo anchor('auth/display_users','Users') ?>
                    </li>
                </ul>
            </div>
        </div>
    </div><!-- NavBar Span -->
</div><!-- NavBar Row -->
