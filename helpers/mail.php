<?php

function smtpResponse($sock) {
  $resp = '';
  do {
    $line = fgets($sock);
    if ($line === false) break;
    $resp .= $line;
  } while (strlen($line) >= 4 && $line[3] === '-');
  return $resp;
}

function sendMail($to, $subject, $htmlBody, $attachments = []) {
    global $SETTINGS;

    $host = $SETTINGS['smtp_host'] ?? '';
    $port = (int)($SETTINGS['smtp_port'] ?? 587);
    $username = $SETTINGS['smtp_username'] ?? '';
    $password = $SETTINGS['smtp_password'] ?? '';
    $encryption = $SETTINGS['smtp_encryption'] ?? 'tls';
    $fromEmail = $SETTINGS['smtp_from_email'] ?? '';
    $fromName = $SETTINGS['smtp_from_name'] ?? $SETTINGS['site_name'] ?? 'Your Store';

    // Build MIME body
    if (!empty($attachments)) {
        $boundary = 'boundary_' . md5(uniqid('', true));
        $body = "--{$boundary}\r\n"
              . "Content-Type: text/html; charset=UTF-8\r\n"
              . "Content-Transfer-Encoding: 8bit\r\n\r\n"
              . $htmlBody . "\r\n\r\n";
        foreach ($attachments as $att) {
            $body .= "--{$boundary}\r\n"
                   . "Content-Type: application/pdf; name=\"" . $att['name'] . "\"\r\n"
                   . "Content-Transfer-Encoding: base64\r\n"
                   . "Content-Disposition: attachment; filename=\"" . $att['name'] . "\"\r\n\r\n"
                   . chunk_split(base64_encode($att['data'])) . "\r\n";
        }
        $body .= "--{$boundary}--\r\n";
        $contentType = "multipart/mixed; boundary=\"{$boundary}\"";
    } else {
        $body = $htmlBody;
        $contentType = "text/html; charset=UTF-8";
    }

    if (empty($host) || empty($fromEmail)) {
        $fallback = mail($to, $subject, $body, "MIME-Version: 1.0\r\nContent-Type: {$contentType}\r\nFrom: {$fromName} <{$fromEmail}>\r\nX-Mailer: PHP/" . phpversion());
        return $fallback;
    }

    if ($port === 465) $encryption = 'ssl';
    set_time_limit(60);

    try {
        $socket = @stream_socket_client(($encryption === 'ssl' ? 'ssl://' : '') . $host . ':' . $port, $errno, $errstr, 30);
        if (!$socket) throw new Exception("Connection failed: $errstr");

        smtpResponse($socket);
        fwrite($socket, "EHLO localhost\r\n");
        smtpResponse($socket);
        if ($encryption === 'tls') {
            fwrite($socket, "STARTTLS\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '220') throw new Exception("STARTTLS failed: " . trim($resp));
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            fwrite($socket, "EHLO localhost\r\n");
            smtpResponse($socket);
        }
        if (!empty($username)) {
            fwrite($socket, "AUTH LOGIN\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '334') throw new Exception("AUTH not supported: " . trim($resp));
            fwrite($socket, base64_encode($username) . "\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '334') throw new Exception("Username rejected: " . trim($resp));
            fwrite($socket, base64_encode($password) . "\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '235') throw new Exception("Authentication failed: " . trim($resp));
        }

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = "MIME-Version: 1.0\r\nContent-Type: {$contentType}\r\n"
                 . "From: {$fromName} <{$fromEmail}>\r\n"
                 . "X-Mailer: PHP\r\n";

        fwrite($socket, "MAIL FROM:<{$fromEmail}>\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '250') throw new Exception("MAIL FROM failed: " . trim($resp));

        fwrite($socket, "RCPT TO:<{$to}>\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '250' && substr($resp, 0, 3) !== '251') throw new Exception("RCPT TO failed: " . trim($resp));

        fwrite($socket, "DATA\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '354') throw new Exception("DATA failed: " . trim($resp));

        fwrite($socket, "Subject: {$encodedSubject}\r\n{$headers}\r\n{$body}\r\n.\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '250') throw new Exception("Send failed: " . trim($resp));

        fwrite($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    } catch (Exception $e) {
        error_log("sendMail failed: " . $e->getMessage());
        return false;
    }
}
