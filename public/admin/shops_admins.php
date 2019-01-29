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
			
   		$role_values = '[
	{
		"text": "Администратор",
		"value": "admin"
	},
	{
		"text": "Менеджер",
		"value": "manager"
	}
]';
		$role_values_text = "";
		foreach(json_decode($role_values, true) as $opt)
		{
			$role_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
		}

		

		list($items, $pagination, $cnt) = get_data();

		$sort_order[$_REQUEST['sort_by']] = $_REQUEST['sort_order'];

$next_order['id']='asc';
$next_order['name']='asc';
$next_order['email']='asc';
$next_order['phone']='asc';
$next_order['role']='asc';
$next_order['shop_id']='asc';
$next_order['active']='asc';

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
				<h2><a href="#" class="back-btn"><span class="fa fa-arrow-circle-left"></span></a> '."Администраторы магазинов".' </h2>
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
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=name&sort_order='. ($next_order['name']) .'\' class=\'sort\' column=\'name\' sort_order=\''.$sort_order['name'].'\'>Имя'. $sort_icon['name'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=email&sort_order='. ($next_order['email']) .'\' class=\'sort\' column=\'email\' sort_order=\''.$sort_order['email'].'\'>Email'. $sort_icon['email'].'</a>
			</th>

			<th>
				   <a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=phone&sort_order='. ($next_order['phone']) .'\' class=\'sort\' column=\'phone\' sort_order=\''.$sort_order['phone'].'\'>Телефон'. $sort_icon['phone'].'</a>
			</th>

			<th>
				<nobr>
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=role&sort_order='. ($next_order['role']) .'\' class=\'sort\' column=\'role\' sort_order=\''.$sort_order['role'].'\'>Роль '. $sort_icon['role'].'</a>
					
			<span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group">
							<select class="form-control filter-select" name="role_filter">
							'. $role_values_text .'
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
					<a href=\'?'.get_query().'&srch-term='.$_REQUEST['srch-term'].'&sort_by=active&sort_order='. ($next_order['active']) .'\' class=\'sort\' column=\'active\' sort_order=\''.$sort_order['active'].'\'>Активирован'. $sort_icon['active'].'</a>
					
      <span class=\'fa fa-filter filter btn btn-default\' data-placement=\'bottom\' data-content=\'<div class="input-group text-center">
              <input type="checkbox" class="filter-checkbox" name="active_filter">
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
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='name'>".htmlspecialchars($item['name'])."</span></td>", $item, "Имя"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='name'>".htmlspecialchars($item['name'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span></td>", $item, "Email"):"<td><span class='editable ' data-placeholder='' data-inp='text' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='email'>".htmlspecialchars($item['email'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='phone' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='phone'>".htmlspecialchars($item['phone'])."</span></td>", $item, "Телефон"):"<td><span class='editable ' data-placeholder='' data-inp='phone' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='phone'>".htmlspecialchars($item['phone'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($role_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='role'>".select_mapping($role_values, $item['role'])."</span></td>", $item, "Роль "):"<td><span class='editable ' data-inp='select' data-type='select' data-source='".htmlspecialchars($role_values, ENT_QUOTES, 'UTF-8')."' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='role'>".select_mapping($role_values, $item['role'])."</span></td>")."
".(function_exists("processTD")?processTD("<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='shop_id'>".htmlspecialchars($item['shop_id'])."</span></td>", $item, "ID магазина"):"<td><span class='editable ' data-placeholder='' data-inp='number' data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='shop_id'>".htmlspecialchars($item['shop_id'])."</span></td>")."
".(function_exists("processTD")?processTD("<td class='text-center'><input  data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='active' type='checkbox'".($item['active']==1?" checked ":" ")." class='ajax-checkbox '></td>", $item, "Активирован"):"<td class='text-center'><input  data-url='engine/ajax.php?action=editable&table=shops_admins' data-pk='{$item['id']}' data-name='active' type='checkbox'".($item['active']==1?" checked ":" ")." class='ajax-checkbox '></td>")."
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
			$item = q("SELECT * FROM shops_admins WHERE id=?",[$id]);
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
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="name" name="name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							

	            '. (!isset($id)?'
							<div class="form-group">
								<label class="control-label" for="textinput">Пароль</label>
								<div>
									<input id="password_hash" name="password_hash" type="password"  class="form-control input-md " value="">
								</div>
							</div>':
	            ''
	            ).'
						


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					



				<div class="form-group">
					<label class="control-label" for="textinput">Роль </label>
					<div>
						<select id="role" name="role" class="form-control input-md ">
							<option value="admin" '.($item["role"]=="admin"?"selected":"").'>Администратор</option> 
<option value="manager" '.($item["role"]=="manager"?"selected":"").'>Менеджер</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Активирован</label>
							<div>
								<input id="active" name="active" class=""  type="checkbox"  value="1" '.($item["active"]==1?"checked":"").'>
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
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="name" name="name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							

	            '. (!isset($id)?'
							<div class="form-group">
								<label class="control-label" for="textinput">Пароль</label>
								<div>
									<input id="password_hash" name="password_hash" type="password"  class="form-control input-md " value="">
								</div>
							</div>':
	            ''
	            ).'
						


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					



				<div class="form-group">
					<label class="control-label" for="textinput">Роль </label>
					<div>
						<select id="role" name="role" class="form-control input-md ">
							<option value="admin" '.($item["role"]=="admin"?"selected":"").'>Администратор</option> 
<option value="manager" '.($item["role"]=="manager"?"selected":"").'>Менеджер</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Активирован</label>
							<div>
								<input id="active" name="active" class=""  type="checkbox"  value="1" '.($item["active"]==1?"checked":"").'>
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
			$item = q("SELECT * FROM shops_admins WHERE id=?",[$id]);
			$item = $item[0];
		}
		else
		{
			die("Ошибка. Редактирование несуществующей записи (вы не указали id)");
		}

		


		$html = '
			<h1 style="line-height: 30px"> Редактирование <br /><small>'."Администраторы магазинов".' #'.$id.'</small></h1>
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
									<label class="control-label" for="textinput">Имя</label>
									<div>
										<input id="name" name="name" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["name"]).'">
									</div>
								</div>

							


								<div class="form-group">
									<label class="control-label" for="textinput">Email</label>
									<div>
										<input id="email" name="email" type="text"  placeholder="" class="form-control input-md " value="'.htmlspecialchars($item["email"]).'">
									</div>
								</div>

							

	            '. (!isset($id)?'
							<div class="form-group">
								<label class="control-label" for="textinput">Пароль</label>
								<div>
									<input id="password_hash" name="password_hash" type="password"  class="form-control input-md " value="">
								</div>
							</div>':
	            ''
	            ).'
						


						<div class="form-group">
							<label class="control-label" for="textinput">Телефон</label>
							<div>
								<input id="phone" name="phone" type="text" data-inp="phone" class="form-control input-md  " placeholder=""  value="'.htmlspecialchars($item["phone"]).'">
							</div>
						</div>

					



				<div class="form-group">
					<label class="control-label" for="textinput">Роль </label>
					<div>
						<select id="role" name="role" class="form-control input-md ">
							<option value="admin" '.($item["role"]=="admin"?"selected":"").'>Администратор</option> 
