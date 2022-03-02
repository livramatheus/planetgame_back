CREATE TABLE `tb_publisher` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `founded` date NOT NULL,
  `logo` varchar(60) NOT NULL,
  `website` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_publisher`(`id`, `name`, `founded`, `logo`, `website`)
     VALUES ('1'  , 'Activision'          , '1979-10-01', 'activision.png'        ,'https://www.activision.com/'),
            ('11' , 'Konami'              , '1969-03-21', 'konami.png'            ,'https://www.konami.com/'),
            ('21' , 'Square Enix'         , '2003-04-01', 'square_enix.png'       ,'https://square-enix-games.com/'),
            ('31' , 'Electronic Arts'     , '1982-05-27', 'electronic_arts.png'   ,'https://ea.com/'),
            ('41' , 'SEGA'                , '1951-04-01', 'sega.png'              ,'https://sega.com/'),
            ('51' , 'Taito Corporation'   , '1953-08-24', 'taito_corporation.png' ,'https://taito.co.jp/'),
            ('61' , 'CD Projekt Red'      , '1994-05-01', 'cd_projekt_red.png'    ,'https://en.cdprojektred.com/'),
            ('71' , 'The Game Kitchen'    , '2010-01-01', 'the_game_kitchen.png'  ,'https://thegamekitchen.com/'),
            ('81' , 'Bethesda Softworks'  , '1986-06-28', 'bethesda_softworks.png','https://bethesda.net/'),
            ('91' , 'Midway Games'        , '1958-01-01', 'midway_games.png'      ,'https://warnerbrosgames.com/'),
            ('101', 'Warner Bros Games'   , '2004-01-14', 'warner_bros_games.png' ,'https://warnerbrosgames.com/'),
            ('111', 'Capcom'              , '1979-05-30', 'capcom.png'            ,'https://capcom.com/');

ALTER TABLE `tb_publisher`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tb_publisher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;