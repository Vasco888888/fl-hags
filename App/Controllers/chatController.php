<?php
class chatController {
    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/Conversation.php';
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Proposal.php';
        require_once __DIR__ . '/../Models/Transaction.php';

        $user = new User($_SESSION['username']);
        $user_id = $user->getID();

        // Propose price (freelancer)
        if (isset($_POST['ajax_propose_price'])) {
            $conversation_id = $_POST['conversation_id'];
            $price = floatval($_POST['proposed_price']);
            $proposalModel = new Proposal();
            $proposalModel->createProposal($conversation_id, $price);
            echo json_encode(['success' => true]);
            exit;
        }

        // Accept proposal (client)
        if (isset($_POST['ajax_accept_proposal'])) {
            $proposal_id = $_POST['proposal_id'];
            $conversation_id = $_POST['conversation_id'];
            $proposalModel = new Proposal();
            $proposal = $proposalModel->getPendingProposal($conversation_id);
            if ($proposal && $proposal['proposal_id'] == $proposal_id) {
                $proposalModel->updateStatus($proposal_id, 'accepted');
                // Create transaction (implement your Transaction model accordingly)
                $transactionModel = new Transaction();
                $transactionModel->createTransaction($conversation_id, $proposal['price']);
            }
            echo json_encode(['success' => true]);
            exit;
        }

        // Refuse proposal (client)
        if (isset($_POST['ajax_refuse_proposal'])) {
            $proposal_id = $_POST['proposal_id'];
            $proposalModel = new Proposal();
            $proposalModel->updateStatus($proposal_id, 'refused');
            echo json_encode(['success' => true]);
            exit;
        }

        // AJAX: Fetch messages and proposal for a conversation
        if (isset($_POST['ajax_get_messages'])) {
            $conversation_id = $_POST['conversation_id'] ?? null;
            $messages = [];
            $serviceTitle = '';
            $is_freelancer = false;
            $is_client = false;
            $pending_proposal = null;
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
                        // Get pending proposal
                        $proposalModel = new Proposal();
                        $pending_proposal = $proposalModel->getPendingProposal($conversation_id);
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
                'is_client' => $is_client,
                'pending_proposal' => $pending_proposal
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
                $conversationModel = new Conversation($client_id, $freelancer_id, $service_id);
                $conversationModel->openConversation(); // Will create if not exists
                $conversation_id = $conversationModel->getId();
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

        include __DIR__ . '/../Views/chat_view.php';
    }
}
?>