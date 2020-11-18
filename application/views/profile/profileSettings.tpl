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
						<nav class="formNavigation">
							<ul>
								<li class="selected"><a href="#common">Общее</a></li>
								<li><a href="#privacy">Приватность</a></li>
								<li><a href="#notifications">Оповещения</a></li>
								<li><a href="#blacklist">Черный список</a></li>
								<li><a href="#balance">Баланс</a></li>
							</ul>
						</nav>
						<div class="clearfix greendevider"></div>
						<div class="stepsWrapper tmar15">
							<form class="settingsForm" action="" method="post">
								<div class="settingsSteps">
									<fieldset class="step">
										Шаг первый
									</fieldset>
									<fieldset class="step">
										Шаг второй
									</fieldset>
									<fieldset class="step">
										Шаг третий
									</fieldset>
									<fieldset class="step">
										Шаг четвертый
									</fieldset>
									<fieldset class="step">
										Шаг пятый
									</fieldset>	
								</div>
								<div class="clearfix greendevider tpad15"></div>
								<div class="tcenter tmar10">
									<input type="submit" value="Сохранить" class="bigButton green clearfix" />
								</div>
							</form>
						</div>
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
		<script src="{/literal}{php} echo base_url(); {/php}{literal}js/sliding.form.js"></script>
       {/literal}
    </body>
</html>