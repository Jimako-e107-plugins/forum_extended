<?php
/**
 * FILE: e107_plugins/forum_extended/languages/Czech/Czech_admin.php
 *
 * Admin constants. Loaded via e107::lan('forum_extended', true, true) at the top of admin/admin_menu.php.
 */

if(!defined('e107_INIT')) { exit; }

return [
	'LAN_FORUM_EXT_NAME'                    => "Forum Extended",
	'LAN_FORUM_EXT_ADMIN_THREADS'           => "Témata",
	'LAN_FORUM_EXT_ADMIN_THREADS_HELP'      => "Jeden plochý seznam všech témat fóra, hlavně pro vyhledávání. Úpravy mění jen řádek tématu - související údaje (počty ve fórech) se nekontrolují. Mazání funguje jako na webu a smaže i příspěvky.",
	'LAN_FORUM_EXT_ADMIN_TOPIC'             => "Téma",
	'LAN_FORUM_EXT_ADMIN_FORUM'             => "Fórum",
	'LAN_FORUM_EXT_ADMIN_VIEWS'             => "Zobrazení",
	'LAN_FORUM_EXT_ADMIN_REPLIES'           => "Odpovědí",
	'LAN_FORUM_EXT_ADMIN_ACTIVE'            => "Otevřené",
	'LAN_FORUM_EXT_ADMIN_STICKY'            => "Připnuté",
	'LAN_FORUM_EXT_ADMIN_NORMAL'            => "Běžné",
	'LAN_FORUM_EXT_ADMIN_ANNOUNCE'          => "Oznámení",
	'LAN_FORUM_EXT_ADMIN_LASTPOST'          => "Poslední příspěvek",
	'LAN_FORUM_EXT_ADMIN_DELETED'           => "Téma bylo smazáno.",
	'LAN_FORUM_EXT_ADMIN_DELETE_FAILED'     => "Téma se nepodařilo smazat.",
	'LAN_FORUM_EXT_ADMIN_LINKS' => "Abecední odkazy",
	'LAN_FORUM_EXT_ADMIN_LINK' => "Odkaz",
	'LAN_FORUM_EXT_ADMIN_SHORTCODE' => "Shortcode",
	'LAN_FORUM_EXT_ADMIN_LINKS_HELP' => "Každé fórum lze zobrazit abecedně. Odkaz zkopírujte do menu (Sitelinks) nebo do připnutého tématu. V šabloně seznamu témat fóra použijte {FORUM_EXT_ALPHA_LINK: forums=67} - aktuální fórum zjistí sám, forums= ho omezí na uvedená fóra. Jinde (např. v šabloně tématu) zadejte fórum: {FORUM_EXT_ALPHA_LINK: id=67}. type=url vrátí jen adresu.",
];
