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
			
   		$type_values = '[
	{
		"text": "ИП",
		"value": "entrepreneur"
	},
	{
		"text": "ООО",
		"value": "llc"
	}
]';
		$type_values_text = "";
		foreach(json_decode($type_values, true) as $opt)
		{
			$type_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['type']='asc';
$next_order['company_name']='asc';
$next_order['last_name']='asc';
$next_order['first_name']='asc';
$next_order['middle_name']='asc';
$next_order['tin']='asc';
$next_order['dsc']='asc';
$next_order['active']='asc';
$next_order['secret_key']='asc';

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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Магазины".' </h2>
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
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=type&sort_order='. ($next_order['type']) .'\' class=\'sort\' column=\'type\' sort_order=\''.$sort_order['type'].'\'>Тип формы собственности'. $sort_icon['type'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="type_filter">
							'. $type_values_text .'
							</select>
							<span class="input-group-btn">
								<button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
							</span>
						</div>\'>
			</span>
				</nobr>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=company_name&sort_order='. ($next_order['company_name']) .'\' class=\'sort\' column=\'company_name\' sort_order=\''.$sort_order['company_name'].'\'>Название'. $sort_icon['company_name'].'</a>
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
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=tin&sort_order='. ($next_order['tin']) .'\' class=\'sort\' column=\'tin\' sort_order=\''.$sort_order['tin'].'\'>ИНН'. $sort_icon['tin'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=dsc&sort_order='. ($next_order['dsc']) .'\' class=\'sort\' column=\'dsc\' sort_order=\''.$sort_order['dsc'].'\'>Описание'. $sort_icon['dsc'].'</a>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=active&sort_order='. ($next_order['active']) .'\' class=\'sort\' column=\'active\' sort_order=\''.$sort_order['active'].'\'>Активный ли магазин'. $sort_icon['active'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="active_filter">
              <span class="input-group-btn">
                <button class="btn btn-primary add-filter" type="button"><span class="fa fa-filter"></a></button>
              </span>
            </div>\'>
      </span>
				</nobr>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=secret_key&sort_order='. ($next_order['secret_key']) .'\' class=\'sort\' column=\'secret_key\' sort_order=\''.$sort_order['secret_key'].'\'>Секретный ключ'. $sort_icon['secret_key'].'</a>
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
".(function_exists("processTD")?processTD("<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($type_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='type'>".select_mapping($type_values, $item['type'])."</span></td>", $item, "Тип формы собственности"):"<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($type_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='type'>".select_mapping($type_values, $item['type'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='company_name'>".htmlspecialchars($item['company_name'])."</span></td>", $item, "Название"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='company_name'>".htmlspecialchars($item['company_name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='last_name'>".htmlspecialchars($item['last_name'])."</span></td>", $item, "Фамилия"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='last_name'>".htmlspecialchars($item['last_name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='first_name'>".htmlspecialchars($item['first_name'])."</span></td>", $item, "Имя"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='first_name'>".htmlspecialchars($item['first_name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='middle_name'>".htmlspecialchars($item['middle_name'])."</span></td>", $item, "Отчество"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='middle_name'>".htmlspecialchars($item['middle_name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='tin'>".htmlspecialchars($item['tin'])."</span></td>", $item, "ИНН"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='tin'>".htmlspecialchars($item['tin'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='textarea' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='dsc'>".htmlspecialchars($item['dsc'])."</span></td>", $item, "Описание"):"<td><span class='editable ' data-placeholder='' data-inp='textarea' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='dsc'>".htmlspecialchars($item['dsc'])."</span></td>")."
".(function_exists("processTD")?processTD("<td class='text-center'><input  data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='active' type='checkbox'".($item['active']==1?" checked ":" ")." class='ajax-checkbox '></td>", $item, "Активный ли магазин"):"<td class='text-center'><input  data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='active' type='checkbox'".($item['active']==1?" checked ":" ")." class='ajax-checkbox '></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='secret_key'>".htmlspecialchars($item['secret_key'])."</span></td>", $item, "Секретный ключ"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops' data-pk='{$item['id']}' data-name='secret_key'>".htmlspecialchars($item['secret_key'])."</span></td>")."
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
			$item = q("SELECT * FROM shops WHERE id=?",[$id]);
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
					<label class="control-label" for="textinput">Тип формы собственности</label>
					<div>
						<select id="type" name="type" class="form-control input-md ">
							<option value="entrepreneur" '.($item["type"]=="entrepreneur"?"selected":"").'>ИП</option> 
<option value="llc" '.($item["type"]=="llc"?"selected":"").'>ООО</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Название</label>
									<div>
										<input id="company_name" name="company_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["company_name"]).'">
									</div>
								</div>

							


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
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


							<div class="form-group">
								<label class="control-label" for="textinput">Описание</label>
								<div>
									<textarea id="dsc" name="dsc" class="form-control input-md  "  >'.htmlspecialchars($item["dsc"]).'</textarea>
								</div>
							</div>

						


						<div class="form-group">
							<label class="control-label" for="textinput">Активный ли магазин</label>
							<div>
								<input id="active" name="active" class=""  type="checkbox"  value="1" '.($item["active"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Секретный ключ</label>
									<div>
										<input id="secret_key" name="secret_key" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["secret_key"]).'">
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
					


				<div class="form-group">
					<label class="control-label" for="textinput">Тип формы собственности</label>
					<div>
						<select id="type" name="type" class="form-control input-md ">
							<option value="entrepreneur" '.($item["type"]=="entrepreneur"?"selected":"").'>ИП</option> 
<option value="llc" '.($item["type"]=="llc"?"selected":"").'>ООО</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Название</label>
									<div>
										<input id="company_name" name="company_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["company_name"]).'">
									</div>
								</div>

							


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
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


							<div class="form-group">
								<label class="control-label" for="textinput">Описание</label>
								<div>
									<textarea id="dsc" name="dsc" class="form-control input-md  "  >'.htmlspecialchars($item["dsc"]).'</textarea>
								</div>
							</div>

						


						<div class="form-group">
							<label class="control-label" for="textinput">Активный ли магазин</label>
							<div>
								<input id="active" name="active" class=""  type="checkbox"  value="1" '.($item["active"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Секретный ключ</label>
									<div>
										<input id="secret_key" name="secret_key" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["secret_key"]).'">
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
			$item = q("SELECT * FROM shops WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Магазины".' #'.$id.'</small></h1>
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
					<label class="control-label" for="textinput">Тип формы собственности</label>
					<div>
						<select id="type" name="type" class="form-control input-md ">
							<option value="entrepreneur" '.($item["type"]=="entrepreneur"?"selected":"").'>ИП</option> 
<option value="llc" '.($item["type"]=="llc"?"selected":"").'>ООО</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">Название</label>
									<div>
										<input id="company_name" name="company_name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["company_name"]).'">
									</div>
								</div>

							


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
									<label class="control-label" for="textinput">ИНН</label>
									<div>
										<input id="tin" name="tin" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["tin"]).'">
									</div>
								</div>

							


							<div class="form-group">
								<label class="control-label" for="textinput">Описание</label>
								<div>
									<textarea id="dsc" name="dsc" class="form-control input-md  "  >'.htmlspecialchars($item["dsc"]).'</textarea>
								</div>
							</div>

						


						<div class="form-group">
							<label class="control-label" for="textinput">Активный ли магазин</label>
							<div>
								<input id="active" name="active" class=""  type="checkbox"  value="1" '.($item["active"]==1?"checked":"").'>
							</div>
						</div>

					


								<div class="form-group">
									<label class="control-label" for="textinput">Секретный ключ</label>
									<div>
										<input id="secret_key" name="secret_key" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["secret_key"]).'">
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
			qi("UPDATE `shops` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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
		$type = $_REQUEST['type'];
$company_name = $_REQUEST['company_name'];
$last_name = $_REQUEST['last_name'];
$first_name = $_REQUEST['first_name'];
$middle_name = $_REQUEST['middle_name'];
$tin = $_REQUEST['tin'];
$dsc = $_REQUEST['dsc'];
$active = $_REQUEST['active'];
$secret_key = $_REQUEST['secret_key'];

		$sql = "INSERT INTO shops (`type`, `company_name`, `last_name`, `first_name`, `middle_name`, `tin`, `dsc`, `active`, `secret_key`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
		if(function_exists("processInsertQuery"))
		{
			$sql = processInsertQuery($sql);
		}

		qi($sql, [$type, $company_name, $last_name, $first_name, $middle_name, $tin, $dsc, $active, $secret_key]);
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

			$set[] = is_null($_REQUEST['type'])?"`type`=NULL":"`type`='".addslashes($_REQUEST['type'])."'";
$set[] = is_null($_REQUEST['company_name'])?"`company_name`=NULL":"`company_name`='".addslashes($_REQUEST['company_name'])."'";
$set[] = is_null($_REQUEST['last_name'])?"`last_name`=NULL":"`last_name`='".addslashes($_REQUEST['last_name'])."'";
$set[] = is_null($_REQUEST['first_name'])?"`first_name`=NULL":"`first_name`='".addslashes($_REQUEST['first_name'])."'";
$set[] = is_null($_REQUEST['middle_name'])?"`middle_name`=NULL":"`middle_name`='".addslashes($_REQUEST['middle_name'])."'";
$set[] = is_null($_REQUEST['tin'])?"`tin`=NULL":"`tin`='".addslashes($_REQUEST['tin'])."'";
$set[] = is_null($_REQUEST['dsc'])?"`dsc`=NULL":"`dsc`='".addslashes($_REQUEST['dsc'])."'";
$set[] = is_null($_REQUEST['active'])?"`active`=NULL":"`active`='".addslashes($_REQUEST['active'])."'";
$set[] = is_null($_REQUEST['secret_key'])?"`secret_key`=NULL":"`secret_key`='".addslashes($_REQUEST['secret_key'])."'";

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE shops SET $set WHERE id=?";
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
			qi("DELETE FROM shops WHERE id=?", [$id]);
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

		

		if(isset2($_REQUEST['type_filter']))
		{
			$filters[] = "`type` = '{$_REQUEST['type_filter']}'";
		}
				

if(isset2($_REQUEST['active_filter']))
{
  $filters[] = "`active` = '{$_REQUEST['active_filter']}'";
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
		$type_values = '[
	{
		"text": "ИП",
		"value": "entrepreneur"
	},
	{
		"text": "ООО",
		"value": "llc"
	}
]';
		$type_values_text = "";
		foreach(json_decode($type_values, true) as $opt)
		{
			$type_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
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
				

		$text_option = array_filter(json_decode($type_values, true), function($i)
		{
			return $i['value']==$_REQUEST['type_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['type_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='type_filter' value='{$_REQUEST['type_filter']}'>
					<span class='fa fa-times remove-tag'></span> Тип формы собственности: <b>{$text_option}</b>
			</div>";

			$filter_caption = "Фильтры: ";
		}
				

if(isset2($_REQUEST['active_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='active_filter' value='{$_REQUEST['active_filter']}'>
       <span class='fa fa-times remove-tag'></span> Активный ли магазин: <b>".($_REQUEST['active_filter']?"Вкл":"Выкл")."</b>
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
				$srch = "WHERE ((`id` LIKE '%{$_REQUEST['srch-term']}%') or (`company_name` LIKE '%{$_REQUEST['srch-term']}%') or (`tin` LIKE '%{$_REQUEST['srch-term']}%'))";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.*  FROM shops main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.*  FROM shops main_table) temp $srch $filter $where $order";
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
	echo masterRender("Магазины", $content, 0);

	// HTML каждого пункта меню. Можно добавлять счетчики непрочитанных сообщений и тд
function processMenuItem($html, $item)
{
	return $html;
}

// HTML меню в целом. Можешь внизу меню дорисовать парочку пунктов. Или над меню добавитт инфу о текущем пользователе.
function processMenu($html)
{
	return $html;
}
