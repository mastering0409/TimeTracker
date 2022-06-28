<?php
/**
 * Sidebar Template for Time Tracker Pages
 *
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Templates;

 ?>
<div id="tt-favorite-functions">
<div class="tt-sidebar-header">Favorites</div>
<a href=<?php echo TT_HOME . "open-task-list" ?> class="tt-sidebar-button">Open Tasks</a>
<a href=<?php echo TT_HOME . "new-time-entry" ?> class="tt-sidebar-button">Log Time</a>
<a href=<?php echo TT_HOME . "new-task" ?> class="tt-sidebar-button">New Task</a>
<a href=<?php echo TT_HOME . "pending-time" ?> class="tt-sidebar-button">Pending Time</a>
</div>
<hr class="tt-sidebar-hr"/>
<div id="tt-time-functions">
<div class="tt-sidebar-header">Time</div>
<a href=<?php echo TT_HOME . "new-time-entry" ?> class="tt-sidebar-button">Log Time</a>
<a href=<?php echo TT_HOME . "time-log" ?> class="tt-sidebar-button">All Time Entries</a>
</div>
<hr class="tt-sidebar-hr"/>
<div id="tt-task-functions">
<div class="tt-sidebar-header">Tasks</div>
<a href=<?php echo TT_HOME . "new-task" ?> class="tt-sidebar-button">New Task</a>
<a href=<?php echo TT_HOME . "new-recurring-task" ?> class="tt-sidebar-button">New Recurring Task</a>
<a href=<?php echo TT_HOME . "task-list" ?> class="tt-sidebar-button">All Tasks</a>
<a href=<?php echo TT_HOME . "recurring-task-list" ?> class="tt-sidebar-button">All Recurring Tasks</a>
</div>
<hr class="tt-sidebar-hr"/>
<div id="tt-time-functions">
<div class="tt-sidebar-header">Projects</div>
<a href=<?php echo TT_HOME . "new-project" ?> class="tt-sidebar-button">New Project</a>
<a href=<?php echo TT_HOME . "projects" ?> class="tt-sidebar-button">All Projects</a>
</div>
<hr class="tt-sidebar-hr"/>
<div id="tt-time-functions">
<div class="tt-sidebar-header">Clients</div>
<a href=<?php echo TT_HOME . "new-client" ?> class="tt-sidebar-button">New Client</a>
<a href=<?php echo TT_HOME . "clients" ?> class="tt-sidebar-button">All Clients</a>
</div>