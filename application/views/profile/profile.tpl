{include file="common/header.tpl"}
    <body>
		<div class="pageWrapper">
			<!--Black "header" on the top of the page-->
           {include file="profile/profileHeader.tpl"}
          <!-- / -->
			<!--Main content of the page-->
			<section class="mainContent">
				<div class="container">
					<div class="content">
						<!-- "My places" block -->
                      {include file="profile/placesSmall.tpl"}
                     <!-- / -->
						<!-- "New photo" block -->
                      {include file="profile/newPhoto.tpl"}
                     <!-- / -->
						<!-- "My galleries" block -->
                      {include file="profile/newGaleries.tpl"}
                     <!-- / -->
                     <!-- "bottom" block -->
                      {include file="common/bottomMenu.tpl"}
                     <!-- / -->
					</div>
				</div>
				<!-- Holder of the left side of the page-->
              {include file="profile/leftContainer.tpl"}
              <!-- / -->
				<!-- Holder of the right side of the page-->
              {include file="profile/rightContainer.tpl"}  
              <!-- / -->
			</section>
		</div>
       {literal}
		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script> -->
		<script>window.jQuery || document.write('<script src="{/literal}{php} echo base_url(); {/php}{literal}js/jq.js"><\/script>')</script>
		<script>if(window.jQuery) document.write('<script src="{/literal}{php} echo base_url(); {/php}{literal}js/jqui.js"><\/script>')</script>
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/carousel.js"></script>
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/upgallery.js"></script>
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/timemachine.js"></script>
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/mousewheel.js"></script>
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/mCustomScrollbar.js"></script>
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/global.js"></script>
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/alert.js"></script>
       {/literal}
    </body>
</html>