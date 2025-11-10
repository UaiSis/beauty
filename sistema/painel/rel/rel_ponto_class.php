<?php 
require_once("../verificar.php");
require_once("../../conexao.php");

$token_rel = 'A5030';
require_once("data_formatada.php");
ob_start();
include("rel_ponto.php");
$html = ob_get_clean();

require_once '../../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

header("Content-Transfer-Encoding: binary");
header("Content-Type: image/png");

$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new DOMPDF($options);

$pdf->set_paper('A4', 'landscape');

$pdf->load_html($html);

$pdf->render();

$pdf->stream(
	'registro_ponto.pdf',
	array("Attachment" => false)
);

?>

