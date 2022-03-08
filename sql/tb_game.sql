CREATE TABLE `tb_game` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `publisher` int(11) NOT NULL,
  `release_date` date NOT NULL,
  `genre` int(11) NOT NULL,
  `abstract` text DEFAULT NULL,
  `contributor` varchar(60) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_game`(`id`, `name`, `publisher`, `release_date`, `genre`, `abstract`, `contributor`, `approved`)
     VALUES ('1'  , 'Call of Duty: Modern Warfare 2' , '1'  , '2009-11-10', '311', 'The sequel to 2007’s wildly successful first-person-shooter Call of Duty 4: Modern Warfare, Call of Duty: Modern Warfare 2 continues the story of American and British soldiers fighting Russian ultra-nationalist forces.', 'Matheus', 1),
            ('11' , 'Castlevania: Aria of Sorrow'    , '11' , '2003-05-06', '41' , 'The third Castlevania installment for the Game Boy Advance, bringing the tale of Castlevania to the 21st century and putting players in the shoes of Soma Cruz, a seemingly-normal guy who has the power to absorb souls of the castles demons.', 'Matheus', 1),
            ('21' , 'Final Fantasy XII'              , '21' , '2006-03-16', '41' , 'The last Final Fantasy game released on the PS2, Final Fantasy XII is distinguished by its condition-driven \"gambit\" battle system, its Monster Hunter-like open environments and side quests, and its setting in the existing fictional universe of Ivalice.', 'Matheus', 1),
            ('31' , 'Army of Two: The Devils Cartel' , '31' , '2013-03-26', '101', 'The third game in the Army of Two franchise, abandoning its original protagonists in favor of two new heroes, known only by the codenames Alpha and Bravo.', 'Matheus', 1),
            ('41' , 'QuackShot: Starring Donald Duck', '41' , '1991-12-19', '401', 'A platforming adventure starring Disney`s titular duck. Inspire by Indiana Jones, QuackShot has Donald, armed with a plunger gun, traveling the world in search of hidden treasure.', 'Matheus', 1),
            ('51' , 'Thunder Fox'                    , '51' , '1991-07-26', '1'  , 'A Taito arcade brawler in which the two members of the Anti-Terrorism Team, Thunder and Fox, save the world from terrorist violence by blowing everything up.', 'Matheus', 1),
            ('61' , 'The Witcher 3: Wild Hunt'       , '61' , '2015-05-19', '41' , 'CD Projekt RED`s third Witcher combines the series` non-linear storytelling with a sprawling open world that concludes the saga of Geralt of Rivia.', 'Matheus', 1),
            ('71' , 'Blasphemous'                    , '71' , '2019-09-10', '401', 'An action-platformer in a dark fantasy setting, by the makers of The Last Door. The player is pitted against bloodthirsty creatures, devotees of a twisted religion.', 'Matheus', 1),
            ('81' , 'Need for Speed Underground 2'   , '31' , '2004-11-15', '51' , 'EA`s Need for Speed series returns for it`s second iteration of illegal street racing in Underground 2. New to this game is the ability to roam around an open-world city,newer racing modes like Street X and URL and much deeper customisation.', 'Matheus', 1),
            ('91' , 'The Elder Scrolls V: Skyrim'    , '81' , '2011-11-1' , '41' , 'The fifth installment in Bethesda`s Elder Scrolls franchise is set in the eponymous province of Skyrim, where the ancient threat of dragons, led by the sinister Alduin, is rising again to threaten all mortal races. Only the player, as the prophesied hero the Dovahkiin, can save the world from destruction.', 'Matheus', 1),
            ('101', 'Battlefield 2'                  , '31' , '2005-06-21', '311', 'DICE`s large-scale multiplayer shooter moves to the present day in this acclaimed sequel, whose online community has remained active for nearly a decade.', 'Matheus', 1),
            ('111', 'Mortal Kombat II'               , '91' , '1993-06-25', '81' , 'The second installment of one of the most violent fighting game franchises of all time, providing a smoother fighting system while adding new fighters, Fatalities, babalities, friendships, and much more.', 'Matheus', 1);

ALTER TABLE `tb_game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_publisher` (`publisher`),
  ADD KEY `fk_genre` (`genre`);

ALTER TABLE `tb_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tb_game`
  ADD CONSTRAINT `fk_genre` FOREIGN KEY (`genre`) REFERENCES `tb_genre` (`id`),
  ADD CONSTRAINT `fk_publisher` FOREIGN KEY (`publisher`) REFERENCES `tb_publisher` (`id`);