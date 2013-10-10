<form role="search" method="get" class="search-form" action="<?=home_url( '/' )?>">
	<div>
		<label for="s">Search:</label>
		<div class="input-append">
			<span class="add-on">
				<i class="icon-search"></i>
			</span>
		<input type="text" value="<?=htmlentities($_GET['s'])?>" name="s" class="search-field" id="s" placeholder="Search" />
		<button type="submit" class="search-submit">Search</button>
	</div>
</form>