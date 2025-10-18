<?php
require_once 'controllers/AuthController.php';

class ReportesController {
    
    public function exportarPagos() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        // Configurar zona horaria para El Salvador
        date_default_timezone_set('America/El_Salvador');

        require_once 'models/PagoModel.php';
        $pagoModel = new PagoModel();
        $pagos = $pagoModel->getPagos();

        // Configurar headers para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_pagos_' . date('Y-m-d') . '.csv"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        // Crear el output
        $output = fopen('php://output', 'w');

        // Escribir BOM para UTF-8 (para que Excel reconozca los caracteres especiales)
        fputs($output, "\xEF\xBB\xBF");

        // Escribir encabezados
        fputcsv($output, [
            'ID Pago',
            'ID Reserva', 
            'Monto',
            'Método de Pago',
            'Fecha',
            'Estado'
        ], ';'); // Usar punto y coma como separador para Excel en español

        // Escribir datos
        foreach ($pagos as $pago) {
            fputcsv($output, [
                $pago['payment_id'],
                $pago['reservation_id'],
                '$' . number_format($pago['amount'], 2),
                $pago['payment_method'],
                $pago['payment_date'],
                $pago['payment_status']
            ], ';');
        }

        fclose($output);
        exit;
    }

    public function exportarPagosPDF() {
        AuthController::checkAuth();
        AuthController::checkRole(['administrador']);

        // Configurar zona horaria para El Salvador
        date_default_timezone_set('America/El_Salvador');

        require_once 'models/PagoModel.php';
        $pagoModel = new PagoModel();
        $pagos = $pagoModel->getPagos();
        $totales = $pagoModel->getTotales();

        // HTML para el PDF
        $html = $this->generarHTMLReporte($pagos, $totales);

        // Configurar headers para PDF
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: inline; filename="reporte_pagos_' . date('Y-m-d_H-i') . '.html"');
        
        echo $html;
        exit;
    }

    private function generarHTMLReporte($pagos, $totales) {
        // Configurar zona horaria para El Salvador
        date_default_timezone_set('America/El_Salvador');
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reporte de Pagos - <?= date('d/m/Y H:i'); ?></title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    line-height: 1.6;
                    color: #333;
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                    padding: 20px;
                }
                
                .reporte-container {
                    max-width: 1200px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                    overflow: hidden;
                }
                
                .header {
                    background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
                    color: white;
                    text-align: center;
                    padding: 40px 20px;
                    position: relative;
                }
                
                .header::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="grain" width="100" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="20" fill="url(%23grain)"/></svg>');
                    opacity: 0.3;
                }
                
                .header h1 {
                    font-size: 2.5em;
                    margin-bottom: 10px;
                    position: relative;
                    z-index: 1;
                }
                
                .header h3 {
                    font-size: 1.2em;
                    font-weight: 300;
                    position: relative;
                    z-index: 1;
                }
                
                .fecha-reporte {
                    text-align: right;
                    padding: 20px 30px;
                    background: #f8f9fc;
                    border-bottom: 1px solid #e3e6f0;
                    color: #5a5c69;
                    font-size: 0.9em;
                }
                
                .content {
                    padding: 30px;
                }
                
                .totales {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                    margin-bottom: 40px;
                }
                
                .total-card {
                    background: white;
                    border-radius: 10px;
                    padding: 25px;
                    text-align: center;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                    border: 1px solid #e3e6f0;
                    transition: transform 0.3s ease;
                }
                
                .total-card:hover {
                    transform: translateY(-2px);
                }
                
                .total-card h4 {
                    font-size: 1.1em;
                    margin-bottom: 15px;
                    color: #5a5c69;
                    font-weight: 600;
                }
                
                .total-card .amount {
                    font-size: 2em;
                    font-weight: 700;
                    margin-bottom: 5px;
                }
                
                .total-card.success .amount { color: #1cc88a; }
                .total-card.warning .amount { color: #f6c23e; }
                .total-card.info .amount { color: #36b9cc; }
                .total-card.danger .amount { color: #e74a3b; }
                
                .table-container {
                    background: white;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                
                th {
                    background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
                    color: white;
                    padding: 15px 12px;
                    text-align: center;
                    font-weight: 600;
                    font-size: 0.9em;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                td {
                    padding: 12px;
                    text-align: center;
                    border-bottom: 1px solid #e3e6f0;
                    font-size: 0.9em;
                }
                
                tr:nth-child(even) {
                    background-color: #f8f9fc;
                }
                
                tr:hover {
                    background-color: #eaecf4;
                }
                
                .status-badge {
                    display: inline-block;
                    padding: 5px 12px;
                    border-radius: 20px;
                    font-size: 0.8em;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                .status-pendiente { background: #fff3cd; color: #856404; }
                .status-pagado { background: #d1ecf1; color: #0c5460; }
                .status-cancelado { background: #f8d7da; color: #721c24; }
                
                .actions {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 1px solid #e3e6f0;
                }
                
                .btn {
                    display: inline-block;
                    padding: 12px 25px;
                    margin: 0 10px;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    border: none;
                    cursor: pointer;
                    font-size: 0.9em;
                }
                
                .btn-primary {
                    background: linear-gradient(135deg, #371783 0%, #8B5A96 100%);
                    color: white;
                }
                
                .btn-secondary {
                    background: #6c757d;
                    color: white;
                }
                
                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                }
                
                @media print {
                    .no-print { display: none !important; }
                    body { background: white !important; padding: 0 !important; }
                    .reporte-container { box-shadow: none !important; }
                }
                
                @media (max-width: 768px) {
                    .totales {
                        grid-template-columns: 1fr;
                    }
                    
                    .content {
                        padding: 20px;
                    }
                    
                    .header h1 {
                        font-size: 1.8em;
                    }
                    
                    table {
                        font-size: 0.8em;
                    }
                }
            </style>
        </head>
        <body>
            <div class="reporte-container">
                <div class="header">
                    <h1>💰 Reporte de Pagos</h1>
                    <h3>Plataforma de Clases Online</h3>
                </div>
                
                <div class="fecha-reporte">
                    📅 Generado el: <?= date('d/m/Y \a \l\a\s H:i:s'); ?>
                </div>
                
                <div class="content">
                    <div class="totales">
                        <div class="total-card success">
                            <h4>💵 Total Recaudado</h4>
                            <div class="amount">$<?= number_format($totales['totalRecaudado'] ?? 0, 2); ?></div>
                        </div>
                        <div class="total-card warning">
                            <h4>⏳ Pagos Pendientes</h4>
                            <div class="amount"><?= $totales['totalPendientes'] ?? 0; ?></div>
                        </div>
                        <div class="total-card info">
                            <h4>✅ Pagos Completados</h4>
                            <div class="amount"><?= $totales['totalPagados'] ?? 0; ?></div>
                        </div>
                        <div class="total-card danger">
                            <h4>❌ Pagos Cancelados</h4>
                            <div class="amount"><?= $totales['totalCancelados'] ?? 0; ?></div>
                        </div>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>🆔 ID Pago</th>
                                    <th>📋 ID Reserva</th>
                                    <th>💰 Monto</th>
                                    <th>💳 Método</th>
                                    <th>📅 Fecha</th>
                                    <th>📊 Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pagos as $pago): ?>
                                    <?php
                                        $estado = strtolower($pago['payment_status']);
                                        $statusClass = match($estado) {
                                            'pendiente' => 'status-pendiente',
                                            'pagado' => 'status-pagado',
                                            'cancelado' => 'status-cancelado',
                                            default => 'status-pendiente'
                                        };
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($pago['payment_id']); ?></td>
                                        <td><?= htmlspecialchars($pago['reservation_id']); ?></td>
                                        <td><strong>$<?= number_format($pago['amount'], 2); ?></strong></td>
                                        <td><?= htmlspecialchars($pago['payment_method']); ?></td>
                                        <td><?= htmlspecialchars($pago['payment_date']); ?></td>
                                        <td>
                                            <span class="status-badge <?= $statusClass; ?>">
                                                <?= ucfirst($pago['payment_status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="actions no-print">
                    <button onclick="window.print()" class="btn btn-primary">
                        🖨️ Imprimir / Guardar como PDF
                    </button>
                    <a href="/plataforma-clases-online/home/pagos" class="btn btn-secondary">
                        ← Volver a Pagos
                    </a>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
?>