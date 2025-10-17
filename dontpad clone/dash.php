<?php
$filename = '';
$content = '';
$message = '';

// On first form submission (filename input)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filename'])) {
    $filename = basename($_POST['filename']);

    // Only allow .txt extension for safety
    if (!str_ends_with($filename, ".txt")) {
        $filename .= ".txt";
    }

    $filepath = "files/" . $filename;

    // Create file if it doesn't exist
    if (!file_exists("files")) {
        mkdir("files");
    }

    if (!file_exists($filepath)) {
        file_put_contents($filepath, ""); // create empty file
    }

    $content = htmlspecialchars(file_get_contents($filepath));
}

// On second submission (content editing)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filecontent'])) {
    $filename = basename($_POST['filename']);
    $filepath = "files/" . $filename;

    file_put_contents($filepath, $_POST['filecontent']);
    $message = "✅ File '$filename' updated successfully.";
    $content = htmlspecialchars($_POST['filecontent']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $filename ? htmlspecialchars($filename) : 'File Editor'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        .editor {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
            padding: 10px;
            white-space: pre-wrap;
            overflow-y: scroll;
        }
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: linear-gradient(90deg, #1976d2 0%, #42a5f5 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 2rem;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.10);
        }
        .navbar h1 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 1px;
            color: #fff;
        }
        .navbar form {
            display: flex;
            gap: 0.5rem;
            margin: 0;
        }
        .navbar input[type="text"] {
            padding: 6px 10px;
            border-radius: 6px;
            border: none;
            font-size: 1rem;
        }
        .navbar button {
            padding: 6px 18px;
            border-radius: 6px;
            border: none;
            background: #fff;
            color: #1976d2;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .navbar button:hover {
            background: #1976d2;
            color: #fff;
        }
        .select-file-btn {
            display: none;
            background: #fff;
            color: #1976d2;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-left: 1rem;
            transition: background 0.2s, color 0.2s;
        }
        .select-file-btn:hover {
            background: #1976d2;
            color: #fff;
        }
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
            width: 32px;
            height: 32px;
            margin-left: 1rem;
        }
        .hamburger span {
            height: 4px;
            width: 100%;
            background: #fff;
            margin: 4px 0;
            border-radius: 2px;
            transition: 0.3s;
        }
        .fullscreen-form-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(25, 118, 210, 0.97);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .fullscreen-form-overlay.active {
            display: flex;
        }
        .fullscreen-form {
            background: #fff;
            padding: 2rem 2rem 1.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 280px;
            max-width: 90vw;
        }
        .fullscreen-form input[type="text"] {
            margin-bottom: 1rem;
            width: 220px;
        }
        .fullscreen-form button {
            width: 100%;
        }
        .fullscreen-form .close-btn {
            background: none;
            color: #1976d2;
            border: none;
            font-size: 2rem;
            position: absolute;
            top: 24px;
            right: 32px;
            cursor: pointer;
        }

            .navbar form {
                display: none;
            }
            .select-file-btn {
                display: inline-block;
            }
        .container {
            margin-top: 80px;
        }
        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
                padding: 0.5rem 1rem;
            }
            .container {
                margin-top: 110px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1><?php echo $filename ? htmlspecialchars($filename) : 'File Editor'; ?></h1>
        <form method="post" class="navbar-form" style="margin:0;">
            <input type="text" name="filename" placeholder="Enter file name" required value="<?php echo htmlspecialchars($filename); ?>">
            <button type="submit">Open/Create File</button>
        </form>
        <button class="select-file-btn" id="selectFileBtn">Select File</button>
    </div>
    <div class="fullscreen-form-overlay" id="fullscreenFormOverlay">
        <form method="post" class="fullscreen-form">
            <button type="button" class="close-btn" id="closeOverlayBtn" aria-label="Close">&times;</button>
            <input type="text" name="filename" placeholder="Enter file name" required value="<?php echo htmlspecialchars($filename); ?>">
            <button type="submit">Open/Create File</button>
        </form>
    </div>
    <div class="container">
        <?php if ($message) echo "<p>$message</p>"; ?>
        <!-- Step 2: Content editor -->
        <?php if (!empty($filename) && isset($content)): ?>
            <form method="post">
                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($filename); ?>">
                <textarea name="filecontent" class="editor"><?php echo $content; ?></textarea><br>
                <button type="submit">Save Changes</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        const selectFileBtn = document.getElementById('selectFileBtn');
        const overlay = document.getElementById('fullscreenFormOverlay');
        const closeBtn = document.getElementById('closeOverlayBtn');

        if (selectFileBtn) {
            selectFileBtn.addEventListener('click', () => {
                overlay.classList.add('active');
            });
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                overlay.classList.remove('active');
            });
        }
        // Optional: close overlay on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                overlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>
