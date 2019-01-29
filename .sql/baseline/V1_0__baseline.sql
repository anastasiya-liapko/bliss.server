-- V1_0__baseline

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL COMMENT 'имя',
  `email` VARCHAR(255) UNIQUE NOT NULL COMMENT 'email и логин',
  `password_hash` VARCHAR(255) NOT NULL COMMENT 'хэш пароля',
  `role` ENUM('super_admin', 'admin', 'manager') NOT NULL COMMENT 'роль пользователя, который может логинится в админку'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'админы системы Bliss';

DROP TABLE IF EXISTS `shops`;
CREATE TABLE `shops` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` ENUM('entrepreneur','llc') NOT NULL COMMENT 'тип формы собственности',
  `company_name` VARCHAR(255) NOT NULL COMMENT 'название магазина',
  `last_name` VARCHAR(255) NOT NULL COMMENT 'фамилия',
  `first_name` VARCHAR(255) NOT NULL COMMENT 'имя',
  `middle_name` VARCHAR(255) NOT NULL COMMENT 'отчество',
  `tin` VARCHAR(12) UNIQUE NOT NULL COMMENT 'инн',
  `dsc` TEXT(1000) COMMENT 'описание',
  `active` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'активный ли магазин',
  `secret_key` VARCHAR(255) NOT NULL COMMENT 'секретный ключ'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'магазины, где можно купить в кредит';

DROP TABLE IF EXISTS `shops_admins`;
CREATE TABLE `shops_admins` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL COMMENT 'имя',
  `email` VARCHAR(255) UNIQUE NOT NULL COMMENT 'email и логин',
  `password_hash` VARCHAR(255) NOT NULL COMMENT 'хэш пароля',
  `phone` VARCHAR(12) COMMENT 'телефон',
  `role` ENUM('admin', 'manager') NOT NULL COMMENT 'роль пользователя, который может логинится в админку',
  `shop_id` INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  `active` TINYINT(1) UNSIGNED DEFAULT 0
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'админы, менеджеры магазинов партнеров';

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `last_name` VARCHAR(255) NOT NULL COMMENT 'фамилия',
  `first_name` VARCHAR(255) NOT NULL COMMENT 'имя',
  `middle_name` VARCHAR(255) NOT NULL COMMENT 'фамилия',
  `birth_date` DATETIME NOT NULL COMMENT 'дата рождения',
  `birth_place` VARCHAR(255) NOT NULL COMMENT 'место рождения',
  `passport_number` VARCHAR(255) NOT NULL COMMENT 'серия и номер паспорта',
  `passport_division_code` VARCHAR(255) NOT NULL COMMENT 'код подразделения, выдавшего паспорт',
  `passport_issued_by` VARCHAR(255) NOT NULL COMMENT 'кем выдан паспорт',
  `passport_issued_date` DATETIME NOT NULL COMMENT 'дата выдачи паспорта',
  `reg_zip_code` VARCHAR(255) NOT NULL COMMENT 'индекс по прописке',
  `reg_city` VARCHAR(255) NOT NULL COMMENT 'город по прописке',
  `reg_street` VARCHAR(255) NOT NULL COMMENT 'улица по прописке',
  `reg_building` VARCHAR(255) NOT NULL COMMENT 'дом по прописке',
  `reg_apartment` VARCHAR(255) NOT NULL COMMENT 'квартира по прописке',
  `is_address_matched` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'совпадают ли фактический и адрес прописки',
  `fact_zip_code` VARCHAR(255) NOT NULL COMMENT 'индекс по факту',
  `fact_city` VARCHAR(255) NOT NULL COMMENT 'город по факту',
  `fact_street` VARCHAR(255) NOT NULL COMMENT 'улица по факту',
  `fact_building` VARCHAR(255) NOT NULL COMMENT 'дом по факту',
  `fact_apartment` VARCHAR(255) NOT NULL COMMENT 'квартира по факту',
  `phone` VARCHAR(12) UNIQUE NOT NULL COMMENT 'телефон',
  `email` VARCHAR(255) COMMENT 'email'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'покупатели';

DROP TABLE IF EXISTS `mfo`;
CREATE TABLE `mfo` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL COMMENT 'название мфо',
  `phone` VARCHAR(12) COMMENT 'телефон',
  `email` VARCHAR(255) COMMENT 'email для связи и уточнения об отказе в выдаче кредита',
  `class_connector` VARCHAR(255) COMMENT 'класс-конектор',
  `api_url` VARCHAR(255) COMMENT 'ссылка на файл api для интеграции',
  `api_id` VARCHAR(255) COMMENT 'идентфикатор системы в МФО',
  `api_password` VARCHAR(255) COMMENT 'пароль системы в МФО'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'мфо';

DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `client_id` INT(11) UNSIGNED NOT NULL COMMENT 'id клиента',
  `shop_id` INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  `order_id` INT(11) UNSIGNED NOT NULL COMMENT 'id заказа в магазине',
  `order_price` FLOAT(11, 2) UNSIGNED NOT NULL COMMENT 'сумма заказа',
  `goods` VARCHAR(255) NOT NULL COMMENT 'товары',
  `status` ENUM('approved','declined','pending','cancel','manual') NOT NULL COMMENT 'статусы заявки (одобрено, отказано, в процессе, сам клиент отменил, что-то пошло не так и менеджеру надо самому решить этот вопрос)',
  `mfo_id` INT(11) UNSIGNED COMMENT 'id одобренной мфо',
  `conditions_url` VARCHAR(255) COMMENT 'ссылка на соглашение',
  `is_loan_deferred` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'кредит отложенный? (1 - да, 0 - нет)',
  `is_loan_received` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'мфо выдала деньги (1 - да, 0 - нет)',
  `is_order_received` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'товар получен клиентом (1 - да, 0 - нет, null - если is_loan_postponed == 0)',
  `tracking_id` VARCHAR(255) COMMENT 'код отслеживания посылки, его выдает служба доставки',
  `time_start` TIMESTAMP NOT NULL COMMENT 'время подачи заявки',
  `time_finish` TIMESTAMP NULL DEFAULT NULL COMMENT 'время закрытия заявки'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'заявки на кредит';

DROP TABLE IF EXISTS `integration_plugins`;
CREATE TABLE `integration_plugins` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL COMMENT 'название системы, с которой возможна интеграция',
  `img_url` VARCHAR(255) NOT NULL COMMENT 'ссылка на логотип системы',
  `url` VARCHAR(255) NOT NULL COMMENT 'ссылка на плагин',
  `orderby` INT(11) UNSIGNED DEFAULT 0 COMMENT 'сортировка'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'плагины для интеграции';

DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL COMMENT 'имя',
  `img_url` VARCHAR(255) NOT NULL COMMENT 'ссылка на логотип',
  `url` VARCHAR(255) NOT NULL COMMENT 'ссылка на сайт партнера',
  `orderby` INT(11) UNSIGNED DEFAULT 0 COMMENT 'сортировка'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'слайдер на главной';

DROP TABLE IF EXISTS `mfo_shop_cooperation`;
CREATE TABLE `mfo_shop_cooperation` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `mfo_id` INT(11) UNSIGNED NOT NULL COMMENT 'id мфо',
  `shop_id` INT(11) UNSIGNED NOT NULL COMMENT 'id магазина'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'мфо и магазины, с которыми они работают';

DROP TABLE IF EXISTS `clients_initial_data`;
CREATE TABLE `clients_initial_data` (
  `token_hash` VARCHAR(64) NOT NULL PRIMARY KEY COMMENT 'хэш токена',
  `client_id` INT(11) UNSIGNED COMMENT 'id клиента',
  `phone` VARCHAR(255) COMMENT 'номер телефона',
  `verified` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'подтверждён ли телефон',
  `control` VARCHAR(255) NOT NULL COMMENT 'переданный хэш',
  `shop_id` INT(11) UNSIGNED NOT NULL COMMENT 'id магазина',
  `order_id` INT(11) UNSIGNED NOT NULL COMMENT 'id заказа',
  `order_price` FLOAT (11, 2) UNSIGNED NOT NULL COMMENT 'сумма заказа',
  `callback_url` VARCHAR(255) NOT NULL COMMENT 'ссылка для возврата',
  `is_loan_deferred` TINYINT(1) UNSIGNED DEFAULT 0 COMMENT 'кредит отложенный? (1 - да, 0 - нет)',
  `goods` VARCHAR(255) NOT NULL COMMENT 'товары',
  `token_expires_at` DATETIME NOT NULL COMMENT 'срок годности токена',
  `sms_code` VARCHAR(255) NOT NULL COMMENT 'sms-код',
  `sms_code_expires_at` DATETIME NOT NULL COMMENT 'срок годности sms-кода'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'данные, с которыми клиент попал на сайт';

DROP TABLE IF EXISTS `remembered_logins`;
CREATE TABLE `remembered_logins` (
  `token_hash` VARCHAR(64) NOT NULL PRIMARY KEY COMMENT 'хэш токена',
  `admin_id` INT(11) UNSIGNED NOT NULL COMMENT 'id администратора',
  `expires_at` DATETIME NOT NULL COMMENT 'срок годности'
)ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT 'данные для авторизации на сайте';

ALTER TABLE `shops_admins`
  ADD CONSTRAINT `shops_admins` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `remembered_logins`
  ADD CONSTRAINT `remembered_logins` FOREIGN KEY (`admin_id`) REFERENCES `shops_admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mfo_shop_cooperation`
  ADD CONSTRAINT `shop_cooperation` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `mfo_shop_cooperation`
  ADD CONSTRAINT `mfo_cooperation` FOREIGN KEY (`mfo_id`) REFERENCES `mfo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/* Inserts the super admin */
