<?php
// APIキーの設定
$expectedApiKey = 'YOUR_EXPECTED_API_KEY'; // ここに期待するAPIキーを設定してください。

header('Content-Type: application/json');

// APIキーのチェック
if ($_POST['apiKey'] !== $expectedApiKey) {
    http_response_code(403); // 不正なアクセス
    echo json_encode(["error" => "Invalid API Key"]);
    exit;
}

$root = "./"; // 適宜変更してください。
$path = $_POST["path"];

// パスの安全性を確認
if (!preg_match('/^[a-zA-Z0-9\/\._-]+$/', $path)) {
    http_response_code(400); // 不正なリクエスト
    echo json_encode(["error" => "Invalid Path"]);
    exit;
}

$dirname = dirname($path);
if (!file_exists($dirname)) {
    mkdir($dirname, 0755, true);
}

// ファイルが既に存在するかチェック
$fullPath = $root . $path;
if (file_exists($fullPath)) {
    http_response_code(409); // コンフリクト
    echo json_encode(["error" => "File already exists."]);
    exit;
}

// ファイルアップロード処理
if (isset($_FILES['media']) && is_uploaded_file($_FILES['media']['tmp_name'])) {
    if (move_uploaded_file($_FILES['media']['tmp_name'], $fullPath)) {
        echo json_encode(["success" => true, "message" => "File uploaded successfully."]);
    } else {
        http_response_code(500); // サーバーエラー
        echo json_encode(["error" => "Failed to upload file."]);
    }
} else {
    http_response_code(400); // 不正なリクエスト
    echo json_encode(["error" => "No file uploaded."]);
}
?>
