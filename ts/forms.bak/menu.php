<?php 
/********************************************************************/
/* DYNAMIC MAIN MENU												*/
/********************************************************************/

include_once '../startup.php'; ?>
<div class="navigation-bar dark">
    <div class="navigation-bar-content container">
        <!--<img src='http://c.hit.ua/hit?i=19154&amp;g=0&amp;x=2' border='0' style="width: 1px; height: 1px;"/>-->

        <a href="<?php echo HTTPS_SERVER; ?>" class="element"><span class="icon-grid-view"></span> TIME SHEET <sup>1.0</sup></a>

        <span class="element-divider"></span>
		
		<?php echo $_form->getHtmlMenu(); ?>

        <span class="element-divider place-right"></span>
        <a title="Log Out" href="<?php echo HTTPS_SERVER; ?>actions/login.php?act=logout" class="element place-right" data-type="post"><span class="icon-unlocked"></span></a>
        <span class="element-divider place-right"></span>
		<a title="Welcome" href="#" class="element place-right"><?php if ($_user->isLogged()) {  echo 'Welcome, '.$_user->getUserDisplayName(); } ?>
		
		</a>
        <span class="element-divider place-right"></span>
    </div>
</div>
</div>
<script type="text/javascript">
	$(function(){
		pfw_prep_submit_button();
		pfw_prep_menulink();
	});
</script>
