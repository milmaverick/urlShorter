<?php

require_once 'App/models/IndexModel.php';

class IndexController extends Controller {

	private $pageTpl = 'App/views/main.tpl.php';

	public function __construct() {
		$this->model = new IndexModel();
		$this->view = new View();
	}

	public function index() {
		$this->view->render($this->pageTpl );
	}

	//Поиск полного URL и переход на исходную страницу----------------------------------------

	public function redirect()
	{
		if ($_GET['c']) {
			// code...
			$longUrl=$this->model->shortCodeToUrl($_GET['c']);
			header("Location: ".$longUrl);
		}
	}

	// Создание короткого URL----------------------------------------------------------

	public function getShort()
	{
		if($_GET['fulURl'])
		{
			echo $shortCode= $this->model->urlToShortCode($_GET['fulURl']);
		}
		else{
			echo "NOO URL!!";
		}
	}
}
?>
