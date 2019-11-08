<?php

class View {

	public function render( $tpl)
	{
		require $tpl;
	}

	public function renderPartial($cnt, $pageData){
		require $cnt;
	}

}
?>
