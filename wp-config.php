<?php
/**
 * Baskonfiguration för WordPress.
 *
 * Denna fil används av wp-config.php-genereringsskript under installationen.
 * Du behöver inte använda webbplatsen, du kan kopiera denna fil direkt till
 * "wp-config.php" och fylla i värdena.
 *
 * Denna fil innehåller följande konfigurationer:
 *
 * * Inställningar för MySQL
 * * Säkerhetsnycklar
 * * Tabellprefix för databas
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL-inställningar - MySQL-uppgifter får du från ditt webbhotell ** //

/** Namnet på databasen du vill använda för WordPress */
define('DB_NAME', 'wpkorv');

/** MySQL-databasens användarnamn */
define('DB_USER', 'root');

/** MySQL-databasens lösenord */
define('DB_PASSWORD', 'root');

/** MySQL-server */
define('DB_HOST', 'localhost');

/** Teckenkodning för tabellerna i databasen. */
define('DB_CHARSET', 'utf8mb4');

/** Kollationeringstyp för databasen. Ändra inte om du är osäker. */
define('DB_COLLATE', '');

/**#@+
 * Unika autentiseringsnycklar och salter.
 *
 * Ändra dessa till unika fraser!
 * Du kan generera nycklar med {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Du kan när som helst ändra dessa nycklar för att göra aktiva cookies obrukbara, vilket tvingar alla användare att logga in på nytt.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '>>3?9@| dzuV|%t`1VxOREqD;MDD&W(,,>fSv[K}n~xk@F*hW*)@3l6.h#/%reSj');
define('SECURE_AUTH_KEY',  '0ZQx<,_QpY0Ss1new|w<L$R7Sm!%n-iXcIv?A:<I_m,WA[mR!DPtAaoM+<-n+J#P');
define('LOGGED_IN_KEY',    'p#F]$Ja|:W,BZb.R`Y?tIt.IHOu%rmAoyQr8Gqx,|{~2]~K;G9Po_*<Ed-,t+7SG');
define('NONCE_KEY',        '^=]^GswH?`u@P3,.AjK-4x+(Tk_#ZT+*s0jB8y G4 Z*s<(/x;zN<R6qr+SE:%|&');
define('AUTH_SALT',        'SON9a-O>my{(p{-aj397Xb8QteSb<2V*{-P*a;- QxJqf+&%Xub;|Yt:bU*CR3Vi');
define('SECURE_AUTH_SALT', 'iR q;gB)GKmLH>S(oY}>><fEdB.h0VC?!ky<)`SgKc(d&L?Z9s)rli* + ap|mh(');
define('LOGGED_IN_SALT',   'RNd_ZBK=-m,:IU%P^>ZoZ;t^[<ju5mPNYk)*XFfa-og!C#HTgt4osTI1Zu[$pukI');
define('NONCE_SALT',       '5!u/:;</V|2hyOr0+WM}i9GpcI2Z4M,8AaXJ1nq(T$PqM5*Oq6A c)D^xYCoH.>O');

/**#@-*/

/**
 * Tabellprefix för WordPress Databasen.
 *
 * Du kan ha flera installationer i samma databas om du ger varje installation ett unikt
 * prefix. Endast siffror, bokstäver och understreck!
 */
$table_prefix  = 'wp_';

/**
 * För utvecklare: WordPress felsökningsläge.
 *
 * Ändra detta till true för att aktivera meddelanden under utveckling.
 * Det är rekommderat att man som tilläggsskapare och temaskapare använder WP_DEBUG
 * i sin utvecklingsmiljö.
 *
 * För information om andra konstanter som kan användas för felsökning,
 * se dokumentationen.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* Det var allt, sluta redigera här! Blogga på. */

/** Absoluta sökväg till WordPress-katalogen. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Anger WordPress-värden och inkluderade filer. */
require_once(ABSPATH . 'wp-settings.php');
