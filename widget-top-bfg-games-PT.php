<?php
/*  Copyright 2008-2011 Denis "Mr.Snow" Kozhukhov  (email : mrsnow@chocosnow.com), ChocoSnow.com, ChocoSnow.com.br

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
Plugin Name: Widget Top BFG Games PT
Plugin URI: http://www.chocosnow.com.br/blog/widget-bfg-top-games/
Description: Este plugin simples é um widget que exibe uma lista de topo de jogos casuais da BigFish PT (Portugal, Brazil, .pt, .com.br) em sua barra lateral widgetized. ENGLISH: This simple plugin is a widget that displays a PT (Portugal, Brazil, .pt, .com.br) list of top casual games from BigFish on your widgetized sidebar.
Author: <a href="http://www.chocosnow.com/">ChocoSnow.com</a>, <a href="http://www.chocosnow.com.br/">ChocoSnow.com.br</a>, <a href="http://www.mrsnow.info/">Denis "Mr.Snow" Kozhukhov</a>
Author URI: http://www.mrsnow.info/
Version: 2.0.2.pt
*/

include_once(ABSPATH . WPINC . '/rss.php');

function widget_mrsnow_bfgtopgames_PT_work ($before, $after)
{
	$options = (array) get_option ('widget_mrsnow_bfgtopgames_PT');
	if ($options['channel']==null) 
	{
		$options['channel'] = str_replace ('.', '', str_replace ('_', '', str_replace ('-', '', $_SERVER['HTTP_HOST'])));
		update_option ('widget_mrsnow_bfgtopgames_PT', $options);
	};
	
	$title = $options['title'];
	$numgames = $options['count'];
	$type = $options['type'];
	$lang = $options['lang'];
	$lang = 'pt';
	$align = $options['align'];
	$showbullets = $options['showbullets'];
	$showpos = $options['showpos'];
	$showimage = $options['showimage'];
	$showtext = $options['showtext'];
	$itype = $options['itype'];
	$above = $options['above'];
	$login = $options['login'];
	$channel = $options['channel'];
	if (!$login || $login == '') $login = 'mrsnow';

	
	//------------------------------------------------------------------------------------------------------------------------------
	$string_to_echo = ($before.$title.$after."\n");

	$url = 'http://rss.bigfishgames.com/rss.php?username='.$login.'&content=glrank&type=2&gametype='.$type.'&local='.$lang.'';
	$rss = fetch_rss ($url);
	$items = array_slice ($rss->items, 0, $numgames);

	if ($align != 'default') $string_to_echo .= '<div style="text-align:'.$align.';">';

	$bef = '';
	$aft = '<br/>';
	$ins = '';
	$ins2 = '';
	$num = '';
	$i = 0;
	$variantTitlei = rand(0, $numgames);
	$variantTitle = '';
	$variantTitleJPlink = '';

	if ($showbullets == '1') 
	{
		$bef = '<li>';
		$aft = '</li>';
	};

	if ($showbullets == '1') $string_to_echo .= '<ul>';
	
	$channel = ($channel == '-') ? '' : '&cid='.$channel;

	if (empty ($items))
	{
	}
	else
	{
		foreach ($items as $item)
		{
			$ins = '';
			$ins2 = '';
			$txt = '';

			$str1 = strpos ($item['description'], '<img ');
			$str2 = strpos ($item['description'], '/>') + 2;
			$img = str_replace ('80x80', $itype, substr ($item['description'], $str1, $str2-$str1));
			$txt = '<br/>'.substr ($item['description'], $str2 + 5);

			if ($showimage == '1')
			{
				//if ($itype == '80x80')      { $img = str_replace ('width="80"', 'width="80"',  str_replace ('height="80"', 'height="80"',  $img)); }
				if ($itype == '60x40')      { $img = str_replace ('width="80"', 'width="60"',  str_replace ('height="80"', 'height="40"',  $img)); }
				if ($itype == 'feature')    { $img = str_replace ('width="80"', 'width="175"', str_replace ('height="80"', 'height="150"', $img)); }
				if ($itype == 'subfeature') { $img = str_replace ('width="80"', 'width="175"', str_replace ('height="80"', 'height="150"', $img)); }

				if ($above == 'text')
				{
					$ins2 = '<br/>'.$img; 
				}
				else
				{
					$ins = $img.'<br/>';
				};
			};
			
			if ($showtext != '1')
			{
				$txt = '';
			};			
		
			$i += 1;
			if ($showpos == '1')
			{
				$num =  $i . '. ';
			};

			$tit = str_replace ('&#8482;', '', $item['title']);
			$string_to_echo .= $bef.$num.'<a href="'.$item['link'].$channel.'" title="'.$tit.'">'.$ins.$tit.$ins2.'</a>'.$txt.$aft;
			
			if ($variantTitlei == $i) 
			{
				$variantTitle = $tit;
				$variantTitleJPlink = str_replace ('http://www.bigfishgames.jp/download-games/', '', $item['link']);
				$variantTitleJPlink = substr ($variantTitleJPlink, strpos ($variantTitleJPlink, "/")+1);
				$variantTitleJPlink = substr ($variantTitleJPlink, 0, strpos ($variantTitleJPlink, "/"));
			};
		};
	};
	
	
	//------------------------------------------------------------------------------------------------------------------------------
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$md5 = hash ("md5", $url);
	$options2 = $newoptions2 = get_option ('widget_mrsnow_bfgtopgames_PT_'.$md5);

	if ($options2['csurl'] == '') 
	{
		$variant = rand(0, 3);
		if ($variant == 0)
		{
			$newoptions2['csurl'] = $csurl[$url_rand] = '-';
			$newoptions2['cstitle'] = $cstitle[$title_rand] = '-';
		}
		else if ($variant == 1)
		{
			$csurl['en'] = 'http://www.chocosnow.com/games-downloads/';
			$csurl['de'] = 'http://www.chocosnow.de/download-spiele/';
			$csurl['es'] = 'http://www.chocosnow.es/juegos-de-descarga/';
			$csurl['fr'] = 'http://www.chocosnow.fr/jeux-a-telecharger/';
			$csurl['jp'] = 'http://www.chocosnow.jp/jp-games-downloads/';
			$csurl['it'] = 'http://www.chocosnow.it/scarica-giochi/';
			$csurl['da'] = 'http://www.chocosnow.dk/download-spil/';
			$csurl['nl'] = 'http://www.chocosnow.nl/download-spellen/';
			$csurl['sv'] = 'http://www.chocosnow.se/ladda-ner-spel/';
			$csurl['pt'] = 'http://www.chocosnow.com.br/jogos-para-baixar/';

			if ($lang=='jp') 
				$newoptions2['csurl'] = $csurl[$lang].$variantTitleJPlink.'/';
			else 
				$newoptions2['csurl'] = $csurl[$lang].GenerateIdFromTitle_PT ($variantTitle).'/';
			
			$variantT = rand(0, 3);
			if ($variant == 0)
			{
				$cstitle['en'] = 'Download '.$variantTitle.' Games';
				$cstitle['de'] = 'Download-Spiele '.$variantTitle.'';
				$cstitle['es'] = 'Gratis '.$variantTitle.' Descargas';
				$cstitle['fr'] = 'Téléchargez des '.$variantTitle.' jeux';
				$cstitle['jp'] = 'ダウンロードゲーム'.$variantTitle.'';
				$cstitle['it'] = 'Scarica '.$variantTitle.' giochi';
				$cstitle['da'] = 'Hent '.$variantTitle.' Spil';
				$cstitle['nl'] = 'Download '.$variantTitle.' Spellen';
				$cstitle['sv'] = 'Ladda ner PC-spel '.$variantTitle.'';
				$cstitle['pt'] = 'Baixar '.$variantTitle.' jogo';		
				
				$newoptions2['cstitle'] = $cstitle[$lang];
			}
			else if ($variant == 1)
			{
				$cstitle['en'] = 'Free '.$variantTitle.' Download';
				$cstitle['de'] = 'Kostenlos '.$variantTitle.' Download-Spiele';
				$cstitle['es'] = 'Juegos '.$variantTitle.' de Descarga';
				$cstitle['fr'] = 'Libres de Jeu '.$variantTitle.' Télécharger';
				$cstitle['jp'] = '自由なゲームダウンロード'.$variantTitle.'';
				$cstitle['it'] = 'Libero Download '.$variantTitle.' Gioco';
				$cstitle['da'] = 'Spil '.$variantTitle.' Gratis';
				$cstitle['nl'] = 'Spellen '.$variantTitle.' Gratis';
				$cstitle['sv'] = 'Gratis '.$variantTitle.' Spel Nedladdning';
				$cstitle['pt'] = 'Grátis Jogos '.$variantTitle.' Download';
				
				$newoptions2['cstitle'] = $cstitle[$lang];
			}		
			else
			{
				$newoptions2['cstitle'] = $variantTitle;
			};
			$maxrnd = 0;
		}
		else
		{
			if ($lang=="en")
			{
				$csurl[0] = 'http://www.chocosnow.com/';
				$csurl[1] = 'http://www.chocosnow.com/games-downloads/';
				$csurl[2] = 'http://www.chocosnow.com/mac-games-downloads/';
				$csurl[3] = 'http://www.chocosnow.com/play-online-games/';
				$csurl[4] = 'http://www.chocosnow.com/iphone-ipod-games/';
				$csurl[5] = 'http://www.chocosnow.com/ipad-games/';
				$csurl[6] = 'http://www.gameschoco.com/';
				$csurl[7] = 'http://www.gamesplasma.com/';
				$csurl[8] = 'http://www.svetagames.com/';
				$csurl[9] = 'http://www.casualplayground.com/';
				$csurl[10] = 'http://www.gamesapple.com/';
				$csurl[11] = 'http://www.gamesakura.com/';
				$csurl[12] = 'http://www.gamespresto.com/';
				$csurl[13] = 'http://www.chocosnow.ru/';
				$csurl[14] = 'http://www.chocosnow.pl/';

				$url_rand = rand(0, 12);
				$newoptions2['csurl'] = $csurl[$url_rand];
				$maxrnd = 0;

				if ($url_rand == 1)
				{
					$cstitle[0] = 'Download Casual Games';
					$cstitle[1] = 'More Casual Games';
					$cstitle[2] = 'Play Casual Games';
					$cstitle[3] = 'Free Games Downloads';
					$cstitle[4] = 'Download PC Games';
					$cstitle[5] = 'More PC Games';
					$cstitle[6] = 'Play PC Games';
					$cstitle[7] = 'PC Games Downloads';
					$cstitle[8] = 'Free PC Games';
					$cstitle[9] = 'Casual Games Download';
					$cstitle[10] = 'Play Online Games';
					$cstitle[11] = 'Free Games Online';
					$cstitle[12] = 'Free Online Games';
					$cstitle[13] = 'Play Free Online Games';
					$cstitle[14] = 'Play Free Games Online';
					$cstitle[15] = 'Play Games Online Free';
					$maxrnd = 15;
				}
				elseif ($url_rand == 2)
				{
					$cstitle[0] = 'Download Casual Games';
					$cstitle[1] = 'More Casual Games';
					$cstitle[2] = 'Play Casual Games';
					$cstitle[3] = 'Free Games Downloads';
					$cstitle[4] = 'Download Mac Games';
					$cstitle[5] = 'More Mac Games';
					$cstitle[6] = 'Play Mac Games';
					$cstitle[7] = 'Mac Games Downloads';
					$cstitle[8] = 'Free Mac Games';
					$cstitle[9] = 'Download Games for Mac';
					$cstitle[10] = 'More Games for Mac';
					$cstitle[11] = 'Play Games for Mac';
					$cstitle[12] = 'Games for Mac Downloads';
					$cstitle[13] = 'Free Games for Mac';
					$cstitle[14] = 'Play Online Mac Games';
					$cstitle[15] = 'Free Mac Games Online';
					$cstitle[16] = 'Casual Mac Games Download';
					$maxrnd = 16;
				}
				elseif ($url_rand == 3)
				{
					$cstitle[0] = 'Play Online Games';
					$cstitle[1] = 'Play Games Online';
					$cstitle[2] = 'Free Games Online';
					$cstitle[3] = 'Free Online Games';
					$cstitle[4] = 'Play Free Online Games';
					$cstitle[5] = 'Play Free Games Online';
					$cstitle[6] = 'Play Games Online Free';
					$maxrnd = 6;
				}
				elseif ($url_rand == 4)
				{
					$cstitle[0] = 'More iPhone Games';
					$cstitle[1] = 'Play iPhone Games';
					$cstitle[2] = 'Download iPhone Games';
					$cstitle[3] = 'Play Games for iPhone';
					$cstitle[4] = 'Play Free iPhone Games';
					$cstitle[5] = 'Get iPhone Games';
					$cstitle[6] = 'Get Games for iPhone';
					$cstitle[7] = 'More iPod Games';
					$cstitle[8] = 'Play iPod Games';
					$cstitle[9] = 'Download iPod Games';
					$cstitle[10] = 'Play Games for iPod';
					$cstitle[11] = 'Play Free iPod Games';
					$cstitle[12] = 'Get iPod Games';
					$cstitle[13] = 'Get Games for iPod';
					$cstitle[14] = 'More iPhone/iPod Games';
					$cstitle[15] = 'Play iPhone/iPod Games';
					$maxrnd = 15;
				}
				elseif ($url_rand == 5)
				{
					$cstitle[0] = 'More iPad Games';
					$cstitle[1] = 'Play iPad Games';
					$cstitle[2] = 'Download iPad Games';
					$cstitle[3] = 'Play Games for iPad';
					$cstitle[4] = 'Play Free iPad Games';
					$cstitle[5] = 'Get iPad Games';
					$cstitle[6] = 'Get Games for iPad';
					$cstitle[7] = 'More iPad2 Games';
					$cstitle[8] = 'Play iPad2 Games';
					$cstitle[9] = 'Download iPad2 Games';
					$cstitle[10] = 'Play Games for iPad2';
					$cstitle[11] = 'Play Free iPad2 Games';
					$cstitle[12] = 'Get iPad2 Games';
					$cstitle[13] = 'Get Games for iPad2';
					$cstitle[14] = 'More iPad3 Games';
					$cstitle[15] = 'Play iPad3 Games';
					$cstitle[16] = 'Download iPad3 Games';
					$cstitle[17] = 'Play Games for iPad3';
					$cstitle[18] = 'Play Free iPad3 Games';
					$cstitle[19] = 'Get iPad3 Games';
					$cstitle[20] = 'Get Games for iPad3';
					$maxrnd = 20;
				}
				elseif ($url_rand == 13)
				{
					$cstitle[0] = 'Скачать игры';
					$cstitle[1] = 'Скачать мини игры';
					$cstitle[2] = 'Играть в игры';
					$cstitle[3] = 'Играть в мини игры';
					$cstitle[4] = 'Скачать миниигры';
					$cstitle[5] = 'Играть в миниигры';
					$cstitle[6] = 'Казуальные игры';
					$cstitle[7] = 'Скачать казуальные игры';
					$cstitle[8] = 'Поиграть в игры';
					$cstitle[9] = 'Мини игры';
					$cstitle[10] = 'Маленькие игры';
					$cstitle[11] = 'Казуалки скачать';
					$cstitle[12] = 'Поиграться в игрушки';
					$cstitle[13] = 'Бесплатные мини игры';
					$cstitle[14] = 'Игры я ищу';
					$cstitle[15] = 'Пазлы мини игры';
					$cstitle[16] = 'Игры поиск предметов';
					$cstitle[17] = 'Игры симуляторы';
					$cstitle[18] = 'Игры менеджмент';
					$cstitle[19] = 'Логические игры';
					$cstitle[20] = 'Игра Аламанди';
					$maxrnd = 20;
				}
				elseif ($url_rand == 14)
				{
					$cstitle[0] = 'Pobierz i zagraj w gry';
					$cstitle[1] = 'Pobierz gry za darmo';
					$cstitle[2] = 'Zagraj w gry';
					$cstitle[3] = 'Pobierz gry';
					$cstitle[4] = 'Pobierz Ukryte obiekty';
					$cstitle[5] = 'Pobierz Dopasuj-3';
					$cstitle[6] = 'Pobierz Na czas';
					$cstitle[7] = 'Zagraj w Ukryte obiekty';
					$cstitle[8] = 'Zagraj w Dopasuj-3';
					$cstitle[9] = 'Zagraj w Na czas';
					$cstitle[10] = 'Pobierz Zręcznościowe';
					$cstitle[11] = 'Pobierz Puzzle';
					$cstitle[12] = 'Zagraj w Zręcznościowe';
					$cstitle[13] = 'Zagraj w Puzzle';
					$cstitle[14] = 'Gry za darmo';
					$maxrnd = 14;
				}
				else // $url_rand ==0 or >5
				{
					$cstitle[0] = 'Download Casual Games';
					$cstitle[1] = 'More Casual Games';
					$cstitle[2] = 'Play Casual Games';
					$cstitle[3] = 'Free Games Downloads';
					$cstitle[4] = 'Download PC Games';
					$cstitle[5] = 'More PC Games';
					$cstitle[6] = 'Play PC Games';
					$cstitle[7] = 'PC Games Downloads';
					$cstitle[8] = 'Free PC Games';
					$cstitle[9] = 'Casual Games Download';
					$cstitle[10] = 'Download Games Free';
					$cstitle[11] = 'Download Free Games';
					$cstitle[12] = 'Games Download';
					$cstitle[13] = 'Games Free Download';
					$cstitle[13] = 'Casual Games Free Download';
					$cstitle[14] = 'No ads Games Free';
					$cstitle[15] = 'Download No ads Games';
					$maxrnd = 15;
				};
				$title_rand = rand(0, $maxrnd);
				$newoptions2['cstitle'] = $cstitle[$title_rand];
			}
			else
			{
				$csurl['en'] = 'http://www.chocosnow.com/';
				$csurl['de'] = 'http://www.chocosnow.de/';
				$csurl['es'] = 'http://www.chocosnow.es/';
				$csurl['fr'] = 'http://www.chocosnow.fr/';
				$csurl['jp'] = 'http://www.chocosnow.jp/';
				$csurl['it'] = 'http://www.chocosnow.it/';
				$csurl['da'] = 'http://www.chocosnow.dk/';
				$csurl['nl'] = 'http://www.chocosnow.nl/';
				$csurl['sv'] = 'http://www.chocosnow.se/';
				$csurl['pt'] = 'http://www.chocosnow.com.br/';				
				
				$variantT = rand(0, 2);
				if ($variant == 0)
				{
					$cstitle['en'] = 'Download Casual PC Games';
					$cstitle['de'] = 'Download-Spiele';
					$cstitle['es'] = 'Gratis Descargas de juegos';
					$cstitle['fr'] = 'Téléchargez des jeux pour jouer';
					$cstitle['jp'] = 'ダウンロードゲーム';
					$cstitle['it'] = 'Scarica giochi per PC Miglior libero';
					$cstitle['da'] = 'Hent Spil, Download PC Spil';
					$cstitle['nl'] = 'Download PC Spellen';
					$cstitle['sv'] = 'Ladda ner PC-spel';
					$cstitle['pt'] = 'Baixar jogos casuais para PC';
				}
				else
				{
					$cstitle['en'] = 'Free Games Download';
					$cstitle['de'] = 'Kostenlos Download-Spiele';
					$cstitle['es'] = 'Juegos de Descarga';
					$cstitle['fr'] = 'Libres de Jeu Télécharger';
					$cstitle['jp'] = '自由なゲームダウンロード';
					$cstitle['it'] = 'Libero Download Gioco';
					$cstitle['da'] = 'Hent Spil Gratis';
					$cstitle['nl'] = 'Download Spellen Gratis';
					$cstitle['sv'] = 'Gratis Spel Nedladdning';
					$cstitle['pt'] = 'Grátis Jogos Download';	
				};
				
				$newoptions2['csurl'] = $csurl[$lang];
				$newoptions2['cstitle'] = $cstitle[$lang];
			};
		};
		
		if ($options2 != $newoptions2) 
		{
			$options2 = $newoptions2;
			update_option ('widget_mrsnow_bfgtopgames_PT_'.$md5, $options2);
		};
	};	
	
	$csurl = $options2['csurl'];
	$cstitle = $options2['cstitle'];
	if (!$csurl || $csurl == '') $csurl = 'http://www.chocosnow.com/';
	if (!$cstitle || $cstitle == '') $cstitle = 'Download Casual Games';
	

	//------------------------------------------------------------------------------------------------------------------------------
	if ($csurl != '-') $string_to_echo .= $bef.'<a href="'.$csurl.'" title="'.$cstitle.'">'.$cstitle.'</a>'.$aft;
	if ($showpos != '1') $string_to_echo .= '</ul>';
		
	if ($align != 'default') $string_to_echo .= '</div>';

	return $string_to_echo;
}

