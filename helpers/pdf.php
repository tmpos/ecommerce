<?php

class InvoicePdf {
  private $x = 50;
  private $y = 50;
  private $content = '';
  private $fonts = [];
  private $pageH = 792;
  private $usedColors = [];

  function addFont($name, $base) {
    $this->fonts[$name] = $base;
    return $this;
  }

  private function emit($s) {
    $this->content .= $s . "\n";
  }

  private function py($y) {
    return $this->pageH - $y;
  }

  function setXY($x, $y) {
    $this->x = $x;
    $this->y = $y;
    return $this;
  }

  function getY() {
    return $this->y;
  }

  function text($size, $text, $bold = false, $color = null) {
    $font = $bold ? 'Fb' : 'F1';
    $text = $this->escape($text);
    $this->emit("BT /$font $size Tf");
    if ($color) $this->emit(($color[0]/255) . ' ' . ($color[1]/255) . ' ' . ($color[2]/255) . ' rg');
    $this->emit($this->x . ' ' . $this->py($this->y) . ' Td (' . $text . ') Tj ET');
    if ($color) $this->emit('0 rg');
    return $this;
  }

  function cell($w, $h, $text, $size = 10, $bold = false, $align = 'left', $color = null) {
    $font = $bold ? 'Fb' : 'F1';
    $text = $this->escape($text);
    $tw = strlen($text) * $size * 0.5;
    $tx = $this->x;
    if ($align === 'right') $tx = $this->x + $w - $tw;
    elseif ($align === 'center') $tx = $this->x + ($w - $tw) / 2;
    $this->emit("BT /$font $size Tf");
    if ($color) $this->emit(($color[0]/255) . ' ' . ($color[1]/255) . ' ' . ($color[2]/255) . ' rg');
    $this->emit($tx . ' ' . $this->py($this->y) . ' Td (' . $text . ') Tj ET');
    if ($color) $this->emit('0 rg');
    $this->x += $w;
    return $this;
  }

  function ln($h) {
    $this->x = 50;
    $this->y += $h;
    return $this;
  }

  function line($x1, $y1, $x2, $y2) {
    $this->emit($x1 . ' ' . $this->py($y1) . ' m ' . $x2 . ' ' . $this->py($y2) . ' l S');
    return $this;
  }

  function rectFill($x, $y, $w, $h, $r, $g, $b) {
    $this->emit(($r/255) . ' ' . ($g/255) . ' ' . ($b/255) . ' rg');
    $this->emit($x . ' ' . $this->py($y + $h) . ' ' . $w . ' ' . $h . ' re f');
    $this->emit('0 rg');
    return $this;
  }

  private function escape($s) {
    $s = str_replace('\\', '\\\\', $s);
    $s = str_replace('(', '\\(', $s);
    $s = str_replace(')', '\\)', $s);
    return $s;
  }

  function output() {
    $fonts = '';
    foreach ($this->fonts as $name => $base) {
      $fonts .= "/$name << /Type /Font /Subtype /Type1 /BaseFont /$base >>\n";
    }

    $stream = $this->content . "\n";
    $streamLen = strlen($stream);

    $obj1 = "1 0 obj\n<</Type /Catalog /Pages 2 0 R>>\nendobj\n";
    $obj2 = "2 0 obj\n<</Type /Pages /Kids [3 0 R] /Count 1>>\nendobj\n";
    $obj3 = "3 0 obj\n<</Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources <</Font << $fonts >> >> >>\nendobj\n";
    $obj4 = "4 0 obj\n<</Length $streamLen>>\nstream\n$stream\nendstream\nendobj\n";

    $pdf = "%PDF-1.4\n";
    $off1 = strlen($pdf);
    $pdf .= $obj1;
    $off2 = strlen($pdf);
    $pdf .= $obj2;
    $off3 = strlen($pdf);
    $pdf .= $obj3;
    $off4 = strlen($pdf);
    $pdf .= $obj4;

    $xref = "xref\n0 5\n0000000000 65535 f \n";
    $xref .= sprintf("%010d 00000 n \n", $off1);
    $xref .= sprintf("%010d 00000 n \n", $off2);
    $xref .= sprintf("%010d 00000 n \n", $off3);
    $xref .= sprintf("%010d 00000 n \n", $off4);

    $xrefStart = strlen($pdf);
    $pdf .= $xref;
    $pdf .= "trailer\n<</Size 5 /Root 1 0 R>>\n";
    $pdf .= "startxref\n" . $xrefStart . "\n%%EOF";

    return $pdf;
  }
}