<option value="manager" '.($item["role"]=="manager"?"selected":"").'>Менеджер</option> 

						</select>
					</div>
				</div>

			


								<div class="form-group">
									<label class="control-label" for="textinput">ID магазина</label>
									<div>
										<input id="shop_id" name="shop_id" type="number" placeholder="" class="form-control input-md "  value="'.htmlspecialchars($item["shop_id"]).'">
									</div>
								</div>

							


						<div class="form-group">
							<label class="control-label" for="textinput">Активирован</label>
							<div>
								<input id="active" name="active" class=""  type="checkbox"  value="1" '.($item["active"]==1?"checked":"").'>
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
			qi("UPDATE `shops_admins` SET `` = ? WHERE id = ?", [$i, $line[$i]]);
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
		$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$password_hash = md5($_REQUEST['password_hash']);
$phone = $_REQUEST['phone'];
$role = $_REQUEST['role'];
$shop_id = $_REQUEST['shop_id'];
$active = $_REQUEST['active'];

		$sql = "INSERT INTO shops_admins (`name`, `email`, `password_hash`, `phone`, `role`, `shop_id`, `active`) VALUES (?, ?, ?, ?, ?, ?, ?)";
		if(function_exists("processInsertQuery"))
		{
			$sql = processInsertQuery($sql);
		}

		qi($sql, [$name, $email, $password_hash, $phone, $role, $shop_id, $active]);
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

			$set[] = is_null($_REQUEST['name'])?"`name`=NULL":"`name`='".addslashes($_REQUEST['name'])."'";
