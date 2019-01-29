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
			
   		$status_values = '[
	{
		"text": "одобрено",
		"value": "approved"
	},
	{
		"text": "отказано",
		"value": "declined"
	},
	{
		"text": "в процессе",
		"value": "pending"
	},
	{
		"text": "отменено клиентом",
		"value": "cancel"
	},
	{
		"text": "требуется решение менеджера",
		"value": "manual"
	}
]';
		$status_values_text = "";
		foreach(json_decode($status_values, true) as $opt)
		{
			$status_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$is_loan_deferred_values = '[{"text":"да", "value":"1"},{"text":"нет", "value":"0"}]';
		$is_loan_deferred_values_text = "";
		foreach(json_decode($is_loan_deferred_values, true) as $opt)
		{
			$is_loan_deferred_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$is_loan_received_values = '[{"text":"да", "value":"1"},{"text":"нет", "value":"0"}]';
		$is_loan_received_values_text = "";
		foreach(json_decode($is_loan_received_values, true) as $opt)
		{
			$is_loan_received_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$is_order_received_values = '[{"text":"да", "value":"1"},{"text":"нет", "value":"0"}]';
		$is_order_received_values_text = "";
		foreach(json_decode($is_order_received_values, true) as $opt)
		{
			$is_order_received_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['client_id']='asc';
$next_order['shop_id']='asc';
$next_order['order_id']='asc';
$next_order['order_price']='asc';
$next_order['status']='asc';
$next_order['mfo_id']='asc';
$next_order['conditions_url']='asc';
$next_order['is_loan_deferred']='asc';
$next_order['is_loan_received']='asc';
$next_order['is_order_received']='asc';
$next_order['tracking_id']='asc';
$next_order['time_start']='asc';
$next_order['time_finish']='asc';

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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Заявки на кредит".' </h2>
				
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
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=client_id&sort_order='. ($next_order['client_id']) .'\' class=\'sort\' column=\'client_id\' sort_order=\''.$sort_order['client_id'].'\'>ID клиента'. $sort_icon['client_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="client_id_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="client_id_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=shop_id&sort_order='. ($next_order['shop_id']) .'\' class=\'sort\' column=\'shop_id\' sort_order=\''.$sort_order['shop_id'].'\'>ID магазина'. $sort_icon['shop_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="shop_id_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="shop_id_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=order_id&sort_order='. ($next_order['order_id']) .'\' class=\'sort\' column=\'order_id\' sort_order=\''.$sort_order['order_id'].'\'>ID заказа'. $sort_icon['order_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="order_id_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="order_id_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=order_price&sort_order='. ($next_order['order_price']) .'\' class=\'sort\' column=\'order_price\' sort_order=\''.$sort_order['order_price'].'\'>Сумма заказа'. $sort_icon['order_price'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="order_price_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="order_price_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=status&sort_order='. ($next_order['status']) .'\' class=\'sort\' column=\'status\' sort_order=\''.$sort_order['status'].'\'>Статус заявки'. $sort_icon['status'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="status_filter">
							'. $status_values_text .'
							</select>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=mfo_id&sort_order='. ($next_order['mfo_id']) .'\' class=\'sort\' column=\'mfo_id\' sort_order=\''.$sort_order['mfo_id'].'\'>ID МФО'. $sort_icon['mfo_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-from" name="mfo_id_filter_from" placeholder="От"/>
							<span class="input-group-btn" style="width:0px;"></span>
							<input type="number" min="-2147483648" max="2147483648" step="0.01" class="form-control filter-number-to" name="mfo_id_filter_to" placeholder="До"/>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=conditions_url&sort_order='. ($next_order['conditions_url']) .'\' class=\'sort\' column=\'conditions_url\' sort_order=\''.$sort_order['conditions_url'].'\'>Cсылка на соглашение'. $sort_icon['conditions_url'].'</a>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_loan_deferred&sort_order='. ($next_order['is_loan_deferred']) .'\' class=\'sort\' column=\'is_loan_deferred\' sort_order=\''.$sort_order['is_loan_deferred'].'\'>Кредит отложенный?'. $sort_icon['is_loan_deferred'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="is_loan_deferred_filter">
              <span class="input-group-btn">
                <button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
              </span>
            </div>\'>
      </span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_loan_received&sort_order='. ($next_order['is_loan_received']) .'\' class=\'sort\' column=\'is_loan_received\' sort_order=\''.$sort_order['is_loan_received'].'\'>МФО выдала деньги?'. $sort_icon['is_loan_received'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="is_loan_received_filter">
              <span class="input-group-btn">
                <button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
              </span>
            </div>\'>
      </span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=is_order_received&sort_order='. ($next_order['is_order_received']) .'\' class=\'sort\' column=\'is_order_received\' sort_order=\''.$sort_order['is_order_received'].'\'>Товар получен клиентом?'. $sort_icon['is_order_received'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="is_order_received_filter">
              <span class="input-group-btn">
                <button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
              </span>
            </div>\'>
      </span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=tracking_id&sort_order='. ($next_order['tracking_id']) .'\' class=\'sort\' column=\'tracking_id\' sort_order=\''.$sort_order['tracking_id'].'\'>Код отслеживания посылки'. $sort_icon['tracking_id'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input type="text" class="form-control filter-text" name="tracking_id_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_start&sort_order='. ($next_order['time_start']) .'\' class=\'sort\' column=\'time_start\' sort_order=\''.$sort_order['time_start'].'\'>Время подачи заявки'. $sort_icon['time_start'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input autocomplete="off" type="text" class="form-control daterange filter-date-range" name="time_start_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=time_finish&sort_order='. ($next_order['time_finish']) .'\' class=\'sort\' column=\'time_finish\' sort_order=\''.$sort_order['time_finish'].'\'>Время закрытия заявки'. $sort_icon['time_finish'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<input autocomplete="off" type="text" class="form-control daterange filter-date-range" name="time_finish_filter">
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
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
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='client_id'>".htmlspecialchars($item['client_id'])."</span></td>", $item, "ID клиента"):"<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='client_id'>".htmlspecialchars($item['client_id'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='shop_id'>".htmlspecialchars($item['shop_id'])."</span></td>", $item, "ID магазина"):"<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='shop_id'>".htmlspecialchars($item['shop_id'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='order_id'>".htmlspecialchars($item['order_id'])."</span></td>", $item, "ID заказа"):"<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='order_id'>".htmlspecialchars($item['order_id'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='decimal' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='order_price'>".htmlspecialchars($item['order_price'])."</span></td>", $item, "Сумма заказа"):"<td><span class='editable ' data-placeholder='' data-inp='decimal' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='order_price'>".htmlspecialchars($item['order_price'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($status_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='status'>".select_mapping($status_values, $item['status'])."</span></td>", $item, "Статус заявки"):"<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($status_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='status'>".select_mapping($status_values, $item['status'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='mfo_id'>".htmlspecialchars($item['mfo_id'])."</span></td>", $item, "ID МФО"):"<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='mfo_id'>".htmlspecialchars($item['mfo_id'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='conditions_url'>".htmlspecialchars($item['conditions_url'])."</span></td>", $item, "Cсылка на соглашение"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='conditions_url'>".htmlspecialchars($item['conditions_url'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($is_loan_deferred_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='is_loan_deferred'>".select_mapping($is_loan_deferred_values, $item['is_loan_deferred'])."</span></td>", $item, "Кредит отложенный?"):"<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($is_loan_deferred_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='is_loan_deferred'>".select_mapping($is_loan_deferred_values, $item['is_loan_deferred'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($is_loan_received_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='is_loan_received'>".select_mapping($is_loan_received_values, $item['is_loan_received'])."</span></td>", $item, "МФО выдала деньги?"):"<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($is_loan_received_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='is_loan_received'>".select_mapping($is_loan_received_values, $item['is_loan_received'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($is_order_received_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='is_order_received'>".select_mapping($is_order_received_values, $item['is_order_received'])."</span></td>", $item, "Товар получен клиентом?"):"<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($is_order_received_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='is_order_received'>".select_mapping($is_order_received_values, $item['is_order_received'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='tracking_id'>".htmlspecialchars($item['tracking_id'])."</span></td>", $item, "Код отслеживания посылки"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='tracking_id'>".htmlspecialchars($item['tracking_id'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='time_start'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['time_start']?$item['time_start']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>", $item, "Время подачи заявки"):"<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='time_start'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['time_start']?$item['time_start']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='time_finish'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['time_finish']?$item['time_finish']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>", $item, "Время закрытия заявки"):"<td><span class='editable '  data-placeholder='' data-inp='date' data-url='engine/ajax.php?action=editable&table=requests' data-pk='{$item['id']}' data-name='time_finish'>".DateTime::createFromFormat('Y-m-d H:i:s', ($item['time_finish']?$item['time_finish']:"1970-01-01 00:00:00") )->format('Y-m-d H:i')."</span></td>")."
					<td><a href='#' class='edit_btn'><i class='fa fa-edit' style='color:grey;'></i></a> </td>
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
			$item = q("SELECT * FROM requests WHERE id=?",[$id]);
			$item = $item[0];
		}

		

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
									<label class="control-label" for="textinput">ID клиента</label>
									<div>
										<input id="client_id" name="client_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["client_id"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ID заказа</label>
									<div>
										<input id="order_id" name="order_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["order_id"]).'">
									</div>
								</div>

							


	               <div class="form-group">
	                 <label class="control-label" for="textinput">Сумма заказа</label>
	                 <div>
	                   <input id="order_price" name="order_price" type="number" step="0.01" class="form-control input-md " placeholder=""  value="'.htmlspecialchars($item["order_price"]).'">
	                 </div>
	               </div>

	             



				<div class="form-group">
					<label class="control-label" for="textinput">Статус заявки</label>
					<div>
						<select id="status" name="status" class="form-control input-md ">
							<option value="approved" '.($item["status"]=="approved"?"selected":"").'>одобрено</option> 
<option value="declined" '.($item["status"]=="declined"?"selected":"").'>отказано</option> 
<option value="pending" '.($item["status"]=="pending"?"selected":"").'>в процессе</option> 
<option value="cancel" '.($item["status"]=="cancel"?"selected":"").'>отменено клиентом</option> 
<option value="manual" '.($item["status"]=="manual"?"selected":"").'>требуется решение менеджера</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID МФО</label>
									<div>
										<input id="mfo_id" name="mfo_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["mfo_id"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Cсылка на соглашение</label>
									<div>
										<input id="conditions_url" name="conditions_url" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["conditions_url"]).'">
									</div>
								</div>

							



				<div class="form-group">
					<label class="control-label" for="textinput">Кредит отложенный?</label>
					<div>
						<select id="is_loan_deferred" name="is_loan_deferred" class="form-control input-md ">
							<option value="1" '.($item["is_loan_deferred"]=="1"?"selected":"").'>да</option> 
<option value="0" '.($item["is_loan_deferred"]=="0"?"selected":"").'>нет</option> 

						</select>
					</div>
				</div>

			



				<div class="form-group">
					<label class="control-label" for="textinput">МФО выдала деньги?</label>
					<div>
						<select id="is_loan_received" name="is_loan_received" class="form-control input-md ">
							<option value="1" '.($item["is_loan_received"]=="1"?"selected":"").'>да</option> 
<option value="0" '.($item["is_loan_received"]=="0"?"selected":"").'>нет</option> 

						</select>
					</div>
				</div>

			



				<div class="form-group">
					<label class="control-label" for="textinput">Товар получен клиентом?</label>
					<div>
						<select id="is_order_received" name="is_order_received" class="form-control input-md ">
							<option value="1" '.($item["is_order_received"]=="1"?"selected":"").'>да</option> 
<option value="0" '.($item["is_order_received"]=="0"?"selected":"").'>нет</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Код отслеживания посылки</label>
									<div>
										<input id="tracking_id" name="tracking_id" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["tracking_id"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Время подачи заявки</label>
						<div>
							<input autocomplete="off" id="time_start" placeholder="" name="time_start" type="text" class="form-control datepicker "  value="'.(isset($item["time_start"])?$item["time_start"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


					<div class="form-group">
						<label class="control-label" for="textinput">Время закрытия заявки</label>
						<div>
							<input autocomplete="off" id="time_finish" placeholder="" name="time_finish" type="text" class="form-control datepicker "  value="'.(isset($item["time_finish"])?$item["time_finish"]:date("Y-m-d H:i")).'"/>
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

		

		$html = '
			<form class="form" enctype="multipart/form-data" method="POST">
				<fieldset>
					<input type="hidden" name="action" value="create_execute">
					
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
			$item = q("SELECT * FROM requests WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Заявки на кредит".' #'.$id.'</small></h1>
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
									<label class="control-label" for="textinput">ID клиента</label>
									<div>
										<input id="client_id" name="client_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["client_id"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">ID заказа</label>
									<div>
										<input id="order_id" name="order_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["order_id"]).'">
									</div>
								</div>

							


	               <div class="form-group">
	                 <label class="control-label" for="textinput">Сумма заказа</label>
	                 <div>
	                   <input id="order_price" name="order_price" type="number" step="0.01" class="form-control input-md " placeholder=""  value="'.htmlspecialchars($item["order_price"]).'">
	                 </div>
	               </div>

	             



				<div class="form-group">
					<label class="control-label" for="textinput">Статус заявки</label>
					<div>
						<select id="status" name="status" class="form-control input-md ">
							<option value="approved" '.($item["status"]=="approved"?"selected":"").'>одобрено</option> 
<option value="declined" '.($item["status"]=="declined"?"selected":"").'>отказано</option> 
<option value="pending" '.($item["status"]=="pending"?"selected":"").'>в процессе</option> 
<option value="cancel" '.($item["status"]=="cancel"?"selected":"").'>отменено клиентом</option> 
<option value="manual" '.($item["status"]=="manual"?"selected":"").'>требуется решение менеджера</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID МФО</label>
									<div>
										<input id="mfo_id" name="mfo_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["mfo_id"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Cсылка на соглашение</label>
									<div>
										<input id="conditions_url" name="conditions_url" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["conditions_url"]).'">
									</div>
								</div>

							



				<div class="form-group">
					<label class="control-label" for="textinput">Кредит отложенный?</label>
					<div>
						<select id="is_loan_deferred" name="is_loan_deferred" class="form-control input-md ">
							<option value="1" '.($item["is_loan_deferred"]=="1"?"selected":"").'>да</option> 
<option value="0" '.($item["is_loan_deferred"]=="0"?"selected":"").'>нет</option> 

						</select>
					</div>
				</div>

			



				<div class="form-group">
					<label class="control-label" for="textinput">МФО выдала деньги?</label>
					<div>
						<select id="is_loan_received" name="is_loan_received" class="form-control input-md ">
							<option value="1" '.($item["is_loan_received"]=="1"?"selected":"").'>да</option> 
<option value="0" '.($item["is_loan_received"]=="0"?"selected":"").'>нет</option> 

						</select>
					</div>
				</div>

			



				<div class="form-group">
					<label class="control-label" for="textinput">Товар получен клиентом?</label>
					<div>
						<select id="is_order_received" name="is_order_received" class="form-control input-md ">
							<option value="1" '.($item["is_order_received"]=="1"?"selected":"").'>да</option> 
<option value="0" '.($item["is_order_received"]=="0"?"selected":"").'>нет</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Код отслеживания посылки</label>
									<div>
										<input id="tracking_id" name="tracking_id" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["tracking_id"]).'">
									</div>
								</div>

							


					<div class="form-group">
						<label class="control-label" for="textinput">Время подачи заявки</label>
						<div>
							<input autocomplete="off" id="time_start" placeholder="" name="time_start" type="text" class="form-control datepicker "  value="'.(isset($item["time_start"])?$item["time_start"]:date("Y-m-d H:i")).'"/>
						</div>
					</div>

				


					<div class="form-group">
						<label class="control-label" for="textinput">Время закрытия заявки</label>
						<div>
							<input autocomplete="off" id="time_finish" placeholder="" name="time_finish" type="text" class="form-control datepicker "  value="'.(isset($item["time_finish"])?$item["time_finish"]:date("Y-m-d H:i")).'"/>
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
			qi("UPDATE `requests` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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
		

		$sql = "INSERT INTO requests () VALUES ()";
		if(function_exists("processInsertQuery"))
		{
			$sql = processInsertQuery($sql);
		}

		qi($sql, []);
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

			$set[] = is_null($_REQUEST['order_price'])?"`order_price`=NULL":"`order_price`='".addslashes($_REQUEST['order_price'])."'";
$set[] = is_null($_REQUEST['status'])?"`status`=NULL":"`status`='".addslashes($_REQUEST['status'])."'";
$set[] = is_null($_REQUEST['conditions_url'])?"`conditions_url`=NULL":"`conditions_url`='".addslashes($_REQUEST['conditions_url'])."'";
$set[] = is_null($_REQUEST['is_loan_deferred'])?"`is_loan_deferred`=NULL":"`is_loan_deferred`='".addslashes($_REQUEST['is_loan_deferred'])."'";
$set[] = is_null($_REQUEST['is_loan_received'])?"`is_loan_received`=NULL":"`is_loan_received`='".addslashes($_REQUEST['is_loan_received'])."'";
$set[] = is_null($_REQUEST['is_order_received'])?"`is_order_received`=NULL":"`is_order_received`='".addslashes($_REQUEST['is_order_received'])."'";
$set[] = is_null($_REQUEST['tracking_id'])?"`tracking_id`=NULL":"`tracking_id`='".addslashes($_REQUEST['tracking_id'])."'";
$set[] = is_null($_REQUEST['time_start'])?"`time_start`=NULL":"`time_start`='".addslashes($_REQUEST['time_start'])."'";
$set[] = is_null($_REQUEST['time_finish'])?"`time_finish`=NULL":"`time_finish`='".addslashes($_REQUEST['time_finish'])."'";

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE requests SET $set WHERE id=?";
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
			qi("DELETE FROM requests WHERE id=?", [$id]);
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

		

		if(isset2($_REQUEST['client_id_filter_from']) && isset2($_REQUEST['client_id_filter_to']))
		{
			$filters[] = "client_id >= {$_REQUEST['client_id_filter_from']} AND client_id <= {$_REQUEST['client_id_filter_to']}";
		}

		

		if(isset2($_REQUEST['shop_id_filter_from']) && isset2($_REQUEST['shop_id_filter_to']))
		{
			$filters[] = "shop_id >= {$_REQUEST['shop_id_filter_from']} AND shop_id <= {$_REQUEST['shop_id_filter_to']}";
		}

		

		if(isset2($_REQUEST['order_id_filter_from']) && isset2($_REQUEST['order_id_filter_to']))
		{
			$filters[] = "order_id >= {$_REQUEST['order_id_filter_from']} AND order_id <= {$_REQUEST['order_id_filter_to']}";
		}

		

		if(isset2($_REQUEST['order_price_filter_from']) && isset2($_REQUEST['order_price_filter_to']))
		{
			$filters[] = "order_price >= {$_REQUEST['order_price_filter_from']} AND order_price <= {$_REQUEST['order_price_filter_to']}";
		}

		

		if(isset2($_REQUEST['status_filter']))
		{
			$filters[] = "`status` = '{$_REQUEST['status_filter']}'";
		}
				

		if(isset2($_REQUEST['mfo_id_filter_from']) && isset2($_REQUEST['mfo_id_filter_to']))
		{
			$filters[] = "mfo_id >= {$_REQUEST['mfo_id_filter_from']} AND mfo_id <= {$_REQUEST['mfo_id_filter_to']}";
		}

		

if(isset2($_REQUEST['is_loan_deferred_filter']))
{
  $filters[] = "`is_loan_deferred` = '{$_REQUEST['is_loan_deferred_filter']}'";
}
    

if(isset2($_REQUEST['is_loan_received_filter']))
{
  $filters[] = "`is_loan_received` = '{$_REQUEST['is_loan_received_filter']}'";
}
    

if(isset2($_REQUEST['is_order_received_filter']))
{
  $filters[] = "`is_order_received` = '{$_REQUEST['is_order_received_filter']}'";
}
    

		if(isset2($_REQUEST['tracking_id_filter']))
		{
			$filters[] = "`tracking_id` LIKE '%{$_REQUEST['tracking_id_filter']}%'";
		}
				

		if(isset2($_REQUEST['time_start_filter_from']) && isset2($_REQUEST['time_start_filter_to']))
		{
			$filters[] = "time_start >= '{$_REQUEST['time_start_filter_from']}' AND time_start <= '{$_REQUEST['time_start_filter_to']}'";
		}

		

		if(isset2($_REQUEST['time_finish_filter_from']) && isset2($_REQUEST['time_finish_filter_to']))
		{
			$filters[] = "time_finish >= '{$_REQUEST['time_finish_filter_from']}' AND time_finish <= '{$_REQUEST['time_finish_filter_to']}'";
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
		$status_values = '[
	{
		"text": "одобрено",
		"value": "approved"
	},
	{
		"text": "отказано",
		"value": "declined"
	},
	{
		"text": "в процессе",
		"value": "pending"
	},
	{
		"text": "отменено клиентом",
		"value": "cancel"
	},
	{
		"text": "требуется решение менеджера",
		"value": "manual"
	}
]';
		$status_values_text = "";
		foreach(json_decode($status_values, true) as $opt)
		{
			$status_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$is_loan_deferred_values = '[{"text":"да", "value":"1"},{"text":"нет", "value":"0"}]';
		$is_loan_deferred_values_text = "";
		foreach(json_decode($is_loan_deferred_values, true) as $opt)
		{
			$is_loan_deferred_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$is_loan_received_values = '[{"text":"да", "value":"1"},{"text":"нет", "value":"0"}]';
		$is_loan_received_values_text = "";
		foreach(json_decode($is_loan_received_values, true) as $opt)
		{
			$is_loan_received_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		
$is_order_received_values = '[{"text":"да", "value":"1"},{"text":"нет", "value":"0"}]';
		$is_order_received_values_text = "";
		foreach(json_decode($is_order_received_values, true) as $opt)
		{
			$is_order_received_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
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
				

		if(isset2($_REQUEST['client_id_filter_from']) && isset2($_REQUEST['client_id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='client_id_filter_from' value='{$_REQUEST['client_id_filter_from']}'>
					<input type='hidden' class='filter' name='client_id_filter_to' value='{$_REQUEST['client_id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID клиента: <b>{$_REQUEST['client_id_filter_from']}–{$_REQUEST['client_id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['shop_id_filter_from']) && isset2($_REQUEST['shop_id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='shop_id_filter_from' value='{$_REQUEST['shop_id_filter_from']}'>
					<input type='hidden' class='filter' name='shop_id_filter_to' value='{$_REQUEST['shop_id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID магазина: <b>{$_REQUEST['shop_id_filter_from']}–{$_REQUEST['shop_id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['order_id_filter_from']) && isset2($_REQUEST['order_id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='order_id_filter_from' value='{$_REQUEST['order_id_filter_from']}'>
					<input type='hidden' class='filter' name='order_id_filter_to' value='{$_REQUEST['order_id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID заказа: <b>{$_REQUEST['order_id_filter_from']}–{$_REQUEST['order_id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['order_price_filter_from']) && isset2($_REQUEST['order_price_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='order_price_filter_from' value='{$_REQUEST['order_price_filter_from']}'>
					<input type='hidden' class='filter' name='order_price_filter_to' value='{$_REQUEST['order_price_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Сумма заказа: <b>{$_REQUEST['order_price_filter_from']}–{$_REQUEST['order_price_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		$text_option = array_filter(json_decode($status_values, true), function($i)
		{
			return $i['value']==$_REQUEST['status_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['status_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='status_filter' value='{$_REQUEST['status_filter']}'>
					<span class='fa fa-times remove-tag'></span> Статус заявки: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['mfo_id_filter_from']) && isset2($_REQUEST['mfo_id_filter_to']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='mfo_id_filter_from' value='{$_REQUEST['mfo_id_filter_from']}'>
					<input type='hidden' class='filter' name='mfo_id_filter_to' value='{$_REQUEST['mfo_id_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> ID МФО: <b>{$_REQUEST['mfo_id_filter_from']}–{$_REQUEST['mfo_id_filter_to']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

if(isset2($_REQUEST['is_loan_deferred_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_loan_deferred_filter' value='{$_REQUEST['is_loan_deferred_filter']}'>
       <span class='fa fa-times remove-tag'></span> Кредит отложенный?: <b>".($_REQUEST['is_loan_deferred_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



if(isset2($_REQUEST['is_loan_received_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_loan_received_filter' value='{$_REQUEST['is_loan_received_filter']}'>
       <span class='fa fa-times remove-tag'></span> МФО выдала деньги?: <b>".($_REQUEST['is_loan_received_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



if(isset2($_REQUEST['is_order_received_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='is_order_received_filter' value='{$_REQUEST['is_order_received_filter']}'>
       <span class='fa fa-times remove-tag'></span> Товар получен клиентом?: <b>".($_REQUEST['is_order_received_filter']?"Вкл":"Выкл")."</b>
  </div>";

  $filter_caption = "Фильтры: ";
}



		if(isset2($_REQUEST['tracking_id_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='tracking_id_filter' value='{$_REQUEST['tracking_id_filter']}'>
				   <span class='fa fa-times remove-tag'></span> Код отслеживания посылки: <b>{$_REQUEST['tracking_id_filter']}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}

		

		if(isset2($_REQUEST['time_start_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['time_start_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['time_start_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='time_start_filter_from' value='{$_REQUEST['time_start_filter_from']}'>
					<input type='hidden' class='filter' name='time_start_filter_to' value='{$_REQUEST['time_start_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Время подачи заявки: <b>{$from}–{$to}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

		if(isset2($_REQUEST['time_finish_filter_from']))
		{
			$from = date('d.m.Y', strtotime($_REQUEST['time_finish_filter_from']));
			$to = date('d.m.Y', strtotime($_REQUEST['time_finish_filter_to']));
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='time_finish_filter_from' value='{$_REQUEST['time_finish_filter_from']}'>
					<input type='hidden' class='filter' name='time_finish_filter_to' value='{$_REQUEST['time_finish_filter_to']}'>
					<span class='fa fa-times remove-tag'></span> Время закрытия заявки: <b>{$from}–{$to}</b>
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
				$srch = "WHERE ((`id` LIKE '%{$_REQUEST['srch-term']}%'))";
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
				$default_sort_order = 'desc';
			

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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.*  FROM requests main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.*  FROM requests main_table) temp $srch $filter $where $order";
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
	echo masterRender("Заявки на кредит", $content, 5);

	// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();
function allowUpdate()
{
	return true;
}
