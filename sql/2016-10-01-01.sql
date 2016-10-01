rename table `user` to `kv_user`;
alter table kv_user add uid bigint(20) unsigned NOT NULL after provider;
alter table kv_user modify userId char(36) NOT NULL;
