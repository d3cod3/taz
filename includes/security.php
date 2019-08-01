<?php

/**************************************************** SECURE HEADERS *******/
/* Force disable proxy and client browser caching */
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: " . date("r", substr("0000000000000", 0, 10))); // epoch start
/* HTTP Strict Transport Security (HSTS) */
header("Strict-Transport-Security: max-age=31536000 ; includeSubDomains");
/* X-Frame-Options */
header("X-Frame-Options: deny");
/* X-XSS-Protection */
header("X-XSS-Protection: 1; mode=block");
/* X-Content-Type-Options */
header("X-Content-Type-Options: nosniff");
/* Content-Security-Policy */
// Allow execute scripts from this domain only
header("Content-Security-Policy: script-src 'self'");
/* X-Permitted-Cross-Domain-Policies */
header("X-Permitted-Cross-Domain-Policies: none");

ini_set("display_errors", "off");

?>

