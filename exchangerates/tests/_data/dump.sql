CREATE TABLE IF NOT EXISTS `t_exchange_rates` (
  `date` date NOT NULL,
  `curr` char(3) NOT NULL,
  `count` int(11) NOT NULL,
  `buy` double NOT NULL,
  `sale` double NOT NULL,
  `nbu` double NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'cash',
  `md5` char(32) NOT NULL
);