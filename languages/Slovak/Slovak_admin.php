<?php
/**
 * FILE: e107_plugins/forum_extended/languages/Slovak/Slovak_admin.php
 *
 * Admin constants. Loaded via e107::lan('forum_extended', true, true) at the top of admin/admin_menu.php.
 */

if(!defined('e107_INIT')) { exit; }

return [
	'LAN_FORUM_EXT_NAME'                    => "Forum Extended",
	'LAN_FORUM_EXT_ADMIN_THREADS'           => "Témy",
	'LAN_FORUM_EXT_ADMIN_THREADS_HELP'      => "Jeden plochý zoznam všetkých tém fóra, hlavne na vyhľadávanie. Úpravy menia iba riadok témy - súvisiace údaje (počty vo fórach) sa nekontrolujú. Mazanie funguje ako na webe a zmaže aj príspevky.",
	'LAN_FORUM_EXT_ADMIN_TOPIC'             => "Téma",
	'LAN_FORUM_EXT_ADMIN_FORUM'             => "Fórum",
	'LAN_FORUM_EXT_ADMIN_VIEWS'             => "Zobrazení",
	'LAN_FORUM_EXT_ADMIN_REPLIES'           => "Odpovedí",
	'LAN_FORUM_EXT_ADMIN_ACTIVE'            => "Otvorená",
	'LAN_FORUM_EXT_ADMIN_STICKY'            => "Pripnutá",
	'LAN_FORUM_EXT_ADMIN_NORMAL'            => "Bežná",
	'LAN_FORUM_EXT_ADMIN_ANNOUNCE'          => "Oznámenie",
	'LAN_FORUM_EXT_ADMIN_LASTPOST'          => "Posledný príspevok",
	'LAN_FORUM_EXT_ADMIN_DELETED'           => "Téma bola zmazaná.",
	'LAN_FORUM_EXT_ADMIN_DELETE_FAILED'     => "Tému sa nepodarilo zmazať.",
	'LAN_FORUM_EXT_ADMIN_LINKS' => "Abecedné odkazy",
	'LAN_FORUM_EXT_ADMIN_LINK' => "Odkaz",
	'LAN_FORUM_EXT_ADMIN_SHORTCODE' => "Shortcode",
	'LAN_FORUM_EXT_ADMIN_LINKS_HELP' => "Každé fórum sa dá zobraziť abecedne. Odkaz skopírujte do menu (Sitelinks) alebo do pripnutej témy. V šablóne zoznamu tém fóra použite {FORUM_EXT_ALPHA_LINK: forums=67} - aktuálne fórum zistí sám, forums= ho obmedzí na uvedené fóra. Inde (napr. v šablóne témy) zadajte fórum: {FORUM_EXT_ALPHA_LINK: id=67}. type=url vráti iba adresu.",
];
