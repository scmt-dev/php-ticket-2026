<?php

// ============== Encryption Configuration ==============
define('ENCRYPTION_KEY', 'Your-Secret-Key-Change-This-32ch'); // 32 bytes for AES-256
define('ENCRYPTION_CIPHER', 'aes-256-cbc');

/**
 * Encrypt file content using AES-256-CBC
 * @param string $sourcePath Path to the file to encrypt
 * @param string $destPath Path to save the encrypted file
 * @return bool True on success, false on failure
 */
function encryptFile($sourcePath, $destPath) {
    if (!file_exists($sourcePath)) {
        return false;
    }

    // Read the file content
    $plaintext = file_get_contents($sourcePath);
    if ($plaintext === false) {
        return false;
    }

    // Generate a random IV (Initialization Vector)
    $ivLength = openssl_cipher_iv_length(ENCRYPTION_CIPHER);
    $iv = openssl_random_pseudo_bytes($ivLength);

    // Encrypt the data
    $encrypted = openssl_encrypt($plaintext, ENCRYPTION_CIPHER, ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);

    if ($encrypted === false) {
        return false;
    }

    // Prepend the IV to the encrypted data (IV is needed for decryption)
    $encryptedData = $iv . $encrypted;

    // Write the encrypted content to destination
    $result = file_put_contents($destPath, $encryptedData);

    return $result !== false;
}

/**
 * Decrypt file content using AES-256-CBC
 * @param string $filePath Path to the encrypted file
 * @return string|false Decrypted content or false on failure
 */
function decryptFile($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    $encryptedData = file_get_contents($filePath);
    if ($encryptedData === false) {
        return false;
    }

    // Extract the IV from the beginning of the file
    $ivLength = openssl_cipher_iv_length(ENCRYPTION_CIPHER);
    $iv = substr($encryptedData, 0, $ivLength);
    $encrypted = substr($encryptedData, $ivLength);

    // Decrypt the data
    $decrypted = openssl_decrypt($encrypted, ENCRYPTION_CIPHER, ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);

    return $decrypted;
}

// ============== Handle File Download (Decrypt) ==============
if (isset($_GET['download']) && !empty($_GET['file'])) {
    $fileName = basename($_GET['file']); // Prevent directory traversal
    $filePath = 'uploads/' . $fileName;

    if (file_exists($filePath)) {
        $decryptedContent = decryptFile($filePath);
        if ($decryptedContent !== false) {
            // Try to determine original extension from filename
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $originalName = 'downloaded_file.' . $ext;

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $originalName . '"');
            header('Content-Length: ' . strlen($decryptedContent));
            echo $decryptedContent;
            exit;
        } else {
            echo "Error: Failed to decrypt file.";
        }
    } else {
        echo "Error: File not found.";
    }
    exit;
}

// ============== Handle File Upload ==============
$message = '';

if (isset($_POST['submit'])) {
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'docx', 'txt', 'doc');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            // Generate unique filename
            $fileNameNew = uniqid('', true) . "." . $fileActualExt;
            $fileDestination = 'uploads/' . $fileNameNew;

            // First, move the uploaded file to destination
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Now encrypt the file content
                $encryptSuccess = encryptFile($fileDestination, $fileDestination);

                if ($encryptSuccess) {
                    $message = '<div class="success">✅ File uploaded and encrypted successfully!</div>';
                } else {
                    $message = '<div class="error">⚠️ File uploaded but encryption failed.</div>';
                }
            } else {
                $message = '<div class="error">❌ Failed to move uploaded file.</div>';
            }
        } else {
            $message = '<div class="error">❌ There was an error uploading your file.</div>';
        }
    } else {
        $message = '<div class="error">❌ You cannot upload files of this type.</div>';
    }
}

// ============== List Uploaded Files ==============
$uploadedFiles = [];
$uploadDir = 'uploads/';
if (is_dir($uploadDir)) {
    $files = array_diff(scandir($uploadDir, SCANDIR_SORT_DESCENDING), ['.', '..', '.htaccess', 'index.php']);
    foreach ($files as $file) {
        $uploadedFiles[] = $file;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload & Encrypt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 { color: #333; }
        h2 { color: #555; margin-top: 30px; }
        .upload-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .upload-form input[type="file"] {
            margin-bottom: 10px;
        }
        .upload-form button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .upload-form button:hover {
            background: #45a049;
        }
        .success {
            background: #dff0d8;
            color: #3c763d;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #d6e9c6;
        }
        .error {
            background: #f2dede;
            color: #a94442;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border: 1px solid #ebccd1;
        }
        .info {
            background: #d9edf7;
            color: #31708f;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .btn-download {
            background: #2196F3;
            color: white;
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 13px;
        }
        .btn-download:hover {
            background: #1976D2;
        }
        .encrypted-badge {
            background: #FF9800;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        .no-files {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>
</head>
<body>

    <h1>📁 Secure File Upload</h1>
    <p class="info">🔒 Files are automatically encrypted using <strong>AES-256-CBC</strong> after upload.</p>

    <?php echo $message; ?>

    <div class="upload-form">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit" name="submit">⬆️ Upload & Encrypt</button>
        </form>
    </div>

    <h2>📋 Uploaded Files</h2>

    <?php if (count($uploadedFiles) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Filename</th>
                    <th>Size</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uploadedFiles as $index => $file): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($file); ?></td>
                        <td><?php echo round(filesize('uploads/' . $file) / 1024, 2); ?> KB</td>
                        <td><span class="encrypted-badge">🔐 Encrypted</span></td>
                        <td>
                            <a href="?download=1&file=<?php echo urlencode($file); ?>" class="btn-download">⬇️ Download & Decrypt</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-files">No files uploaded yet.</p>
    <?php endif; ?>

</body>
</html>
