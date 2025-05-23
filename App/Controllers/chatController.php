<?php
class chatController {
    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/Conversation.php';
        require_once __DIR__ . '/../Models/User.php';

        $user = new User($_SESSION['username']);
        $user_id = $user->getID();

        // AJAX: Fetch messages for a conversation
        if (isset($_POST['ajax_get_messages'])) {
            $conversation_id = $_POST['conversation_id'] ?? null;
            $messages = [];
            $serviceTitle = '';
            $is_freelancer = false;
            $is_client = false;
            if ($conversation_id) {
                $conversationModel = new Conversation();
                $conversations = $conversationModel->getConversationsByUser($user_id);
                foreach ($conversations as $conv) {
                    if ($conv['conversation_id'] == $conversation_id) {
                        $is_freelancer = ($conv['freelancer_id'] == $user_id);
                        $is_client = ($conv['client_id'] == $user_id);
                        $selectedConv = new Conversation($conv['client_id'], $conv['freelancer_id'], $conv['service_id']);
                        $selectedConv->setId($conversation_id);
                        $messages = $selectedConv->openConversation();
                        foreach ($messages as &$msg) {
                            $msg['sender_name'] = User::getNameById($msg['send_id']) ?? 'Unknown';
                        }
                        $serviceTitle = $conversationModel->getConvoTitle($conversation_id);
                        break;
                    }
                }
            }
            header('Content-Type: application/json');
            echo json_encode([
                'messages' => $messages,
                'user_id' => $user_id,
                'service_title' => $serviceTitle,
                'is_freelancer' => $is_freelancer,
                'is_client' => $is_client
            ]);
            exit;
        }

        // AJAX: Send a message
        if (isset($_POST['ajax_send_message'])) {
            $conversation_id = $_POST['conversation_id'] ?? null;
            $text = trim($_POST['message'] ?? '');
            $success = false;
            if ($conversation_id && $text !== '') {
                require_once __DIR__ . '/../Models/Message.php';
                $msgModel = new Message();
                $msgModel->sendMessage($conversation_id, $user_id, $text);
                $success = true;
            }
            echo json_encode(['success' => $success]);
            exit;
        }

        // Start a new conversation
        if (isset($_POST['start_conversation'])) {
            $freelancer_id = $_POST['freelancer_id'] ?? null;
            $service_id = $_POST['service_id'] ?? null;
            if ($freelancer_id && $service_id) {
                $client_id = $user_id;

                // 1. Create the order (demand) first
                require_once __DIR__ . '/../Models/Demand.php';
                $demandModel = new Demand($service_id, $client_id);
                $order_id = $demandModel->createDemand();

                // 2. Then create the conversation with the order_id
                $conversationModel = new Conversation($client_id, $freelancer_id, $service_id, $order_id);
                $conversationModel->openConversation();

                // Redirect to chat and open this conversation using POST
                echo '
                <form id="redirectForm" action="index.php?page=chat" method="post">
                    <input type="hidden" name="conversation_id" value="' . htmlspecialchars($conversation_id) . '">
                </form>
                <script>document.getElementById("redirectForm").submit();</script>';
                exit;
            }
        }

        // Normal page load: get conversations for sidebar
        $conversationModel = new Conversation();
        $conversations = $conversationModel->getConversationsByUser($user_id);

        $conversationList = [];
        foreach ($conversations as $conv) {
            $other_id = ($conv['client_id'] == $user_id) ? $conv['freelancer_id'] : $conv['client_id'];
            $other_user_name = User::getNameById($other_id);
            $conversationList[] = [
                'conversation_id' => $conv['conversation_id'],
                'other_user_name' => $other_user_name ?? 'Unknown'
            ];
        }

        // Determine which conversation to open
        $selected_conversation_id = $_POST['conversation_id'] ?? null;
        if (!$selected_conversation_id && !empty($conversationList)) {
            // Open the last conversation by default
            $selected_conversation_id = end($conversationList)['conversation_id'];
        }

        // Get the service title for the selected conversation
        $serviceTitle = '';
        if ($selected_conversation_id) {
            $serviceTitle = $conversationModel->getConvoTitle($selected_conversation_id);
        }

        // Only show sidebar and empty chat on initial load
        $messages = [];

        // After determining $selected_conversation_id and $serviceTitle
        $order_id = null;
        $conv = null;
        if ($selected_conversation_id) {
            $conversationModel = new Conversation();
            // Fetch the full conversation data (must include order_id)
            $conv = $conversationModel->getConvoFromId($selected_conversation_id);
            if ($conv && isset($conv['order_id'])) {
                $order_id = $conv['order_id'];
            }
        }

        include __DIR__ . '/../Views/chat_view.php';
    }
}
?>