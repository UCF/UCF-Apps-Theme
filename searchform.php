<form role="search" method="get" class="search-form" action="<?=home_url( '/' )?>">
	<div>
		<label for="s">Search:</label>
		<div class="input-append">
			<input type="text" value="<?=htmlentities($_GET['s'])?>" name="s" class="search-field" id="appendInputButton" placeholder="Search" />
			<button type="submit" class="btn"><i class="icon-search"></i></button>
		</div>
	</div>
</form>