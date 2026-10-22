
<?php



$configLocal = __DIR__ . '/Config.local.php';

if (!file_exists($configLocal)) {
    throw new RuntimeException(
        'Falta el archivo privado Config/Config.local.php'
    );
}

$config = require $configLocal;

if (!is_array($config)) {
    throw new RuntimeException(
        'La configuración privada no tiene un formato válido.'
    );
}


define(
    'BASE_URL',
    $config['BASE_URL'] ?? 'http://localhost/adulto'
);

date_default_timezone_set('America/Bogota');

define('DB_HOST', $config['DB_HOST'] ?? '127.0.0.1');
define('DB_PORT', $config['DB_PORT'] ?? 3306);
define('DB_NAME', $config['DB_NAME'] ?? 'adultomayor');
define('DB_USER', $config['DB_USER'] ?? '');
define('DB_PASSWORD', $config['DB_PASSWORD'] ?? '');
define('DB_CHARSET', $config['DB_CHARSET'] ?? 'utf8mb4');


//Datos envio de correo
const NOMBRE_REMITENTE = "SENA La Jagua";
const EMAIL_REMITENTE = "sena.lajagua@sena.edu.co";
const NOMBRE_EMPESA = "Neila";
const WEB_EMPRESA = "www.sena.edu.co";

const DESCRIPCION = "Servicio Nacional de Aprendizaje";
const SHAREDHASH = "Servicio Nacional de Aprendizaje";

//Datos Empresa
const DIRECCION = "La Jagua - Cesar, Colombia";
const TELEMPRESA = "3114689298";
const WHATSAPP = "3114689298";
const EMAIL_EMPRESA = "sena.lajagua@sena.edu.co";
const EMAIL_CONTACTO = "sena.lajagua@sena.edu.co";

const CAT_SLIDER = "1,2,3";
const CAT_BANNER = "4,5,6";
const CAT_FOOTER = "1,2,3,4,5";

//Datos para Encriptar / Desencriptar
const KEY = 'joseramos';
const METHODENCRIPT = "AES-128-ECB";

//Módulos
const MDASHBOARD = 1;
const MINICIO = 2;
const MUSUARIOS = 3;
const MPROGRAMAS = 4;
const MFICHAS = 5;
const MCOMPETENCIAS = 6;
const MASIGNACIONES = 7;
const MREPORTES = 8;
//Páginas
const PINICIO = 1;

//Roles
const RADMINISTRADOR = 1;
const RCOORDINADOR = 2;
