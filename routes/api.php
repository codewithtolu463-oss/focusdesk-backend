<?php



$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

 
if ($requestUri === '/index.php/
/register' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/AuthController.php';
    register();
} elseif ($requestUri === '/index.php/
/login' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/AuthController.php';
    login();
}
    elseif ($requestUri === '/index.php/
/workspace/create' && $requestMethod === 'POST') {
   require_once __DIR__ . '/../controllers/WorkspaceController.php';
    createworkspace();
} 
elseif ($requestUri === '/index.php/
/workspace/user' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/WorkspaceController.php';
    getuserworkspaces();
} 
elseif ($requestUri === '/index.php/
/task/create' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    createTask();
}
elseif ($requestUri === '/index.php/
/task/all' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    getTask();
}
elseif ($requestUri === '/index.php/
/task/update' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    updateTask();
}
elseif ($requestUri === '/index.php/
/task/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    deleteTask();
}
    elseif ($requestUri === '/index.php/
/chat/send' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    sendMessage();
}
elseif ($requestUri === '/index.php/
/chat/messages' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    getMessage();
}
elseif ($requestUri === '/index.php/
/chat/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    deleteMessage();
}
elseif ($requestUri === '/index.php/
/timer/start' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    startTimer();
}
elseif ($requestUri === '/index.php/
/timer/stop' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    stopTimer();
}
elseif ($requestUri === '/index.php/
/timer/get' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    getlogs();
}
elseif ($requestUri === '/index.php/
/notification/get' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/NotificationController.php';
     getnotifcation();
}
elseif ($requestUri === '/index.php/
/notification/read' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/NotificationController.php';
     markread();
}
 elseif ($requestUri === '/index.php/
/workspace/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/DeleteWorkspaceController.php';
    deleteWorkspace();
}
elseif ($requestUri === '/index.php/
/workspace/invite' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/WorkspaceController.php';
    invitemember();
}

else {
    http_response_code(404);
    echo json_encode(['message' => 'Route not found']);
}