function GenerateIdFromTitle_PT ($title)
{
	$gameidtemp = strtolower (trim ($title));

	$gameidtemp = str_replace  ('`', '\'', $gameidtemp);
	$gameidtemp = str_replace  ('&apos;', '\'', $gameidtemp);
	$gameidtemp = str_replace  ('&#8217;', '\'', $gameidtemp);

	$gameidtemp = str_replace  (' &amp; ', ' and ', $gameidtemp);
	$gameidtemp = str_replace  (' &#38; ', ' and ', $gameidtemp);
	$gameidtemp = str_replace  ('&#38;', ' and ', $gameidtemp);

	$gameidtemp = str_replace  ('&#8482;', '', $gameidtemp);
	$gameidtemp = str_replace  ('%e2%84%a2', '', $gameidtemp);
	$gameidtemp = str_replace  ('&amp;trade;', '', $gameidtemp);
	$gameidtemp = str_replace  ('&trade', '', $gameidtemp);
	$gameidtemp = str_replace  ('%c2%ae', '', $gameidtemp);
	$gameidtemp = str_replace  ('™', '', $gameidtemp);
	$gameidtemp = str_replace  ('®', '', $gameidtemp);
	$gameidtemp = str_replace  ('©', '', $gameidtemp);
	$gameidtemp = str_replace  ('&#169;', '', $gameidtemp); // ©
	$gameidtemp = str_replace  ('&#233;', 'e', $gameidtemp); // e'

	$gameidtemp = str_replace  ('&#174;', '', $gameidtemp);
	$gameidtemp = str_replace  ('&reg;', '', $gameidtemp);
	$gameidtemp = str_replace  ('(R)', '', $gameidtemp);
	$gameidtemp = str_replace  ('(r)', '', $gameidtemp);
	$gameidtemp = str_replace  ('&#194;', '', $gameidtemp); // A с тильдой сверху

	$gameidtemp = str_replace  (' &#8212; ', ' - ', $gameidtemp);
	$gameidtemp = str_replace  (' — ', ' - ', $gameidtemp);
	$gameidtemp = str_replace  (' %e2%80%94 ', ' - ', $gameidtemp);
	$gameidtemp = str_replace  ('&#8212;', ' - ', $gameidtemp);
	$gameidtemp = str_replace  ('—', ' - ', $gameidtemp);
	$gameidtemp = str_replace  ('%e2%80%94', ' - ', $gameidtemp);
	$gameidtemp = str_replace  (' - ', ': ', $gameidtemp);
	$gameidtemp = str_replace  ('&lt;br/&gt;', '', $gameidtemp);
	
	$gameidtemp = str_replace  ("Drawn: Par-del&#195; l&#226;&#8364;\n			Obscurit&#195;", "Drawn: Par-del&#224; l'Obscurit&#233;", $gameidtemp);

	$gameidtemp = str_replace  (' iii', ' 3', $gameidtemp);
	$gameidtemp = str_replace  (' ii', ' 2', $gameidtemp);
	$gameidtemp = str_replace  (' III', ' 3', $gameidtemp);
	$gameidtemp = str_replace  (' II', ' 2', $gameidtemp);
	$gameidtemp = str_replace  (' : ', ': ', $gameidtemp);

	$gameidtemp = str_replace  ('  ', ' ', $gameidtemp);
	$gameidtemp = str_replace  ('  ', ' ', $gameidtemp);
	$gameidtemp = str_replace  ('  ', ' ', $gameidtemp);
	
		
	$gameidtemp = str_replace ("’", "", str_replace ("`", "", str_replace (":", "", str_replace ("?", "", str_replace ("!", "", $gameidtemp)))));
	$gameidtemp = str_replace (".", "", str_replace ("/", "-", str_replace (" ", "-", str_replace ("_", "-", $gameidtemp))));
	$gameidtemp = str_replace ("'", "", str_replace ("\'", "", str_replace (",", "", str_replace ("\"", "", $gameidtemp)))); 
	$gameidtemp = str_replace ('"', '', str_replace ('@', 'a', str_replace ("$", "s", str_replace ("–", "", $gameidtemp))));
	$gameidtemp = str_replace ("  ", " ", str_replace ("  ", " ", str_replace ("--", "-", str_replace ("--", "-", $gameidtemp))));	

	$gameidtemp = str_replace ("À", "a", str_replace ("Á", "a", str_replace ("Â", "a", str_replace ("Ã", "a", str_replace ("Ä", "a", $gameidtemp)))));
	$gameidtemp = str_replace ("Å", "a", str_replace ("Æ", "ae", str_replace ("Ā", "a", str_replace ("Ă", "a", str_replace ("Ą", "a", $gameidtemp)))));
	$gameidtemp = str_replace ("Ç", "c", str_replace ("Ć", "c", str_replace ("Ĉ", "c", str_replace ("Ċ", "c", str_replace ("Č", "c", $gameidtemp)))));
	$gameidtemp = str_replace ("Ð", "d", str_replace ("Ď", "d", str_replace ("Đ", "d", $gameidtemp)));
	$gameidtemp = str_replace ("È", "e", str_replace ("É", "e", str_replace ("Ê", "e", str_replace ("Ë", "e", str_replace ("Ē", "e", $gameidtemp)))));
	$gameidtemp = str_replace ("Ė", "e", str_replace ("Ę", "e", str_replace ("Ě", "e", str_replace ("Ə", "e", $gameidtemp))));
	$gameidtemp = str_replace ("Ĝ", "g", str_replace ("Ğ", "g", str_replace ("Ġ", "g", str_replace ("Ģ", "g", $gameidtemp))));
	$gameidtemp = str_replace ("Ĥ", "h", str_replace ("Ħ", "h", $gameidtemp));
	$gameidtemp = str_replace ("Ì", "i", str_replace ("Í", "i", str_replace ("Î", "i", str_replace ("Ï", "i", str_replace ("Ī", "i", $gameidtemp)))));
	$gameidtemp = str_replace ("Į", "i", str_replace ("İ", "i", str_replace ("I", "i", str_replace ("Ĳ", "ij", $gameidtemp))));
	$gameidtemp = str_replace ("Ĵ", "j", $gameidtemp);
	$gameidtemp = str_replace ("Ķ", "k", $gameidtemp);
	$gameidtemp = str_replace ("Ĺ", "l", str_replace ("Ļ", "l", str_replace ("Ľ", "l", str_replace ("Ł", "l", $gameidtemp))));
	$gameidtemp = str_replace ("Ñ", "n", str_replace ("Ń", "n", str_replace ("Ņ", "n", str_replace ("Ň", "n", $gameidtemp))));
	$gameidtemp = str_replace ("Ò", "o", str_replace ("Ó", "o", str_replace ("Ô", "o", str_replace ("Õ", "o", str_replace ("Ö", "o", $gameidtemp)))));
	$gameidtemp = str_replace ("Ø", "o", str_replace ("Ő", "o", str_replace ("Œ", "oe", str_replace ("Ơ", "o", str_replace ("Ŕ", "r", $gameidtemp)))));
	$gameidtemp = str_replace ("Ř", "r", $gameidtemp); 
	$gameidtemp = str_replace ("ß", "ss", str_replace ("ſ", "s", str_replace ("Ś", "s", str_replace ("Ŝ", "s", str_replace ("Ş", "s", $gameidtemp)))));
	$gameidtemp = str_replace ("Š", "s", $gameidtemp);
	$gameidtemp = str_replace ("Þ", "th", str_replace ("Ţ", "t", str_replace ("Ť", "t", $gameidtemp)));
	$gameidtemp = str_replace ("Ù", "u", str_replace ("Ú", "u", str_replace ("Û", "u", str_replace ("Ü", "u", str_replace ("Ū", "u", $gameidtemp)))));
	$gameidtemp = str_replace ("Ŭ", "u", str_replace ("Ů", "u", str_replace ("Ű", "u", str_replace ("Ų", "u", str_replace ("Ư", "u", $gameidtemp)))));
	$gameidtemp = str_replace ("Ŵ", "w", $gameidtemp);
	$gameidtemp = str_replace ("Ý", "y", str_replace ("Ŷ", "y", str_replace ("Ÿ", "y", $gameidtemp)));
	$gameidtemp = str_replace ("Ź", "z", str_replace ("Ż", "z", str_replace ("Ž", "z", str_replace ("Ƶ", "z", $gameidtemp))));

	$gameidtemp = str_replace ("à", "a", str_replace ("á", "a", str_replace ("â", "a", str_replace ("ã", "a", str_replace ("ä", "a", $gameidtemp)))));
	$gameidtemp = str_replace ("å", "a", str_replace ("æ", "ae", str_replace ("ā", "a", str_replace ("ă", "a", str_replace ("ą", "a", $gameidtemp)))));
	$gameidtemp = str_replace ("ç", "c", str_replace ("ć", "c", str_replace ("ĉ", "c", str_replace ("ċ", "c", str_replace ("č", "c", $gameidtemp)))));
	$gameidtemp = str_replace ("ð", "d", str_replace ("ď", "d", str_replace ("đ", "d", $gameidtemp)));
	$gameidtemp = str_replace ("è", "e", str_replace ("é", "e", str_replace ("ê", "e", str_replace ("ë", "e", str_replace ("ē", "e", $gameidtemp)))));
	$gameidtemp = str_replace ("ė", "e", str_replace ("ę", "e", str_replace ("ě", "e", str_replace ("ə", "e", $gameidtemp))));
	$gameidtemp = str_replace ("ĝ", "g", str_replace ("ğ", "g", str_replace ("ġ", "g", str_replace ("ģ", "g", $gameidtemp))));
	$gameidtemp = str_replace ("ĥ", "h", str_replace ("ħ", "h", $gameidtemp));
	$gameidtemp = str_replace ("ì", "i", str_replace ("í", "i", str_replace ("î", "i", str_replace ("ï", "i", str_replace ("ī", "i", $gameidtemp)))));
	$gameidtemp = str_replace ("į", "i", str_replace ("İ", "i", str_replace ("i", "i", str_replace ("ĳ", "ij", $gameidtemp))));
	$gameidtemp = str_replace ("ĵ", "j", $gameidtemp);
	$gameidtemp = str_replace ("ķ", "k", $gameidtemp);
	$gameidtemp = str_replace ("ĺ", "l", str_replace ("ļ", "l", str_replace ("ľ", "l", str_replace ("ł", "l", $gameidtemp))));
	$gameidtemp = str_replace ("ñ", "n", str_replace ("ń", "n", str_replace ("ņ", "n", str_replace ("ň", "n", $gameidtemp))));
	$gameidtemp = str_replace ("ò", "o", str_replace ("ó", "o", str_replace ("ô", "o", str_replace ("õ", "o", str_replace ("ö", "o", $gameidtemp)))));
	$gameidtemp = str_replace ("ø", "o", str_replace ("ő", "o", str_replace ("œ", "oe", str_replace ("ơ", "o", $gameidtemp))));
	$gameidtemp = str_replace ("ŕ", "r", str_replace ("ř", "r", $gameidtemp));
	$gameidtemp = str_replace ("ß", "ss", str_replace ("ſ", "s", str_replace ("ś", "s", str_replace ("ŝ", "s", str_replace ("ş", "s", $gameidtemp)))));
	$gameidtemp = str_replace ("š", "s", $gameidtemp);
	$gameidtemp = str_replace ("þ", "th", str_replace ("ţ", "t", str_replace ("ť", "t", $gameidtemp)));
	$gameidtemp = str_replace ("ù", "u", str_replace ("ú", "u", str_replace ("û", "u", str_replace ("ü", "u", str_replace ("ū", "u", $gameidtemp)))));
	$gameidtemp = str_replace ("ŭ", "u", str_replace ("ů", "u", str_replace ("ű", "u", str_replace ("ų", "u", str_replace ("ư", "u", $gameidtemp)))));
	$gameidtemp = str_replace ("ŵ", "w", $gameidtemp);
	$gameidtemp = str_replace ("ý", "y", str_replace ("ŷ", "y", str_replace ("ÿ", "y", str_replace ("ź", "z", $gameidtemp))));
	$gameidtemp = str_replace ("ż", "z", str_replace ("ž", "z", str_replace ("ƶ", "z", $gameidtemp)));

	//echo $gameidtemp.'<br>';
	return $gameidtemp;
};

