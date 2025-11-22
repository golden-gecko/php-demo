<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
	<head>
		<title>MTG Market</title>
		<meta http-equiv="content-type" content="text/html; charset=windows-1250" />
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
	</head>
<body>
	<div class="header"><a href="index.php">MTG Shop</a></div>
	<div class="top">
		<p>
		{if $smarty.session.login != ''}
			<a href="index.php?p=user_profile&amp;i={$smarty.session.user_id}">My Profile</a>
			|| <a href="index.php?p=cart">Cart</a>
			|| <a href="index.php?p=order">Order</a>
			|| <a href="index.php?a=log_out">Log Out</a>
		{else}
			<a href="index.php?p=register">Register</a>
		{/if}
		</p>
	</div>
	<div class="left">
		<p>Categories</p>
		<div class="categories">
		{foreach from=$categories item=i}
			<p><a href="index.php?p=products&amp;category_id={$i[0]}&amp;sort=name">{$i[1]}</a></p>
		{foreachelse}
			<p>Not found.</p>
		{/foreach}
		</div>
		<div class="white_line"></div>
		<p>Quick Search</p>
		<div class="search">
			<form action="index.php" method="get">
				<p>
					<input name="p" type="hidden" value="quick_search" />
					<input name="quick_search" value="{$smarty.get.quick_search}" />
				</p>
				<p><input class="button" type="submit" value="Search" style="padding: 2px; margin: 0;" /></p>
			</form>
		</div>
		<div class="white_line"></div>
		<p>Random Offerts</p>
		<div class="random_offerts">
		{foreach from=$random_offerts item=i}
			<p><a href="index.php?p=product_profile&amp;i={$i[0]}">{$i[2]}</a></p>
			<p>{$i[5]} zł</p>
		{foreachelse}
			<p>Not found.</p>
		{/foreach}
		</div>
		{if $smarty.session.permissions >= 2}
			<div class="white_line"></div>
			<p>Administration Panel</p>
			<div class="administration">
				<p><a href="index.php?p=admin_news">Newsy</a></p>
				<p><a href="index.php?p=admin_categories">Categories</a></p>
				<p><a href="index.php?p=admin_products">Products</a></p>
				<p><a href="index.php?p=admin_users">Users</a></p>
				<p><a href="index.php?p=admin_orders">Orders</a></p>
				<p><a href="index.php?p=admin_payment_shipping">Payment / Shipping</a></p>
				<p><a href="index.php?p=admin_database">Database</a></p>
			</div>
		{/if}
	</div>
	<div class="right">
		{if $smarty.session.login == ''}
			<p>Log In</p>
			<div class="log_in">
				<form action="index.php?a=log_in" method="post">
					<p><input name="login" /></p>
					<p><input name="password" type="password" /></p>
					<p><input class="button" type="submit" value="Log in" /></p>
				</form>
			</div>
			<div class="white_line"></div>
		{/if}
		<p>Cart</p>
		<div class="cart">
			{if $smarty.session.login == ''}
				<p>Log in,<br />to see your cart.</p>
			{elseif $cart}
				<table class="cart">
				{foreach from=$cart item=i}
					<tr>
						<td class="quantity">{$i[2]} x </td>
						<td><a href="index.php?p=product_profile&amp;i={$i[0]}">{$i[1]}</a></td>
					</tr>
				{/foreach}
				</table>
				<p>{$cart_price} zł</p>
			{else}
				<p>Your cart is empty.</p>
			{/if}
		</div>
		<div class="white_line"></div>
		<p>Contact</p>
		<div class="contact">
			<p><a href="mailto:wholisz@ath.eu?subject=MTG%20Market">wholisz@ath.eu</a></p>
		</div>
		<div class="white_line"></div>
		<p>Languages</p>
		<div class="languages">
			<p>
				<a href="index.php?a=change_language&amp;i=pl"><img src="img/pl.gif" alt="Polski" /></a>
				<a href="index.php?a=change_language&amp;i=en"><img src="img/en.gif" alt="English" /></a>
			</p>
		</div>
	</div>
	{if $subheaderContent != ''}
		<div class="message"><p>{$subheaderContent}</p></div>
	{/if}

	<div class="middle">

		{if $smarty.get.p == 'register'}

		<!-- Register -->

		<h1>Register</h1>
		<div class="blue_line"></div>
		<form action="index.php?p=confirm_register" method="post">
		<table class="search_form">
			<tr><td>Login</td>
				<td><input class="text" name="login" value="{$smarty.post.login}" /></td></tr>
			<tr><td>Password</td>
				<td><input class="text" name="password" type="password" value="{$smarty.post.password}" /></td></tr>
			<tr><td>Retype password</td>
				<td><input class="text" name="retype_password" type="password" value="{$smarty.post.retype_password}" /></td></tr>
			<tr><td>E-mail</td>
				<td><input class="text" name="e_mail" value="{$smarty.post.e_mail}" /></td></tr>
			<tr><td colspan="2"><div class="blue_line"></div></td></tr>
			<tr><td>First name</td>
				<td><input class="text" name="first_name" value="{$smarty.post.first_name}" /></td></tr>
			<tr><td>Last name</td>
				<td><input class="text" name="last_name" value="{$smarty.post.last_name}" /></td></tr>
			<tr><td>Street</td>
				<td><input class="text" name="street" value="{$smarty.post.street}" /></td></tr>
			<tr><td>Postal code</td>
				<td><input class="text" name="postal_code" value="{$smarty.post.postal_code}" /></td></tr>
			<tr><td>City</td>
				<td><input class="text" name="city" value="{$smarty.post.city}" /></td></tr>
			<tr><td colspan="2"><div class="blue_line"></div></td></tr>
			<tr><td><input class="button" type="reset" value="Reset" /></td>
				<td><input class="button" type="submit" value="Register" /></td></tr>
		</table>
		</form>

		{elseif $smarty.get.p == 'cart' && $smarty.session.login}

			<!-- Cart -->

			<h1>Your cart</h1>
			<div class="blue_line"></div>
			<table class="cart">
				<tr>
					<td><h2>Name</h2></td>
					<td><h2>Quantity</h2></td>
					<td><h2>Unit price</h2></td>
					<td><h2>Delete</h2></td>
				</tr>
			{foreach from=$cart item=i}
				<tr>
					<td>{$i[1]}</td>
					<td>{$i[2]}</td>
					<td>{$i[3]} zł</td>
					<td><a href="index.php?p=cart&amp;a=delete_from_cart&amp;i={$i[0]}">Delete</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
				<tr>
					<td><h2>All</h2></td>
					<td><h2>{$cart_quantity}</h2></td>
					<td><h2>{$cart_price} zł</h2></td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<div class="blue_line"></div>
			<p><a href="index.php?p=order"><button>Order</button></a></p>

		{elseif $smarty.get.p == 'order' && $smarty.session.permissions >= 1}

			<!-- Order -->

			<h1>Your order</h1>
			<div class="blue_line"></div>
			<table class="order">
				<tr>
					<td><h2>Name</h2></td>
					<td><h2>Quantity</h2></td>
					<td><h2>Unit price</h2></td>
				</tr>
			{foreach from=$cart item=i}
				<tr>
					<td>{$i[1]}</td>
					<td class="quantity">{$i[2]}</td>
					<td class="unit_price">{$i[3]} zł</td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
				<tr>
					<td><h2>All</h2></td>
					<td><h2>{$cart_quantity}</h2></td>
					<td><h2>{$cart_price} zł</h2></td>
				</tr>
			</table>
			<div class="blue_line"></div>
			<form action="index.php" method="get">
				<table class="method">
					<tr>
						<td>Choose payment method</td>
						<td>
							<input name="p" type="hidden" value="confirm_order" />
							<select name="payment_method_id">
							{foreach from=$payment_methods item=i}
								<option value="{$i[0]}">{$i[1]}</option>
							{foreachelse}
								<option>Not found.</option>
							{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td>Chosse shipping method</td>
						<td><select name="shipping_method_id">
						{foreach from=$shipping_methods item=i}
							<option value="{$i[0]}">{$i[1]}</option>
						{foreachelse}
							<option>Not found.</option>
						{/foreach}
						</select></td>
					</tr>
				</table>
				<div class="blue_line"></div>
				<h1>Twoje dane</h1>
				<div class="blue_line"></div>
				<p>Your order will be sent at address below.</p>
				<p>
				{$user[4]} {$user[5]}<br />
				{$user[6]}<br />
				{$user[7]} {$user[8]}
				</p>
				<div class="blue_line"></div>
				<p><input class="button" type="submit" value="Confirm" /></p>
			</form>

		{elseif $smarty.get.p == 'products'}

			<!-- Products -->

			<h1>Search</h1>
			<div class="blue_line"></div>
			<form action="index.php" method="get">
				<table class="search_form">
					<tr><td>Category</td><td><input name="p" type="hidden" value="products" /><select name="category_id">
						<option value="">All</option>
					{foreach from=$categories item=i}
						<option value="{$i[0]}"
						{if $smarty.get.category_id == $i[0]}
							selected="selected"
						{/if}
						>{$i[1]}</option>
					{/foreach}
					</select></td></tr>
					<tr><td>Name</td><td><input name="name" value="{$smarty.get.name}" /></td></tr>
					<tr><td>Quantity</td><td><input name="quantity" value="{$smarty.get.quantity}" /></td></tr>
					<tr><td>Unit price</td><td><input name="unit_price" value="{$smarty.get.unit_price}" /><input name="sort" type="hidden" value="{$smarty.get.sort}" /></td></tr>
					<tr><td><input class="button" type="reset" value="Reset" /></td><td><input class="button" type="submit" value="Search" /></td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<h1>Products</h1>
			<div class="blue_line"></div>
			<table class="products">
				<tr>
					<td><h2><a href="index.php?p=products&amp;category_id={$smarty.get.category_id}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;sort=name">Name</a></h2></td>
					<td><h2><a href="index.php?p=products&amp;category_id={$smarty.get.category_id}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;sort=quantity">Quantity</a></h2></td>
					<td><h2><a href="index.php?p=products&amp;category_id={$smarty.get.category_id}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;sort=unit_price">Unit price</a></h2></td>
					<td><h2>Add to cart</h2></td>
				</tr>
			{foreach from=$products item=i}
				<tr>
					<td>{$i[2]}</td>
					<td class="quantity">{$i[4]}</td>
					<td class="unit_price">{$i[5]} zł</td>
					<td><a href="index.php?p=products&amp;category_id={$smarty.get.category_id}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;sort={$smarty.get.sort}&amp;a=add_to_cart&amp;i={$i[0]}">Add to cart</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>

		{elseif $smarty.get.p == 'admin_news' && $smarty.session.permissions >= 3}

			<!-- Administration News -->

			<h1>Add</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=admin_news&amp;a=add_news" method="post">
				<p><textarea class="news" name="content" cols="50" rows="5"></textarea></p>
				<p><input class="button" type="reset" value="Reset"/> <input class="button" type="submit" value="Add" /></p>
			</form>
			<div class="blue_line"></div>
			<h1>Search</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=admin_news" method="get">
				<table class="search_form">
					<tr><td>Content</td><td><input name="content" value="{$smarty.get.content}" /></td></tr>
					<tr><td>Author</td><td><input name="login" value="{$smarty.get.login}" /></td></tr>
					<tr><td><input class="button" type="reset" value="Reset"/></td><td><input class="button" type="submit" value="Search" /></td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<h1>Edit / Delete</h1>
			<div class="blue_line"></div>
			<table class="admin_news">
				<tr>
					<td><h2><a href="index.php?p=admin_news&amp;content={$smarty.get.content}&amp;login={$smarty.get.author}&amp;sort=date">Data</a></h2></td>
					<td><h2><a href="index.php?p=admin_news&amp;content={$smarty.get.content}&amp;login={$smarty.get.author}&amp;sort=content">Content</a></h2></td>
					<td><h2><a href="index.php?p=admin_news&amp;content={$smarty.get.content}&amp;login={$smarty.get.author}&amp;sort=login">Author</a></h2></td>
					<td><h2>Delete</h2></td>
				</tr>
			{foreach from=$news item=i}
				<tr>
					<td>{$i[1]}</td>
					<td>{$i[2]}</td>
					<td><a href="index.php?p=user_profile&amp;i={$i[3]}">{$i[4]}</a></td>
					<td><a href="index.php?p=admin_news&amp;a=delete_news&amp;i={$i[0]}">Delete</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>

		{elseif $smarty.get.p == 'admin_categories' && $smarty.session.permissions >= 4}

			<!-- Administration Categories -->

			<h1>Add</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=admin_categories&amp;a=add_category" method="post">
				<table class="search_form">
					<tr><td>Name</td><td><input name="name" /></td></tr>
					<tr><td>Description</td><td><textarea name="description" rows="2" cols="16"></textarea></td></tr>
					<tr><td><input class="button"type="reset" value="Reset" /></td><td><input class="button"type="submit" value="Add" /></td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<h1>Search</h1>
			<div class="blue_line"></div>
			<form action="index.php" method="get">
				<table class="search_form">
					<tr><td>Name</td><td><input name="p" type="hidden" value="admin_categories" /><input name="name" value="{$smarty.get.name}" /></td></tr>
					<tr><td>Description</td><td><input name="description" value="{$smarty.get.description}" /><input name="sort" type="hidden" value="name" /></td></tr>
					<tr><td><input class="button" type="reset" value="Reset" /></td><td><input class="button" type="submit" value="Search" /></td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<h1>Edit / Delete</h1>
			<div class="blue_line"></div>
			<table>
				<tr>
					<td><h2><a href="index.php?p=admin_categories&amp;name={$smarty.get.name}&amp;description={$smarty.get.description}&amp;sort=name">Name</a></h2></td>
					<td><h2><a href="index.php?p=admin_categories&amp;name={$smarty.get.name}&amp;description={$smarty.get.description}&amp;sort=description">Description</a></h2></td>
					<td><h2>Delete</h2></td>
				</tr>
			{foreach from=$admin_categories item=i}
				<tr>
					<td><a href="index.php?p=category_profile&amp;i={$i[0]}">{$i[1]}</a></td>
					<td>{$i[2]}</td>
					<td><a href="index.php?p=admin_categories&amp;a=delete_category&amp;i={$i[0]}">Delete</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>

		{elseif $smarty.get.p == 'admin_products' && $smarty.session.permissions >= 4}

			<!-- Administration Products -->

			<h1>Add</h1>
			<div class="blue_line"></div>
			{if $categories == ''}
				Not found. <a href="index.php?p=admin_categories">Add</a> one or more categories first.
				<div class="blue_line"></div>
			{/if}
			<form action="index.php?p=admin_products&amp;a=add_product" method="post">
				<table class="search_form">
					<tr><td>Category</td><td><select name="category_id">
					{foreach from=$categories item=i}
						<option value="{$i[0]}">{$i[1]}</option>
					{foreachelse}
						<option value="">Brak kategorii.</option>
					{/foreach}
					</select></td></tr>
					<tr><td>Name</td><td><input name="name" /></td></tr>
					<tr><td>Description</td><td><textarea name="description" rows="2" cols="14"></textarea></td></tr>
					<tr><td>Quantity</td><td><input name="quantity" /></td></tr>
					<tr><td>Unit price</td><td><input name="unit_price" /></td></tr>
					<tr><td>VAT</td><td>
						<select name="vat">
						{foreach from=$vat item=i}
							<option value="{$i}">{$i}%</option>
						{/foreach}
						</select>
					</td></tr>
					<tr><td><input class="button" type="reset" value="Reset" /></td><td>
						<input class="button" type="submit" value="Add" />
					</td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<h1>Search</h1>
			<div class="blue_line"></div>
			<form action="index.php?" method="get">
				<table class="search_form">
					<tr><td>Category</td><td><input name="p" type="hidden" value="admin_products" /><select name="category_id">
						<option value="">All</option>
					{foreach from=$categories item=i}
						<option value="{$i[0]}"
						{if $smarty.get.category_id == $i[0]}
							selected="selected"
						{/if}
						>{$i[1]}</option>
					{/foreach}
					</select></td></tr>
					<tr><td>Name</td><td><input name="name" value="{$smarty.get.name}" /></td></tr>
					<tr><td>Description</td><td><input name="description" value="{$smarty.get.description}" /></td></tr>
					<tr><td>Quantity</td><td><input name="quantity" value="{$smarty.get.quantity}" /></td></tr>
					<tr><td>Unit price</td><td><input name="unit_price" value="{$smarty.get.unit_price}" /></td></tr>
					<tr><td>VAT</td><td><select name="vat">
						<option value="">All</option>
					{foreach from=$vat item=i}
						<option value="{$i}"
						{if $smarty.get.vat == $i}
							selected="selected"
						{/if}
						>{$i}%</option>
					{/foreach}
					</select><input name="sort" type="hidden" value="{$smarty.get.sort}" /></td></tr>
					<tr><td><input class="button" type="reset" value="Reset" /></td><td><input class="button" type="submit" value="Search" /></td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<h1>Edit / Delete</h1>
			<div class="blue_line"></div>
			<table class="admin_products">
				<tr>
					<td><h2><a href="index.php?p=admin_products&amp;category_id={$smarty.get.category}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;vat={$smarty.get.vat}&amp;sort=name">Name</a></h2></td>
					<td><h2><a href="index.php?p=admin_products&amp;category_id={$smarty.get.category}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;vat={$smarty.get.vat}&amp;sort=quantity">Quantity</a></h2></td>
					<td><h2><a href="index.php?p=admin_products&amp;category_id={$smarty.get.category}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;vat={$smarty.get.vat}&amp;sort=unit_price">Unit price</a></h2></td>
					<td><h2><a href="index.php?p=admin_products&amp;category_id={$smarty.get.category}&amp;name={$smarty.get.name}&amp;quantity={$smarty.get.quantity}&amp;unit_price={$smarty.get.unit_price}&amp;vat={$smarty.get.vat}&amp;sort=vat">VAT</a></h2></td>
					<td><h2>Delete</h2></td>
				</tr>
			{foreach from=$products item=i}
				<tr>
					<td><a href="index.php?p=product_profile&amp;i={$i[0]}">{$i[2]}</a></td>
					<td>{$i[4]}</td>
					<td>{$i[5]} zł</td>
					<td>{$i[6]} %</td>
					<td><a href="index.php?p=admin_products&amp;a=delete_product&amp;i={$i[0]}">Delete</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>

		{elseif $smarty.get.p == 'admin_users' && $smarty.session.permissions >= 4}

			<!-- Administration Users -->

			<h1>Użytkownicy</h1>
			<div class="blue_line"></div>
			<table class="admin_clients">
				<tr>
					<td><h2>Login</h2></td>
					<td><h2>Name</h2></td>
					<td><h2>City</h2></td>
					<td><h2>E-mail</h2></td>
					<td>&nbsp;</td>
				</tr>
			{foreach from=$users item=i}
				<tr>
					<td><a href="index.php?p=user_profile&amp;i={$i[0]}">{$i[1]}</a></td>
					<td>{$i[4]} {$i[3]}</td>
					<td>{$i[7]}</td>
					<td>{$i[8]}</td>
					<td><a href="index.php?p=admin_users&amp;a=delete_user&amp;i={$i[0]}">Delete</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>

		{elseif $smarty.get.p == 'admin_orders' && $smarty.session.permissions >= 4}

			<!-- Administration Orders -->

			<h1>Zamówienia</h1>
			<div class="blue_line"></div>
			<table class="admin_orders">
				<tr>
					<td><h2>Nr</h2></td>
					<td><h2>Client</h2></td>
					<td><h2>Order date</h2></td>
					<td><h2>Payment date</h2></td>
					<td><h2>Shipping date</h2></td>
					<td><h2>Cancel</h2></td>
				</tr>
			{foreach from=$admin_orders item=i}
				<tr>
					<td><a href="index.php?p=order_profile&amp;i={$i[3]}">Zamówienie nr {$i[3]}</a></td>
					<td><a href="index.php?p=user_profile&amp;i={$i[0]}">{$i[2]} {$i[1]}</a></td>
					<td>{$i[4]}</td>
					<td>
					{if $i[5]}
						{$i[5]}
					{else}
						No payment.
					{/if}
					</td>
					<td>
					{if $i[6]}
						{$i[6]}
					{else}
						No shipping.
					{/if}
					</td>
					<td>
					{if $admin_orders[6] == ''}
						<a href="index.php?p=admin_orders&amp;a=cancel_order&amp;i={$i[3]}">Cancel</a>
					{else}
						Cannot be canceled.
					{/if}
					</td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>

		{elseif $smarty.get.p == 'admin_payment_shipping' && $smarty.session.permissions >= 4}

			<!-- Administration Payment / Shipping -->

			<h1>Sposób płatności</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=admin_payment_shipping&amp;a=add_payment_method" method="post">
				<table class="search_form">
					<tr><td>Name</td><td><input class="text" name="name" /></td></tr>
					<tr><td colspan="2"><input class="button" type="submit" value="Add" /></td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<table class="two_col">
				<tr>
					<td><h2>Name</h2></td>
					<td><h2>Delete</h2></td>
				</tr>
			{foreach from=$payment_methods item=i}
				<tr>
					<td>{$i[1]}</td>
					<td><a href="index.php?p=admin_payment_shipping&amp;a=delete_payment_method&amp;i={$i[0]}">Delete</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>
			<div class="blue_line"></div>
			<h1>Sposób wysyłki</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=admin_payment_shipping&amp;a=add_shipping_method" method="post">
				<table class="search_form">
					<tr><td>Name</td><td><input class="text" name="name" /></td></tr>
					<tr><td>Koszt</td><td><input class="text" name="cost" /></td></tr>
					<tr><td colspan="2"><input class="button" type="submit" value="Add" /></td></tr>
				</table>
			</form>
			<div class="blue_line"></div>
			<table class="three_col">
				<tr>
					<td><h2>Name</h2></td>
					<td><h2>Koszt</h2></td>
					<td><h2>Delete</h2></td>
				</tr>
			{foreach from=$shipping_methods item=i}
				<tr>
					<td>{$i[1]}</td>
					<td>{$i[2]} zł</td>
					<td><a href="index.php?p=admin_payment_shipping&amp;a=delete_shipping_method&amp;i={$i[0]}">Delete</a></td>
				</tr>
			{foreachelse}
				<tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr>
			{/foreach}
			</table>

		{elseif $smarty.get.p == 'admin_database' && $smarty.session.permissions >= 4}

			<!-- Administration Database -->

			<h1>Zarządzanie bazą danych</h1>
			<div class="blue_line"></div>
			<h2>Wykonaj zapytanie</h2>
			<div class="blue_line"></div>
			<p style="margin-top: 10px;">
			W przypadku wpisania więcej niż jednego zapytania (każde <strong>musi</strong> zostać oddzielone <strong>średnikiem</strong>),<br />
			zostaną wyświetlone wyniki tylko <strong>ostatniego</strong> z nich.</p>
			<form action="index.php?p=admin_database&amp;a=execute_query" method="post">
				<p><textarea name="query" rows="10" cols="20" style="width: 80%; margin-top: 10px; font-family: Courier New;">{$smarty.post.query}</textarea></p>
				<p><input class="button" type="submit" value="Wykonaj" /></p>
			</form>
			<table>
			{foreach from=$result item=i}
				<tr>
				{foreach from=$i item=j}
					<td>{$j}</td>
				{/foreach}
				</tr>
			{/foreach}
			</table>
			<!--
			<div class="blue_line"></div>
			<h2>Eksport</h2>
			<div class="blue_line"></div>
			<p>a</p>
			<div class="blue_line"></div>
			<h2>Import</h2>
			<div class="blue_line"></div>
			<p style="margin-bottom: 5px;">a</p>
			-->

		{elseif $smarty.get.p == 'user_profile' && $smarty.session.permissions >= 1}

			<!-- User Profile -->

			<h1>Profil użytkownika</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=user_profile&amp;i={$user[0]}&amp;a=update_user&amp;user_id={$user[0]}" method="post">
			<table class="profile">
			{if $smarty.session.user_id == $user[0]}
				<tr><td>Login</td><td>{$user[1]}<input name="login" type="hidden" value="{$smarty.session.login}" /><input name="permissions" type="hidden" value="{$smarty.session.permissions}" /></td></tr>
				<tr><td>Hasło</td><td><input class="text" name="password" type="password" /></td></tr>
				<tr><td>Potwórz hasło</td><td><input class="text" name="retype_password" type="password" /></td></tr>
				<tr><td>E-mail</td><td><input class="text" name="e_mail" value="{$user[9]}" /></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td colspan="2"><h2>Dane osobowe</h2></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td>Imię</td><td><input class="text" name="first_name" value="{$user[4]}" /></td></tr>
				<tr><td>Nazwisko</td><td><input class="text" name="last_name" value="{$user[5]}" /></td></tr>
				<tr><td>Ulica</td><td><input class="text" name="street" value="{$user[6]}" /></td></tr>
				<tr><td>Kod-pocztowy</td><td><input class="text" name="postal_code" value="{$user[7]}" /></td></tr>
				<tr><td>Miejscowość</td><td><input class="text" name="city" value="{$user[8]}" /></td></tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td><input class="button" type="reset" value="Reset" /></td>
					<td><input class="button" type="submit" value="Confirm" /></td>
				</tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td colspan="2"><h2>Zamówienia</h2></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td>Złożone</td><td>{$user_order_quantity}</td></tr>
				<tr><td>Zapłacone</td><td>{$user_payed_order_quantity}</td></tr>
				<tr><td>Zrealizowane</td><td>{$user_shipped_order_quantity}</td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td colspan="2"><h2>Komentarze</h2></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td>Quantity</td><td>{$user_comment_quantity}</td></tr>
			{else}
				<tr><td>Login</td><td>{$user[1]}</td></tr>
				<tr><td>E-mail</td><td><a href="mailto:{$user[9]}">{$user[9]}</a></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td colspan="2"><h2>Dane osobowe</h2></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td>Nazwisko i Imię</td><td>{$user[5]} {$user[4]}</td></tr>
				<tr><td>Adres</td><td>{$user[6]}, {$user[7]} {$user[8]}</td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td colspan="2"><h2>Zamówienia</h2></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td>Złożone</td><td>{$user_order_quantity}</td></tr>
				<tr><td>Zapłacone</td><td>{$user_payed_order_quantity}</td></tr>
				<tr><td>Zrealizowane</td><td>{$user_shipped_order_quantity}</td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td colspan="2"><h2>Komentarze</h2></td></tr>
				<tr><td colspan="2"><div class="blue_line"></div></td></tr>
				<tr><td>Quantity</td><td>{$user_comment_quantity}</td></tr>
			{/if}
			</table>
			</form>

		{elseif $smarty.get.p == 'product_profile'}

			<!-- Product Profile -->

			{if $smarty.session.permissions < 4}

			<table class="two_columns">
				<tr><td><h1>{$product[2]}</h1></td><td>&nbsp;</td></tr>
				<tr><td colspan="2"><em>{$product[3]}</em></td></tr>
				<tr><td>Quantity</td><td>{$product[4]}</td></tr>
				<tr><td>Unit price</td><td>{$product[5]} + ({$product[6]}% VAT)</td></tr>
				<tr>
					<td colspan="2">
						<p><a href="index.php?p=product_profile&amp;a=add_to_cart&amp;i={$product[0]}">
							<input class="button" value="Add to cart" />
						</a></p>
					</td>
				</tr>
			</table>

				{if $smarty.session.permissions < 1}

					<div class="blue_line"></div>
					<h2>Komentarze użytkowników</h2>
					<div class="blue_line"></div>
					{foreach from=$product_comments item=i}
						<p class="content">{$i[4]}</p>
						<p class="date"><em>dodane {$i[3]}</em></p>
						<p class="author"><em>przez <a href="index.php?p=user_profile&amp;i={$i[2]}">{$i[5]}</a></em></p>
						<div class="blue_line"></div>
					{foreachelse}
						<p>Add swoją opinię.</p>
						<div class="blue_line"></div>
					{/foreach}
					<form action="index.php?p=product_profile&amp;i={$smarty.get.i}&amp;a=add_comment" method="post">
					<p>
						<input name="product_id" type="hidden" value="{$product[0]}" />
						<textarea class="news" rows="2" cols="12" name="content" style="margin-top: 10px;"></textarea>
					</p>
					<p><input class="button" type="submit" value="Add komnetarz" style="width: 120px;" /></p>
					</form>

				{/if}

			{else}

			<h1>Edit produkt</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=product_profile&amp;a=update_product&amp;i={$i[0]}" method="post">
			<table class="search_form">
				<tr><td>Category</td><td>
				<select name="category_id">
					{foreach from=$categories item=i}
						<option value="{$i[0]}"
						{if $i[0] == $product[0]}
							selected="selected"
						{/if}
						>{$i[1]}</option>
					{/foreach}
					</select>
				</td></tr>
				<tr><td>Name</td><td><input name="name" class="text" value="{$product[2]}" /></td></tr>
				<tr><td>Description</td><td><textarea name="description" rows="2" cols="2">{$product[3]}</textarea></td></tr>
				<tr><td>Quantity</td><td><input name="quantity" class="text" value="{$product[4]}" /></td></tr>
				<tr><td>Unit price</td><td><input name="unit_price" class="text" value="{$product[5]}" /></td></tr>
				<tr><td>VAT</td><td>
				<select name="vat">
				{foreach from=$vat item=i}
					<option value="{$i}"
					{if $i == $product[6]}
						selected="selected"
					{/if}
					>{$i}%</option>
				{/foreach}
				</select>
				</td></tr>
				<tr><td><input class="button" type="reset" value="Reset" /></td><td><input class="button" type="submit" value="Wyślij" /></td></tr>
			</table>
			</form>
			<div class="blue_line"></div>
			<h2>Komentarze użytkowników</h2>
			<div class="blue_line"></div>
			{foreach from=$product_comments item=i}
				<p class="content">{$i[4]}</p>
				<p class="date"><em>dodane {$i[3]}</em></p>
				<p class="author"><em>przez <a href="index.php?p=user_profile&amp;i={$i[2]}">{$i[5]}</a></em></p>
				<div class="blue_line"></div>
			{foreachelse}
				<p>Add swoją opinię.</p>
				<div class="blue_line"></div>
			{/foreach}
			<form action="index.php?p=product_profile&amp;i={$smarty.get.i}&amp;a=add_comment" method="post">
			<p>
				<input name="product_id" type="hidden" value="{$product[0]}" />
				<textarea class="news" rows="2" cols="12" name="content" style="margin-top: 10px;"></textarea>
			</p>
			<p><input class="button" type="submit" value="Add komnetarz" style="width: 120px;" /></p>
			</form>

			{/if}

		{elseif $smarty.get.p == 'category_profile' && $smarty.session.permissions >= 4}

			<!-- Category Profile -->
			<h1>Edit kategorię</h1>
			<div class="blue_line"></div>
			<form action="index.php?p=category_profile&amp;a=update_category&amp;i={$category[0]}" method="post">
			<table class="search_form">
				<tr>
					<td>Name</td>
					<td><input class="text" name="name" value="{$category[1]}" /></td>
				</tr>
				<tr>
					<td>Description</td>
					<td><textarea name="description" rows="2" cols="2">{$category[2]}</textarea></td>
				</tr>
				<tr>
					<td><input class="button" type="reset" value="Reset" /></td>
					<td><input class="button" type="submit" value="Wyślij" /></td>
				</tr>
			</table>
			</form>

		{else}

			<!-- Main Page -->

			<h1>News</h1>
			{foreach from=$news item=i}
				<div class="blue_line"></div>
				<p class="content">{$i[1]}</p>
				<p class="date"><em>dodane {$i[0]}</em></p>
				<p class="author"><em>przez <a href="index.php?p=user_profile&amp;i={$i[2]}">{$i[3]}</a></em></p>
			{/foreach}

		{/if}

	</div>

	<div class="bottom"><p>{$current_date}</p></div>
	<div class="footer">Copyright &copy; 2007 by Wojciech Holisz</div>

	{if $debug}
		<div class="debug"><p>
			Debug<br />
			<br />
			$_SESSION['user_id'] = {$smarty.session.user_id}<br />
			$_SESSION['login'] = {$smarty.session.login}<br />
			$_SESSION['permissions'] = {$smarty.session.permissions}<br />
			<br />
			{foreach from=$debugQueries item=i}
				{$i}<br />
			{foreachelse}
				-
			{/foreach}
		</p></div>
	{/if}

</body>
</html>