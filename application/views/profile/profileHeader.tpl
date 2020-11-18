			<header>
				<!-- User photo -->
				<div class="userAvatar blockPadd">
					<a href="{php} echo base_url(); {/php}myprofile"><img src="{$logged_user_avatar}" alt="{$logged_user_fn} {$logged_user_ln}" title="{$logged_user_fn} {$logged_user_ln}" /></a>
				</div>
				<!-- User short info -->
				<div class="userInfo blockPadd">
					<h1 class="uName">{$logged_user_fn} {$logged_user_ln}</h1>
					<span class="uAddr"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon point green" /> Донецк, Украина</span><span class="uStatus">Онлайн</span>
				</div>
				<!-- MicroIconsMenu -->
				<div class="iconMenu">
					<a href="{php} echo base_url(); {/php}upload"><img src="{php} echo base_url(); {/php}images/spacer.gif" class="icon upload" alt="" title="Загрузить фотографии"/></a>
				</div>
				<!-- Site Logo -->
				<div class="logo"><a href="{php} echo base_url(); {/php}home"></a></div>
				<!-- Search form -->
				<div class="searchForm blockPadd">
					<form method="post" action="">
						<input type="text" value="" placeholder="Поиск мест, людей, фото" /><input type="image" src="{php} echo base_url(); {/php}images/spacer.gif" />
					</form>
				</div>
			</header>
