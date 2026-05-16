<?php
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/register' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/AuthController.php';
    register();
} elseif ($requestUri === '/login' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/AuthController.php';
    login();
} elseif ($requestUri === '/workspace/create' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/WorkspaceController.php';
    createworkspace();
} elseif ($requestUri === '/workspace/user' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/WorkspaceController.php';
    getuserworkspaces();
} elseif ($requestUri === '/workspace/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/DeleteWorkspaceController.php';
    deleteWorkspace();
} elseif ($requestUri === '/workspace/invite' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/WorkspaceController.php';
    invitemember();
} elseif ($requestUri === '/task/create' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    createTask();
} elseif ($requestUri === '/task/all' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    getTask();
} elseif ($requestUri === '/task/update' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    updateTask();
} elseif ($requestUri === '/task/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    deleteTask();
} elseif ($requestUri === '/chat/send' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    sendMessage();
} elseif ($requestUri === '/chat/messages' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    getMessage();
} elseif ($requestUri === '/chat/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    deleteMessage();
} elseif ($requestUri === '/timer/start' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    startTimer();
} elseif ($requestUri === '/timer/stop' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    stopTimer();
} elseif ($requestUri === '/timer/get' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    getlogs();
} elseif ($requestUri === '/notification/get' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/NotificationController.php';
    getnotifcation();
} elseif ($requestUri === '/notification/read' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/NotificationController.php';
    markread();
} elseif ($requestUri === '/task/check' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    checkduetasks();
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Route not found']);
}
?>