function widget_mrsnow_bfgtopgames_PT_control () 
{
	$text['title'] = 'Widget título:';
	$text['titled'] = 'BigFish top jogos';
	$text['gnum'] = 'Número de jogos para mostrar:';
	$text['gtype'] = 'Tipo jogos de exibição:';
	$text['gtypep'] = 'Jogos para PC';
	//$text['gtypem'] = 'Mac Games (English sites only)';
	$text['gtypeo'] = 'Jogos on-line';
	//$text['glang'] = 'Games Language to Display:';
	$text['align'] = 'Texto e Imagens alinhar:';
	$text['alignd'] = 'Tema padrão';
	$text['alignr'] = 'Direito';
	$text['alignl'] = 'Esquerda';
	$text['alignc'] = 'Centro';
	$text['bullets'] = 'Mostrar balas Lista';
	$text['pos'] = 'Mostrar Posição Jogos';
	$text['img'] = 'Jogos Imagens mostram';
	$text['imgtype'] = 'Tipo de imagens:';
	$text['imgtypes'] = 'Pequeno (60x40)';
	$text['imgtyped'] = 'Padrão (80x80)';
	$text['imgtypef'] = 'Feature (175x50)';
	$text['imgtypesf'] = 'Subfeature (175x50)';
	$text['showt'] = 'Mostrar Texto Jogos';
	$text['titlepos'] = 'Posição do título:';
	$text['titleposti'] = 'Título da Imagem Acima';
	$text['titleposit'] = 'Título imagem acima';
	$text['bfglogin'] = 'Sua <a href="http://affiliates.bigfishgames.com/index.html?afcode=af6e89e0ae1a">afiliado BigFishGames</a> login:';
	$text['bfgloginnb'] = 'Você pode deixar este campo vazio, <br/> widget pode trabalhar sem login, <br/> ou uso de login correto.';
	$text['bfgchan'] = 'BigFishGames canal do cliente afiliado:';
	$text['bfgchannb'] = 'Nome de domínio será usado por padrão. <br/> Deixe este campo em branco, para não usar os canais. <br/> Por favor, use alfa numéricos.';
	$text['copy'] = 'Copyright 2008-2011 por <a href="http://www.chocosnow.com.br/">ChocoSnow.com.br</a>';

	$options = $newoptions = get_option ('widget_mrsnow_bfgtopgames_PT');

	if ($_POST['bfgtopgames-submit']) 
	{
		$newoptions['title'] = strip_tags (stripslashes ($_POST['bfgtopgames-title']));
		$newoptions['count'] = (int) $_POST['bfgtopgames-count'];
		$newoptions['type'] = $_POST['bfgtopgames-type'];
		//$newoptions['lang'] = $_POST['bfgtopgames-lang'];
		$newoptions['lang'] = 'pt';
		$newoptions['align'] = $_POST['bfgtopgames-align'];
		$newoptions['showbullets'] = ($_POST['bfgtopgames-showbullets'] ? '1' : '2');
		$newoptions['showpos'] = ($_POST['bfgtopgames-showpos'] ? '1' : '2');
		$newoptions['showimage'] = ($_POST['bfgtopgames-showimage'] ? '1' : '2');
		$newoptions['showtext'] = ($_POST['bfgtopgames-showtext'] ? '1' : '2');
		$newoptions['itype'] = $_POST['bfgtopgames-itype'];
		$newoptions['above'] = $_POST['bfgtopgames-above'];
		$newoptions['login'] = trim (strip_tags (stripslashes ($_POST['bfgtopgames-login'])));
		$newoptions['channel'] = trim (strip_tags (stripslashes (str_replace ('.', '', str_replace ('_', '', str_replace ('-', '', $_POST['bfgtopgames-channel']))))));
		if ($newoptions['channel']=='') $newoptions['channel'] = '-';

		if ($options != $newoptions) 
		{
			$options = $newoptions;
			update_option ('widget_mrsnow_bfgtopgames_PT', $options);
		};
	};

	$options['title'] = $options['title'] ? $options['title'] : $text['titled'];
	$options['count'] = (int) $options['count'] ? $options['count'] : 10;
	$options['type'] = $options['type'] ? $options['type'] : 'pc';
	$options['lang'] = $options['lang'] ? $options['lang'] : 'pt';
	$options['align'] = $options['align'] ? $options['align'] : 'default';
	$options['showbullets'] = $options['showbullets'] ? $options['showbullets'] : '1';
	$options['showpos'] = $options['showpos'] ? $options['showpos'] : '2';
	$options['showimage'] = $options['showimage'] ? $options['showimage'] : '1';
	$options['showtext'] = $options['showtext'] ? $options['showtext'] : '2';
	$options['itype'] = $options['itype'] ? $options['itype'] : '80x80';
	$options['above'] = $options['above'] ? $options['above'] : 'text';
	$options['login'] = $options['login'] ? $options['login'] : '';
	if ($options['channel']==null) 
	{
		$options['channel'] = str_replace ('.', '', str_replace ('_', '', str_replace ('-', '', $_SERVER['HTTP_HOST'])));
		update_option ('widget_mrsnow_bfgtopgames_PT', $options);
	};
	if ($options['channel']=='-') $options['channel'] = '';

	$options['title'] = wp_specialchars ($options['title'], true);

?>
			<div style="text-align:right">

			<label for="bfgtopgames-title" style="line-height:25px;display:block;"><?php _e($text['title'], 'widgets'); ?><input style="width: 200px;" type="text" id="bfgtopgames-title" name="bfgtopgames-title" value="<?php echo $options['title']; ?>" /></label>

			<label for="bfgtopgames-count" style="line-height:25px;display:block;">
				<?php _e($text['gnum'], 'widgets'); ?>
					<select style="width: 200px;" id="bfgtopgames-count" name="bfgtopgames-count">
						<?php for ($cnt=1; $cnt<=10; $cnt++): ?>
							<option value="<?php echo $cnt ?>"<?php if($options['count'] == $cnt) echo ' selected' ?>><?php echo $cnt ?></option>
						<?php endfor; ?>
					</select>
			</label>

			<label for="bfgtopgames-type" style="line-height:25px;display:block;">
				<?php _e($text['gtype'], 'widgets'); ?>
					<select style="width: 200px;" id="bfgtopgames-type" name="bfgtopgames-type">
						<option value="pc"<?php if($options['type'] == 'pc') echo ' selected' ?>><?php _e($text['gtypep'], 'widgets'); ?></option>
						<option value="og"<?php if($options['type'] == 'og') echo ' selected' ?>><?php _e($text['gtypeo'], 'widgets'); ?></option>
					</select>
			</label>

			<label for="bfgtopgames-align" style="line-height:25px;display:block;">
				<?php _e($text['align'], 'widgets'); ?>
					<select style="width: 200px;" id="bfgtopgames-align" name="bfgtopgames-align">
						<option value="default"<?php if($options['align'] == 'default') echo ' selected' ?>><?php _e($text['alignd'], 'widgets'); ?></option>
						<option value="left"<?php if($options['align'] == 'left') echo ' selected' ?>><?php _e($text['alignl'], 'widgets'); ?></option>
						<option value="center"<?php if($options['align'] == 'center') echo ' selected' ?>><?php _e($text['alignc'], 'widgets'); ?></option>
						<option value="right"<?php if($options['align'] == 'right') echo ' selected' ?>><?php _e($text['alignr'], 'widgets'); ?></option>
					</select>
			</label>

			<label for="bfgtopgames-showbullets" style="line-height:25px;display:block;"><?php _e($text['bullets'], 'widgets'); ?> <input type="checkbox" value="1" id="bfgtopgames-showbullets" name="bfgtopgames-showbullets"<?php echo ($options['showbullets']=='1') ? ' checked="checked"' : ''; ?> /></label>

			<label for="bfgtopgames-showpos" style="line-height:25px;display:block;"><?php _e($text['pos'], 'widgets'); ?> <input type="checkbox" value="1" id="bfgtopgames-showpos" name="bfgtopgames-showpos"<?php echo ($options['showpos']=='1') ? ' checked="checked"' : ''; ?> /></label>
			
			<label for="bfgtopgames-showimage" style="line-height:25px;display:block;"><?php _e($text['img'], 'widgets'); ?> <input type="checkbox" value="1" id="bfgtopgames-showimage" name="bfgtopgames-showimage"<?php echo ($options['showimage']=='1') ? ' checked="checked"' : ''; ?> /></label>

			<label for="bfgtopgames-itype" style="line-height:25px;display:block;">
				<?php _e($text['imgtype'], 'widgets'); ?>
					<select style="width: 200px;" id="bfgtopgames-itype" name="bfgtopgames-itype">
						<option value="60x40"<?php if($options['itype'] == '60x40') echo ' selected' ?>><?php _e($text['imgtypes'], 'widgets'); ?></option>
						<option value="80x80"<?php if($options['itype'] == '80x80') echo ' selected' ?>><?php _e($text['imgtyped'], 'widgets'); ?></option>
						<option value="feature"<?php if($options['itype'] == 'feature') echo ' selected' ?><?php _e($text['imgtypef'], 'widgets'); ?></option>
						<option value="subfeature"<?php if($options['itype'] == 'subfeature') echo ' selected' ?>><?php _e($text['imgtypesf'], 'widgets'); ?></option>
					</select>
			</label>

			<label for="bfgtopgames-showtext" style="line-height:25px;display:block;"><?php _e($text['showt'], 'widgets'); ?> <input type="checkbox" value="1" id="bfgtopgames-showtext" name="bfgtopgames-showtext"<?php echo ($options['showtext']=='1') ? ' checked="checked"' : ''; ?> /></label>
			
			<label for="bfgtopgames-above" style="line-height:25px;display:block;">
				<?php _e($text['titlepos'], 'widgets'); ?>
					<select style="width: 200px;" id="bfgtopgames-above" name="bfgtopgames-above">
						<option value="text"<?php if($options['above'] == 'text') echo ' selected' ?>><?php _e($text['titleposti'], 'widgets'); ?></option>
						<option value="image"<?php if($options['above'] == 'image') echo ' selected' ?>><?php _e($text['titleposit'], 'widgets'); ?></option>
					</select>
			</label>

			<label for="bfgtopgames-login" style="line-height:25px;display:block;"><?php _e($text['bfglogin'], 'widgets'); ?><input style="width: 200px;" type="text" id="bfgtopgames-login" name="bfgtopgames-login" value="<?php echo $options['login']; ?>" /></label><small><?php _e($text['bfgloginnb'], 'widgets'); ?></small>
			
			<label for="bfgtopgames-channel" style="line-height:25px;display:block;"><?php _e($text['bfgchan'], 'widgets'); ?><input style="width: 200px;" type="text" id="bfgtopgames-channel" name="bfgtopgames-channel" value="<?php echo $options['channel']; ?>" /></label><small><?php _e($text['bfgchannb'], 'widgets'); ?></small>

			<br/><br/>
			<?php _e($text['copy'], 'widgets'); ?>
			<br/><br/>
			<input type="hidden" name="bfgtopgames-submit" id="bfgtopgames-submit" value="1" />
			</div>
<?php
};