function generateInvoicePdf($orderId, $items, $subtotal, $shipping, $total, $user, $address, $city, $zip, $siteName, $currency = 'RD$', $primaryColor = '#2563eb') {
  $pdf = new InvoicePdf();
  $pdf->addFont('F1', 'Helvetica')
      ->addFont('Fb', 'Helvetica-Bold');

  $pageW = 612;
  $r = hexdec(substr($primaryColor, 1, 2));
  $g = hexdec(substr($primaryColor, 3, 2));
  $b = hexdec(substr($primaryColor, 5, 2));
  $gy = [100, 100, 100];
  $gyL = [150, 150, 150];

  // === HEADER BAR ===
  $pdf->rectFill(0, 0, $pageW, 140, 17, 24, 39);
  $pdf->setXY(50, 30)->text(28, 'INVOICE', true, [255, 255, 255]);
  $pdf->setXY(50, 62)->text(12, $siteName, false, [180, 190, 200]);

  // Right: Invoice #
  $pdf->setXY($pageW - 95, 29)->cell(45, 12, 'Invoice #', 10, false, 'right', [180, 190, 200]);
  $pdf->setXY($pageW - 95, 44)->cell(45, 12, str_pad($orderId, 8, '0', STR_PAD_LEFT), 14, true, 'right', [255, 255, 255]);

  // === BILLED TO ===
  $pdf->setXY(50, 165)->text(10, 'BILLED TO', true, [$r, $g, $b]);
  $pdf->setXY(50, 182)->text(16, $user['name'], true);
  $pdf->setXY(50, 198)->text(10, $user['email'], false, $gy);

  $pdf->setXY($pageW - 95, 165)->cell(45, 12, 'Date:', 10, false, 'right', $gy);
  $pdf->setXY($pageW - 95, 180)->text(10, date('M j, Y'), true);
  $pdf->setXY($pageW - 95, 195)->cell(45, 12, 'Status:', 10, false, 'right', $gy);
  $pdf->setXY($pageW - 95, 210)->text(10, 'Paid', true);

  // === DIVIDER ===
  $pdf->line(40, 225, $pageW - 40, 225);

  // === TABLE HEADER ===
  $y0 = 240;
  $colX = [50, 280, 360, 440];
  $colW = [230, 80, 80, 122];
  $headers = ['Product', 'Qty', 'Price', 'Total'];
  $pdf->rectFill(50, $y0, 512, 28, $r, $g, $b);
  $pdf->setXY(50, $y0 + 9);
  foreach ($headers as $hidx => $h) {
    $pdf->cell($colW[$hidx], 12, $h, 10, true, $hidx === 0 ? 'left' : 'right', [255, 255, 255]);
  }

  // === ITEMS ===
  $y1 = $y0 + 28;
  $rowH = 30;
  foreach ($items as $idx => $item) {
    $isEven = $idx % 2 === 0;
    $bg = $isEven ? [245, 247, 250] : [255, 255, 255];
    $pdf->rectFill(50, $y1, 512, $rowH, $bg[0], $bg[1], $bg[2]);

    $detail = $item['name'];
    $size = $item['size'] ?? '';
    $color = $item['color'] ?? '';
    if ($size || $color) $detail .= ' (' . trim($size . ($size && $color ? ', ' : '') . $color) . ')';

    $qty = (int)($item['quantity']);
    $price = (float)($item['price']);
    $totalRow = $price * $qty;

    $vals = [$detail, (string)$qty, $currency . ' ' . number_format($price, 2), $currency . ' ' . number_format($totalRow, 2)];
    $pdf->setXY(55, $y1 + 8);
    foreach ($vals as $vidx => $v) {
      $pdf->cell($colW[$vidx], 12, $v, 9, false, $vidx === 0 ? 'left' : 'right', [50, 50, 50]);
    }

    $y1 += $rowH;
  }

  // === TOTALS ===
  $totY = $y1 + 10;
  $labelX = $colX[2] - 20;
  $valueX = $colX[3] - 10;
  $totW = $colW[3] + 15;
  $pdf->line($valueX, $totY, $valueX + $totW, $totY);
  $totals = [
    ['Subtotal', $currency . ' ' . number_format($subtotal, 2)],
    ['Shipping', $shipping > 0 ? $currency . ' ' . number_format($shipping, 2) : 'Free'],
  ];
  foreach ($totals as $t) {
    $totY += 18;
    $pdf->setXY($labelX, $totY)->text(10, $t[0], false, $gy);
    $pdf->setXY($valueX + $totW - 5, $totY)->cell(5, 12, $t[1], 10, true, 'right');
  }
  // Total
  $totY += 24;
  $pdf->line($valueX, $totY, $valueX + $totW, $totY);
  $totY += 8;
  $pdf->setXY($labelX, $totY)->text(14, 'Total', true);
  $pdf->setXY($valueX + $totW - 5, $totY)->cell(5, 12, $currency . ' ' . number_format($total, 2), 14, true, 'right', [$r, $g, $b]);

  // === SHIPPING ADDRESS ===
  $shipY = $totY + 50;
  $pdf->rectFill(50, $shipY, 512, 45, 248, 250, 252);
  $pdf->setXY(55, $shipY + 8)->text(9, 'SHIPPING ADDRESS', true, [$r, $g, $b]);
  $pdf->setXY(55, $shipY + 22)->text(10, $address . ', ' . $city . ' ' . $zip, false, [50, 50, 50]);

  // === FOOTER ===
  $pdf->line(40, 720, $pageW - 40, 720);
  $pdf->setXY(50, 728)->text(8, 'Thank you for your purchase!', false, $gyL);

  return $pdf->output();
}
