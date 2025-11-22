<?php

	$data ='
	
		drop table if exists shop_attribute;
		drop table if exists shop_cart;
		drop table if exists shop_category;
		drop table if exists shop_comment;
		drop table if exists shop_label;
		drop table if exists shop_language;
		drop table if exists shop_message;
		drop table if exists shop_news;
		drop table if exists shop_payment_method;
		drop table if exists shop_product;
		drop table if exists shop_salshop_order;
		drop table if exists shop_salshop_order_item;
		drop table if exists shop_setting;
		drop table if exists shop_shipping_method;
		drop table if exists shop_user;
		drop table if exists shop_vat;

		/*==============================================================*/
		/* Table: attribute                                             */
		/*==============================================================*/
		create table shop_attribute
		(
		   attribute_id         int not null auto_increment,
		   product_id           int not null,
		   name                 char(32) not null,
		   value                char(32) not null,
		   primary key (attribute_id)
		);

		/*==============================================================*/
		/* Table: cart                                                  */
		/*==============================================================*/
		create table shop_cart
		(
		   user_id              int not null,
		   product_id           int not null,
		   quantity             int not null,
		   primary key (product_id, user_id)
		)
		type = InnoDB;

		/*==============================================================*/
		/* Table: category                                              */
		/*==============================================================*/
		create table shop_category
		(
		   category_id          int not null auto_increment,
		   name                 char(32) not null,
		   description          varchar(256),
		   primary key (category_id)
		);

		/*==============================================================*/
		/* Table: comment                                               */
		/*==============================================================*/
		create table shop_comment
		(
		   comment_id           int not null auto_increment,
		   product_id           int not null,
		   user_id              int not null,
		   date                 datetime not null,
		   content              varchar(256) not null,
		   primary key (comment_id)
		);

		/*==============================================================*/
		/* Table: "label"                                               */
		/*==============================================================*/
		create table shop_label
		(
		   label_id             int not null auto_increment,
		   language_id          int not null,
		   name                 char(32) not null,
		   value                char(32) not null,
		   primary key (label_id)
		);

		/*==============================================================*/
		/* Table: language                                              */
		/*==============================================================*/
		create table shop_language
		(
		   language_id          int not null auto_increment,
		   name                 char(32) not null,
		   primary key (language_id)
		);

		INSERT INTO shop_language (name) VALUES ("Polski");
		INSERT INTO shop_language (name) VALUES ("English");

		/*==============================================================*/
		/* Table: message                                               */
		/*==============================================================*/
		create table shop_message
		(
		   message_id           int not null auto_increment,
		   user_id              int not null,
		   mshop_message_id       int,
		   use_user_id          int not null,
		   date                 datetime not null,
		   content              varchar(256) not null,
		   primary key (message_id)
		);

		/*==============================================================*/
		/* Table: news                                                  */
		/*==============================================================*/
		create table shop_news
		(
		   news_id              int not null auto_increment,
		   user_id              int not null,
		   date                 datetime not null,
		   content              varchar(256) not null,
		   primary key (news_id)
		);

		/*==============================================================*/
		/* Table: payment_method                                        */
		/*==============================================================*/
		create table shop_payment_method
		(
		   payment_method_id    int not null auto_increment,
		   name                 char(32) not null,
		   primary key (payment_method_id)
		);

		/*==============================================================*/
		/* Table: product                                               */
		/*==============================================================*/
		create table shop_product
		(
		   product_id           int not null auto_increment,
		   category_id          int not null,
		   name                 char(32) not null,
		   description          varchar(256),
		   quantity             int not null,
		   unit_price           decimal(4,2) not null,
		   vat                  decimal(4,2) not null,
		   primary key (product_id)
		)
		type = InnoDB;

		/*==============================================================*/
		/* Table: salshop_order                                           */
		/*==============================================================*/
		create table shop_salshop_order
		(
		   salshop_order_id       int not null auto_increment,
		   user_id              int not null,
		   payment_method_id    int not null,
		   shipping_method_id   int not null,
		   order_date           date not null,
		   payment_date         date,
		   shipping_date        date,
		   primary key (salshop_order_id)
		)
		type = InnoDB;

		/*==============================================================*/
		/* Table: salshop_order_item                                      */
		/*==============================================================*/
		create table shop_salshop_order_item
		(
		   salshop_order_id       int not null,
		   product_id           int not null,
		   quantity             int not null,
		   unit_price           decimal(4,2) not null,
		   vat                  decimal(4,2) not null,
		   primary key (salshop_order_id, product_id)
		)
		type = InnoDB;

		/*==============================================================*/
		/* Table: shipping_method                                       */
		/*==============================================================*/
		create table shop_shipping_method
		(
		   shipping_method_id   int not null auto_increment,
		   name                 char(32) not null,
		   cost                 decimal(4,2) not null,
		   primary key (shipping_method_id)
		);

		/*==============================================================*/
		/* Table: user                                                  */
		/*==============================================================*/
		create table shop_user
		(
		   user_id              int not null auto_increment,
		   language_id          int not null,
		   login                char(32) not null,
		   password             varchar(100) not null,
		   permissions          smallint not null default 1,
		   first_name           char(32) not null,
		   last_name            char(32) not null,
		   street               char(32) not null,
		   postal_code          char(6) not null,
		   city                 char(32) not null,
		   e_mail               char(32) not null,
		   send_news            smallint not null default 0,
		   primary key (user_id)
		);

		/*==============================================================*/
		/* Table: vat                                                   */
		/*==============================================================*/
		create table shop_vat
		(
		   vat                  decimal(4,2) not null
		);

		INSERT INTO shop_vat (vat) VALUES (0);
		INSERT INTO shop_vat (vat) VALUES (3);
		INSERT INTO shop_vat (vat) VALUES (5);
		INSERT INTO shop_vat (vat) VALUES (7);
		INSERT INTO shop_vat (vat) VALUES (22);
		
		create table shop_setting
		{
			name				varchar(100) not null,
			value				varchar(100) not null
		};
		
		';
?>