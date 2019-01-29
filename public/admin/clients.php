<?php
	include "engine/core.php";
	if(!in_array($_SESSION['user']['role'], array (
  0 => 'super_admin',
  1 => 'admin',
  2 => 'manager',
))){
			include "menu.php";
			foreach($menu as $m)
			{
				$rls = [];
				foreach(explode(",", $m["roles"]) as $r)
				{
					$rls[] = trim($r);
				}
				if(in_array($_SESSION["user"]["role"], $rls))
				{
					header("Location: {$m['link']}");
					die("");
				}
			}

			die("У вас нет доступа");
		}

	class GLOBAL_STORAGE
	{
	   static $parent_object;
	}
	

	$action = $_REQUEST['action'];
	$actions = [];

	define("RPP", 50); //кол-во строк на странице

	function array2csv($array)
	{
	   if (count($array) == 0)
	   {
	     return null;
	   }
	   ob_start();
	   $df = fopen("php://output", 'w');
	   fputcsv($df, array_keys($array[0]));
	   foreach ($array as $row)
	   {
	      fputcsv($df, array_values($row));
	   }
	   fclose($df);
	   return ob_get_clean();
	}

	function download_send_headers($filename)
	{
	    // disable caching
	    $now = gmdate("D, d M Y H:i:s");
	    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	    header("Last-Modified: {$now} GMT");

	    // force download
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");

	    // disposition / encoding on response body
	    header("Content-Disposition: attachment;filename={$filename}");
	    header("Content-Transfer-Encoding: binary");
	}

	$actions['csv'] = function()
	{
		if(function_exists("allowCSV"))
		{
			if(!allowCSV())
			{
				die("У вас нет прав на экспорт CSV");
			}
		}
		download_send_headers("data_export_" . date("Y-m-d") . ".csv");
		$data = get_data(true)[0];

		if(function_exists("processCSV"))
		{
			$data = processCSV($data);
		}

		echo array2csv($data);
		die();
	};

	$actions[''] = function()
	{
			
   		$is_address_matched_values = '[
	{
		"text": "Совпадают",
		"value": "1"
	},
	{
		"text": "Не совпадают",
		"value": "0"
	}
]';
			$is_address_matched_values_text = "";
			foreach(json_decode($is_address_matched_values, true) as $opt)
			{
			  $is_address_matched_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
			}
				  

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['last_name']='asc';
$next_order['first_name']='asc';
$next_order['middle_name']='asc';
$next_order['birth_date']='asc';
$next_order['passport_number']='asc';
$next_order['passport_issued_by']='asc';
$next_order['passport_issued_date']='asc';
$next_order['reg_zip_code']='asc';
$next_order['reg_city']='asc';
$next_order['reg_street']='asc';
$next_order['reg_building']='asc';
$next_order['reg_apartment']='asc';
$next_order['is_address_matched']='asc';
$next_order['fact_zip_code']='asc';
$next_order['fact_city']='asc';
$next_order['fact_street']='asc';
$next_order['fact_building']='asc';
$next_order['fact_apartment']='asc';
$next_order['phone']='asc';
$next_order['email']='asc';

		if($_REQUEST['sort_order']=='asc')
		{
			$sort_icon[$_REQUEST['sort_by']] = '<span class="fa fa-long-arrow-up" style="margin-left:5px;"></span>';
			$next_order[$_REQUEST['sort_by']] = 'desc';
		}
		else if($_REQUEST['sort_order']=='desc')
		{
			$sort_icon[$_REQUEST['sort_by']] = '<span class="fa fa-long-arrow-down" style="margin-left:5px;"></span>';
			$next_order[$_REQUEST['sort_by']] = '';
		}
		else if($_REQUEST['sort_order']=='')
		{
			$next_order[$_REQUEST['sort_by']] = 'asc';
		}
		$filter_caption = "";
		$show = '
		<script>
				window.onload = function ()
				{
					$(\'.big-icon\').html(\'<i class="fas fa-star"></i>\');
				};


		</script>
		
		<style>
			html body.concept, html body.concept header, body.concept .table
			{
				background-color:;
				color:;
			}

			#tableMain tr:nth-child(even)
			{
  				background-color: ;
			}
		</style>
		<div class="content-header">
			<div class="btn-wrap">
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Клиенты".' </h2>
				<button class="btn blue-inline add_button" data-toggle="modal" data-target="#modal-main">ДОБАВИТЬ</button>
				<p class="small res-cnt">Кол-во результатов: <span class="cnt-number-span">'.$cnt.'</span></p>
			</div>
			
			<form class="navbar-form search-form" role="search">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Поиск" name="srch-term" id="srch-term" value="'.$_REQUEST['srch-term'].'">
					<button class="input-group-addon"><i class="fa fa-search"></i></button>
				</div>
			</form>
		</div>
		<div>'.
		""
		.'</div>';

		$show .= filter_divs();

		$show.='

		<div class="table-wrap" data-fl-scrolls>';
		$table='
			<table class="table table-bordered table-clickable-page" id="tableMain">
			<thead>
				<tr>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=id&sort_order='. ($next_order['id']) .'\' class=\'sort\' column=\'id\' sort_order=\''.$sort_order['id'].'\'>id'. $sort_icon['id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="id_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="id_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=last_name&sort_order='. ($next_order['last_name']) .'\' class=\'sort\' column=\'last_name\' sort_order=\''.$sort_order['last_name'].'\'>Фамилия'. $sort_icon['last_name'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=first_name&sort_order='. ($next_order['first_name']) .'\' class=\'sort\' column=\'first_name\' sort_order=\''.$sort_order['first_name'].'\'>Имя'. $sort_icon['first_name'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=middle_name&sort_order='. ($next_order['middle_name']) .'\' class=\'sort\' column=\'middle_name\' sort_order=\''.$sort_order['middle_name'].'\'>Отчество'. $sort_icon['middle_name'].'</a>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=birth_date&sort_order='. ($next_order['birth_date']) .'\' class=\'sort\' column=\'birth_date\' sort_order=\''.$sort_order['birth_date'].'\'>Дата рождения'. $sort_icon['birth_date'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input autocomplete="off" type="text" class="form-control daterange filter-date-range" name="birth_date_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=passport_number&sort_order='. ($next_order['passport_number']) .'\' class=\'sort\' column=\'passport_number\' sort_order=\''.$sort_order['passport_number'].'\'>Серия и номер паспорта'. $sort_icon['passport_number'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=passport_issued_by&sort_order='. ($next_order['passport_issued_by']) .'\' class=\'sort\' column=\'passport_issued_by\' sort_order=\''.$sort_order['passport_issued_by'].'\'>Кем выдан паспорт'. $sort_icon['passport_issued_by'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=passport_issued_date&sort_order='. ($next_order['passport_issued_date']) .'\' class=\'sort\' column=\'passport_issued_date\' sort_order=\''.$sort_order['passport_issued_date'].'\'>Дата выдачи паспорта'. $sort_icon['passport_issued_date'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=reg_zip_code&sort_order='. ($next_order['reg_zip_code']) .'\' class=\'sort\' column=\'reg_zip_code\' sort_order=\''.$sort_order['reg_zip_code'].'\'>Индекс по прописке'. $sort_icon['reg_zip_code'].'</a>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=reg_city&sort_order='. ($next_order['reg_city']) .'\' class=\'sort\' column=\'reg_city\' sort_order=\''.$sort_order['reg_city'].'\'>Город по прописке'. $sort_icon['reg_city'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="reg_city_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=reg_street&sort_order='. ($next_order['reg_street']) .'\' class=\'sort\' column=\'reg_street\' sort_order=\''.$sort_order['reg_street'].'\'>Улица по прописке'. $sort_icon['reg_street'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=reg_building&sort_order='. ($next_order['reg_building']) .'\' class=\'sort\' column=\'reg_building\' sort_order=\''.$sort_order['reg_building'].'\'>Дом по прописке'. $sort_icon['reg_building'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=reg_apartment&sort_order='. ($next_order['reg_apartment']) .'\' class=\'sort\' column=\'reg_apartment\' sort_order=\''.$sort_order['reg_apartment'].'\'>Квартира по прописке'. $sort_icon['reg_apartment'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_address_matched&sort_order='. ($next_order['is_address_matched']) .'\' class=\'sort\' column=\'is_address_matched\' sort_order=\''.$sort_order['is_address_matched'].'\'>Совпадают ли фактический и адрес прописки?'. $sort_icon['is_address_matched'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=fact_zip_code&sort_order='. ($next_order['fact_zip_code']) .'\' class=\'sort\' column=\'fact_zip_code\' sort_order=\''.$sort_order['fact_zip_code'].'\'>Индекс по факту'. $sort_icon['fact_zip_code'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=fact_city&sort_order='. ($next_order['fact_city']) .'\' class=\'sort\' column=\'fact_city\' sort_order=\''.$sort_order['fact_city'].'\'>Город по факту'. $sort_icon['fact_city'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=fact_street&sort_order='. ($next_order['fact_street']) .'\' class=\'sort\' column=\'fact_street\' sort_order=\''.$sort_order['fact_street'].'\'>Улица по факту'. $sort_icon['fact_street'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=fact_building&sort_order='. ($next_order['fact_building']) .'\' class=\'sort\' column=\'fact_building\' sort_order=\''.$sort_order['fact_building'].'\'>Дом по факту'. $sort_icon['fact_building'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=fact_apartment&sort_order='. ($next_order['fact_apartment']) .'\' class=\'sort\' column=\'fact_apartment\' sort_order=\''.$sort_order['fact_apartment'].'\'>Квартира по факту'. $sort_icon['fact_apartment'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=phone&sort_order='. ($next_order['phone']) .'\' class=\'sort\' column=\'phone\' sort_order=\''.$sort_order['phone'].'\'>Телефон'. $sort_icon['phone'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=email&sort_order='. ($next_order['email']) .'\' class=\'sort\' column=\'email\' sort_order=\''.$sort_order['email'].'\'>Email'. $sort_icon['email'].'</a>
			</th>
					<th></th>
				</tr>
		</thead><tbody>';


		if(count($items) > 0)
		{
			foreach($items as $item)
			{
				$master = ($item['master'] == 1) ? 'Да' : 'Нет';

				$tr = "

				<tr pk='{$item['id']}'>
					
					".(function_exists("processTD")?processTD("<td>".htmlspecialchars($item['id'])."</td>", $item, "id"):"<td>".htmlspecialchars($item['id'])."</td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='last_name'>".htmlspecialchars($item['last_name'])."</span></td>", $item, "Фамилия"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='last_name'>".htmlspecialchars($item['last_name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='first_name'>".htmlspecialchars($item['first_name'])."</span></td>", $item, "Имя"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='first_name'>".htmlspecialchars($item['first_name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='middle_name'>".htmlspecialchars($item['middle_name'])."</span></td>", $item, "Отчество"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='middle_name'>".htmlspecialchars($item['middle_name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='birth_date'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['birth_date']?$item['birth_date']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>", $item, "Дата рождения"):"<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='birth_date'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['birth_date']?$item['birth_date']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_number'>".htmlspecialchars($item['passport_number'])."</span></td>", $item, "Серия и номер паспорта"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_number'>".htmlspecialchars($item['passport_number'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_issued_by'>".htmlspecialchars($item['passport_issued_by'])."</span></td>", $item, "Кем выдан паспорт"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_issued_by'>".htmlspecialchars($item['passport_issued_by'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_issued_date'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['passport_issued_date']?$item['passport_issued_date']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>", $item, "Дата выдачи паспорта"):"<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='passport_issued_date'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['passport_issued_date']?$item['passport_issued_date']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_zip_code'>".htmlspecialchars($item['reg_zip_code'])."</span></td>", $item, "Индекс по прописке"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_zip_code'>".htmlspecialchars($item['reg_zip_code'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_city'>".htmlspecialchars($item['reg_city'])."</span></td>", $item, "Город по прописке"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_city'>".htmlspecialchars($item['reg_city'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_street'>".htmlspecialchars($item['reg_street'])."</span></td>", $item, "Улица по прописке"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_street'>".htmlspecialchars($item['reg_street'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_building'>".htmlspecialchars($item['reg_building'])."</span></td>", $item, "Дом по прописке"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_building'>".htmlspecialchars($item['reg_building'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_apartment'>".htmlspecialchars($item['reg_apartment'])."</span></td>", $item, "Квартира по прописке"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='reg_apartment'>".htmlspecialchars($item['reg_apartment'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class=' '>".renderRadioGroup("is_address_matched", $is_address_matched_values, "clients", $item['id'], $item['is_address_matched'])."</td>", $item, "Совпадают ли фактический и адрес прописки?"):"<td><span class=' '>".renderRadioGroup("is_address_matched", $is_address_matched_values, "clients", $item['id'], $item['is_address_matched'])."</td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_zip_code'>".htmlspecialchars($item['fact_zip_code'])."</span></td>", $item, "Индекс по факту"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_zip_code'>".htmlspecialchars($item['fact_zip_code'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_city'>".htmlspecialchars($item['fact_city'])."</span></td>", $item, "Город по факту"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_city'>".htmlspecialchars($item['fact_city'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_street'>".htmlspecialchars($item['fact_street'])."</span></td>", $item, "Улица по факту"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_street'>".htmlspecialchars($item['fact_street'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_building'>".htmlspecialchars($item['fact_building'])."</span></td>", $item, "Дом по факту"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_building'>".htmlspecialchars($item['fact_building'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_apartment'>".htmlspecialchars($item['fact_apartment'])."</span></td>", $item, "Квартира по факту"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='fact_apartment'>".htmlspecialchars($item['fact_apartment'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='phone' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='phone'>".htmlspecialchars($item['phone'])."</span></td>", $item, "Телефон"):"<td><span class='editable ' data-placeholder='' data-inp='phone' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='phone'>".htmlspecialchars($item['phone'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span></td>", $item, "Email"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=clients' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span></td>")."
					<td><a href='#' class='edit_btn'><i class='fa fa-edit' style='color:grey;'></i></a> <a href='#' class='delete_btn'><i class='fa fa-trash' style='color:red;'></i></a></td>
				</tr>";

				if(function_exists("processTR"))
				{
					$tr = processTR($tr, $item);
				}

				$table.=$tr;
			}

			$table .= '</tbody></table></div>'.$pagination;

		}
		else
		{
			$table.=' </tbody></table><div class="empty_table">Нет информации</div>';
		}

		if(function_exists("processTable"))
		{
			$table = processTable($table);
		}

		$show.=$table."<div></div>".'<button class="btn blue-inline csv-button float-right">СКАЧАТЬ CSV</button>';

		if(function_exists("processPage"))
		{
			$show = processPage($show);
		}

		$show.="
		<style></style>
		<script></script>";


		return $show;

	};

	$actions['edit'] = function()
	{
		$id = $_REQUEST['genesis_edit_id'];
		if(isset($id))
		{
			$item = q("SELECT * FROM clients WHERE id=?",[$id]);
			$item = $item[0];
		}

		$is_address_matched_values = '[
	{
		"text": "Совпадают",
		"value": "1"
	},
	{
		"text": "Не совпадают",
		"value": "0"
	}
]';

		$html = '
			<form class="form" enctype="multipart/form-data" method="POST">
				<fieldset>'.
					(isset($id)?
					'<input type="hidden" name="id" value="'.$id.'">
					<input type="hidden" name="action" value="edit_execute">'
					:
					'<input type="hidden" name="action" value="create_execute">'
					)
					.'

					

								<div class="form-group">
									<label class="control-label" for="textinput">Фамилия</label>
									<div>
										<input id="last_name" name="last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="first_name" name="first_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["first_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Отчество</label>
									<div>
										<input id="middle_name" name="middle_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["middle_name"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения</label>
						<div>
							<input autocomplete="off" id="birth_date" placeholder="" name="birth_date" type="text" class="form-control datepicker "  value="'.(isset($item["birth_date"])?$item["birth_date"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Серия и номер паспорта</label>
									<div>
										<input id="passport_number" name="passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_number"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт</label>
									<div>
										<input id="passport_issued_by" name="passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта</label>
						<div>
							<input autocomplete="off" id="passport_issued_date" placeholder="" name="passport_issued_date" type="text" class="form-control datepicker "  value="'.(isset($item["passport_issued_date"])?$item["passport_issued_date"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по прописке</label>
									<div>
										<input id="reg_zip_code" name="reg_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по прописке</label>
									<div>
										<input id="reg_city" name="reg_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по прописке</label>
									<div>
										<input id="reg_street" name="reg_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по прописке</label>
									<div>
										<input id="reg_building" name="reg_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по прописке</label>
									<div>
										<input id="reg_apartment" name="reg_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_apartment"]).'">
									</div>
								</div>

							



            <div class="form-group">
              <label class="control-label" for="textinput">Совпадают ли фактический и адрес прописки?</label>
              <div class="" >'.renderEditRadioGroup("is_address_matched", $is_address_matched_values, $item["is_address_matched"]).'
              </div>
            </div>

          


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по факту</label>
									<div>
										<input id="fact_zip_code" name="fact_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по факту</label>
									<div>
										<input id="fact_city" name="fact_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по факту</label>
									<div>
										<input id="fact_street" name="fact_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по факту</label>
									<div>
										<input id="fact_building" name="fact_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по факту</label>
									<div>
										<input id="fact_apartment" name="fact_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							
					<div class="text-center not-editable">
						
					</div>

				</fieldset>
			</form>

		';

		if(function_exists("processEditModalHTML"))
		{
			$html = processEditModalHTML($html);
		}
		die($html);
	};

	$actions['create'] = function()
	{

		$is_address_matched_values = '[
	{
		"text": "Совпадают",
		"value": "1"
	},
	{
		"text": "Не совпадают",
		"value": "0"
	}
]';

		$html = '
			<form class="form" enctype="multipart/form-data" method="POST">
				<fieldset>
					<input type="hidden" name="action" value="create_execute">
					

								<div class="form-group">
									<label class="control-label" for="textinput">Фамилия</label>
									<div>
										<input id="last_name" name="last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="first_name" name="first_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["first_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Отчество</label>
									<div>
										<input id="middle_name" name="middle_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["middle_name"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения</label>
						<div>
							<input autocomplete="off" id="birth_date" placeholder="" name="birth_date" type="text" class="form-control datepicker "  value="'.(isset($item["birth_date"])?$item["birth_date"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Серия и номер паспорта</label>
									<div>
										<input id="passport_number" name="passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_number"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт</label>
									<div>
										<input id="passport_issued_by" name="passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта</label>
						<div>
							<input autocomplete="off" id="passport_issued_date" placeholder="" name="passport_issued_date" type="text" class="form-control datepicker "  value="'.(isset($item["passport_issued_date"])?$item["passport_issued_date"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по прописке</label>
									<div>
										<input id="reg_zip_code" name="reg_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по прописке</label>
									<div>
										<input id="reg_city" name="reg_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по прописке</label>
									<div>
										<input id="reg_street" name="reg_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по прописке</label>
									<div>
										<input id="reg_building" name="reg_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по прописке</label>
									<div>
										<input id="reg_apartment" name="reg_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_apartment"]).'">
									</div>
								</div>

							



            <div class="form-group">
              <label class="control-label" for="textinput">Совпадают ли фактический и адрес прописки?</label>
              <div class="" >'.renderEditRadioGroup("is_address_matched", $is_address_matched_values, $item["is_address_matched"]).'
              </div>
            </div>

          


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по факту</label>
									<div>
										<input id="fact_zip_code" name="fact_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по факту</label>
									<div>
										<input id="fact_city" name="fact_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по факту</label>
									<div>
										<input id="fact_street" name="fact_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по факту</label>
									<div>
										<input id="fact_building" name="fact_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по факту</label>
									<div>
										<input id="fact_apartment" name="fact_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							
					<div class="text-center not-editable">
						
					</div>
				</fieldset>
			</form>

		';

		if(function_exists("processCreateModalHTML"))
		{
			$html = processCreateModalHTML($html);
		}
		die($html);
	};


	$actions['edit_page'] = function()
	{
		$id = $_REQUEST['genesis_edit_id'];
		if(isset($id))
		{
			$item = q("SELECT * FROM clients WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		$is_address_matched_values = '[
	{
		"text": "Совпадают",
		"value": "1"
	},
	{
		"text": "Не совпадают",
		"value": "0"
	}
]';


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Клиенты".' #'.$id.'</small></h1>
			<form class="form" enctype="multipart/form-data" method="POST">
				<input type="hidden" name="back" value="'.$_SERVER['HTTP_REFERER'].'">
				<fieldset>'.
					(isset($id)?
					'<input type="hidden" name="id" value="'.$id.'">
					<input type="hidden" name="action" value="edit_execute">'
					:
					'<input type="hidden" name="action" value="create_execute">'
					)
					.'

					

								<div class="form-group">
									<label class="control-label" for="textinput">Фамилия</label>
									<div>
										<input id="last_name" name="last_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["last_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="first_name" name="first_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["first_name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Отчество</label>
									<div>
										<input id="middle_name" name="middle_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["middle_name"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата рождения</label>
						<div>
							<input autocomplete="off" id="birth_date" placeholder="" name="birth_date" type="text" class="form-control datepicker "  value="'.(isset($item["birth_date"])?$item["birth_date"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Серия и номер паспорта</label>
									<div>
										<input id="passport_number" name="passport_number" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_number"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Кем выдан паспорт</label>
									<div>
										<input id="passport_issued_by" name="passport_issued_by" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["passport_issued_by"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Дата выдачи паспорта</label>
						<div>
							<input autocomplete="off" id="passport_issued_date" placeholder="" name="passport_issued_date" type="text" class="form-control datepicker "  value="'.(isset($item["passport_issued_date"])?$item["passport_issued_date"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по прописке</label>
									<div>
										<input id="reg_zip_code" name="reg_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по прописке</label>
									<div>
										<input id="reg_city" name="reg_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по прописке</label>
									<div>
										<input id="reg_street" name="reg_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по прописке</label>
									<div>
										<input id="reg_building" name="reg_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по прописке</label>
									<div>
										<input id="reg_apartment" name="reg_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["reg_apartment"]).'">
									</div>
								</div>

							



            <div class="form-group">
              <label class="control-label" for="textinput">Совпадают ли фактический и адрес прописки?</label>
              <div class="" >'.renderEditRadioGroup("is_address_matched", $is_address_matched_values, $item["is_address_matched"]).'
              </div>
            </div>

          


								<div class="form-group">
									<label class="control-label" for="textinput">Индекс по факту</label>
									<div>
										<input id="fact_zip_code" name="fact_zip_code" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_zip_code"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Город по факту</label>
									<div>
										<input id="fact_city" name="fact_city" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_city"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Улица по факту</label>
									<div>
										<input id="fact_street" name="fact_street" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_street"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Дом по факту</label>
									<div>
										<input id="fact_building" name="fact_building" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_building"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Квартира по факту</label>
									<div>
										<input id="fact_apartment" name="fact_apartment" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["fact_apartment"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							

				</fieldset>
				<div>
					<a href="?'.(http_build_query(array_filter($_REQUEST, function($k){return !in_array($k, ['action', 'genesis_edit_id']);}, ARRAY_FILTER_USE_KEY))).'" class="btn cancel" >Закрыть</a>
					<button type="button" class="btn blue-inline" id="edit_page_save">Сохранить</a>
				</div>
			</form>

		';

		if(function_exists("processEditPageHTML"))
		{
			$html = processEditPageHTML($html);
		}
		return $html;
	};

	$actions['reorder'] = function()
	{
		$line = json_decode($_REQUEST['genesis_ids_in_order'], true);
		for ($i=0; $i < count($line); $i++)
		{
			qi("UPDATE `clients` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
		}


		die(json_encode(['status'=>0]));

	};

	$actions['create_execute'] = function()
	{
		if(function_exists("allowInsert"))
		{
			if(!allowInsert())
			{
				header("Location: ".$_SERVER['HTTP_REFERER']);
				die("");
			}
		}
		$last_name = $_REQUEST['last_name'];
$first_name = $_REQUEST['first_name'];
$middle_name = $_REQUEST['middle_name'];
$birth_date = $_REQUEST['birth_date'];
$passport_number = $_REQUEST['passport_number'];
$passport_issued_by = $_REQUEST['passport_issued_by'];
$passport_issued_date = $_REQUEST['passport_issued_date'];
$reg_zip_code = $_REQUEST['reg_zip_code'];
$reg_city = $_REQUEST['reg_city'];
$reg_street = $_REQUEST['reg_street'];
$reg_building = $_REQUEST['reg_building'];
$reg_apartment = $_REQUEST['reg_apartment'];
$is_address_matched = $_REQUEST['is_address_matched'];
$fact_zip_code = $_REQUEST['fact_zip_code'];
$fact_city = $_REQUEST['fact_city'];
$fact_street = $_REQUEST['fact_street'];
$fact_building = $_REQUEST['fact_building'];
$fact_apartment = $_REQUEST['fact_apartment'];
$phone = $_REQUEST['phone'];
$email = $_REQUEST['email'];

		$sql = "INSERT INTO clients (`last_name`, `first_name`, `middle_name`, `birth_date`, `passport_number`, `passport_issued_by`, `passport_issued_date`, `reg_zip_code`, `reg_city`, `reg_street`, `reg_building`, `reg_apartment`, `is_address_matched`, `fact_zip_code`, `fact_city`, `fact_street`, `fact_building`, `fact_apartment`, `phone`, `email`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		if(function_exists("processInsertQuery"))
		{
			$sql = processInsertQuery($sql);
		}

		qi($sql, [$last_name, $first_name, $middle_name, $birth_date, $passport_number, $passport_issued_by, $passport_issued_date, $reg_zip_code, $reg_city, $reg_street, $reg_building, $reg_apartment, $is_address_matched, $fact_zip_code, $fact_city, $fact_street, $fact_building, $fact_apartment, $phone, $email]);
		$last_id = qInsertId();

		if(function_exists("afterInsert"))
		{
			afterInsert($last_id);
		}

		

		header("Location: ".$_SERVER['HTTP_REFERER']);
		die("");

	};

	$actions['edit_execute'] = function()
	{
		$skip = false;
		if(function_exists("allowUpdate"))
		{
			if(!allowUpdate())
			{
				$skip = true;
			}
		}
		if(!$skip)
		{
			$id = $_REQUEST['id'];
			$set = [];

			$set[] = is_null($_REQUEST['last_name'])?"`last_name`=NULL":"`last_name`='".addslashes($_REQUEST['last_name'])."'";
$set[] = is_null($_REQUEST['first_name'])?"`first_name`=NULL":"`first_name`='".addslashes($_REQUEST['first_name'])."'";
$set[] = is_null($_REQUEST['middle_name'])?"`middle_name`=NULL":"`middle_name`='".addslashes($_REQUEST['middle_name'])."'";
$set[] = is_null($_REQUEST['birth_date'])?"`birth_date`=NULL":"`birth_date`='".addslashes($_REQUEST['birth_date'])."'";
$set[] = is_null($_REQUEST['passport_number'])?"`passport_number`=NULL":"`passport_number`='".addslashes($_REQUEST['passport_number'])."'";
$set[] = is_null($_REQUEST['passport_issued_by'])?"`passport_issued_by`=NULL":"`passport_issued_by`='".addslashes($_REQUEST['passport_issued_by'])."'";
$set[] = is_null($_REQUEST['passport_issued_date'])?"`passport_issued_date`=NULL":"`passport_issued_date`='".addslashes($_REQUEST['passport_issued_date'])."'";
$set[] = is_null($_REQUEST['reg_zip_code'])?"`reg_zip_code`=NULL":"`reg_zip_code`='".addslashes($_REQUEST['reg_zip_code'])."'";
$set[] = is_null($_REQUEST['reg_city'])?"`reg_city`=NULL":"`reg_city`='".addslashes($_REQUEST['reg_city'])."'";
$set[] = is_null($_REQUEST['reg_street'])?"`reg_street`=NULL":"`reg_street`='".addslashes($_REQUEST['reg_street'])."'";
$set[] = is_null($_REQUEST['reg_building'])?"`reg_building`=NULL":"`reg_building`='".addslashes($_REQUEST['reg_building'])."'";
$set[] = is_null($_REQUEST['reg_apartment'])?"`reg_apartment`=NULL":"`reg_apartment`='".addslashes($_REQUEST['reg_apartment'])."'";
$set[] = is_null($_REQUEST['is_address_matched'])?"`is_address_matched`=NULL":"`is_address_matched`='".addslashes($_REQUEST['is_address_matched'])."'";
$set[] = is_null($_REQUEST['fact_zip_code'])?"`fact_zip_code`=NULL":"`fact_zip_code`='".addslashes($_REQUEST['fact_zip_code'])."'";
$set[] = is_null($_REQUEST['fact_city'])?"`fact_city`=NULL":"`fact_city`='".addslashes($_REQUEST['fact_city'])."'";
$set[] = is_null($_REQUEST['fact_street'])?"`fact_street`=NULL":"`fact_street`='".addslashes($_REQUEST['fact_street'])."'";
$set[] = is_null($_REQUEST['fact_building'])?"`fact_building`=NULL":"`fact_building`='".addslashes($_REQUEST['fact_building'])."'";
$set[] = is_null($_REQUEST['fact_apartment'])?"`fact_apartment`=NULL":"`fact_apartment`='".addslashes($_REQUEST['fact_apartment'])."'";
$set[] = is_null($_REQUEST['phone'])?"`phone`=NULL":"`phone`='".addslashes($_REQUEST['phone'])."'";
$set[] = is_null($_REQUEST['email'])?"`email`=NULL":"`email`='".addslashes($_REQUEST['email'])."'";

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE clients SET $set WHERE id=?";
				if(function_exists("processUpdateQuery"))
				{
					$sql = processUpdateQuery($sql);
				}

				qi($sql, [$id]);
				if(function_exists("afterUpdate"))
				{
					afterUpdate();
				}
			}
		}

		if(isset($_REQUEST['back']))
		{
			header("Location: {$_REQUEST['back']}");
		}
		else
		{
			header("Location: ".$_SERVER['HTTP_REFERER']);
		}
		die("");
	};



	$actions['delete'] = function()
	{
		if(function_exists("allowDelete"))
		{
			if(!allowDelete())
			{
				die("0");
			}
		}

		$id = $_REQUEST['id'];
		try
		{
			qi("DELETE FROM clients WHERE id=?", [$id]);
			if(function_exists("afterDelete"))
			{
				afterDelete();
			}
			echo "1";
		}
		catch (Exception $e)
		{
			echo "0";
		}

		die("");
	};

	function filter_query($srch)
	{
		$filters = [];
		
		if(isset2($_REQUEST['id_filter_from']) && isset2($_REQUEST['id_filter_to']))
		{
			$filters[] = "id >= {$_REQUEST['id_filter_from']} AND id <= {$_REQUEST['id_filter_to']}";
		}

		

		if(isset2($_REQUEST['birth_date_filter_from']) && isset2($_REQUEST['birth_date_filter_to']))
		{
			$filters[] = "birth_date >= '{$_REQUEST['birth_date_filter_from']}' AND birth_date <= '{$_REQUEST['birth_date_filter_to']}'";
		}

		

		if(isset2($_REQUEST['reg_city_filter']))
		{
			$filters[] = "`reg_city` LIKE '%{$_REQUEST['reg_city_filter']}%'";
		}
				

		$filter="";
		if(count($filters)>0)
		{
			$filter = implode(" AND ", $filters);
			if($srch=="")
			{
				$filter = " WHERE $filter";
			}
			else
			{
				$filter = " AND ($filter)";
			}
		}
		return $filter;
	}

	function filter_divs()
	{
		$is_address_matched_values = '[
	{
		"text": "Совпадают",
		"value": "1"
	},
	{
		"text": "Не совпадают",
		"value": "0"
	}
]';
			$is_address_matched_values_text = "";
			foreach(json_decode($is_address_matched_values, true) as $opt)
			{
			  $is_address_matched_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
			}
				  
		
		if(isset2($_REQUEST['id_filter_from']) && isset2($_REQUEST['id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='id_filter_from' value='{$_REQUEST['id_filter_from']}'>
					<input type='hidden' class='filter' name='id_filter_to' value='{$_REQUEST['id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> id: <b>{$_REQUEST['id_filter_from']}–{$_REQUEST['id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['birth_date_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['birth_date_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['birth_date_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='birth_date_filter_from' value='{$_REQUEST['birth_date_filter_from']}'>
					<input type='hidden' class='filter' name='birth_date_filter_to' value='{$_REQUEST['birth_date_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Дата рождения: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['reg_city_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='reg_city_filter' value='{$_REQUEST['reg_city_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Город по прописке: <b>{$_REQUEST['reg_city_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		
		$show = $filter_caption.$filter_divs;

		return $show;
	}


	function get_data($force_kill_pagination=false)
	{
		if(function_exists("allowSelect"))
		{
			if(!allowSelect())
			{
				die("У вас нет доступа к данной странице");
			}
		}

		$pagination = 1;
		if($force_kill_pagination==true)
		{
			$pagination = 0;
		}
		$items = [];

		$srch = "";
		
			if($_REQUEST['srch-term'])
			{
				$srch = "WHERE ((`id` LIKE '%{$_REQUEST['srch-term']}%') or (`last_name` LIKE '%{$_REQUEST['srch-term']}%') or (`first_name` LIKE '%{$_REQUEST['srch-term']}%') or (`middle_name` LIKE '%{$_REQUEST['srch-term']}%') or (`passport_number` LIKE '%{$_REQUEST['srch-term']}%') or (`phone` LIKE '%{$_REQUEST['srch-term']}%') or (`email` LIKE '%{$_REQUEST['srch-term']}%'))";
			}

		$filter = filter_query($srch);
		$where = "";
		if($where != "")
		{
			if($filter!='' || $srch !='')
			{
				$where = " AND ($where)";
			}
			else
			{
				$where = " WHERE ($where)";
			}
		}


		
				$default_sort_by = 'id';
				$default_sort_order = '';
			

		if(isset($default_sort_by) && $default_sort_by)
		{
			$order = "ORDER BY $default_sort_by $default_sort_order";
		}

		if(isset($_REQUEST['sort_by']) && $_REQUEST['sort_by']!="")
		{
			$order = "ORDER BY {$_REQUEST['sort_by']} {$_REQUEST['sort_order']}";
		}


		if($pagination == 1)
		{
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.*  FROM clients main_table) temp $srch $filter $where $order LIMIT :start, :limit";
			if(function_exists("processSelectQuery"))
			{
				$sql = processSelectQuery($sql);
			}

			$items = q($sql,
				[
					'start' => MAX(($_GET['page']-1), 0)*RPP,
					'limit' => RPP
				]);
			$cnt = qRows();
			$pagination = pagination($cnt);
		}
		else
		{
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.*  FROM clients main_table) temp $srch $filter $where $order";
			if(function_exists("processSelectQuery"))
			{
				$sql = processSelectQuery($sql);
			}
			$items = q($sql, []);
			$cnt = qRows();
			$pagination = "";
		}

		if(function_exists("processData"))
		{
			$items = processData($items);
		}

		return [$items, $pagination, $cnt];
	}

	

	$content = $actions[$action]();
	echo masterRender("Клиенты", $content, 4);

	// обработка массива данных, которые будут отбражаться на этой странице
function processData($data)
{
	return $data;
}

// обработка sql-запроса вставки данных
function processInsertQuery($sql_query_text)
{
	return $sql_query_text;
}

// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();
function allowUpdate()
{
 $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );
 
	return true;
}

function allowInsert()
{
 $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );
 
	return true;
}

//этот код будет вставлен в конец файла. можешь объявлять в нем свои функции и тд