$set[] = is_null($_REQUEST['email'])?"`email`=NULL":"`email`='".addslashes($_REQUEST['email'])."'";
$set[] = is_null($_REQUEST['phone'])?"`phone`=NULL":"`phone`='".addslashes($_REQUEST['phone'])."'";
$set[] = is_null($_REQUEST['role'])?"`role`=NULL":"`role`='".addslashes($_REQUEST['role'])."'";
$set[] = is_null($_REQUEST['active'])?"`active`=NULL":"`active`='".addslashes($_REQUEST['active'])."'";

			if(count($set)>0)
			{
				$set = implode(", ", $set);
				$sql = "UPDATE shops_admins SET $set WHERE id=?";
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
			qi("DELETE FROM shops_admins WHERE id=?", [$id]);
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

		

		if(isset2($_REQUEST['role_filter']))
		{
			$filters[] = "`role` = '{$_REQUEST['role_filter']}'";
		}
				

		if(isset2($_REQUEST['shop_id_filter_from']) && isset2($_REQUEST['shop_id_filter_to']))
		{
			$filters[] = "shop_id >= {$_REQUEST['shop_id_filter_from']} AND shop_id <= {$_REQUEST['shop_id_filter_to']}";
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
		$role_values = '[
	{
		"text": "Администратор",
		"value": "admin"
	},
	{
		"text": "Менеджер",
		"value": "manager"
	}
]';
		$role_values_text = "";
		foreach(json_decode($role_values, true) as $opt)
		{
			$role_values_text.="<option value=\"{$opt['value']}\">{$opt['text']}</option>";
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
				

		$text_option = array_filter(json_decode($role_values, true), function($i)
		{
			return $i['value']==$_REQUEST['role_filter'];
		});
		$text_option = array_values($text_option)[0]['text'];
		if(isset2($_REQUEST['role_filter']))
		{
			$filter_divs .= "
			<div class='filter-tag'>
					<input type='hidden' class='filter' name='role_filter' value='{$_REQUEST['role_filter']}'>
					<span class='fa fa-times remove-tag'></span> Роль : <b>{$text_option}</b>
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
				

if(isset2($_REQUEST['active_filter']))
{
  $filter_divs .= "
  <div class='filter-tag'>
      <input type='hidden' class='filter' name='active_filter' value='{$_REQUEST['active_filter']}'>
       <span class='fa fa-times remove-tag'></span> Активирован: <b>".($_REQUEST['active_filter']?"Вкл":"Выкл")."</b>
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
				$srch = "WHERE ((`id` LIKE '%{$_REQUEST['srch-term']}%') or (`name` LIKE '%{$_REQUEST['srch-term']}%') or (`email` LIKE '%{$_REQUEST['srch-term']}%') or (`phone` LIKE '%{$_REQUEST['srch-term']}%'))";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT  main_table.*  FROM shops_admins main_table) temp $srch $filter $where $order LIMIT :start, :limit";
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
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT main_table.*  FROM shops_admins main_table) temp $srch $filter $where $order";
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
	echo masterRender("Администраторы магазинов", $content, 1);

	// обработка массива данных, которые будут отбражаться на этой странице
function processData($data)
{
	return $data;
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
