alter table `addresses` add `purpose` varchar(25) null  
  alter table `guest_menu_items` add `menu_item_id` bigint unsigned not null, add `event_user_id` bigint unsigned not null  
  alter table `guest_menu_items` add constraint `guest_menu_items_event_user_id_foreign` foreign key (`event_user_id`) references `splurge_event_users` (`id`) on delete cascade  
  alter table `splurge_event_users` add `barcode_image_url` varchar(255) null  
  alter table `venue_tables` add `capacity` smallint null  