INSERT INTO admins (id, name, email, password_hash, role) VALUES (1, 'Супер администратор', 'admin@bliss.ru', 'de65262646860a5aba74c271718c2f32', 'super_admin');

/* Inserts mfo */
INSERT INTO mfo (id, name, phone, email, api_url, api_id, api_password) VALUES (1, 'WEBBANKIR', '', '', 'https://demo.webbankir.partners/api-rmk/v1', '1', 'qwerty');

/* Inserts test shop */
INSERT INTO shops (id, type, company_name, last_name, first_name, middle_name, tin, dsc, active, secret_key) VALUES (1, 'entrepreneur', 'ИП "Петров П.П."', 'Петров', 'Пётр', 'Петрович', '777777777777', '', 1, 'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj');

/* Inserts test shop admin */
INSERT INTO shops_admins (id, name, email, password_hash, phone, role, shop_id, active) VALUES (1, 'Петров Пётр Петрович', 'petrov_pp@mail.ru', '$2y$10$C083/DXBHYYUVlIaXeHm7OTZ/8XPecVx4Kh0eHYLla2UHorPFXRb6', '', 'admin', 1, 1);

/* Inserts test cooperation */
INSERT INTO mfo_shop_cooperation (id, mfo_id, shop_id) VALUES (1, 1, 1);