//function mrsnow_microtime_float ()
//{
//	list ($usec, $sec) = explode (" ", microtime ());
//	return ((float)$usec + (float)$sec);
//};

function widget_mrsnow_bfgtopgames_PT_init () 
{	
	// Check for the required API functions
	if ( !function_exists ('register_sidebar_widget') || !function_exists ('register_widget_control') )
		return;

	// This prints the widget
	function widget_mrsnow_bfgtopgames_PT ($args) 
	{
		extract ($args);
		//$start = mrsnow_microtime_float ();
		//echo $before_widget;
		echo "\n".'<!-- Widget Top BFG Games PT: START -->'."\n";
		echo widget_mrsnow_bfgtopgames_PT_work ($before_title, $after_title);
		echo "\n".'<!-- Widget Top BFG Games PT: END -->'."\n";
		//echo $after_widget;
		//$end = mrsnow_microtime_float ();
		//echo "\n".'<!-- Time taken for the 2 queries to complete is '.($end - $start).' seconds -->'."\n";
	};

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget (array ('Widget Top BFG Games PT', 'widgets'), 'widget_mrsnow_bfgtopgames_PT');
	register_widget_control (array ('Widget Top BFG Games PT', 'widgets'), 'widget_mrsnow_bfgtopgames_PT_control');
};

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action ('widgets_init', 'widget_mrsnow_bfgtopgames_PT_init');

?>