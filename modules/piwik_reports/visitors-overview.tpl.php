<?php
// $Id$
?>
<br />
<h2><?php print t('Overview of the last @period', array('@period' => $period)) ?></h2>
<div>
	<object codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="150" id="VisitsSummarygetLastVisitsGraphChart">
	<param name="movie" value="<?php print $url?>" />
	<param name="allowScriptAccess" value="sameDomain"/>
	<embed src="<?php print $url?>" allowacriptaccess="sameDomain" quality="high" bgcolor="#FFFFFF" width="100%" height="150" name="open-flash-chart" type="application/x-shockwave-flash" id="VisitsSummarygetLastVisitsGraphChart" />
	</object>
</div>
