alter table `service_items` drop `image_url`
alter table `service_tiers` drop `image_url`

alter table `communications` drop `internal`

drop table if exists `jobs`

alter table `communications` drop `subject`

drop table if exists `splurge_access_tokens`

alter table `service_tiers` drop `position`

drop table if exists `communications`

drop table if exists `payments`

drop table if exists `bookings`

drop table if exists `customers`

drop table if exists `addresses`

drop table if exists `company_settings`

drop table if exists `service_tiers`

alter table `service_items` drop `sort_number`

drop table if exists `service_items`

drop table if exists `user_roles`

alter table `media_owners` drop `image_options`
alter table `galleries` drop `image_options`
alter table `posts` drop `image_options`
alter table `services` drop `image_options`

alter table `posts` drop `thumbnail_image_url`
drop table if exists `media_owners`

drop table if exists `services`
drop table if exists `taggables`
drop table if exists `tags`
drop table if exists `post_items`
drop table if exists `posts`
drop table if exists `gallery_items`
drop table if exists `galleries`

drop table if exists `personal_access_tokens`

drop table if exists `failed_jobs`

drop table if exists `password_resets`

drop table if exists `users`
