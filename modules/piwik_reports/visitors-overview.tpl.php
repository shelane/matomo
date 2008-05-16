<?php
// $Id$
?>
<h2><?php print t('Overview of the last @period', array('@period' => $period)) ?></h2>
<div class="content">
  <object type="application/x-shockwave-flash" width="100%" height="150" data="<?php print $url?>" id="VisitsSummarygetLastVisitsGraphChart">
    <param name="movie" value="<?php print $url?>" />
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="quality" value="high" />
    <param name="wmode" value="#ffffff" />
  </object>
</div>
