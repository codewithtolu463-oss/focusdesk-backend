<?php



$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

 
if ($requestUri === '/focusdesk/backend/index.php/register' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/AuthController.php';
    register();
} elseif ($requestUri === '/focusdesk/backend/index.php/login' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/AuthController.php';
    login();
}
    elseif ($requestUri === '/focusdesk/backend/index.php/workspace/create' && $requestMethod === 'POST') {
   require_once __DIR__ . '/../controllers/WorkspaceController.php';
    createworkspace();
} 
elseif ($requestUri === '/focusdesk/backend/index.php/workspace/user' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/WorkspaceController.php';
    getuserworkspaces();
} 
elseif ($requestUri === '/focusdesk/backend/index.php/task/create' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    createTask();
}
elseif ($requestUri === '/focusdesk/backend/index.php/task/all' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    getTask();
}
elseif ($requestUri === '/focusdesk/backend/index.php/task/update' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    updateTask();
}
elseif ($requestUri === '/focusdesk/backend/index.php/task/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/TaskController.php';
    deleteTask();
}
    elseif ($requestUri === '/focusdesk/backend/index.php/chat/send' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    sendMessage();
}
elseif ($requestUri === '/focusdesk/backend/index.php/chat/messages' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    getMessage();
}
elseif ($requestUri === '/focusdesk/backend/index.php/chat/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/ChatController.php';
    deleteMessage();
}
elseif ($requestUri === '/focusdesk/backend/index.php/timer/start' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    startTimer();
}
elseif ($requestUri === '/focusdesk/backend/index.php/timer/stop' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    stopTimer();
}
elseif ($requestUri === '/focusdesk/backend/index.php/timer/get' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/TimeController.php';
    getlogs();
}
elseif ($requestUri === '/focusdesk/backend/index.php/notification/get' && $requestMethod === 'GET') {
    require_once __DIR__ . '/../controllers/NotificationController.php';
     getnotifcation();
}
elseif ($requestUri === '/focusdesk/backend/index.php/notification/read' && $requestMethod === 'PUT') {
    require_once __DIR__ . '/../controllers/NotificationController.php';
     markread();
}
 elseif ($requestUri === '/focusdesk/backend/index.php/workspace/delete' && $requestMethod === 'DELETE') {
    require_once __DIR__ . '/../controllers/DeleteWorkspaceController.php';
    deleteWorkspace();
}
elseif ($requestUri === '/focusdesk/backend/index.php/workspace/invite' && $requestMethod === 'POST') {
    require_once __DIR__ . '/../controllers/WorkspaceController.php';
    invitemember();
}

else {
    http_response_code(404);
    echo json_encode(['message' => 'Route not found']);
}