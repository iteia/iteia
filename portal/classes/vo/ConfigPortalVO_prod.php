<?php
session_name('session_penc');
session_start();

class ConfigPortalVO {

	//const DIR_PENC = "/home/ramom/penc/"; //local
	const DIR_PENC = '/home/pages/iteia4/'; //no ar

	public static function getDirPenc() {
		return self::DIR_PENC;
	}

	public static function getDirClassesPortal() {
		return self::DIR_PENC."portal/classes/";
	}

	public static function getDirClassesRaiz() {
		return self::DIR_PENC."classes/";
	}

}
