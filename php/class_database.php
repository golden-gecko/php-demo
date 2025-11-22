<?php

	// --------------------------------------------------
	// Copyright © 2007 by Wojciech Holisz
	//
	// wholisz@wp.pl
	// --------------------------------------------------

	class Database
	{
		var $connection;
		var $debug_mode;

		var $queriesNum;
		var $queries;

		// --------------------------------------------------
		// __construct
		// --------------------------------------------------

		function __construct()
		{
			$this->connection = NULL;

			$this->queriesNum = 0;
			$this->queries = NULL;
		}

		// --------------------------------------------------
		// connect
		// --------------------------------------------------

		function connect($host, $user, $password, $database_name, $debug_mode = false)
		{
			$this->connection = mysql_connect($host, $user, $password);

			mysql_selectdb($database_name);

			$this->debug_mode = $debug_mode;
		}

		// --------------------------------------------------
		// __destruct
		// --------------------------------------------------

		function __destruct()
		{
			mysql_close($this->connection);
		}

		// --------------------------------------------------
		// query
		// --------------------------------------------------

		function query($query)
		{
			$result = mysql_query($query);

			if (mysql_error())
			{
				$this->queries[$this->queriesNum++] = ' - '.$query;
				$this->queries[$this->queriesNum++] = '&nbsp;&nbsp;&nbsp;&nbsp;'.mysql_error();
			}
			else
			{
				$this->queries[$this->queriesNum++] = ' - '.$query;
			}

			echo($queriesNum);

			return $result;
		}

		// --------------------------------------------------
		// multiple_query
		// --------------------------------------------------

		function multiple_query($multiple_query)
		{
			$query = strtok($multiple_query, ';');

			while ($query)
			{
				$new_query = '';

				for ($i = 0; $i < strlen($query); $i++)
				{
					if ($query[$i] != '\\')
					{
						$new_query .= $query[$i];
					}
				}

				$r = $this->query($new_query);
				$query = strtok(';');
			}

			if (gettype($r) == 'resource')
			{
				$i = 0;

				while ($row = mysql_fetch_row($r))
				{
					$result[$i++] = $row;
				}
			}

			return $result;
		}

		// --------------------------------------------------
		// create
		// --------------------------------------------------

		function create($multiple_query)
		{
			$query = strtok($multiple_query, ';');

			while ($query)
			{
				$new_query = '';

				for ($i = 0; $i < strlen($query); $i++)
				{
					if ($query[$i] != '\\' && $query[$i] != '\n')
					{
						$new_query .= $query[$i];
					}
				}

				$r = $this->query($new_query);
				$query = strtok(';');

				if (!$r)
				{
					$result = $this->query('SHOW TABLES');

					while ($row = mysql_fetch_row($result))
					{
						$this->query('DROP TABLE IF EXISTS '.$row[0]);
					}

					return 0;
				}
			}

			return 1;
		}

		// --------------------------------------------------
		// log_in
		// --------------------------------------------------

		function log_in($login, $password)
		{
			$result = $this->query('
				SELECT user_id
				FROM shop_user
				WHERE login LIKE "'.$login.'" AND password LIKE "'.md5($password).'"');

			if ($row = mysql_fetch_array($result))
			{
				return $row['user_id'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// get_user_permissions
		// --------------------------------------------------

		function get_user_permissions($user_id)
		{
			$result = $this->query('
				SELECT permissions
				FROM shop_user
				WHERE user_id = '.$user_id);

			if ($row = mysql_fetch_array($result))
			{
				return $row['permissions'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// check_login
		// --------------------------------------------------

		function check_login($login)
		{
			$result = $this->query('SELECT user_id FROM shop_user WHERE login LIKE "'.$login.'"');

			return mysql_fetch_array($result) ? 0 : 1;
		}

		// --------------------------------------------------
		// check_password
		// --------------------------------------------------

		function check_password($password, $retype_password)
		{
			return $password != '' && $password == $retype_password;
		}

		// --------------------------------------------------
		// register_user
		// --------------------------------------------------

		function register_user($login, $password, $retype_password, $first_name, $last_name, $street, $postal_code, $city, $e_mail)
		{
			if ($this->check_login($login) == 0)
			{
				return 0;
			}

			if ($this->check_password($password, $retype_password) == 0)
			{
				return 0;
			}
			
			return $this->query('
				INSERT INTO shop_user (login, password, first_name, last_name, street, postal_code, city, e_mail)
					VALUES ("'.$login.'", "'.md5($password).'", "'.$first_name.'", "'.$last_name.'", "'.$street.'", "'.$postal_code.'", "'.$city.'", "'.$e_mail.'")');
		}

		// --------------------------------------------------
		// get_user_cart
		// --------------------------------------------------

		function get_user_cart($user_id)
		{
			$result = $this->query('
				SELECT cart.product_id, name, cart.quantity, unit_price
				FROM shop_cart JOIN shop_product ON shop_cart.product_id = shop_product.product_id
				WHERE user_id = '.$user_id);

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$cart[$i++] = $row;
			}

			return $cart;
		}

		// --------------------------------------------------
		// add_product_to_cart
		// --------------------------------------------------

		function add_product_to_cart($user_id, $product_id)
		{
			$result = $this->query('
				SELECT quantity
				FROM shop_cart
				WHERE user_id = '.$user_id.' AND product_id = '.$product_id);

			if ($row = mysql_fetch_array($result))
			{
				return $this->query('
					UPDATE shop_cart
					SET quantity = '.($row['quantity'] + 1).'
					WHERE user_id = '.$user_id.' AND product_id = '.$product_id);
			}
			else
			{
				return $this->query('
					INSERT INTO shop_cart (user_id, product_id, quantity)
					VALUES ('.$user_id.', '.$product_id.', 1)');
			}
		}

		// --------------------------------------------------
		// delete_product_from_cart
		// --------------------------------------------------

		function delete_product_from_cart($user_id, $product_id)
		{
			return $this->query('
				DELETE FROM shop_cart
				WHERE user_id = '.$user_id.' AND product_id = '.$product_id);
		}

		// --------------------------------------------------
		// get_news
		// --------------------------------------------------

		function get_news()
		{
			$result = $this->query('
				SELECT date, content, news.user_id AS user_id, login
				FROM shop_news JOIN shop_user ON shop_news.user_id = shop_user.user_id');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$news[$i++] = $row;
			}

			return $news;
		}

		// --------------------------------------------------
		// get_categories
		// --------------------------------------------------

		function get_categories($name = '', $description = '', $sort = '')
		{
			$query = '
				SELECT *
				FROM shop_category
				WHERE name LIKE "%'.$name.'%"
				AND description LIKE "%'.$description.'%"';

			if ($sort == '')
			{
				$sort = 'name';
			}

			$result = $this->query($query.' ORDER BY '.$sort);

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$categories[$i++] = $row;
			}

			return $categories;
		}

		// --------------------------------------------------
		// get_user_cart_price
		// --------------------------------------------------

		function get_user_cart_price($user_id)
		{
			$result = $this->query('
				SELECT SUM(cart.quantity * product.unit_price) AS price
				FROM shop_cart JOIN shop_product ON shop_cart.product_id = shop_product.product_id
				WHERE user_id = '.$user_id);

			if ($row = mysql_fetch_array($result))
			{
				return $row['price'];
			}
			else
			{
				return -1;
			}
		}

		// --------------------------------------------------
		// get_payment_methods
		// --------------------------------------------------

		function get_payment_methods()
		{
			$result = $this->query('SELECT * FROM shop_payment_method ORDER BY name');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$payment_methods[$i++] = $row;
			}

			return $payment_methods;
		}

		// --------------------------------------------------
		// get_shipping_methods
		// --------------------------------------------------

		function get_shipping_methods()
		{
			$result = $this->query('SELECT * FROM shop_shipping_method');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$shipping_methods[$i++] = $row;
			}

			return $shipping_methods;
		}

		// --------------------------------------------------
		// list_vat
		// --------------------------------------------------

		function list_vat()
		{
			$result = $this->query('SELECT * FROM shop_vat ORDER BY vat DESC');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$vat[$i++] = $row['vat'];
			}

			return $vat;
		}

		// --------------------------------------------------
		// get_user_cart_quantity
		// --------------------------------------------------

		function get_user_cart_quantity($user_id)
		{
			$result = $this->query('
				SELECT SUM(cart.quantity) AS quantity
				FROM shop_cart
				WHERE user_id = '.$user_id);

			if ($row = mysql_fetch_array($result))
			{
				return $row['quantity'];
			}
			else
			{
				return -1;
			}
		}

		// --------------------------------------------------
		// get_products
		// --------------------------------------------------

		function get_products($category_id = -1, $name = '', $description = '', $quantity = -1, $unit_price = -1, $vat = -1, $sort = '')
		{
			$query = '
				SELECT *
				FROM shop_product
				WHERE name LIKE "%'.$name.'%"
				AND description LIKE "%'.$description.'%"';

			if ($category_id != '' && intval($category_id) >= 0)
			{
				$query .= ' AND category_id = '.intval($category_id);
			}

			if ($quantity != '' && intval($quantity) >= 0)
			{
				$query .= ' AND quantity = '.intval($quantity);
			}

			if ($unit_price != '' && floatval($unit_price) >= 0)
			{
				$query .= ' AND unit_price = '.floatval($unit_price);
			}

			if ($vat != '' && floatval($vat) >= 0)
			{
				$query .= ' AND vat = '.floatval($vat);
			}

			if ($sort == '')
			{
				$sort = 'name';
			}

			$result = $this->query($query.' ORDER BY '.$sort);

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$products[$i++] = $row;
			}

			return $products;
		}

		// --------------------------------------------------
		// add_news
		// --------------------------------------------------

		function add_news($user_id, $date, $content)
		{
			return $this->query('
				INSERT INTO shop_news (user_id, date, content)
				VALUES ('.$user_id.', "'.$date.'", "'.$content.'")');
		}

		// --------------------------------------------------
		// update_user
		// --------------------------------------------------

		function update_user($user_id, $login, $password, $permissions, $first_name, $last_name, $street, $postal_code, $city, $e_mail)
		{
			$query = 'UPDATE shop_user SET login = "'.$login.'",';

			if ($password != '')
			{
				$query .= 'password = "'.md5($password).'",';
			}

			$query .= 'permissions = '.$permissions.',
				first_name = "'.$first_name.'",
				last_name = "'.$last_name.'",
				street = "'.$street.'",
				postal_code = "'.$postal_code.'",
				city = "'.$city.'",
				e_mail = "'.$e_mail.'"
				WHERE user_id = '.$user_id;

			return $this->query($query);
		}

		// --------------------------------------------------
		// update_product
		// --------------------------------------------------

		function update_product($product_id, $category_id, $name, $description, $quantity, $unit_price, $vat)
		{
			return $this->query('
				UPDATE shop_product
				SET	category_id = '.$category_id.',
					name = "'.$name.'",
					description = "'.$description.'",
					quantity = '.$quantity.',
					unit_price = '.$unit_price.',
					vat = '.$vat.'
				WHERE product_id = '.$product_id);
		}

		// --------------------------------------------------
		// update_category
		// --------------------------------------------------

		function update_category($category_id, $name, $description)
		{
			return $this->query('
				UPDATE shop_category
				SET	name = "'.$name.'",
					description = "'.$description.'"
				WHERE category_id = '.$category_id);
		}

		// --------------------------------------------------
		// delete
		// --------------------------------------------------

		function delete($table, $id)
		{
			return $this->query('
				DELETE FROM '.$table.'
				WHERE '.$table.'_id = '.$id);
		}

		// --------------------------------------------------
		// is_product_available
		// --------------------------------------------------

		function is_product_available($user_id, $product_id)
		{
			$result = $this->query('
				SELECT quantity
				FROM shop_product
				WHERE product_id = '.$product_id);

			if ($row = mysql_fetch_array($result))
			{
				return $row['quantity'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// cancel_sales_order
		// --------------------------------------------------

		function cancel_sales_order($sales_order_id)
		{
			$this->query('SET AUTOCOMMIT = 0');

			$r0 = $this->query('
				DELETE FROM shop_sales_order
				WHERE sales_order_id = '.$sales_order_id);

			$r1 = $this->query('
				DELETE FROM shop_sales_order_item WHERE sales_order_id = '.$sales_order_id);

			if ($r0 && $r1)
			{
				$this->query('COMMIT');
				$result = 1;
			}
			else
			{
				$this->query('ROLLBACK');
				$result = 0;
			}

			$this->query('SET AUTOCOMMIT = 1');

			return $result;
		}

		// --------------------------------------------------
		// get_order
		// --------------------------------------------------

		function get_order($sales_order_id)
		{
			$result = $this->query('
				SELECT user.user_id, first_name, last_name, street, postal_code, city, sales_order_id, order_date, payment_date, shipping_date
				FROM shop_user JOIN shop_sales_order ON shop_user.user_id = shop_sales_order.user_id
				WHERE sales_order_id = '.$sales_order_id);

			return $row = mysql_fetch_array($result);
		}

		// --------------------------------------------------
		// get_order_items
		// --------------------------------------------------

		function get_order_items($sales_order_id)
		{
			$result = $this->query('
				SELECT product.product_id as product_id, product.name as name, sales_order_item.quantity as quantity, (sales_order_item.unit_price) as price, sales_order_item.vat as vat
				FROM shop_sales_order_item JOIN shop_product ON shop_sales_order_item.product_id = shop_product.product_id
				WHERE sales_order_id = '.$sales_order_id);

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$order_items[$i++] = $row;
			}

			return $order_items;
		}

		// --------------------------------------------------
		// get_order_price
		// --------------------------------------------------

		function get_order_price($sales_order_id)
		{
			$result = $this->query('
				SELECT SUM(quantity * unit_price * (1 + vat / 100)) as price
				FROM sales_order_item
				WHERE sales_order_id = '.$sales_order_id.'
				GROUP BY sales_order_id');

			$row = mysql_fetch_array($result);

			return $row['price'];
		}

		// --------------------------------------------------
		// get_order_quantity
		// --------------------------------------------------

		function get_order_quantity($sales_order_id)
		{
			$result = $this->query('
				SELECT SUM(quantity) as quantity
				FROM shop_sales_order_item
				WHERE shop_sales_order_id = '.$sales_order_id);

			$row = mysql_fetch_array($result);

			return $row['quantity'];
		}

		// --------------------------------------------------
		// get_user_orders
		// --------------------------------------------------

		function get_user_orders($user_id)
		{
			$result = $this->query('
				SELECT user.user_id, first_name, last_name, sales_order_id, order_date, payment_date, shipping_date
				FROM shop_user JOIN shop_sales_order ON shop_user.user_id = shop_sales_order.user_id
				WHERE shop_sales_order.user_id = '.$user_id);

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$orders[$i++] = $row;
			}

			return $orders;
		}

		// --------------------------------------------------
		// get_orders
		// --------------------------------------------------

		function get_orders()
		{
			$result = $this->query('
				SELECT user.user_id, first_name, last_name, sales_order_id, order_date, payment_date, shipping_date
				FROM shop_user JOIN shop_sales_order ON shop_user.user_id = shop_sales_order.user_id');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$orders[$i++] = $row;
			}

			return $orders;
		}

		// --------------------------------------------------
		// delete_category
		// --------------------------------------------------

		function delete_category($category_id)
		{
			return $this->query(
				'DELETE FROM category WHERE category_id = '.$category_id);
		}

		// --------------------------------------------------
		// delete_product
		// --------------------------------------------------

		function delete_product($product_id)
		{
			$this->query('SET AUTOCOMMIT = 0');

			$r0 = $this->query('DELETE FROM shop_cart WHERE product_id = '.$product_id);

			$r1 = $this->query('DELETE FROM shop_product WHERE product_id = '.$product_id);

			if ($r0 && $r1)
			{
				$this->query('COMMIT');

				$result = 1;
			}
			else
			{
				$this->query('ROLLBACK');

				$result = 0;
			}

			$this->query('SET AUTOCOMMIT = 1');

			return $result;
		}

		// --------------------------------------------------
		// add_payment_method
		// --------------------------------------------------

		function add_payment_method($name)
		{
			return $this->query('INSERT INTO shop_payment_method (name) VALUES ("'.$name.'")');
		}

		// --------------------------------------------------
		// add_shipping_method
		// --------------------------------------------------

		function add_shipping_method($name, $cost)
		{
			return $this->query('INSERT INTO shop_shipping_method (name, cost) VALUES ("'.$name.'", '.$cost.')');
		}

		// --------------------------------------------------
		// add_comment
		// --------------------------------------------------

		function add_comment($product_id, $user_id, $content)
		{
			return $this->query('
				INSERT INTO shop_comment (product_id, user_id, date, content)
				VALUES ('.$product_id.', '.$user_id.', "'.date('Y-m-d H:i:s').'", "'.$content.'")');
		}

		// --------------------------------------------------
		// make_order
		// --------------------------------------------------

		function make_order($user_id, $payment_method_id, $shipping_method_id)
		{
			// Begin transaction.

			$this->query('SET AUTOCOMMIT = 0');

			// Add new sales order.

			$r0 = $this->query('
				INSERT INTO shop_sales_order (user_id, payment_method_id, shipping_method_id, order_date)
				VALUES ('.$user_id.', '.$payment_method_id.', '.$shipping_method_id.', "'.date('Y-m-d').'")');

			// Get last sales order ID.

			$r1 = $this->query('SELECT @@identity');
			$sales_order_id = mysql_fetch_array($r1);

			// Get products from cart.

			$cart_items = $this->query('
				SELECT product_id AS product_id, cart.quantity AS quantity, product.unit_price AS unit_price, product.vat AS vat
				FROM shop_cart JOIN shop_product ON shop_cart.product_id = shop_product.product_id
				WHERE user_id = '.$user_id);

			// Add sales order items and remove products from store.

			while ($row = mysql_fetch_array($cart_items))
			{
				$r2 = $this->query('
					INSERT INTO shop_sales_order_item (sales_order_id, product_id, quantity, unit_price, vat)
					VALUES ('.$sales_order_id[0].', '.$row['product_id'].', '.$row['quantity'].', '.$row['unit_price'].', '.$row['vat'].')');

				$r3 = $this->query('
					UPDATE shop_product SET quantity = quantity - '.$row['quantity'].'
					WHERE product_id = '.$row['product_id']);

				if ($r2 == 0 || $r3 == 0)
				{
					break;
				}
			}

			// Delete items from cart.

			$r4 = $this->query('DELETE FROM shop_cart WHERE user_id = '.$user_id);

			// End transaction.

			if ($r0 && $r1 && $r2 && $r3 && $r4)
			{
				$this->query('COMMIT');
				$result = 1;
			}
			else
			{
				$this->query('ROLLBACK');
				$result = 0;
			}

			$this->query('SET AUTOCOMMIT = 1');

			return $result;
		}

		// --------------------------------------------------
		// get_user
		// --------------------------------------------------

		function get_user($user_id)
		{
			$result = $this->query(
				'SELECT * FROM shop_user WHERE user_id = '.$user_id);

			return $row = mysql_fetch_array($result);
		}

		// --------------------------------------------------
		// get_user_order_quantity
		// --------------------------------------------------

		function get_user_order_quantity($user_id)
		{
			$result = $this->query('
				SELECT COUNT(sales_order_id) AS order_quantity
				FROM shop_sales_order
				WHERE user_id = '.$user_id);

			if ($row = mysql_fetch_array($result))
			{
				return $row['order_quantity'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// get_user_payed_order_quantity
		// --------------------------------------------------

		function get_user_payed_order_quantity($user_id)
		{
			$result = $this->query('
				SELECT COUNT(sales_order_id) AS order_quantity
				FROM shop_sales_order
				WHERE user_id = '.$user_id.' AND payment_date IS NOT NULL');

			if ($row = mysql_fetch_array($result))
			{
				return $row['order_quantity'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// get_user_shipped_order_quantity
		// --------------------------------------------------

		function get_user_shipped_order_quantity($user_id)
		{
			$result = $this->query('
				SELECT COUNT(sales_order_id) AS order_quantity
				FROM shop_sales_order
				WHERE user_id = '.$user_id.' AND shipping_date IS NOT NULL');

			if ($row = mysql_fetch_array($result))
			{
				return $row['order_quantity'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// get_user_comment_quantity
		// --------------------------------------------------

		function get_user_comment_quantity($user_id)
		{
			$result = $this->query('
				SELECT COUNT(comment_id) AS comment_quantity
				FROM comment
				WHERE user_id = '.$user_id);

			if ($row = mysql_fetch_array($result))
			{
				return $row['comment_quantity'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// add_category
		// --------------------------------------------------

		function add_category($name, $description)
		{
			return $this->query('
				INSERT INTO category (name, description)
				VALUES ("'.$name.'", "'.$description.'")');
		}

		// --------------------------------------------------
		// add_product
		// --------------------------------------------------

		function add_product($category_id, $name, $description, $quantity, $unit_price, $vat)
		{
			return $this->query('
				INSERT INTO product (category_id, name, description, quantity, unit_price, vat)
				VALUES ('.$category_id.', "'.$name.'", "'.$description.'", '.$quantity.', '.$unit_price.', '.$vat.')');
		}

		// --------------------------------------------------
		// get_users
		// --------------------------------------------------

		function get_users()
		{
			$result = $this->query('
				SELECT user_id, login, permissions, first_name, last_name, street, postal_code, city, e_mail, send_news
				FROM user');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$users[$i++] = $row;
			}

			return $users;
		}

		// --------------------------------------------------
		// get_product
		// --------------------------------------------------

		function get_product($product_id)
		{
			$result = $this->query(
				'SELECT * FROM product WHERE product_id = '.$product_id);

			return $row = mysql_fetch_array($result);
		}

		// --------------------------------------------------
		// get_category
		// --------------------------------------------------

		function get_category($category_id)
		{
			$result = $this->query(
				'SELECT * FROM category WHERE category_id = '.$category_id);

			return $row = mysql_fetch_array($result);
		}

		// --------------------------------------------------
		// get_product_comments
		// --------------------------------------------------

		function get_product_comments($product_id)
		{
			$result = $this->query('
				SELECT comment_id, product_id, user.user_id, date, content, user.login
				FROM comment JOIN user ON comment.user_id = user.user_id
				WHERE product_id = '.$product_id.' ORDER BY date DESC');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$comments[$i++] = $row;
			}

			return $comments;
		}

		// --------------------------------------------------
		// list_tables
		// --------------------------------------------------

		function list_tables()
		{
			$result = $this->query('SHOW TABLES');

			$i = 0;

			while ($row = mysql_fetch_array($result))
			{
				$tables[$i++] = $row[0];
			}

			return $tables;
		}

		// --------------------------------------------------
		// get_setting
		// --------------------------------------------------

		function get_setting($name)
		{
			$result = $this->query('SELECT value FROM shop_setting WHERE name LIKE "'.$name.'"');

			if ($result)
			{
				$row = mysql_fetch_array($result);

				return $row['value'];
			}
			else
			{
				return 0;
			}
		}

		// --------------------------------------------------
		// set_setting
		// --------------------------------------------------

		function set_setting($name, $value)
		{
			return $this->query('
				UPDATE setting
				SET value = "'.$value.'"
				WHERE name LIKE "'.$name.'"');
		}
	};
?>