<?php require_once "header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Messages - School Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4F46E5;
            --secondary: #6B7280;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --light: #F8FAFC;
            --dark: #1F2937;
            --border: #E2E8F0;
        }
        
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card { 
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .unread-dot { 
            color: var(--danger); 
            font-size: 10px;
            vertical-align: middle;
            margin-right: 8px;
        }
        
        .status-badge { 
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .loading { 
            text-align: center; 
            font-size: 16px; 
            color: var(--secondary); 
            padding: 20px; 
        }
        
        .table-responsive { 
            max-height: 500px; 
            overflow-y: auto;
            border-radius: 8px;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: var(--primary);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.05);
            cursor: pointer;
        }
        
        .action-btn {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
        }
        
        .header-card {
            background: linear-gradient(135deg, var(--primary) 0%, #7C3AED 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .header-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .btn-export {
            background-color: var(--success);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
        }
        
        .btn-export:hover {
            background-color: #0D9F6E;
            color: white;
        }
        
        .modal-content {
            border-radius: 12px;
        }
        
        .message-content {
            background-color: var(--light);
            padding: 12px;
            border-radius: 8px;
            white-space: pre-wrap;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--secondary);
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: var(--border);
        }
        
        .badge-unread {
            background-color: var(--warning);
            color: var(--dark);
        }
        
        .badge-read {
            background-color: var(--success);
            color: white;
        }
        
        .timestamp {
            color: var(--secondary);
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="header-title"><i class="fas fa-paper-plane me-2"></i>Sent Messages</h1>
                <p class="header-subtitle mb-0">View and manage all sent communications</p>
            </div>
            <button class="btn btn-export" onclick="exportMessages()">
                <i class="fas fa-file-export me-2"></i>Export CSV
            </button>
        </div>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover" id="messageTable">
                <thead>
                    <tr>
                        <th>Recipient</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Time Sent</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sentMessages">
                    <tr><td colspan="5" class="loading"><i class="fas fa-spinner fa-spin me-2"></i>Loading messages...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Message View & Reply Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-envelope me-2"></i>Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Recipient:</label>
                    <p id="modalRecipient" class="mb-0"></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Message:</label>
                    <div id="modalMessage" class="message-content"></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Time Sent:</label>
                    <p id="modalTime" class="mb-0 timestamp"></p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Reply:</label>
                    <textarea id="replyMessage" class="form-control mb-3" rows="4" placeholder="Type your reply..."></textarea>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-danger" onclick="deleteMessage()">
                            <i class="fas fa-trash me-2"></i>Delete
                        </button>
                        <button class="btn btn-primary" onclick="sendReply()">
                            <i class="fas fa-paper-plane me-2"></i>Send Reply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this message? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Global variables
let currentMessageId = null;
let currentRecipient = "";
let currentMessage = "";
let currentTime = "";

document.addEventListener("DOMContentLoaded", function () {
    fetchSentMessages();
    setInterval(fetchSentMessages, 10000); // Auto-refresh every 10 seconds
    
    // Set up confirmation modal
    document.getElementById('confirmDelete').addEventListener('click', function() {
        performDelete();
    });
});

function fetchSentMessages() {
    fetch('qq/get_sent_messages.php')
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("sentMessages");
            tableBody.innerHTML = "";

            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h5 class="mt-3">No messages found</h5>
                            <p class="text-muted">You haven't sent any messages yet</p>
                        </td>
                    </tr>`;
                return;
            }

            data.forEach(msg => {
                let unreadDot = msg.status === 'unread' ? `<span class="unread-dot">●</span>` : "";
                let statusBadge = msg.status === 'unread' 
                    ? `<span class="badge badge-unread status-badge"><i class="fas fa-envelope me-1"></i>Unread</span>` 
                    : `<span class="badge badge-read status-badge"><i class="fas fa-envelope-open me-1"></i>Read</span>`;

                let safeMessage = msg.message.length > 100 
                    ? msg.message.substring(0, 100) + '...' 
                    : msg.message;
                safeMessage = safeMessage.replace(/"/g, '&quot;').replace(/'/g, '&#39;');

                let row = `<tr onclick="openMessage(${msg.id}, '${msg.receiver_id}', '${safeMessage}', '${msg.created_at}')">
                    <td class="align-middle">${unreadDot}${msg.receiver_id}</td>
                    <td class="align-middle">${safeMessage}</td>
                    <td class="align-middle">${statusBadge}</td>
                    <td class="align-middle timestamp">${msg.created_at}</td>
                    <td class="align-middle">
                        <button class="btn btn-sm btn-outline-danger action-btn" onclick="confirmDelete(event, ${msg.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => {
            console.error("Error fetching messages:", error);
            document.getElementById("sentMessages").innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading messages. Please try again.
                    </td>
                </tr>`;
        });
}

function openMessage(id, recipient, message, time) {
    currentMessageId = id;
    currentRecipient = recipient;
    currentMessage = message;
    currentTime = time;
    
    document.getElementById("modalRecipient").innerText = recipient;
    document.getElementById("modalMessage").innerText = message;
    document.getElementById("modalTime").innerText = time;
    document.getElementById("replyMessage").value = "";
    
    let modal = new bootstrap.Modal(document.getElementById("messageModal"));
    modal.show();

    // Mark message as read
    if (document.querySelector(`tr[onclick*="openMessage(${id},"] .badge-unread`)) {
        fetch("mark_message_read.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message_id: id })
        }).then(() => fetchSentMessages());
    }
}

function confirmDelete(event, id) {
    event.stopPropagation(); // Prevent row click from triggering
    currentMessageId = id;
    
    let confirmModal = new bootstrap.Modal(document.getElementById("confirmModal"));
    confirmModal.show();
}

function performDelete() {
    if (!currentMessageId) return;
    
    fetch("delete_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message_id: currentMessageId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close both modals
            bootstrap.Modal.getInstance(document.getElementById("confirmModal")).hide();
            bootstrap.Modal.getInstance(document.getElementById("messageModal")).hide();
            
            // Refresh messages
            fetchSentMessages();
            
            // Show success message
            alert("Message deleted successfully!");
        } else {
            alert("Failed to delete message: " + (data.message || "Unknown error"));
        }
    })
    .catch(error => {
        console.error("Error deleting message:", error);
        alert("Error deleting message. Please try again.");
    });
}

function deleteMessage() {
    if (!currentMessageId) return;
    
    // Reuse the confirmation modal
    confirmDelete(event, currentMessageId);
}

function sendReply() {
    let replyText = document.getElementById("replyMessage").value.trim();
    if (replyText === "") {
        alert("Please enter a reply message!");
        return;
    }

    fetch("send_reply.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            recipient: currentRecipient, 
            message: replyText,
            original_message_id: currentMessageId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Reply sent successfully!");
            document.getElementById("replyMessage").value = "";
            
            // Close the modal if desired
            // bootstrap.Modal.getInstance(document.getElementById("messageModal")).hide();
        } else {
            alert("Failed to send reply: " + (data.message || "Unknown error"));
        }
    })
    .catch(error => {
        console.error("Error sending reply:", error);
        alert("Error sending reply. Please try again.");
    });
}

function exportMessages() {
    let table = document.getElementById("sentMessages");
    let rows = table.getElementsByTagName("tr");
    let csvContent = "Recipient,Message,Status,Time Sent\n";

    for (let i = 0; i < rows.length; i++) {
        // Skip empty state row
        if (rows[i].querySelector('.empty-state')) continue;
        
        let cols = rows[i].getElementsByTagName("td");
        if (cols.length < 4) continue;
        
        let rowData = [
            `"${cols[0].innerText.replace(/"/g, '""')}"`,
            `"${cols[1].innerText.replace(/"/g, '""')}"`,
            `"${cols[2].innerText.replace(/"/g, '""')}"`,
            `"${cols[3].innerText.replace(/"/g, '""')}"`
        ];
        csvContent += rowData.join(",") + "\n";
    }

    let hiddenElement = document.createElement("a");
    hiddenElement.href = "data:text/csv;charset=utf-8," + encodeURI(csvContent);
    hiddenElement.target = "_blank";
    hiddenElement.download = `sent_messages_${new Date().toISOString().slice(0,10)}.csv`;
    hiddenElement.click();
}
</script>
</body>
</html>