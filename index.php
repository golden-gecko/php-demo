<?php
	session_start();

	// ----------------------------------------
	// Smarty
	// ----------------------------------------

	require_once('libs/Smarty.class.php');

	$smarty = new Smarty();

	// ----------------------------------------
	// Database
	// ----------------------------------------

	require_once('php/class_database.php');

	$database = new Database();
//	$database->connect('localhost:3306', 'root', '', 'db261766');
	$database->connect('mysql.rootnode.net:3306', 'wholisz_admin', 'starfir3', 'wholisz_db');

	$_SESSION['language'] = $_SESSION['language'] ? $_SESSION['language'] : 'pl';

	// ----------------------------------------
	// Debug on / off
	// ----------------------------------------

	$debug = 0;
	$smarty->assign('subheaderContent', '');

	if (!$database->get_setting('first_run'))
	{
		include ('E-Shop.sql');

		if ($database->create($data))
		{
			$_SESSION['user_id'] = 1;
			$_SESSION['login'] = 'admin';
			$_SESSION['permissions'] = $database->get_user_permissions($result);

			$smarty->assign(
				'subheaderContent', '
				Sklep zosta³ poprawnie zainstalowany.<br />
				Zosta³eœ zalogowany na konto administratora, uzupe³nij swoje dane oraz has³o.<br />
				Baza zosta³a uzupe³niona przyk³adowy danymi. Mog¹ byæ niewidoczne do czasu odœwie¿enia strony.<br />
				Mo¿esz je edytowaæ z widocznego po lewej stronie Panelu Administracyjnego.');
		}
		else
		{
			$smarty->assign(
				'subheaderContent',
				'Instalacja sklepu nie powiod³a siê - b³¹d w wykonywaniu zapytania.');
		}
	}
	else
	{
		$debug = $database->get_setting('debug') ? 1 : 0;
		$debug = 1;

		// ----------------------------------------
		// 'a' parameter
		// ----------------------------------------

		switch ($_GET['a'])
		{
			// ----------------------------------------
			// change_language
			// ----------------------------------------

			case 'change_language':

				($_GET['i'] == 'pl' || $_GET['i'] == 'en') ? $_SESSION['language'] = $_GET['i'] : 'pl';

				break;

			// ----------------------------------------
			// log_in
			// ----------------------------------------

			case 'log_in':

				$result = $database->log_in($_POST['login'], $_POST['password']);
				
				if ($result)
				{
					$_SESSION['user_id'] = $result;
					$_SESSION['login'] = $_POST['login'];
					$_SESSION['permissions'] = $database->get_user_permissions($result);

					$smarty->assign('subheaderContent', 'Witamy w serwisie.');
				}
				else
				{
					$smarty->assign('subheaderContent', 'B³êdny login lub has³o. Proszê spróbowaæ ponownie.');
				}

				break;

			// ----------------------------------------
			// log_out
			// ----------------------------------------

			case 'log_out':

				$_SESSION['user_id'] = NULL;
				$_SESSION['login'] = NULL;
				$_SESSION['permissions'] = NULL;

				$smarty->assign('subheaderContent', 'Zapraszamy do ponownego logowania.');

				break;

			// ----------------------------------------
			// update_user
			// ----------------------------------------

			case 'update_user':

				if ($_SESSION['permissions'] < 1 || $_SESSION['user_id'] != $_GET['i'])
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				if ($_POST['first_name'] == ''
					|| $_POST['last_name'] == ''
					|| $_POST['street'] == ''
					|| $_POST['postal_code'] == ''
					|| $_POST['city'] == ''
					|| $_POST['e_mail'] == '')
				{
					$smarty->assign(
						'subheaderContent',
						'Uzupe³nij poprawnie wszystkie dane.');

					break;
				}
				
				if ($_POST['password'] != $_POST['retype_password'])
				{
					$smarty->assign(
						'subheaderContent',
						'Podane has³a nie zgadzaj¹ siê.');

					break;
				}

				$result = $database->update_user(
					$_GET['user_id'],
					$_POST['login'],
					$_POST['password'],
					$_POST['permissions'],
					$_POST['first_name'],
					$_POST['last_name'],
					$_POST['street'],
					$_POST['postal_code'],
					$_POST['city'],
					$_POST['e_mail']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Twój profil zosta³ zaktualizowany.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// update_product
			// ----------------------------------------

			case 'update_product':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				if ($_POST['category_id'] == ''
					|| $_POST['name'] == ''
					|| $_POST['description'] == ''
					|| $_POST['quantity'] == ''
					|| $_POST['unit_price'] == ''
					|| $_POST['vat'] == '')
				{
					$smarty->assign(
						'subheaderContent',
						'Uzupe³nij poprawnie wszystkie dane.');

					break;
				}

				$result = $database->update_product(
					$_GET['i'],
					$_POST['category_id'],
					$_POST['name'],
					$_POST['description'],
					$_POST['quantity'],
					$_POST['unit_price'],
					$_POST['vat']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Produkt zosta³ zaktualizowany.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// update_category
			// ----------------------------------------

			case 'update_category':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				if ($_GET['i'] == ''
					|| $_POST['name'] == ''
					|| $_POST['description'] == '')
				{
					$smarty->assign(
						'subheaderContent',
						'Uzupe³nij poprawnie wszystkie dane.');

					break;
				}

				$result = $database->update_category(
					$_GET['i'],
					$_POST['name'],
					$_POST['description']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Kategoria zosta³a zaktualizowana.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// add_comment
			// ----------------------------------------

			case 'add_comment':

				if ($_SESSION['permissions'] < 1)
				{
					$smarty->assign(
						'subheaderContent',
						'Zaloguj siê, aby dodawaæ komentarze. Jeœli nie masz jeszcze konta, zarejestruj siê.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				if ($_POST['content'] == '')
				{
					$smarty->assign(
						'subheaderContent',
						'Uzupe³nij poprawnie wszystkie dane.');

					break;
				}

				$result = $database->add_comment(
					$_POST['product_id'],
					$_SESSION['user_id'],
					$_POST['content']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Komentarz zosta³ dodany.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// add_to_cart
			// ----------------------------------------

			case 'add_to_cart':

				if ($_SESSION['permissions'] < 1)
				{
					$smarty->assign(
						'subheaderContent',
						'Zaloguj siê, aby dodawaæ produkty do koszyka. Jeœli nie masz jeszcze konta, zarejestruj siê.');

					$smarty->assign(
						'products',
						$database->get_products(
							$_GET['category'],
							$_GET['name'],
							$_GET['description'],
							$_GET['quantity'] ? $_GET['quantity'] : -1,
							$_GET['unit_price'] ? $_GET['unit_price'] : -1,
							$_GET['vat'] ? $_GET['vat'] : -1,
							$_GET['sort'] ? $_GET['sort'] : 'name'));

					break;
				}

				if ($database->is_product_available($_SEESION['user_id'], $_GET['i']) == 0)
				{
					$smarty->assign(
						'subheaderContent',
						'Zapas produktów zosta³ ju¿ wyczerpany.');

					break;
				}

				$result = $database->add_product_to_cart($_SESSION['user_id'], $_GET['i']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Produkt zosta³ dodany do koszyka.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// cancel_order
			// ----------------------------------------

			case cancel_order:

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = $database->cancel_sales_order($_GET['i']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Zamówienie zosta³o anulowane.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// delete_from_cart
			// ----------------------------------------

			case 'delete_from_cart':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = $database->delete_product_from_cart($_SESSION['user_id'], $_GET['i']);

				if ($result)
				{
					$smarty->assign('subheaderContent', 'Produkt zosta³ usuniêty z koszyka.');
				}
				else
				{
					$smarty->assign('subheaderContent', 'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// add_news
			// ----------------------------------------

			case 'add_news':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = $database->add_news($_SESSION['user_id'], date('Y-m-d H-i-s'), $_POST['content']);

				if ($result)
				{
					$smarty->assign('subheaderContent', 'News zosta³ dodany do bazy.');
				}
				else
				{
					$smarty->assign('subheaderContent', 'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// delete_news
			// ----------------------------------------

			case 'delete_news':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = $database->delete('news', $_GET['i']);

				if ($result)
				{
					$smarty->assign('subheaderContent', 'News zosta³ usuniêty z bazy.');
				}
				else
				{
					$smarty->assign('subheaderContent', 'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// delete_category
			// ----------------------------------------

			case 'delete_category':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = $database->delete('category', $_GET['i']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Kategoria zosta³a usuniêta z bazy.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// delete_product
			// ----------------------------------------

			case 'delete_product':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = $database->delete_product($_GET['i']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Produkt zosta³ usuniêty z bazy.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// updateUser
			// ----------------------------------------

			case 'updateUser':

				$result = mysql_query('
					UPDATE shop_user SET
					login = "'.$_POST['login'].'",
					permissions = "'.$_POST['permissions'].'",
					first_name = "'.$_POST['first_name'].'",
					last_name = "'.$_POST['last_name'].'",
					street = "'.$_POST['street'].'",
					postal_code = "'.$_POST['postal_code'].'",
					city = "'.$_POST['city'].'",
					e_mail = "'.$_POST['e_mail'].'"
					WHERE user_id = '.$_GET['i']);

				if ($result)
				{
					$smarty->assign('subheaderContent', 'Dane u¿ytkownika zosta³y zaktualizowane.');
				}
				else
				{
					$smarty->assign('subheaderContent', 'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// delete_user
			// ----------------------------------------

			case 'delete_user':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = mysql_query('DELETE FROM user WHERE user_id = '.$_GET['i']);

				if ($result)
				{
					$smarty->assign('subheaderContent', 'U¿ytkownik zosta³ usuniêty z bazy.');
				}
				else
				{
					$smarty->assign('subheaderContent', 'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// add_category
			// ----------------------------------------

			case 'add_category':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				if ($_POST['name'] == '' && $_POST['description'] == '')
				{
					$smarty->assign('subheaderContent', 'Wype³nij wszystkie pola.');

					break;
				}

				$result = $database->add_category(
					$_POST['name'],
					$_POST['description']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Kategoria zosta³a dodany do bazy.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// add_product
			// ----------------------------------------

			case 'add_product':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				if (
					$_POST['category_id'] == ''
					|| $_POST['name'] == ''
					|| $_POST['description'] == ''
					|| $_POST['quantity'] == ''
					|| $_POST['unit_price'] == ''
					|| $_POST['vat'] == '')
				{
					$smarty->assign(
						'subheaderContent',
						'Wype³nij poprawnie wszystkie pola.');

					break;
				}

				$result = $database->add_product(
					$_POST['category_id'],
					$_POST['name'],
					$_POST['description'],
					$_POST['quantity'],
					$_POST['unit_price'],
					$_POST['vat']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Produkt zosta³ dodany do bazy.');
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// add_payment_method
			// ----------------------------------------

			case 'add_payment_method':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$database->add_payment_method($_POST['name']);

				break;

			// ----------------------------------------
			// delete_payment_method
			// ----------------------------------------

			case 'delete_payment_method':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$database->delete('payment_method', $_GET['i']);

				break;

			// ----------------------------------------
			// add_shipping_method
			// ----------------------------------------

			case 'add_shipping_method':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$database->add_shipping_method($_POST['name'], $_POST['cost']);

				break;

			// ----------------------------------------
			// delete_shipping_method
			// ----------------------------------------

			case 'delete_shipping_method':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$database->delete('shipping_method', $_GET['i']);

				break;

			// ----------------------------------------
			// execute_query
			// ----------------------------------------

			case 'execute_query':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('result', $database->multiple_query($_POST['query']));

				break;
		}

		// ----------------------------------------
		// 'p' parameter
		// ----------------------------------------

		switch ($_GET['p'])
		{
			// ----------------------------------------
			// confirm_register
			// ----------------------------------------

			case 'confirm_register':

				if ($_SESSION['permissions'])
				{
					$smarty->assign(
						'subheaderContent',
						'Posiadasz ju¿ konto i jesteœ aktualnie zalogowany. Wyloguj siê, aby za³o¿yæ drugie konto.');

					$smarty->assign('news', $database->get_news());

					break;
				}

				if ($_POST['login'] == ''
					|| $_POST['password'] == ''
					|| $_POST['retype_password'] == ''
					|| $_POST['first_name'] == ''
					|| $_POST['last_name'] == ''
					|| $_POST['street'] == ''
					|| $_POST['postal_code'] == ''
					|| $_POST['city'] == ''
					|| $_POST['e_mail'] == '')
				{
					$_GET['p'] = 'register';

					$smarty->assign(
						'subheaderContent',
						'Wype³nij poprawnie formularz rejestracji.');

					break;
				}

				$result = $database->register_user(
					$_POST['login'],
					$_POST['password'],
					$_POST['retype_password'],
					$_POST['first_name'],
					$_POST['last_name'],
					$_POST['street'],
					$_POST['postal_code'],
					$_POST['city'],
					$_POST['e_mail']);

				if ($result)
				{
					$smarty->assign(
						'subheaderContent',
						'Twoje konto zosta³o utworzone.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
				}

				break;

			// ----------------------------------------
			// quick_search
			// ----------------------------------------

			case 'quick_search':

				$_GET['p'] = 'products';

				$smarty->assign(
					'products',
					$database->get_products(0, $_GET['quick_search']));

				break;

			// ----------------------------------------
			// products
			// ----------------------------------------

			case 'products':

				$smarty->assign(
					'products',
					$database->get_products(
						$_GET['category_id'],
						$_GET['name'],
						$_GET['description'],
						$_GET['quantity'],
						$_GET['unit_price'],
						$_GET['vat'],
						$_GET['sort']));

				break;

			// ----------------------------------------
			// cart
			// ----------------------------------------

			case 'cart':

				if ($_SESSION['permissions'] < 1)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$smarty->assign('news', $database->get_news());
				}
				else
				{
					$smarty->assign('cart_quantity', $database->get_user_cart_quantity($_SESSION['user_id']));
				}

				break;

			// ----------------------------------------
			// order
			// ----------------------------------------

			case 'order':

				if ($_SESSION['permissions'] < 1)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());
				}
				else
				{
					$smarty->assign('cart_quantity', $database->get_user_cart_quantity($_SESSION['user_id']));
					$smarty->assign('user', $database->get_user($_SESSION['user_id']));
					$smarty->assign('payment_methods', $database->get_payment_methods());
					$smarty->assign('shipping_methods', $database->get_shipping_methods());
				}

				break;

			// ----------------------------------------
			// order
			// ----------------------------------------

			case 'confirm_order':

				if ($_SESSION['permissions'] < 1)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());
				}
				else if ($database->get_user_cart_quantity($_SESSION['user_id']) > 0)
				{
					$result = $database->make_order($_SESSION['user_id'], $_GET['payment_method_id'], $_GET['shipping_method_id']);

					if ($result)
					{
						$smarty->assign(
							'subheaderContent',
							'Twoje zamówienie zosta³o wys³ane. Zapraszamy do ponownych zakupów.');
					}
					else
					{
						$smarty->assign(
							'subheaderContent',
							'Wyst¹pi³ b³¹d, proszê spróbowaæ ponownie lub skontaktowaæ siê z administratorem.');
					}
				}
				else
				{
					$smarty->assign(
						'subheaderContent',
						'Nie mo¿esz z³o¿yæ zamówienia, poniewa¿ Twój koszyk jest pusty.');
				}

				$_GET['p'] = '';

				$smarty->assign('news', $database->get_news());

				break;

			// ----------------------------------------
			// admin_news
			// ----------------------------------------

			case 'admin_news':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$result = mysql_query('SELECT news_id, date, content, news.user_id AS user_id, login FROM shop_news JOIN shop_user ON shop_news.user_id = shop_user.user_id');

				while ($row = mysql_fetch_array($result))
					$smarty->append('news', $row);

				break;

			// ----------------------------------------
			// admin_users
			// ----------------------------------------

			case 'admin_users':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('users', $database->get_users());

				break;

			// ----------------------------------------
			// admin_categories
			// ----------------------------------------

			case 'admin_categories':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign(
					'admin_categories',
					$database->get_categories(
						$_GET['name'],
						$_GET['description'],
						$_GET['sort']));

				break;

			// ----------------------------------------
			// admin_products
			// ----------------------------------------

			case 'admin_products':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('products',
					$database->get_products(
						$_GET['category_id'],
						$_GET['name'],
						$_GET['description'],
						$_GET['quantity'],
						$_GET['unit_price'],
						$_GET['vat'],
						$_GET['sort']));
				$smarty->assign('vat', $database->list_vat());

				break;

			// ----------------------------------------
			// admin_orders
			// ----------------------------------------

			case 'admin_orders':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('admin_orders', $database->get_orders());

				break;

			// ----------------------------------------
			// admin_users
			// ----------------------------------------

			case 'admin_users':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('users', $database->get_users());

				break;

			// ----------------------------------------
			// admin_payment_shipping
			// ----------------------------------------

			case 'admin_payment_shipping':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('payment_methods', $database->get_payment_methods());
				$smarty->assign('shipping_methods', $database->get_shipping_methods());

				break;

			// ----------------------------------------
			// user_profile
			// ----------------------------------------

			case 'user_profile':

				if (($_SESSION['permissions'] < 1) || ($_SESSION['permissions'] < 4 && $_SESSION['user_id'] != $_GET['i']))
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('user', $database->get_user($_GET['i']));
				$smarty->assign('user_order_quantity', $database->get_user_order_quantity($_GET['i']));
				$smarty->assign('user_payed_order_quantity', $database->get_user_payed_order_quantity($_GET['i']));
				$smarty->assign('user_shipped_order_quantity', $database->get_user_shipped_order_quantity($_GET['i']));
				$smarty->assign('user_comment_quantity', $database->get_user_comment_quantity($_GET['i']));
				$smarty->assign('admin_orders', $database->get_user_orders($_GET['i']));

				break;

			// ----------------------------------------
			// product_profile
			// ----------------------------------------

			case 'product_profile':

				$smarty->assign('product', $database->get_product($_GET['i']));
				$smarty->assign('product_comments', $database->get_product_comments($_GET['i']));

				if ($_SESSION['permissions'] == 4)
				{
					$smarty->assign('vat', $database->list_vat());
				}

				break;

			// ----------------------------------------
			// order_profile
			// ----------------------------------------

			case 'order_profile':

				if ($_SESSION['permissions'] < 1)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('order', $database->get_order($_GET['i']));
				$smarty->assign('order_items', $database->get_order_items($_GET['i']));
				$smarty->assign('order_quantity', $database->get_order_quantity($_GET['i']));
				$smarty->assign('order_price', $database->get_order_price($_GET['i']));

				break;

			// ----------------------------------------
			// category_profile
			// ----------------------------------------

			case 'category_profile':

				if ($_SESSION['permissions'] < 4)
				{
					$smarty->assign(
						'subheaderContent',
						'Nie masz uprawnieñ do przegl¹dania tej czêœci serwisu.');

					$_GET['p'] = '';

					$smarty->assign('news', $database->get_news());

					break;
				}

				$smarty->assign('category', $database->get_category($_GET['i']));

				break;

			// ----------------------------------------
			// admin_database
			// ----------------------------------------

			case 'admin_database':
				break;
		}


		$smarty->assign('categories', $database->get_categories());

		// ----------------------------------------
		// Cart
		// ----------------------------------------

		if ($_SESSION['login'])
		{
			$smarty->assign('cart', $database->get_user_cart($_SESSION['user_id']));
			$smarty->assign('cart_price', $database->get_user_cart_price($_SESSION['user_id']));
		}

		// ----------------------------------------
		// Date
		// ----------------------------------------

		$days = array(
			'Niedziela',
			'Poniedzia³ek',
			'Wtorek',
			'Œroda',
			'Czwartek',
			'Pi¹tek',
			'Sobota');

		$months = array(
			'stycznia',
			'lutego',
			'marca',
			'kwietnia',
			'maja',
			'czerwca',
			'lipca',
			'sierpnia',
			'wrzeœnia',
			'paŸdziernika',
			'listopada',
			'grudnia');

		$smarty->assign('current_date', $days[date('w')].', '.date('j ').$months[date('n') - 1].date(' Y'));

		// ----------------------------------------
		// Random Offerts
		// ----------------------------------------

		srand((float) microtime() * 10000000);

		$products = $database->get_products();
		$products_count = count($products) - 1;

		if ($products_count > 2)
		{
			$a = rand(0, $products_count);

			do
			{
				$b = rand(0, $products_count);
			}
			while ($b == $a);

			do
			{
				$c = rand(0, $products_count);
			}
			while ($c == $a || $c == $b);

			$smarty->append('random_offerts', $products[$a]);
			$smarty->append('random_offerts', $products[$b]);
			$smarty->append('random_offerts', $products[$c]);
		}
		else
		{
			$smarty->assign('random_offerts', $products);
		}

		// ----------------------------------------
		// main page
		// ----------------------------------------

		if ($_GET['p'] == '')
		{
			$smarty->assign('news', $database->get_news());
		}
	}

	$smarty->assign('debug', 0);
	$smarty->assign('debugQueries', $database->queries);

	$smarty->display('index_'.$_SESSION['language'].'.tpl');
?>