/* Inserts test integration plugins */
INSERT INTO integration_plugins (id, name, url, img_url) VALUES (1, 'OpenCart', '#', '/uploads/1547725662_8b37a1efc1991f7abb550fb47c5bf24d.png');
INSERT INTO integration_plugins (id, name, url, img_url) VALUES (2, 'WooCommerce', '#', '/uploads/1547725698_5c278af26289375771943c6e54e268b0.png');
INSERT INTO integration_plugins (id, name, url, img_url) VALUES (3, 'Shopify', '#', '/uploads/1547725731_2da3f439436169159ae51ebe0b2e7b0a.png');
INSERT INTO integration_plugins (id, name, url, img_url) VALUES (4, 'PrestaShop', '#', '/uploads/1547725770_59a007620dd0c030af70468049763ff3.png');

/* Inserts test partners */
INSERT INTO partners (id, name, img_url, url, orderby) VALUES (1, 'Почта России', '/uploads/1547630932_5c456dceb09f8a5ae444918e05a6e1bd.png', 'https://www.pochta.ru/', 0);
INSERT INTO partners (id, name, img_url, url, orderby) VALUES (2, 'Bork', '/uploads/1547631016_cea4d3d16767857c6b074d73c70e14f9.png', 'https://www.bork.ru/', 0);
INSERT INTO partners (id, name, img_url, url, orderby) VALUES (3, 'Евросеть', '/uploads/1547631045_d0ea8af957225bfbcea7bd46c9b34154.png', 'https://euroset.ru/', 0);

