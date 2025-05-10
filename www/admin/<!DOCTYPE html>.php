<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Fees Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --border: #dee2e6;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--dark);
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 16px 24px;
            border-bottom: none;
        }

        .card-title {
            font-weight: 600;
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--dark);
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .student-info-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            display: none;
        }

        .student-info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .student-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        .student-class {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .payment-summary {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .payment-summary-item {
            flex: 1;
            background-color: var(--light);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .payment-summary-label {
            font-size: 0.8rem;
            color: var(--gray);
            margin-bottom: 5px;
        }

        .payment-summary-value {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .alert {
            border-radius: 8px;
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            padding-left: 40px;
            border-radius: 8px;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .required-field::after {
            content: "*";
            color: var(--danger);
            margin-left: 4px;
        }

        @media (max-width: 768px) {
            .payment-summary {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Record School Fees Payment</h5>
            </div>
            <div class="card-body">
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form id="paymentForm" method="POST" action="">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="search-container">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="studentSearch" class="form-control search-input" placeholder="Search for a student...">
                            </div>
                            <div class="mb-3">
                                <label for="student_id" class="form-label required-field">Select Student</label>
                                <select class="form-select" id="student_id" name="student_id" required>
                                    <option value="">-- Select Student --</option>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?php echo $student['id']; ?>" 
                                            data-first-name="<?php echo htmlspecialchars($student['first_name']); ?>"
                                            data-last-name="<?php echo htmlspecialchars($student['last_name']); ?>"
                                            data-class="<?php echo htmlspecialchars($student['class']); ?>">
                                            <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] . ' - ' . $student['class']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="student-info-card" id="studentInfoCard">
                                <div class="student-info-header">
                                    <div>
                                        <h6 class="student-name" id="studentName"></h6>
                                        <span class="student-class" id="studentClass"></span>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">Active</span>
                                    </div>
                                </div>
                                <div class="payment-summary">
                                    <div class="payment-summary-item">
                                        <div class="payment-summary-label">Total Paid</div>
                                        <div class="payment-summary-value" id="totalPaid">GHS 0.00</div>
                                    </div>
                                    <div class="payment-summary-item">
                                        <div class="payment-summary-label">Balance</div>
                                        <div class="payment-summary-value" id="balance">GHS 0.00</div>
                                    </div>
                                    <div class="payment-summary-item">
                                        <div class="payment-summary-label">Last Payment</div>
                                        <div class="payment-summary-value" id="lastPayment">Never</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fee_type" class="form-label required-field">Fee Type</label>
                            <select class="form-select" id="fee_type" name="fee_type" required>
                                <option value="">-- Select Fee Type --</option>
                                <?php foreach ($fee_types as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                                <?php endforeach; ?>
                                <option value="other">Other (Specify in Notes)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label required-field">Amount (GHS)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="payment_date" class="form-label required-field">Payment Date</label>
                            <input type="text" class="form-control datepicker" id="payment_date" name="payment_date" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="payment_method" class="form-label required-field">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">-- Select Method --</option>
                                <option value="Cash">Cash</option>
                                <option value="Mobile Money">Mobile Money</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Check">Check</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="receipt_number" class="form-label">Receipt Number</label>
                            <input type="text" class="form-control" id="receipt_number" name="receipt_number">
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                        
                        <div class="col-12 d-flex justify-content-end">
                            <button type="reset" class="btn btn-outline-secondary me-3">Clear Form</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Record Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Initialize datepicker
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
            defaultDate: "today",
            maxDate: "today"
        });

        // Student search functionality
        $('#studentSearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#student_id option').each(function() {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm));
            });
        });

        // Show student info when selected
        $('#student_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const firstName = selectedOption.data('first-name');
            const lastName = selectedOption.data('last-name');
            const studentClass = selectedOption.data('class');
            
            if (selectedOption.val()) {
                $('#studentName').text(firstName + ' ' + lastName);
                $('#studentClass').text('Class: ' + studentClass);
                $('#studentInfoCard').fadeIn();
                
                // Here you would fetch payment history via AJAX
                // For now, we'll simulate it
                simulatePaymentData(selectedOption.val());
            } else {
                $('#studentInfoCard').fadeOut();
            }
        });

        // Simulate fetching payment data (replace with actual AJAX call)
        function simulatePaymentData(studentId) {
            // This is just for demonstration - replace with actual API call
            setTimeout(() => {
                const randomPaid = (Math.random() * 1000).toFixed(2);
                const randomBalance = (Math.random() * 500).toFixed(2);
                const lastPayment = new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000);
                
                $('#totalPaid').text('GHS ' + randomPaid);
                $('#balance').text('GHS ' + randomBalance);
                $('#lastPayment').text(lastPayment.toLocaleDateString());
            }, 300);
        }

        // Form validation
        $('#paymentForm').submit(function(e) {
            let valid = true;
            
            // Check required fields
            $('.required-field').each(function() {
                const fieldId = $(this).attr('for');
                if (!$('#' + fieldId).val()) {
                    valid = false;
                    $('#' + fieldId).addClass('is-invalid');
                } else {
                    $('#' + fieldId).removeClass('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</body>
</html>