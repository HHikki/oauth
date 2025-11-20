<?php
require_once 'includes/session.php';
require_once 'includes/clients.php';

// Verificar si composer está instalado
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('<div style="font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">
        <h3>⚠️ Error: Librería TCPDF no instalada</h3>
        <p>Para generar PDF, necesitas instalar las dependencias de Composer.</p>
        <p><strong>Ejecuta en la terminal:</strong></p>
        <pre style="background: #fff; padding: 10px; border-radius: 3px;">composer install</pre>
        <p>O si no tienes Composer instalado, descárgalo de: <a href="https://getcomposer.org/" target="_blank">https://getcomposer.org/</a></p>
        <p><a href="dashboard.php" style="color: #721c24;">← Volver al Dashboard</a></p>
    </div>');
}

require_once __DIR__ . '/vendor/autoload.php';

requireLogin();

$clientsManager = new ClientsManager();
$clients = $clientsManager->getAllClients();

// Crear nuevo PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Configurar información del documento
$pdf->SetCreator('Sistema de Gestión de Clientes');
$pdf->SetAuthor(getUserEmail());
$pdf->SetTitle('Lista de Clientes');
$pdf->SetSubject('Reporte de Clientes');

// Configurar fuente
$pdf->SetFont('helvetica', '', 10);

// Quitar header y footer por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Agregar página
$pdf->AddPage();

// Título
$pdf->SetFont('helvetica', 'B', 20);
$pdf->SetTextColor(102, 126, 234);
$pdf->Cell(0, 15, 'Gestión de Clientes', 0, 1, 'C');

// Subtítulo
$pdf->SetFont('helvetica', '', 12);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 8, 'Sistema CRUD completo con generación de PDF', 0, 1, 'C');
$pdf->Ln(5);

// Información del reporte
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 6, 'Fecha: ' . date('d/m/Y H:i:s'), 0, 1, 'L');
$pdf->Cell(0, 6, 'Total de clientes: ' . count($clients), 0, 1, 'L');
$pdf->Cell(0, 6, 'Generado por: ' . getUserEmail(), 0, 1, 'L');
$pdf->Ln(10);

// Crear tabla
if (empty($clients)) {
    $pdf->SetFont('helvetica', 'I', 12);
    $pdf->SetTextColor(150, 150, 150);
    $pdf->Cell(0, 10, 'No hay clientes registrados', 0, 1, 'C');
} else {
    // Cabecera de la tabla
    $pdf->SetFillColor(102, 126, 234);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 11);
    
    $pdf->Cell(10, 10, 'ID', 1, 0, 'C', true);
    $pdf->Cell(45, 10, 'Nombre', 1, 0, 'C', true);
    $pdf->Cell(50, 10, 'Email', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Teléfono', 1, 0, 'C', true);
    $pdf->Cell(55, 10, 'Dirección', 1, 1, 'C', true);
    
    // Contenido de la tabla
    $pdf->SetFont('helvetica', '', 9);
    $pdf->SetTextColor(60, 60, 60);
    
    $fill = false;
    $counter = 1;
    
    foreach ($clients as $client) {
        if ($fill) {
            $pdf->SetFillColor(240, 240, 255);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
        
        $pdf->Cell(10, 8, $counter++, 1, 0, 'C', true);
        $pdf->Cell(45, 8, substr($client['nombre'], 0, 25), 1, 0, 'L', true);
        $pdf->Cell(50, 8, substr($client['email'], 0, 30), 1, 0, 'L', true);
        $pdf->Cell(30, 8, $client['telefono'], 1, 0, 'C', true);
        $pdf->Cell(55, 8, substr($client['direccion'], 0, 35), 1, 1, 'L', true);
        
        $fill = !$fill;
    }
}

// Pie de página
$pdf->Ln(15);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(150, 150, 150);
$pdf->Cell(0, 5, 'Documento generado automáticamente por el Sistema de Gestión de Clientes', 0, 1, 'C');

// Salida del PDF
$pdf->Output('clientes_' . date('Y-m-d_His') . '.pdf', 'I');
