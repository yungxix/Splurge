create table `users` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `email` varchar(255) not null, `email_verified_at` timestamp null, `password` varchar(255) not null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `users` add unique `users_email_unique`(`email`);
create table `password_resets` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `password_resets` add index `password_resets_email_index`(`email`);
create table `failed_jobs` (`id` bigint unsigned not null auto_increment primary key, `uuid` varchar(255) not null, `connection` text not null, `queue` text not null, `payload` longtext not null, `exception` longtext not null, `failed_at` timestamp default CURRENT_TIMESTAMP not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `failed_jobs` add unique `failed_jobs_uuid_unique`(`uuid`);
create table `personal_access_tokens` (`id` bigint unsigned not null auto_increment primary key, `tokenable_type` varchar(255) not null, `tokenable_id` bigint unsigned not null, `name` varchar(255) not null, `token` varchar(64) not null, `abilities` text null, `last_used_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `personal_access_tokens` add index `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`);
alter table `personal_access_tokens` add unique `personal_access_tokens_token_unique`(`token`);
create table `galleries` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `caption` varchar(255) not null, `image_url` varchar(255) not null, `description` mediumtext not null, `author_id` bigint unsigned null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `galleries` add index `galleries_author_id_index`(`author_id`);
create table `gallery_items` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `content` mediumtext not null, `heading` varchar(255) not null, `gallery_id` bigint unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `gallery_items` add index `gallery_items_gallery_id_index`(`gallery_id`);
create table `posts` (`id` bigint unsigned not null auto_increment primary key, `image_url` varchar(255) not null, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) not null, `description` mediumtext not null, `author_id` bigint unsigned null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `posts` add index `posts_author_id_index`(`author_id`);
create table `post_items` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) not null, `content` mediumtext not null, `author_id` bigint unsigned null, `post_id` bigint unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `post_items` add index `post_items_author_id_index`(`author_id`);
alter table `post_items` add index `post_items_post_id_index`(`post_id`);
create table `tags` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) not null, `category` varchar(120) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `taggables` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `tag_id` bigint unsigned not null, `taggeable_type` varchar(255) not null, `taggeable_id` bigint unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `taggables` add index `taggables_taggeable_type_taggeable_id_index`(`taggeable_type`, `taggeable_id`);
alter table `taggables` add index `taggables_tag_id_index`(`tag_id`);
create table `services` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `image_url` varchar(255) not null, `thumbnail_image_url` varchar(255) null, `description` mediumtext not null, `name` varchar(255) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `media_owners` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) not null, `media_type` varchar(255) not null, `url` varchar(255) not null, `thumbnail_url` varchar(255) null, `owner_type` varchar(255) not null, `owner_id` bigint unsigned not null, `fs` varchar(12) not null default 'local') default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `media_owners` add index `media_owners_owner_type_owner_id_index`(`owner_type`, `owner_id`);
alter table `posts` add `thumbnail_image_url` varchar(255) null;
alter table `media_owners` add `image_options` json null;
alter table `galleries` add `image_options` json null;
alter table `posts` add `image_options` json null;
alter table `services` add `image_options` json null;
create table `user_roles` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) not null, `user_id` bigint unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `user_roles` add index `user_roles_user_id_index`(`user_id`);
create table `service_items` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `service_id` bigint not null, `price` double(8, 2) null, `name` varchar(255) not null, `pricing_type` varchar(255) not null default 'fixed', `options` json null, `description` mediumtext null, `required` tinyint(1) not null default '0', `category` varchar(15) not null default 'default') default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `service_items` add index `service_items_service_id_index`(`service_id`);
alter table `service_items` add `sort_number` smallint not null default '0';
create table `service_tiers` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `service_id` int unsigned not null, `name` varchar(200) not null, `code` varchar(15) not null, `description` mediumtext not null, `price` double(8, 2) null, `options` json not null, `footer_message` varchar(255) null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `service_tiers` add index `service_tiers_service_id_index`(`service_id`);
alter table `service_tiers` add unique `service_tiers_code_unique`(`code`);
create table `company_settings` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `contact_email` varchar(100) not null, `contact_phone` varchar(100) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `addresses` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) null, `line1` varchar(255) not null, `line2` varchar(255) null, `addressable_type` varchar(255) not null, `addressable_id` bigint unsigned not null, `cateogory` varchar(60) null, `latitude` double(8, 2) null, `longitude` double(8, 2) null, `state` varchar(30) not null default 'Lagos', `zip` varchar(12) null, `country` varchar(4) not null default 'NG') default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `addresses` add index `addresses_addressable_type_addressable_id_index`(`addressable_type`, `addressable_id`);
create table `customers` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `first_name` varchar(120) not null, `last_name` varchar(120) not null, `email` varchar(100) not null, `phone` varchar(42) not null, `user_id` mediumint null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `customers` add index `customers_user_id_index`(`user_id`);
create table `bookings` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `service_tier_id` int unsigned not null, `code` varchar(12) not null, `customer_id` mediumint not null, `description` varchar(500) not null, `event_date` date not null, `current_charge` decimal(8, 2) null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `bookings` add index `bookings_service_tier_id_index`(`service_tier_id`);
alter table `bookings` add unique `bookings_code_unique`(`code`);
alter table `bookings` add index `bookings_customer_id_index`(`customer_id`);
create table `payments` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `code` varchar(12) not null, `statement` varchar(255) not null, `amount` decimal(8, 2) not null, `booking_id` mediumint not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `payments` add unique `payments_code_unique`(`code`);
alter table `payments` add index `payments_booking_id_index`(`booking_id`);
create table `communications` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `sender` varchar(20) not null, `content` text not null, `receiver` varchar(255) not null, `channel_type` varchar(255) not null, `channel_id` bigint unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `communications` add index `communications_channel_type_channel_id_index`(`channel_type`, `channel_id`);
alter table `service_tiers` add `position` smallint not null default '1';
create table `splurge_access_tokens` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `user_id` mediumint not null, `token` varchar(255) not null, `access_type` varchar(255) not null, `access_id` bigint unsigned not null, `expires_at` datetime not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `splurge_access_tokens` add index `splurge_access_tokens_access_type_access_id_index`(`access_type`, `access_id`);
alter table `splurge_access_tokens` add index `splurge_access_tokens_user_id_index`(`user_id`);
alter table `communications` add `subject` varchar(255) null;
create table `jobs` (`id` bigint unsigned not null auto_increment primary key, `queue` varchar(255) not null, `payload` longtext not null, `attempts` tinyint unsigned not null, `reserved_at` int unsigned null, `available_at` int unsigned not null, `created_at` int unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `jobs` add index `jobs_queue_index`(`queue`);
alter table `communications` add `internal` tinyint(1) not null default '0';
alter table `service_items` add `image_url` varchar(255) null;
alter table `service_tiers` add `image_url` varchar(255) null;
alter table `services` add `display` varchar(12) not null default 'default';


-- 2022-11-19 10:41

create table `customer_events` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) not null, `event_date` date not null, `booking_id` bigint null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `customer_events` add index `customer_events_name_index`(`name`);
alter table `customer_events` add index `customer_events_booking_id_index`(`booking_id`);

create table `customer_event_guests` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `customer_event_id` bigint not null, `name` varchar(255) not null, `gender` varchar(20) null, `accepted` json null, `presented` json null, `attendance_at` datetime null, `table_name` varchar(120) null, `barcode_image_url` varchar(255) null, `tag` varchar(255) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `customer_event_guests` add index `customer_event_guests_customer_event_id_index`(`customer_event_id`);  


alter table `personal_access_tokens` add `expires_at` date null;


/-- 2022-12-15

create table `menu_preferences` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `guest_id` bigint not null, `comment` varchar(255) null, `name` varchar(255) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `menu_preferences` add index `menu_preferences_guest_id_index`(`guest_id`); 
create table `menu_items` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `name` varchar(255) not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';


/-- 2023-01-15

alter table `customer_event_guests` add `person_count` smallint not null default '1';
create table `event_tables` (`id` bigint unsigned not null auto_increment primary key, `created_at` timestamp null, `updated_at` timestamp null, `customer_event_id` bigint not null, `name` varchar(60) not null, `capacity` smallint not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'  ;  
alter table `event_tables` add index `idx_customer_event_name`(`name`, `customer_event_id`);
alter table `event_tables` add index `event_tables_customer_event_id_index`(`customer_event_id`);  

