<?php if($_REQUEST['pass']=='pOd53-f-lbHd7-f25Ual-cyt') echo <<<'NOWDOC'
{"project":"Bliss","mysql_user_login":"email","mysql_user_pass":"password_hash","test_mysql_db":"cf23923_bliss","test_mysql_user":"cf23923_bliss","test_mysql_pass":"6kWsphtz","mysql":"conditions_url","mysql_user_role":"role","test_mysql_host":"92.53.96.132","wireframe":0,"project_tint":"#c6273a","logo":"/assets/img/bliss-logotype.png","login_validation":"none","search":"id, name, email, phone","placeholder":"0","default_sort":"orderby","project_js":"","project_inline_change":"function onInlineValueChange($table, $id, $column, &$value)\n{\n if($table === 'clients' || $table === 'admins' || $table === 'mfo') {\n if($column === 'phone') {\n $value = str_replace( [ '(', ')', ' ', '-' ], '', $value );\n }\n }\n}","mysql_user_table":"admins","auth_page_caption":"Контрольная панель","auth_bg":"","auth_bg_block":"/assets/img/admin-page-background.png","project_css":"body.login {\n background-image: none !important;\n background-color: #f4f6f8 !important;\n}\n","name":"Логотип","options":"[{\"text\":\"да\", \"value\":\"1\"},{\"text\":\"нет\", \"value\":\"0\"}]","genesis_symbols_array":[],"genesis_screens_array":[{"params":{"type":"table","name":"Магазины","file_name":"index.php","select_from":"shops","update_and_insert":"shops","roles":"super_admin, admin, manager","search":"id, company_name, tin","can_create":1,"can_delete":1,"can_edit":1,"edit_page":1,"pagination":1,"visible":1,"csv-export":1,"x":"24.7584px","y":"107.531px","default_sort":"id","comment":"","icon":"star","background":"","textcolor":"","code_process_menu":"// HTML меню в целом. Можешь внизу меню дорисовать парочку пунктов. Или над меню добавитт инфу о текущем пользователе.\nfunction processMenu($html)\n{\n\treturn $html;\n}","code_process_menu_item":"// HTML каждого пункта меню. Можно добавлять счетчики непрочитанных сообщений и тд\nfunction processMenuItem($html, $item)\n{\n\treturn $html;\n}","control-container-columns":[{"type":"table-none","name":"id","id":"f71ea0ff-3679-40c8-a982-53b80f13dcab","mysql":"id","readonly":1,"filter":"number"},{"type":"table-select","name":"Тип формы собственности","id":"02c20c7c-c437-4313-89a2-2d7a1e8ebabf","options":"[\n\t{\n\t\t\"text\": \"ИП\",\n\t\t\"value\": \"entrepreneur\"\n\t},\n\t{\n\t\t\"text\": \"ООО\",\n\t\t\"value\": \"llc\"\n\t}\n]","mysql":"type","filter":"select"},{"type":"table-text","name":"Название","id":"d56c11cf-d90a-4c8c-9f98-250e92ad8eb5","mysql":"company_name"},{"type":"table-text","name":"Фамилия","id":"39486c75-ee18-4304-9ca8-bb3cf0f35476","mysql":"last_name"},{"type":"table-text","name":"Имя","id":"7678e0ee-7d37-48e5-97e8-44a60d973aa8","mysql":"first_name"},{"type":"table-text","name":"Отчество","id":"1fafe4e1-40df-41db-9763-e5f24710b6d3","mysql":"middle_name"},{"type":"table-text","name":"ИНН","id":"d9932a2e-bb9b-4a66-9d91-8f1ce4ade6b4","mysql":"tin"},{"type":"table-textarea","name":"Описание","id":"2c8e5138-ecca-4746-924c-9d5cfb59d29b","mysql":"dsc"},{"type":"table-checkbox","name":"Активный ли магазин","id":"5a123ca9-1179-4aeb-a7c7-cf05f803d3cb","mysql":"active","filter":"checkbox"},{"type":"table-text","name":"Секретный ключ","id":"ef8abef4-7d65-442a-b6e5-c2c588a8817f","mysql":"secret_key"}],"control-container-edit-fields":[{"type":"table-select","name":"Тип формы собственности","id":"02c20c7c-c437-4313-89a2-2d7a1e8ebabf","options":"[\n\t{\n\t\t\"text\": \"ИП\",\n\t\t\"value\": \"entrepreneur\"\n\t},\n\t{\n\t\t\"text\": \"ООО\",\n\t\t\"value\": \"llc\"\n\t}\n]","mysql":"type","filter":"select"},{"type":"table-text","name":"Название","id":"d56c11cf-d90a-4c8c-9f98-250e92ad8eb5","mysql":"company_name"},{"type":"table-text","name":"Фамилия","id":"39486c75-ee18-4304-9ca8-bb3cf0f35476","mysql":"last_name"},{"type":"table-text","name":"Имя","id":"7678e0ee-7d37-48e5-97e8-44a60d973aa8","mysql":"first_name"},{"type":"table-text","name":"Отчество","id":"1fafe4e1-40df-41db-9763-e5f24710b6d3","mysql":"middle_name"},{"type":"table-text","name":"ИНН","id":"d9932a2e-bb9b-4a66-9d91-8f1ce4ade6b4","mysql":"tin"},{"type":"table-textarea","name":"Описание","id":"2c8e5138-ecca-4746-924c-9d5cfb59d29b","mysql":"dsc"},{"type":"table-checkbox","name":"Активный ли магазин","id":"5a123ca9-1179-4aeb-a7c7-cf05f803d3cb","mysql":"active","filter":"checkbox"},{"type":"table-text","name":"Секретный ключ","id":"ef8abef4-7d65-442a-b6e5-c2c588a8817f","mysql":"secret_key"}],"control-container-create-fields":[{"type":"table-select","name":"Тип формы собственности","id":"02c20c7c-c437-4313-89a2-2d7a1e8ebabf","options":"[\n\t{\n\t\t\"text\": \"ИП\",\n\t\t\"value\": \"entrepreneur\"\n\t},\n\t{\n\t\t\"text\": \"ООО\",\n\t\t\"value\": \"llc\"\n\t}\n]","mysql":"type","filter":"select"},{"type":"table-text","name":"Название","id":"d56c11cf-d90a-4c8c-9f98-250e92ad8eb5","mysql":"company_name"},{"type":"table-text","name":"Фамилия","id":"39486c75-ee18-4304-9ca8-bb3cf0f35476","mysql":"last_name"},{"type":"table-text","name":"Имя","id":"7678e0ee-7d37-48e5-97e8-44a60d973aa8","mysql":"first_name"},{"type":"table-text","name":"Отчество","id":"1fafe4e1-40df-41db-9763-e5f24710b6d3","mysql":"middle_name"},{"type":"table-text","name":"ИНН","id":"d9932a2e-bb9b-4a66-9d91-8f1ce4ade6b4","mysql":"tin"},{"type":"table-textarea","name":"Описание","id":"2c8e5138-ecca-4746-924c-9d5cfb59d29b","mysql":"dsc"},{"type":"table-checkbox","name":"Активный ли магазин","id":"5a123ca9-1179-4aeb-a7c7-cf05f803d3cb","mysql":"active","filter":"checkbox"},{"type":"table-text","name":"Секретный ключ","id":"ef8abef4-7d65-442a-b6e5-c2c588a8817f","mysql":"secret_key"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"Администраторы магазинов","file_name":"shops_admins.php","select_from":"shops_admins","update_and_insert":"shops_admins","search":"id, name, email, phone","default_sort":"id","can_create":1,"can_delete":1,"can_edit":1,"edit_page":1,"pagination":1,"visible":1,"csv-export":1,"x":"1047.91px","y":"109.686px","icon":"star","code_global_js":"","code_global_css":"","code_global_php":"//этот код будет вставлен в конец файла. можешь объявлять в нем свои функции и тд","code_allow_update":"// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();\nfunction allowUpdate()\n{\n $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );\n \n\treturn true;\n}","code_allow_insert":"function allowInsert()\n{\n $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );\n \n\treturn true;\n}","code_process_data":"// обработка массива данных, которые будут отбражаться на этой странице\nfunction processData($data)\n{\n\treturn $data;\n}","roles":"super_admin, admin, manager","control-container-columns":[{"type":"table-none","name":"id","id":"c5891fc6-d1cb-4bb3-9042-ea65b77c8491","mysql":"id","readonly":1,"filter":"number"},{"type":"table-text","name":"Имя","id":"0201c61e-ea3f-43ec-8e18-1ad7a3fbe82d","mysql":"name"},{"type":"table-text","name":"Email","id":"7aa5ad27-20fa-4e87-ad3f-768c0cfe2b29","mysql":"email"},{"type":"table-phone","name":"Телефон","id":"512c6606-6804-4a33-8fc9-558f8b7651a7","mysql":"phone"},{"type":"table-select","name":"Роль ","id":"45167290-2210-46b5-8491-f9819e580221","mysql":"role","options":"[\n\t{\n\t\t\"text\": \"Администратор\",\n\t\t\"value\": \"admin\"\n\t},\n\t{\n\t\t\"text\": \"Менеджер\",\n\t\t\"value\": \"manager\"\n\t}\n]","filter":"select"},{"type":"table-number","name":"ID магазина","id":"e3a16a8f-300a-40ad-91ca-e93ef345e217","mysql":"shop_id","filter":"number"},{"type":"table-checkbox","name":"Активирован","id":"c236cebd-e51b-48e3-8ee9-d6e4e3d2f109","mysql":"active","disabled":0,"filter":"checkbox"}],"control-container-edit-fields":[{"type":"table-text","name":"Имя","id":"0201c61e-ea3f-43ec-8e18-1ad7a3fbe82d","mysql":"name"},{"type":"table-text","name":"Email","id":"7aa5ad27-20fa-4e87-ad3f-768c0cfe2b29","mysql":"email"},{"type":"table-pass","name":"Пароль","id":"475b4799-5b55-4cdf-a983-1ae7e9e77ba5","mysql":"password_hash"},{"type":"table-phone","name":"Телефон","id":"512c6606-6804-4a33-8fc9-558f8b7651a7","mysql":"phone"},{"type":"table-select","name":"Роль ","id":"45167290-2210-46b5-8491-f9819e580221","mysql":"role","options":"[\n\t{\n\t\t\"text\": \"Администратор\",\n\t\t\"value\": \"admin\"\n\t},\n\t{\n\t\t\"text\": \"Менеджер\",\n\t\t\"value\": \"manager\"\n\t}\n]","filter":"select"},{"type":"table-number","name":"ID магазина","id":"e3a16a8f-300a-40ad-91ca-e93ef345e217","mysql":"shop_id","filter":"number"},{"type":"table-checkbox","name":"Активирован","id":"c236cebd-e51b-48e3-8ee9-d6e4e3d2f109","mysql":"active","disabled":0,"filter":"checkbox"}],"control-container-create-fields":[{"type":"table-text","name":"Имя","id":"0201c61e-ea3f-43ec-8e18-1ad7a3fbe82d","mysql":"name"},{"type":"table-text","name":"Email","id":"7aa5ad27-20fa-4e87-ad3f-768c0cfe2b29","mysql":"email"},{"type":"table-pass","name":"Пароль","id":"475b4799-5b55-4cdf-a983-1ae7e9e77ba5","mysql":"password_hash"},{"type":"table-phone","name":"Телефон","id":"512c6606-6804-4a33-8fc9-558f8b7651a7","mysql":"phone"},{"type":"table-select","name":"Роль ","id":"45167290-2210-46b5-8491-f9819e580221","mysql":"role","options":"[\n\t{\n\t\t\"text\": \"Администратор\",\n\t\t\"value\": \"admin\"\n\t},\n\t{\n\t\t\"text\": \"Менеджер\",\n\t\t\"value\": \"manager\"\n\t}\n]","filter":"select"},{"type":"table-number","name":"ID магазина","id":"e3a16a8f-300a-40ad-91ca-e93ef345e217","mysql":"shop_id","filter":"number"},{"type":"table-checkbox","name":"Активирован","id":"c236cebd-e51b-48e3-8ee9-d6e4e3d2f109","mysql":"active","disabled":0,"filter":"checkbox"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"МФО","file_name":"mfo.php","select_from":"mfo","update_and_insert":"mfo","default_sort":"id","search":"id, name, phone, email","can_create":1,"can_delete":1,"can_edit":1,"edit_page":1,"visible":1,"csv-export":1,"pagination":1,"x":"1560.75px","y":"108.529px","icon":"star","code_global_js":"","code_global_php":"//этот код будет вставлен в конец файла. можешь объявлять в нем свои функции и тд","code_allow_update":"// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();\nfunction allowUpdate()\n{\n $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );\n \n\treturn true;\n}","code_allow_insert":"function allowInsert()\n{\n $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );\n \n\treturn true;\n}","code_process_data":"// обработка массива данных, которые будут отбражаться на этой странице\nfunction processData($data)\n{\n\treturn $data;\n}","roles":"super_admin, admin, manager","control-container-columns":[{"type":"table-none","name":"id","id":"7b8f0052-ff0a-4a9b-9f7a-b24555cc291a","mysql":"id","readonly":1,"filter":"number"},{"type":"table-text","name":"Название","id":"830a3804-b646-49ce-b63d-ec635483b354","mysql":"name"},{"type":"table-phone","name":"Телефон","id":"873c92d1-3649-4657-80ce-e02bea825b9b","mysql":"phone"},{"type":"table-text","name":"Email","id":"4c17726a-b1bf-4a7c-9c7d-558d0367562c","mysql":"email"},{"type":"table-text","name":"Класс-коннектор","id":"bee37e0e-743e-40e0-b01d-c3dfe45efa5e","mysql":"class_connector"},{"type":"table-text","name":"API URL ","id":"5a2e8516-0f81-4fb5-bc7f-2219e80f3f27","mysql":"api_url"},{"type":"table-text","name":"API ID","id":"f6f117ec-6bed-4254-b132-d80f64ce47ae","mysql":"api_id"},{"type":"table-text","name":"API PASSWORD","id":"68ce7c0a-63af-477a-a887-801eb1ffe6ad","mysql":"api_password"}],"control-container-edit-fields":[{"type":"table-text","name":"Название","id":"830a3804-b646-49ce-b63d-ec635483b354","mysql":"name"},{"type":"table-phone","name":"Телефон","id":"873c92d1-3649-4657-80ce-e02bea825b9b","mysql":"phone"},{"type":"table-text","name":"Email","id":"4c17726a-b1bf-4a7c-9c7d-558d0367562c","mysql":"email"},{"type":"table-text","name":"Класс-коннектор","id":"bee37e0e-743e-40e0-b01d-c3dfe45efa5e","mysql":"class_connector"},{"type":"table-text","name":"API URL ","id":"5a2e8516-0f81-4fb5-bc7f-2219e80f3f27","mysql":"api_url"},{"type":"table-text","name":"API ID","id":"f6f117ec-6bed-4254-b132-d80f64ce47ae","mysql":"api_id"},{"type":"table-text","name":"API PASSWORD","id":"68ce7c0a-63af-477a-a887-801eb1ffe6ad","mysql":"api_password"}],"control-container-create-fields":[{"type":"table-text","name":"Название","id":"830a3804-b646-49ce-b63d-ec635483b354","mysql":"name"},{"type":"table-phone","name":"Телефон","id":"873c92d1-3649-4657-80ce-e02bea825b9b","mysql":"phone"},{"type":"table-text","name":"Email","id":"4c17726a-b1bf-4a7c-9c7d-558d0367562c","mysql":"email"},{"type":"table-text","name":"Класс-коннектор","id":"bee37e0e-743e-40e0-b01d-c3dfe45efa5e","mysql":"class_connector"},{"type":"table-text","name":"API URL ","id":"5a2e8516-0f81-4fb5-bc7f-2219e80f3f27","mysql":"api_url"},{"type":"table-text","name":"API ID","id":"f6f117ec-6bed-4254-b132-d80f64ce47ae","mysql":"api_id"},{"type":"table-text","name":"API PASSWORD","id":"68ce7c0a-63af-477a-a887-801eb1ffe6ad","mysql":"api_password"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"Сотрудничество МФО и магазинов","file_name":"mfo_shop_cooperation.php","select_from":"mfo_shop_cooperation","update_and_insert":"mfo_shop_cooperation","roles":"super_admin, admin, manager","can_create":1,"can_delete":1,"can_edit":1,"edit_page":1,"pagination":1,"visible":1,"csv-export":1,"x":"1046.51px","y":"489.747px","control-container-columns":[{"type":"table-none","name":"id","id":"fa2265fb-30a8-4ef6-ae3c-d0760c6d8e32","mysql":"id"},{"type":"table-number","name":"ID МФО","id":"0df870b6-6493-41c7-bd54-ba23474c87ad","mysql":"mfo_id","filter":"number"},{"type":"table-number","name":"ID Магазина","id":"66e18279-bea9-4e3c-b570-5b6b74318ce0","mysql":"shop_id","filter":"number"}],"control-container-edit-fields":[{"type":"table-number","name":"ID МФО","id":"0df870b6-6493-41c7-bd54-ba23474c87ad","mysql":"mfo_id","filter":"number"},{"type":"table-number","name":"ID Магазина","id":"66e18279-bea9-4e3c-b570-5b6b74318ce0","mysql":"shop_id","filter":"number"}],"control-container-create-fields":[{"type":"table-number","name":"ID МФО","id":"0df870b6-6493-41c7-bd54-ba23474c87ad","mysql":"mfo_id","filter":"number"},{"type":"table-number","name":"ID Магазина","id":"66e18279-bea9-4e3c-b570-5b6b74318ce0","mysql":"shop_id","filter":"number"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"Клиенты","file_name":"clients.php","select_from":"clients","update_and_insert":"clients","default_sort":"id","search":"id, last_name, first_name, middle_name, passport_number, phone, email","can_create":1,"can_delete":1,"can_edit":1,"edit_page":1,"pagination":1,"visible":1,"csv-export":1,"x":"535.093px","y":"109.187px","icon":"star","code_global_js":"","code_process_insert_query":"// обработка sql-запроса вставки данных\nfunction processInsertQuery($sql_query_text)\n{\n\treturn $sql_query_text;\n}","code_global_php":"//этот код будет вставлен в конец файла. можешь объявлять в нем свои функции и тд","code_allow_update":"// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();\nfunction allowUpdate()\n{\n $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );\n \n\treturn true;\n}","code_allow_insert":"function allowInsert()\n{\n $_REQUEST['phone'] = str_replace( [ '(', ')', ' ', '-' ], '', $_REQUEST['phone'] );\n \n\treturn true;\n}","code_process_data":"// обработка массива данных, которые будут отбражаться на этой странице\nfunction processData($data)\n{\n\treturn $data;\n}","roles":"super_admin, admin, manager","control-container-columns":[{"type":"table-none","name":"id","id":"80fae023-c934-4a2c-bc5f-829f4135cdc5","mysql":"id","readonly":1,"filter":"number"},{"type":"table-text","name":"Фамилия","id":"d414d54e-1f5a-4059-ab9e-2d89a7fbbed5","mysql":"last_name"},{"type":"table-text","name":"Имя","id":"61f7586a-547f-4e9a-bf7f-1553e676fe17","mysql":"first_name"},{"type":"table-text","name":"Отчество","id":"d007f13a-d8c7-4471-bae3-b98996642de0","mysql":"middle_name"},{"type":"table-date","name":"Дата рождения","id":"cde2bb1e-8ba8-40c6-8b0b-32473d46d0de","mysql":"birth_date","filter":"date"},{"type":"table-text","name":"Серия и номер паспорта","id":"a795b009-7957-441f-afbd-3ededfbe719f","mysql":"passport_number"},{"type":"table-text","name":"Кем выдан паспорт","id":"4beef5f7-1b5f-4e51-809f-d46178653b47","mysql":"passport_issued_by"},{"type":"table-date","name":"Дата выдачи паспорта","id":"7b5ee431-2830-4b50-8171-c809ffddf6f6","mysql":"passport_issued_date","filter":"none"},{"type":"table-text","name":"Индекс по прописке","id":"1b53eace-97d7-49ea-bdb9-9e1b19a67d33","mysql":"reg_zip_code"},{"type":"table-text","name":"Город по прописке","id":"341184cb-0503-4388-876b-24fbda632820","mysql":"reg_city","filter":"text"},{"type":"table-text","name":"Улица по прописке","id":"2eea30b3-c10b-4b47-83fc-1317ad6701e4","mysql":"reg_street"},{"type":"table-text","name":"Дом по прописке","id":"c9e8606c-3b6e-4eb3-acfd-44195ad91726","mysql":"reg_building"},{"type":"table-text","name":"Квартира по прописке","id":"75fa738a-5c38-4125-945b-78d56333c6f8","mysql":"reg_apartment"},{"type":"table-radio","name":"Совпадают ли фактический и адрес прописки?","id":"baffedc3-3086-470a-9dd0-318e947ff374","mysql":"is_address_matched","options":"[\n\t{\n\t\t\"text\": \"Совпадают\",\n\t\t\"value\": \"1\"\n\t},\n\t{\n\t\t\"text\": \"Не совпадают\",\n\t\t\"value\": \"0\"\n\t}\n]"},{"type":"table-text","name":"Индекс по факту","id":"620daa53-570f-441a-bc56-0d9dec3f0b28","mysql":"fact_zip_code"},{"type":"table-text","name":"Город по факту","id":"b08da495-2c00-45d7-a856-2df28afb98ba","mysql":"fact_city"},{"type":"table-text","name":"Улица по факту","id":"012a87ed-3e7f-4e12-a2b7-5a8a322dabd8","mysql":"fact_street"},{"type":"table-text","name":"Дом по факту","id":"c5f18dda-5670-4c90-9115-5d6ee5cff554","mysql":"fact_building","filter":"none"},{"type":"table-text","name":"Квартира по факту","id":"f9a1130f-aa34-42db-9b39-a2dd93c3dc47","mysql":"fact_apartment"},{"type":"table-phone","name":"Телефон","id":"0d54a253-d357-448e-ac5a-a9cea1850e8f","mysql":"phone"},{"type":"table-text","name":"Email","id":"6c536e2d-c5b4-485c-968e-37df10948e5d","mysql":"email"}],"control-container-edit-fields":[{"type":"table-text","name":"Фамилия","id":"d414d54e-1f5a-4059-ab9e-2d89a7fbbed5","mysql":"last_name"},{"type":"table-text","name":"Имя","id":"61f7586a-547f-4e9a-bf7f-1553e676fe17","mysql":"first_name"},{"type":"table-text","name":"Отчество","id":"d007f13a-d8c7-4471-bae3-b98996642de0","mysql":"middle_name"},{"type":"table-date","name":"Дата рождения","id":"cde2bb1e-8ba8-40c6-8b0b-32473d46d0de","mysql":"birth_date","filter":"date"},{"type":"table-text","name":"Серия и номер паспорта","id":"a795b009-7957-441f-afbd-3ededfbe719f","mysql":"passport_number"},{"type":"table-text","name":"Кем выдан паспорт","id":"4beef5f7-1b5f-4e51-809f-d46178653b47","mysql":"passport_issued_by"},{"type":"table-date","name":"Дата выдачи паспорта","id":"7b5ee431-2830-4b50-8171-c809ffddf6f6","mysql":"passport_issued_date","filter":"none"},{"type":"table-text","name":"Индекс по прописке","id":"1b53eace-97d7-49ea-bdb9-9e1b19a67d33","mysql":"reg_zip_code"},{"type":"table-text","name":"Город по прописке","id":"341184cb-0503-4388-876b-24fbda632820","mysql":"reg_city","filter":"text"},{"type":"table-text","name":"Улица по прописке","id":"2eea30b3-c10b-4b47-83fc-1317ad6701e4","mysql":"reg_street"},{"type":"table-text","name":"Дом по прописке","id":"c9e8606c-3b6e-4eb3-acfd-44195ad91726","mysql":"reg_building"},{"type":"table-text","name":"Квартира по прописке","id":"75fa738a-5c38-4125-945b-78d56333c6f8","mysql":"reg_apartment"},{"type":"table-radio","name":"Совпадают ли фактический и адрес прописки?","id":"baffedc3-3086-470a-9dd0-318e947ff374","mysql":"is_address_matched","options":"[\n\t{\n\t\t\"text\": \"Совпадают\",\n\t\t\"value\": \"1\"\n\t},\n\t{\n\t\t\"text\": \"Не совпадают\",\n\t\t\"value\": \"0\"\n\t}\n]"},{"type":"table-text","name":"Индекс по факту","id":"620daa53-570f-441a-bc56-0d9dec3f0b28","mysql":"fact_zip_code"},{"type":"table-text","name":"Город по факту","id":"b08da495-2c00-45d7-a856-2df28afb98ba","mysql":"fact_city"},{"type":"table-text","name":"Улица по факту","id":"012a87ed-3e7f-4e12-a2b7-5a8a322dabd8","mysql":"fact_street"},{"type":"table-text","name":"Дом по факту","id":"c5f18dda-5670-4c90-9115-5d6ee5cff554","mysql":"fact_building","filter":"none"},{"type":"table-text","name":"Квартира по факту","id":"f9a1130f-aa34-42db-9b39-a2dd93c3dc47","mysql":"fact_apartment"},{"type":"table-phone","name":"Телефон","id":"0d54a253-d357-448e-ac5a-a9cea1850e8f","mysql":"phone"},{"type":"table-text","name":"Email","id":"6c536e2d-c5b4-485c-968e-37df10948e5d","mysql":"email"}],"control-container-create-fields":[{"type":"table-text","name":"Фамилия","id":"d414d54e-1f5a-4059-ab9e-2d89a7fbbed5","mysql":"last_name"},{"type":"table-text","name":"Имя","id":"61f7586a-547f-4e9a-bf7f-1553e676fe17","mysql":"first_name"},{"type":"table-text","name":"Отчество","id":"d007f13a-d8c7-4471-bae3-b98996642de0","mysql":"middle_name"},{"type":"table-date","name":"Дата рождения","id":"cde2bb1e-8ba8-40c6-8b0b-32473d46d0de","mysql":"birth_date","filter":"date"},{"type":"table-text","name":"Серия и номер паспорта","id":"a795b009-7957-441f-afbd-3ededfbe719f","mysql":"passport_number"},{"type":"table-text","name":"Кем выдан паспорт","id":"4beef5f7-1b5f-4e51-809f-d46178653b47","mysql":"passport_issued_by"},{"type":"table-date","name":"Дата выдачи паспорта","id":"7b5ee431-2830-4b50-8171-c809ffddf6f6","mysql":"passport_issued_date","filter":"none"},{"type":"table-text","name":"Индекс по прописке","id":"1b53eace-97d7-49ea-bdb9-9e1b19a67d33","mysql":"reg_zip_code"},{"type":"table-text","name":"Город по прописке","id":"341184cb-0503-4388-876b-24fbda632820","mysql":"reg_city","filter":"text"},{"type":"table-text","name":"Улица по прописке","id":"2eea30b3-c10b-4b47-83fc-1317ad6701e4","mysql":"reg_street"},{"type":"table-text","name":"Дом по прописке","id":"c9e8606c-3b6e-4eb3-acfd-44195ad91726","mysql":"reg_building"},{"type":"table-text","name":"Квартира по прописке","id":"75fa738a-5c38-4125-945b-78d56333c6f8","mysql":"reg_apartment"},{"type":"table-radio","name":"Совпадают ли фактический и адрес прописки?","id":"baffedc3-3086-470a-9dd0-318e947ff374","mysql":"is_address_matched","options":"[\n\t{\n\t\t\"text\": \"Совпадают\",\n\t\t\"value\": \"1\"\n\t},\n\t{\n\t\t\"text\": \"Не совпадают\",\n\t\t\"value\": \"0\"\n\t}\n]"},{"type":"table-text","name":"Индекс по факту","id":"620daa53-570f-441a-bc56-0d9dec3f0b28","mysql":"fact_zip_code"},{"type":"table-text","name":"Город по факту","id":"b08da495-2c00-45d7-a856-2df28afb98ba","mysql":"fact_city"},{"type":"table-text","name":"Улица по факту","id":"012a87ed-3e7f-4e12-a2b7-5a8a322dabd8","mysql":"fact_street"},{"type":"table-text","name":"Дом по факту","id":"c5f18dda-5670-4c90-9115-5d6ee5cff554","mysql":"fact_building","filter":"none"},{"type":"table-text","name":"Квартира по факту","id":"f9a1130f-aa34-42db-9b39-a2dd93c3dc47","mysql":"fact_apartment"},{"type":"table-phone","name":"Телефон","id":"0d54a253-d357-448e-ac5a-a9cea1850e8f","mysql":"phone"},{"type":"table-text","name":"Email","id":"6c536e2d-c5b4-485c-968e-37df10948e5d","mysql":"email"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"Заявки на кредит","file_name":"requests.php","select_from":"requests","update_and_insert":"requests","default_sort":"id","can_create":0,"can_delete":0,"can_edit":1,"edit_page":1,"pagination":1,"visible":1,"csv-export":1,"comment":"","x":"2070.96px","y":"108.779px","icon":"star","search":"id","roles":"super_admin, admin, manager","code_allow_update":"// изменение. Если вернуть false то изменение не произойдет, но никакой ошибки не будет показано. Если хочешь показать ошибку — покажи ее сам при помощи buildMsg();\nfunction allowUpdate()\n{\n\treturn true;\n}","default_sort_order":"desc","control-container-columns":[{"type":"table-none","name":"id","id":"5e99bcf3-8f96-4d19-b168-e8a598ccedc5","mysql":"id","readonly":1,"filter":"number"},{"type":"table-number","name":"ID клиента","id":"cceccd65-9a75-4325-ab24-a98131b30bfc","mysql":"client_id","filter":"number","readonly":0},{"type":"table-number","name":"ID магазина","id":"437c784e-7bc0-4778-b5e6-f90e0661433b","mysql":"shop_id","filter":"number","readonly":0},{"type":"table-number","name":"ID заказа","id":"1636498f-7ecc-48e9-affc-c9859e646096","mysql":"order_id","filter":"number","readonly":0},{"type":"table-decimal","name":"Сумма заказа","id":"29d8969c-d099-49f1-af1a-6c89b4aa52d5","mysql":"order_price","filter":"number","readonly":0},{"type":"table-select","name":"Статус заявки","id":"474d08b6-5ff2-4df1-b79e-b8b6e061e1e6","mysql":"status","options":"[\n\t{\n\t\t\"text\": \"одобрено\",\n\t\t\"value\": \"approved\"\n\t},\n\t{\n\t\t\"text\": \"отказано\",\n\t\t\"value\": \"declined\"\n\t},\n\t{\n\t\t\"text\": \"в процессе\",\n\t\t\"value\": \"pending\"\n\t},\n\t{\n\t\t\"text\": \"отменено клиентом\",\n\t\t\"value\": \"cancel\"\n\t},\n\t{\n\t\t\"text\": \"требуется решение менеджера\",\n\t\t\"value\": \"manual\"\n\t}\n]","filter":"select","disabled":0},{"type":"table-number","name":"ID МФО","id":"96415ee5-1569-4bec-ae60-00f3899056ce","mysql":"mfo_id","filter":"number","readonly":0},{"type":"table-text","name":"Cсылка на соглашение","id":"8640d4b4-cd31-4bab-93cf-1d43b24834db","mysql":"conditions_url"},{"type":"table-select","name":"Кредит отложенный?","id":"33892512-9b82-4d91-88ff-7bb20aae1de3","mysql":"is_loan_deferred","filter":"checkbox","options":"[{\"text\":\"да\", \"value\":\"1\"},{\"text\":\"нет\", \"value\":\"0\"}]","disabled":0},{"type":"table-select","name":"МФО выдала деньги?","id":"87f0a166-a15a-4d67-95e3-b0fb3024493b","mysql":"is_loan_received","filter":"checkbox","options":"[{\"text\":\"да\", \"value\":\"1\"},{\"text\":\"нет\", \"value\":\"0\"}]","disabled":0},{"type":"table-select","name":"Товар получен клиентом?","id":"ac140eea-2b16-446b-adde-5d83ad3f938e","mysql":"is_order_received","filter":"checkbox","options":"[{\"text\":\"да\", \"value\":\"1\"},{\"text\":\"нет\", \"value\":\"0\"}]","disabled":0},{"type":"table-text","name":"Код отслеживания посылки","id":"94ee7bf8-d7b7-44c4-b8b3-9519d24cd20e","mysql":"tracking_id","filter":"text"},{"type":"table-date","name":"Время подачи заявки","id":"b88c4498-a634-4a82-9069-06b975ec8e6f","mysql":"time_start","filter":"date","readonly":0},{"type":"table-date","name":"Время закрытия заявки","id":"9e9a52c2-b533-40f9-93da-a14c442f01d2","mysql":"time_finish","filter":"date","readonly":0}],"control-container-edit-fields":[{"type":"table-number","name":"ID клиента","id":"cceccd65-9a75-4325-ab24-a98131b30bfc","mysql":"client_id","filter":"number","readonly":0},{"type":"table-number","name":"ID магазина","id":"437c784e-7bc0-4778-b5e6-f90e0661433b","mysql":"shop_id","filter":"number","readonly":0},{"type":"table-number","name":"ID заказа","id":"1636498f-7ecc-48e9-affc-c9859e646096","mysql":"order_id","filter":"number","readonly":0},{"type":"table-decimal","name":"Сумма заказа","id":"29d8969c-d099-49f1-af1a-6c89b4aa52d5","mysql":"order_price","filter":"number","readonly":0},{"type":"table-select","name":"Статус заявки","id":"474d08b6-5ff2-4df1-b79e-b8b6e061e1e6","mysql":"status","options":"[\n\t{\n\t\t\"text\": \"одобрено\",\n\t\t\"value\": \"approved\"\n\t},\n\t{\n\t\t\"text\": \"отказано\",\n\t\t\"value\": \"declined\"\n\t},\n\t{\n\t\t\"text\": \"в процессе\",\n\t\t\"value\": \"pending\"\n\t},\n\t{\n\t\t\"text\": \"отменено клиентом\",\n\t\t\"value\": \"cancel\"\n\t},\n\t{\n\t\t\"text\": \"требуется решение менеджера\",\n\t\t\"value\": \"manual\"\n\t}\n]","filter":"select","disabled":0},{"type":"table-number","name":"ID МФО","id":"96415ee5-1569-4bec-ae60-00f3899056ce","mysql":"mfo_id","filter":"number","readonly":0},{"type":"table-text","name":"Cсылка на соглашение","id":"8640d4b4-cd31-4bab-93cf-1d43b24834db","mysql":"conditions_url"},{"type":"table-select","name":"Кредит отложенный?","id":"33892512-9b82-4d91-88ff-7bb20aae1de3","mysql":"is_loan_deferred","filter":"checkbox","options":"[{\"text\":\"да\", \"value\":\"1\"},{\"text\":\"нет\", \"value\":\"0\"}]","disabled":0},{"type":"table-select","name":"МФО выдала деньги?","id":"87f0a166-a15a-4d67-95e3-b0fb3024493b","mysql":"is_loan_received","filter":"checkbox","options":"[{\"text\":\"да\", \"value\":\"1\"},{\"text\":\"нет\", \"value\":\"0\"}]","disabled":0},{"type":"table-select","name":"Товар получен клиентом?","id":"ac140eea-2b16-446b-adde-5d83ad3f938e","mysql":"is_order_received","filter":"checkbox","options":"[{\"text\":\"да\", \"value\":\"1\"},{\"text\":\"нет\", \"value\":\"0\"}]","disabled":0},{"type":"table-text","name":"Код отслеживания посылки","id":"94ee7bf8-d7b7-44c4-b8b3-9519d24cd20e","mysql":"tracking_id","filter":"text"},{"type":"table-date","name":"Время подачи заявки","id":"b88c4498-a634-4a82-9069-06b975ec8e6f","mysql":"time_start","filter":"date","readonly":0},{"type":"table-date","name":"Время закрытия заявки","id":"9e9a52c2-b533-40f9-93da-a14c442f01d2","mysql":"time_finish","filter":"date","readonly":0}],"control-container-create-fields":[]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"Партнёры","file_name":"partners.php","select_from":"partners","update_and_insert":"partners","default_sort":"orderby","search":"name","can_create":1,"can_edit":1,"can_delete":1,"edit_page":1,"pagination":0,"visible":1,"csv-export":1,"x":"2585.54px","y":"111.312px","id":"fbed3ded-1b4d-4494-9e37-9e33968e47d8","inline_sorting_field":"orderby","icon":"star","roles":"super_admin, admin, manager","control-container-columns":[{"type":"table-none","name":"id","id":"6499d8c3-b827-4f80-b8c7-a12625c647ef","mysql":"id","readonly":1,"filter":"none"},{"type":"table-text","name":"Имя","id":"69015540-51c0-4073-aa40-0e003406126b","mysql":"name","filter":"text"},{"type":"table-image","name":"Логотип","id":"59b6ba4c-a7e2-4beb-92e9-1795a127e498","mysql":"img_url"},{"type":"table-text","name":"Ссылка на сайт","id":"dd1f4c75-a396-4d34-8c9c-7581f2c7e3cf","mysql":"url"}],"control-container-edit-fields":[{"type":"table-text","name":"Имя","id":"69015540-51c0-4073-aa40-0e003406126b","mysql":"name","filter":"text"},{"type":"table-image","name":"Логотип","id":"59b6ba4c-a7e2-4beb-92e9-1795a127e498","mysql":"img_url"},{"type":"table-text","name":"Ссылка на сайт","id":"dd1f4c75-a396-4d34-8c9c-7581f2c7e3cf","mysql":"url"}],"control-container-create-fields":[{"type":"table-text","name":"Имя","id":"69015540-51c0-4073-aa40-0e003406126b","mysql":"name","filter":"text"},{"type":"table-image","name":"Логотип","id":"59b6ba4c-a7e2-4beb-92e9-1795a127e498","mysql":"img_url"},{"type":"table-text","name":"Ссылка на сайт","id":"dd1f4c75-a396-4d34-8c9c-7581f2c7e3cf","mysql":"url"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"Плагины","file_name":"integration_plugins.php","select_from":"integration_plugins","update_and_insert":"integration_plugins","can_create":1,"can_delete":1,"edit_page":1,"can_edit":1,"visible":1,"csv-export":1,"inline_sorting_field":"orderby","search":"name","default_sort":"orderby","x":"535.937px","y":"488.42px","roles":"super_admin, admin, manager","control-container-columns":[{"type":"table-none","name":"id","id":"7283c7b5-2db6-4d66-b09e-91b85a456b77","mysql":"id"},{"type":"table-text","name":"name","id":"c2af1bf1-1703-42d5-bcf8-cae940a1358e","mysql":"name"},{"type":"table-image","name":"Логотип","id":"4bd01b3a-8f6e-4394-81d3-8c68d288ffc3","mysql":"img_url"},{"type":"table-text","name":"Ссылка","id":"5a58b54e-1361-484a-a207-ff230f26e36e","mysql":"url"}],"control-container-edit-fields":[{"type":"table-text","name":"name","id":"c2af1bf1-1703-42d5-bcf8-cae940a1358e","mysql":"name"},{"type":"table-image","name":"Логотип","id":"4bd01b3a-8f6e-4394-81d3-8c68d288ffc3","mysql":"img_url"},{"type":"table-text","name":"Ссылка","id":"5a58b54e-1361-484a-a207-ff230f26e36e","mysql":"url"}],"control-container-create-fields":[{"type":"table-text","name":"name","id":"c2af1bf1-1703-42d5-bcf8-cae940a1358e","mysql":"name"},{"type":"table-image","name":"Логотип","id":"4bd01b3a-8f6e-4394-81d3-8c68d288ffc3","mysql":"img_url"},{"type":"table-text","name":"Ссылка","id":"5a58b54e-1361-484a-a207-ff230f26e36e","mysql":"url"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}},{"params":{"type":"table","name":"Администраторы Bliss","file_name":"admins.php","select_from":"admins","update_and_insert":"admins","can_create":1,"can_delete":1,"can_edit":1,"edit_page":1,"pagination":1,"visible":1,"csv-export":1,"roles":"super_admin","x":"24.7896px","y":"488.389px","control-container-columns":[{"type":"table-none","name":"id","id":"d6f25fbd-e053-49c5-a307-e75025204e48","mysql":"id"},{"type":"table-text","name":"Имя","id":"d707c8c3-b8ed-498a-8a4a-1a832f5a1fec","mysql":"name"},{"type":"table-text","name":"Email","id":"435a57f9-266d-4cec-9964-27f9c9fdcdc1","mysql":"email"},{"type":"table-pass","name":"Пароль","id":"f6245982-f943-49f9-8e6e-6bad6885bd6f","mysql":"password_hash"},{"type":"table-select","name":"Роль","id":"9b1fbe04-c323-4424-9ec1-94de65f36abc","mysql":"role","options":"[{\"text\":\"Супер администратор\", \"value\":\"super_admin\"},{\"text\":\"Администратор\", \"value\":\"admin\"},{\"text\":\"Менеджер\", \"value\":\"manager\"}]"}],"control-container-edit-fields":[{"type":"table-text","name":"Имя","id":"d707c8c3-b8ed-498a-8a4a-1a832f5a1fec","mysql":"name"},{"type":"table-text","name":"Email","id":"435a57f9-266d-4cec-9964-27f9c9fdcdc1","mysql":"email"},{"type":"table-pass","name":"Пароль","id":"f6245982-f943-49f9-8e6e-6bad6885bd6f","mysql":"password_hash"},{"type":"table-select","name":"Роль","id":"9b1fbe04-c323-4424-9ec1-94de65f36abc","mysql":"role","options":"[{\"text\":\"Супер администратор\", \"value\":\"super_admin\"},{\"text\":\"Администратор\", \"value\":\"admin\"},{\"text\":\"Менеджер\", \"value\":\"manager\"}]"}],"control-container-create-fields":[{"type":"table-text","name":"Имя","id":"d707c8c3-b8ed-498a-8a4a-1a832f5a1fec","mysql":"name"},{"type":"table-text","name":"Email","id":"435a57f9-266d-4cec-9964-27f9c9fdcdc1","mysql":"email"},{"type":"table-pass","name":"Пароль","id":"f6245982-f943-49f9-8e6e-6bad6885bd6f","mysql":"password_hash"},{"type":"table-select","name":"Роль","id":"9b1fbe04-c323-4424-9ec1-94de65f36abc","mysql":"role","options":"[{\"text\":\"Супер администратор\", \"value\":\"super_admin\"},{\"text\":\"Администратор\", \"value\":\"admin\"},{\"text\":\"Менеджер\", \"value\":\"manager\"}]"}]},"ghost":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"extra":{"control-container-columns":[],"control-container-edit-fields":[],"control-container-create-fields":[]},"sorting":{}}]}
NOWDOC;
