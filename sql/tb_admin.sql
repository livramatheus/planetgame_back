CREATE TABLE `tb_admin` (
  `user_name` varchar(16) NOT NULL,
  `password` char(128) NOT NULL,
  `first_name` varchar(16) NOT NULL,
  `last_name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`user_name`);