/* Inserts test requests */
INSERT INTO requests (id, client_id, shop_id, order_id, order_price, goods, status, mfo_id, conditions_url, is_loan_deferred, is_loan_received, is_order_received, tracking_id, time_start, time_finish) VALUES (1, 1, 1, 1, 122190, 'a:1:{i:0;a:3:{s:4:"name";s:49:"Смартфон Apple iPhone XS Max 512GB Silver";s:5:"price";i:122190;s:6:"amount";i:1;}}', 'pending', 1, '', 0, 0, 0, '', '2019-01-21 21:47:16', null);
INSERT INTO requests (id, client_id, shop_id, order_id, order_price, goods, status, mfo_id, conditions_url, is_loan_deferred, is_loan_received, is_order_received, tracking_id, time_start, time_finish) VALUES (2, 1, 1, 2, 95190, 'a:1:{i:0;a:3:{s:4:"name";s:43:"Смартфон Apple iPhone XS 256GB Gold";s:5:"price";i:95190;s:6:"amount";i:1;}}', 'pending', 1, '', 0, 0, 0, '', '2019-01-21 21:47:25', null);
INSERT INTO requests (id, client_id, shop_id, order_id, order_price, goods, status, mfo_id, conditions_url, is_loan_deferred, is_loan_received, is_order_received, tracking_id, time_start, time_finish) VALUES (3, 1, 1, 3, 63990, 'a:1:{i:0;a:3:{s:4:"name";s:41:"Смартфон Apple iPhone XR 64GB RED";s:5:"price";i:63990;s:6:"amount";i:1;}}', 'approved', 1, '', 0, 0, 0, '', '2019-01-21 21:47:28', null);
INSERT INTO requests (id, client_id, shop_id, order_id, order_price, goods, status, mfo_id, conditions_url, is_loan_deferred, is_loan_received, is_order_received, tracking_id, time_start, time_finish) VALUES (4, 1, 1, 4, 71490, 'a:1:{i:0;a:3:{s:4:"name";s:57:"Смартфон Samsung Galaxy Note 9 128Gb Черный";s:5:"price";i:71490;s:6:"amount";i:1;}}', 'approved', 1, '', 0, 0, 0, '', '2019-01-21 21:47:29', null);
INSERT INTO requests (id, client_id, shop_id, order_id, order_price, goods, status, mfo_id, conditions_url, is_loan_deferred, is_loan_received, is_order_received, tracking_id, time_start, time_finish) VALUES (5, 1, 1, 5, 21990, 'a:1:{i:0;a:3:{s:4:"name";s:45:"Смартфон Samsung Galaxy A7 64Gb Black";s:5:"price";i:21990;s:6:"amount";i:1;}}', 'declined', 1, '', 0, 0, 0, '', '2019-01-21 21:47:34', null);
INSERT INTO requests (id, client_id, shop_id, order_id, order_price, goods, status, mfo_id, conditions_url, is_loan_deferred, is_loan_received, is_order_received, tracking_id, time_start, time_finish) VALUES (6, 1, 1, 6, 39990, 'a:1:{i:0;a:3:{s:4:"name";s:40:"Смартфон Huawei P20 Pro Twilight";s:5:"price";i:39990;s:6:"amount";i:1;}}', 'cancel', 1, '', 0, 0, 0, '', '2019-01-21 21:47:34', null);
INSERT INTO requests (id, client_id, shop_id, order_id, order_price, goods, status, mfo_id, conditions_url, is_loan_deferred, is_loan_received, is_order_received, tracking_id, time_start, time_finish) VALUES (7, 1, 1, 7, 24990, 'a:1:{i:0;a:3:{s:4:"name";s:35:"Смартфон Honor 10 64Gb Blue";s:5:"price";i:24990;s:6:"amount";i:1;}}', 'manual', 1, '', 0, 0, 0, '', '2019-01-21 21:47:34', null);

/* Machine God set us free */
