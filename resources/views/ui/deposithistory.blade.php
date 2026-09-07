<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Cyera AI - Dashboard</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <!-- DataTables CSS -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <link href="{{asset('ctassets/css/scriptui.css')}}" rel="stylesheet">
      <style>
         .deposit-container {
         width: 100%;
         max-width: 600px;
         margin: 0 auto;
         }
         .deposit-header {
         text-align: center;
         margin-bottom: 30px;
         }
         .deposit-title {
         font-family: 'Segoe UI', sans-serif;
         font-size: 2.2rem;
         font-weight: 900;
         margin-bottom: 10px;
         background: linear-gradient(90deg, var(--primary-blue), var(--accent-green));
         -webkit-background-clip: text;
         background-clip: text;
         color: transparent;
         letter-spacing: 1px;
         text-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
         }
         .deposit-subtitle {
         color: var(--text-muted);
         font-size: 1rem;
         }
         .deposit-card {
         background: var(--card-bg);
         backdrop-filter: blur(10px);
         border-radius: 16px;
         border: 1px solid var(--glass-border);
         box-shadow: 
         0 20px 40px rgba(0, 0, 0, 0.6),
         0 0 0 1px rgba(0, 212, 255, 0.1),
         inset 0 1px 0 rgba(255, 255, 255, 0.05);
         overflow: hidden;
         position: relative;
         }
         .deposit-content {
         padding: 30px;
         }
         .warning-banner {
         background: linear-gradient(135deg, rgba(255, 167, 38, 0.1), rgba(255, 167, 38, 0.05));
         border: 1px solid rgba(255, 167, 38, 0.3);
         border-radius: 10px;
         padding: 20px;
         margin-bottom: 25px;
         display: flex;
         align-items: flex-start;
         gap: 15px;
         }
         .warning-icon {
         color: var(--warning);
         font-size: 1.5rem;
         flex-shrink: 0;
         }
         .warning-text {
         color: var(--text-color);
         font-size: 0.95rem;
         line-height: 1.6;
         }
         .warning-text strong {
         color: var(--warning);
         }
         .form-group {
         margin-bottom: 25px;
         }
         .form-label {
         display: block;
         margin-bottom: 10px;
         color: var(--text-color);
         font-weight: 500;
         font-size: 0.95rem;
         display: flex;
         align-items: center;
         gap: 8px;
         }
         .form-label span {
         color: var(--accent-green);
         margin-left: 3px;
         }
         .form-label i {
         color: var(--primary-blue);
         font-size: 0.95rem;
         }
         .input-group {
         position: relative;
         display: flex;
         align-items: center;
         }
         .input-icon {
         position: absolute;
         left: 16px;
         color: var(--primary-blue);
         font-size: 1.1rem;
         z-index: 2;
         }
         .form-input {
         width: 100%;
         padding: 16px 16px 16px 50px;
         background: rgba(0, 0, 0, 0.4);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 10px;
         color: var(--text-color);
         font-size: 1rem;
         transition: all 0.3s ease;
         letter-spacing: 0.5px;
         }
         .form-input:focus {
         outline: none;
         border-color: var(--accent-green);
         background: rgba(0, 0, 0, 0.5);
         box-shadow: 0 0 0 2px rgba(0, 255, 157, 0.2);
         }
         .form-input::placeholder {
         color: rgba(255, 255, 255, 0.3);
         }
         .currency-select {
         width: 100%;
         padding: 16px 16px 16px 50px;
         background: rgba(0, 0, 0, 0.4);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 10px;
         color: var(--text-color);
         font-size: 1rem;
         transition: all 0.3s ease;
         appearance: none;
         background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2300D4FF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
         background-repeat: no-repeat;
         background-position: right 16px center;
         background-size: 16px;
         }
         .currency-select:focus {
         outline: none;
         border-color: var(--accent-green);
         background: rgba(0, 0, 0, 0.5);
         box-shadow: 0 0 0 2px rgba(0, 255, 157, 0.2);
         }
         .payment-info {
         background: rgba(0, 102, 255, 0.05);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 10px;
         padding: 25px;
         margin-top: 20px;
         }
         .payment-info-title {
         display: flex;
         align-items: center;
         gap: 10px;
         margin-bottom: 15px;
         color: var(--primary-blue);
         font-size: 1.1rem;
         }
         .payment-info-title i {
         font-size: 1.2rem;
         }
         .payment-details {
         color: var(--text-muted);
         font-size: 0.95rem;
         line-height: 1.6;
         }
         .payment-address {
         background: rgba(0, 0, 0, 0.3);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 8px;
         padding: 12px;
         margin: 15px 0;
         font-size: 0.9rem;
         word-break: break-all;
         display: flex;
         justify-content: space-between;
         align-items: center;
         }
         .payment-address span {
         flex: 1;
         margin-right: 10px;
         }
         .qr-code-container {
         text-align: center;
         margin: 20px 0;
         }
         .qr-code-placeholder {
         width: 200px;
         height: 200px;
         background: linear-gradient(45deg, rgba(0, 212, 255, 0.1), rgba(0, 102, 255, 0.1));
         margin: 0 auto 15px;
         border-radius: 10px;
         display: flex;
         align-items: center;
         justify-content: center;
         color: var(--primary-blue);
         font-size: 3rem;
         border: 2px dashed var(--primary-blue);
         }
         .transaction-status {
         padding: 25px;
         text-align: center;
         }
         .status-icon {
         width: 80px;
         height: 80px;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin: 0 auto 20px;
         font-size: 2.5rem;
         }
         .status-icon.success {
         background: rgba(0, 255, 157, 0.1);
         color: var(--success);
         border: 2px solid rgba(0, 255, 157, 0.3);
         }
         .status-icon.pending {
         background: rgba(255, 167, 38, 0.1);
         color: var(--warning);
         border: 2px solid rgba(255, 167, 38, 0.3);
         }
         .status-title {
         font-size: 1.5rem;
         margin-bottom: 10px;
         color: var(--text-color);
         }
         .status-text {
         color: var(--text-muted);
         font-size: 0.95rem;
         line-height: 1.6;
         max-width: 400px;
         margin: 0 auto;
         }
         .transaction-id {
         background: rgba(0, 0, 0, 0.3);
         border: 1px solid rgba(0, 212, 255, 0.2);
         border-radius: 8px;
         padding: 12px;
         margin: 20px auto;
         font-size: 0.9rem;
         display: inline-block;
         }
         .form-actions {
         padding: 30px;
         background: rgba(0, 0, 0, 0.2);
         border-top: 1px solid rgba(0, 212, 255, 0.1);
         display: flex;
         justify-content: space-between;
         gap: 20px;
         }
         .btn {
         padding: 16px 32px;
         border: none;
         border-radius: 10px;
         font-size: 1.1rem;
         font-weight: 600;
         cursor: pointer;
         transition: all 0.3s ease;
         display: flex;
         align-items: center;
         justify-content: center;
         gap: 12px;
         letter-spacing: 0.5px;
         min-width: 160px;
         }
         .btn-primary {
         background: linear-gradient(135deg, var(--primary-blue), var(--accent-green));
         color: var(--dark-bg);
         box-shadow: 0 8px 20px rgba(0, 102, 255, 0.3);
         }
         .btn-primary:hover {
         transform: translateY(-2px);
         box-shadow: 0 12px 25px rgba(0, 102, 255, 0.5);
         }
         .btn-primary:disabled {
         opacity: 0.6;
         cursor: not-allowed;
         transform: none;
         }
         .btn-secondary {
         background: rgba(255, 255, 255, 0.05);
         color: var(--text-color);
         border: 1px solid rgba(0, 212, 255, 0.2);
         }
         .btn-secondary:hover {
         background: rgba(255, 255, 255, 0.1);
         border-color: var(--primary-blue);
         }
         .btn-icon {
         font-size: 1.2rem;
         }
         .message {
         padding: 16px;
         border-radius: 10px;
         margin-bottom: 25px;
         text-align: center;
         font-weight: 500;
         display: none;
         animation: messageSlide 0.4s ease forwards;
         border: 1px solid transparent;
         }
         @keyframes messageSlide {
         from { opacity: 0; transform: translateY(-10px); }
         to { opacity: 1; transform: translateY(0); }
         }
         .message.success {
         background: rgba(0, 255, 157, 0.1);
         border-color: rgba(0, 255, 157, 0.2);
         color: var(--success);
         }
         .message.error {
         background: rgba(255, 77, 125, 0.1);
         border-color: rgba(255, 77, 125, 0.2);
         color: var(--danger);
         }
         .message.info {
         background: rgba(0, 212, 255, 0.1);
         border-color: rgba(0, 212, 255, 0.2);
         color: var(--info);
         }
         /* Animations */
         @keyframes pulse {
         0% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.7); }
         70% { box-shadow: 0 0 0 12px rgba(0, 212, 255, 0); }
         100% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0); }
         }
         .pulse-animation {
         animation: pulse 2s infinite;
         }
         /* Web3 Glow Effects */
         .glow-effect {
         position: relative;
         overflow: hidden;
         }
         .glow-effect::before {
         content: '';
         position: absolute;
         top: -2px;
         left: -2px;
         right: -2px;
         bottom: -2px;
         background: linear-gradient(45deg, var(--primary-blue), var(--accent-purple), var(--secondary-blue), var(--accent-green));
         z-index: -1;
         border-radius: inherit;
         opacity: 0;
         transition: opacity 0.4s ease;
         filter: blur(10px);
         }
         .glow-effect:hover::before {
         opacity: 0.6;
         }
         /* Floating Crypto Icons Animation */
         .crypto-icons {
         position: fixed;
         width: 100%;
         height: 100%;
         pointer-events: none;
         z-index: -1;
         overflow: hidden;
         }
         .crypto-icon {
         position: absolute;
         font-size: 1.5rem;
         opacity: 0.1;
         animation: floatIcon 20s infinite linear;
         color: var(--primary-blue);
         }
         @keyframes floatIcon {
         0% { transform: translateY(100vh) translateX(0) rotate(0deg); }
         100% { transform: translateY(-100px) translateX(calc(100vw - 100px)) rotate(360deg); }
         }
         /* Neon Border Animation */
         @keyframes neonGlow {
         0% { background-position: 0 0; }
         50% { background-position: 400% 0; }
         100% { background-position: 0 0; }
         }
         .neon-border:hover::after {
         opacity: 0.5;
         }
         /* Footer */
         .footer {
         text-align: center;
         padding: 20px;
         color: var(--text-muted);
         font-size: 0.85rem;
         border-top: 1px solid rgba(0, 212, 255, 0.2);
         margin-top: 30px;
         background: rgba(5, 10, 20, 0.5);
         border-radius: 10px;
         }
         
         /* ============================ */
         /* DEPOSIT HISTORY TABLE STYLES */
         /* ============================ */
         
         .deposit-history-container {
            margin-top: 40px;
            margin-bottom: 40px;
            width: 100%;
            overflow-x: auto;
         }
         
         .history-title {
            font-family: 'Segoe UI', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(90deg, var(--primary-blue), var(--accent-green));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
         }
         
         .deposit-history-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            box-shadow: 
               0 20px 40px rgba(0, 0, 0, 0.6),
               0 0 0 1px rgba(0, 212, 255, 0.1),
               inset 0 1px 0 rgba(255, 255, 255, 0.05);
            overflow: hidden;
            position: relative;
            padding: 20px;
            width: 100%;
         }
         
         /* DataTables Custom Styling */
         .dataTables_wrapper {
            color: var(--text-color);
            font-family: 'Segoe UI', sans-serif;
            width: 100%;
         }
         
         .dataTables_length,
         .dataTables_filter {
            margin-bottom: 15px;
            display: inline-block;
         }
         
         .dataTables_length label,
         .dataTables_filter label {
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.9rem;
         }
         
         .dataTables_wrapper .dataTables_length select {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 8px;
            color: var(--text-color);
            padding: 6px 10px;
            margin: 0 5px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
         }
         
         .dataTables_wrapper .dataTables_filter input {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 8px;
            color: var(--text-color);
            padding: 6px 12px;
            margin-left: 10px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            width: 200px;
         }
         
         .dataTables_wrapper .dataTables_length select:focus,
         .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--accent-green);
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 0 2px rgba(0, 255, 157, 0.2);
         }
         
         /* Pagination - SMALL BOXES IN ONE ROW */
         .dataTables_wrapper .dataTables_paginate {
            margin-top: 15px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
            padding: 10px 0;
         }
         
         .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 6px;
            color: var(--text-color) !important;
            margin: 0;
            padding: 5px 10px !important;
            font-size: 0.8rem;
            min-width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            line-height: 1;
            text-decoration: none !important;
         }
         
         .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: rgba(0, 212, 255, 0.15) !important;
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 212, 255, 0.2);
         }
         
         .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-green)) !important;
            color: var(--dark-bg) !important;
            border: none;
            font-weight: 600;
         }
         
         .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
         .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            opacity: 0.3;
            cursor: not-allowed;
            background: rgba(0, 0, 0, 0.2) !important;
            transform: none;
            box-shadow: none;
         }
         
         .dataTables_wrapper .dataTables_info {
            color: var(--text-muted);
            padding: 10px 0;
            font-size: 0.85rem;
            text-align: center;
            width: 100%;
         }
         
         /* Table styling */
         table.dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 15px 0;
            border-radius: 10px;
            overflow: hidden;
         }
         
         table.dataTable thead th {
            background: rgba(0, 0, 0, 0.5);
            color: var(--primary-blue);
            font-weight: 600;
            border-bottom: 2px solid rgba(0, 212, 255, 0.3);
            padding: 12px 10px;
            white-space: nowrap;
            font-size: 0.9rem;
            text-align: left;
         }
         
         table.dataTable tbody td {
            padding: 10px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
            vertical-align: middle;
            color: var(--text-color);
            font-size: 0.9rem;
         }
         
         table.dataTable tbody tr {
            transition: all 0.3s ease;
            background: transparent;
         }
         
         table.dataTable tbody tr:hover {
            background: rgba(0, 212, 255, 0.08);
         }
         
         table.dataTable tbody tr:nth-child(even) {
            background: rgba(0, 0, 0, 0.08);
         }
         
         table.dataTable tbody tr:nth-child(even):hover {
            background: rgba(0, 212, 255, 0.12);
         }
         
         /* Status badges */
         .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            min-width: 70px;
            transition: all 0.3s ease;
         }
         
         .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
         }
         
         .status-pending {
            background: rgba(255, 167, 38, 0.15);
            color: var(--warning);
            border: 1px solid rgba(255, 167, 38, 0.4);
         }
         
         .status-completed {
            background: rgba(0, 255, 157, 0.15);
            color: var(--success);
            border: 1px solid rgba(0, 255, 157, 0.4);
         }
         
         .status-failed {
            background: rgba(255, 77, 125, 0.15);
            color: var(--danger);
            border: 1px solid rgba(255, 77, 125, 0.4);
         }
         
         .status-approved {
            background: rgba(0, 191, 255, 0.15);
            color: var(--info);
            border: 1px solid rgba(0, 191, 255, 0.4);
         }
         
         /* Action buttons */
         .action-btn {
            padding: 4px 8px;
            border-radius: 5px;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.2);
            color: var(--primary-blue);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-width: 60px;
         }
         
         .action-btn:hover {
            background: rgba(0, 212, 255, 0.2);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 212, 255, 0.1);
         }
         
         /* Table controls container */
         .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
         }
         
         /* Refresh button */
         .refresh-btn {
            padding: 6px 12px;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 6px;
            color: var(--primary-blue);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
         }
         
         .refresh-btn:hover {
            background: rgba(0, 212, 255, 0.2);
            transform: translateY(-2px);
         }
         
         /* Responsive adjustments */
         @media (max-width: 1200px) {
            .deposit-history-card {
               padding: 15px;
            }
            
            table.dataTable thead th,
            table.dataTable tbody td {
               padding: 10px 8px;
               font-size: 0.85rem;
            }
         }
         
         @media (max-width: 992px) {
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
               display: block;
               margin-bottom: 10px;
               width: 100%;
            }
            
            .dataTables_wrapper .dataTables_filter input {
               width: 100%;
               margin-left: 0;
               margin-top: 5px;
            }
            
            .table-controls {
               flex-direction: column;
               align-items: flex-start;
            }
         }
         
         @media (max-width: 768px) {
            .deposit-history-card {
               padding: 10px;
            }
            
            .dataTables_wrapper .dataTables_paginate {
               flex-wrap: wrap;
               gap: 3px;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button {
               padding: 4px 8px !important;
               font-size: 0.75rem;
               min-width: 26px;
               height: 26px;
            }
            
            .history-title {
               font-size: 1.5rem;
               text-align: center;
            }
            
            .status-badge {
               min-width: 60px;
               font-size: 0.7rem;
               padding: 3px 6px;
            }
            
            .action-btn {
               min-width: 50px;
               font-size: 0.75rem;
               padding: 3px 6px;
            }
         }
         
         @media (max-width: 576px) {
            /* Make table horizontally scrollable on small screens */
            .deposit-history-container {
               overflow-x: auto;
            }
            
            .deposit-history-card {
               min-width: 500px; /* Minimum width for scrolling */
            }
            
            table.dataTable {
               min-width: 500px;
            }
            
            .dataTables_wrapper .dataTables_info {
               font-size: 0.8rem;
               text-align: center;
            }
            
            .dataTables_wrapper .dataTables_length select {
               width: 80px;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button {
               padding: 3px 6px !important;
               min-width: 24px;
               height: 24px;
               font-size: 0.7rem;
            }
         }
         
         @media (max-width: 400px) {
            .deposit-history-card {
               padding: 8px;
               min-width: 450px;
            }
            
            table.dataTable {
               min-width: 450px;
            }
            
            .dataTables_wrapper .dataTables_paginate {
               gap: 2px;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button {
               padding: 2px 5px !important;
               min-width: 22px;
               height: 22px;
               font-size: 0.65rem;
            }
         }
         
         /* Loading animation */
         .table-loading {
            position: relative;
            min-height: 200px;
         }
         
         .table-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 3px solid rgba(0, 212, 255, 0.2);
            border-top: 3px solid var(--primary-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
         }
         
         @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
         }
      </style>
   </head>
   <body>
      <!-- Premium Animated Background -->
      <div class="bg-animation" id="particles"></div>
      <div class="grid-lines"></div>
      <div class="energy-wave"></div>
      <div class="crypto-icons" id="cryptoIcons"></div>
      <div class="container">
         <!-- Sidebar -->
         @include('ui.sidebaruser')
         <!-- Sidebar -->
         <!-- Main Content -->
         <div class="main-content">
            @include('ui.topbaruser')
            <!-- Enter code here -->
            <div class="deposit-history-container">
               <h2 class="history-title">DEPOSIT HISTORY</h2>
               <div class="deposit-history-card">
                  <div class="table-controls">
                     <div class="dataTables_length">
                        <label>Show 
                           <select name="depositHistoryTable_length" aria-controls="depositHistoryTable" class="">
                              <option value="5">5</option>
                              <option value="10" selected>10</option>
                              <option value="25">25</option>
                              <option value="50">50</option>
                           </select> entries
                        </label>
                     </div>
                     <button class="refresh-btn" onclick="refreshTable()">
                        <i class="fas fa-sync-alt"></i> Refresh
                     </button>
                     <div class="dataTables_filter">
                        <label>Search:
                           <input type="search" class="" placeholder="Search transactions..." aria-controls="depositHistoryTable">
                        </label>
                     </div>
                  </div>
                  
                  <table id="depositHistoryTable" class="display nowrap" style="width:100%">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Transaction ID</th>
                           <th>Amount</th>
                           <th>Currency</th>
                           <th>Payment Method</th>
                           <th>Date & Time</th>
                           <th>Status</th>
                           <th>Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>1</td>
                           <td>CAI8082650-001</td>
                           <td>$500.00</td>
                           <td>USDT</td>
                           <td>BSC Network</td>
                           <td>11-12-2025 14:30</td>
                           <td><span class="status-badge status-pending">Pending</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-001')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>2</td>
                           <td>CAI8082650-002</td>
                           <td>$100.00</td>
                           <td>USDT</td>
                           <td>BSC Network</td>
                           <td>28-11-2025 10:15</td>
                           <td><span class="status-badge status-pending">Pending</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-002')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>3</td>
                           <td>CAI8082650-003</td>
                           <td>$100.00</td>
                           <td>USDT</td>
                           <td>BSC Network</td>
                           <td>28-11-2025 09:45</td>
                           <td><span class="status-badge status-failed">Failed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-003')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>4</td>
                           <td>CAI8082650-004</td>
                           <td>$750.00</td>
                           <td>BTC</td>
                           <td>Bitcoin Network</td>
                           <td>25-11-2025 16:20</td>
                           <td><span class="status-badge status-completed">Completed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-004')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>5</td>
                           <td>CAI8082650-005</td>
                           <td>$250.00</td>
                           <td>ETH</td>
                           <td>ERC20 Network</td>
                           <td>20-11-2025 11:10</td>
                           <td><span class="status-badge status-completed">Completed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-005')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>6</td>
                           <td>CAI8082650-006</td>
                           <td>$1000.00</td>
                           <td>USDT</td>
                           <td>TRC20 Network</td>
                           <td>15-11-2025 13:45</td>
                           <td><span class="status-badge status-approved">Approved</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-006')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>7</td>
                           <td>CAI8082650-007</td>
                           <td>$50.00</td>
                           <td>BNB</td>
                           <td>BEP20 Network</td>
                           <td>10-11-2025 08:30</td>
                           <td><span class="status-badge status-failed">Failed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-007')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>8</td>
                           <td>CAI8082650-008</td>
                           <td>$300.00</td>
                           <td>USDT</td>
                           <td>BSC Network</td>
                           <td>05-11-2025 17:55</td>
                           <td><span class="status-badge status-completed">Completed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-008')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>9</td>
                           <td>CAI8082650-009</td>
                           <td>$150.00</td>
                           <td>ETH</td>
                           <td>ERC20 Network</td>
                           <td>01-11-2025 12:25</td>
                           <td><span class="status-badge status-pending">Pending</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-009')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>10</td>
                           <td>CAI8082650-010</td>
                           <td>$2000.00</td>
                           <td>BTC</td>
                           <td>Bitcoin Network</td>
                           <td>28-10-2025 09:15</td>
                           <td><span class="status-badge status-completed">Completed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-010')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>11</td>
                           <td>CAI8082650-011</td>
                           <td>$450.00</td>
                           <td>USDT</td>
                           <td>TRC20 Network</td>
                           <td>25-10-2025 14:20</td>
                           <td><span class="status-badge status-completed">Completed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-011')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>12</td>
                           <td>CAI8082650-012</td>
                           <td>$120.00</td>
                           <td>ETH</td>
                           <td>ERC20 Network</td>
                           <td>20-10-2025 11:45</td>
                           <td><span class="status-badge status-pending">Pending</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-012')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>13</td>
                           <td>CAI8082650-013</td>
                           <td>$800.00</td>
                           <td>BTC</td>
                           <td>Bitcoin Network</td>
                           <td>15-10-2025 09:30</td>
                           <td><span class="status-badge status-failed">Failed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-013')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>14</td>
                           <td>CAI8082650-014</td>
                           <td>$350.00</td>
                           <td>USDT</td>
                           <td>BSC Network</td>
                           <td>10-10-2025 16:15</td>
                           <td><span class="status-badge status-completed">Completed</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-014')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                        <tr>
                           <td>15</td>
                           <td>CAI8082650-015</td>
                           <td>$600.00</td>
                           <td>BNB</td>
                           <td>BEP20 Network</td>
                           <td>05-10-2025 13:40</td>
                           <td><span class="status-badge status-approved">Approved</span></td>
                           <td><button class="action-btn" onclick="viewTransaction('CAI8082650-015')"><i class="fas fa-eye"></i> View</button></td>
                        </tr>
                     </tbody>
                  </table>
                  
                  <div class="dataTables_info" id="depositHistoryTable_info" role="status" aria-live="polite">Showing 1 to 10 of 15 entries</div>
                  <div class="dataTables_paginate paging_simple_numbers" id="depositHistoryTable_paginate">
                     <a class="paginate_button previous disabled" aria-controls="depositHistoryTable" data-dt-idx="0" tabindex="-1" id="depositHistoryTable_previous">Previous</a>
                     <span>
                        <a class="paginate_button current" aria-controls="depositHistoryTable" data-dt-idx="1" tabindex="0">1</a>
                        <a class="paginate_button" aria-controls="depositHistoryTable" data-dt-idx="2" tabindex="0">2</a>
                     </span>
                     <a class="paginate_button next" aria-controls="depositHistoryTable" data-dt-idx="3" tabindex="0" id="depositHistoryTable_next">Next</a>
                  </div>
               </div>
            </div>
         
            <div class="footer">
               <p>© 2026 Cyera AI. All rights reserved.</p>
            </div>
         </div>
      </div>
      <script src="{{asset('ctassets/js/scriptui.js')}}"></script>
      <!-- DataTables JS -->
      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
      <script>
         // Initialize DataTable with custom pagination styling
         $(document).ready(function() {
            var table = $('#depositHistoryTable').DataTable({
               responsive: true,
               pageLength: 10,
               lengthMenu: [[5, 10, 15, 25, 50], [5, 10, 15, 25, 50]],
               order: [[0, 'desc']],
               language: {
                  search: "",
                  searchPlaceholder: "Search transactions...",
                  lengthMenu: "Show _MENU_ entries",
                  info: "Showing _START_ to _END_ of _TOTAL_ entries",
                  infoEmpty: "Showing 0 to 0 of 0 entries",
                  infoFiltered: "(filtered from _MAX_ total entries)",
                  zeroRecords: "No matching records found",
                  paginate: {
                     first: "«",
                     last: "»",
                     next: "›",
                     previous: "‹"
                  }
               },
               initComplete: function() {
                  // Custom styling for pagination
                  $('.dataTables_paginate').addClass('custom-pagination');
                  
                  // Update pagination text
                  $('.paginate_button').each(function() {
                     var text = $(this).text();
                     if (text === '‹') $(this).text('‹');
                     if (text === '›') $(this).text('›');
                     if (text === '«') $(this).text('«');
                     if (text === '»') $(this).text('»');
                  });
                  
                  // Animation for table rows
                  $('#depositHistoryTable tbody tr').each(function(i) {
                     $(this).css('opacity', '0');
                     $(this).css('transform', 'translateX(-10px)');
                     
                     setTimeout(() => {
                        $(this).animate({
                           opacity: 1,
                           transform: 'translateX(0)'
                        }, 300);
                     }, i * 50);
                  });
               }
            });
            
            // Add hover effect
            $('#depositHistoryTable tbody').on('mouseenter', 'tr', function() {
               $(this).addClass('hover-effect');
               $(this).css('transform', 'translateY(-2px)');
               $(this).css('box-shadow', '0 4px 12px rgba(0, 212, 255, 0.1)');
            }).on('mouseleave', 'tr', function() {
               $(this).removeClass('hover-effect');
               $(this).css('transform', 'translateY(0)');
               $(this).css('box-shadow', 'none');
            });
         });
         
         // Function to view transaction details
         function viewTransaction(transactionId) {
            // In a real application, you would open a modal here
            alert(`Viewing transaction: ${transactionId}\n\nThis would open a modal with complete details in a real application.`);
         }
         
         // Function to refresh table data
         function refreshTable() {
            const table = $('#depositHistoryTable').DataTable();
            const tableCard = $('.deposit-history-card');
            
            // Show loading state
            tableCard.addClass('table-loading');
            
            // Simulate API call delay
            setTimeout(() => {
               table.ajax.reload(null, false);
               tableCard.removeClass('table-loading');
               
               // Show success message
               showNotification('Table data refreshed successfully!', 'success');
            }, 1000);
         }
         
         // Function to show notification
         function showNotification(message, type) {
            // Remove existing notification
            $('.notification').remove();
            
            // Create notification element
            const notification = $('<div class="notification"></div>')
               .text(message)
               .addClass('notification-' + type)
               .css({
                  position: 'fixed',
                  top: '20px',
                  right: '20px',
                  padding: '12px 20px',
                  borderRadius: '8px',
                  color: 'white',
                  zIndex: '9999',
                  animation: 'slideIn 0.3s ease',
                  boxShadow: '0 4px 12px rgba(0,0,0,0.3)'
               });
            
            // Set background color based on type
            if (type === 'success') {
               notification.css('background', 'linear-gradient(135deg, var(--accent-green), var(--primary-blue))');
            } else if (type === 'error') {
               notification.css('background', 'linear-gradient(135deg, var(--danger), #ff4757)');
            } else {
               notification.css('background', 'linear-gradient(135deg, var(--primary-blue), #3498db)');
            }
            
            // Add to page
            $('body').append(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
               notification.animate({
                  opacity: 0,
                  right: '-100px'
               }, 300, function() {
                  $(this).remove();
               });
            }, 3000);
            
            // Add animation
            $('<style>')
               .text(`
                  @keyframes slideIn {
                     from { opacity: 0; transform: translateX(100px); }
                     to { opacity: 1; transform: translateX(0); }
                  }
               `)
               .appendTo('head');
         }
         
         // Existing functions from your original code
         function generateWalletAddress() {
           const chars = '0123456789abcdef';
           let address = '0x';
           for (let i = 0; i < 40; i++) {
               address += chars[Math.floor(Math.random() * chars.length)];
           }
           return address;
         }
         
         function generateTransactionId() {
           return Math.random().toString(36).substring(2, 10).toUpperCase();
         }
         
         function formatAmount(amount, currency) {
           const currencyNames = {
               'USDT_BEP20': 'USDT',
               'USDT_ERC20': 'USDT',
               'USDT_TRC20': 'USDT',
               'BTC': 'BTC',
               'ETH': 'ETH',
               'BNB': 'BNB'
           };
           return `${amount} ${currencyNames[currency]}`;
         }
         
         function formatNetwork(currency) {
           const networks = {
               'USDT_BEP20': 'BEP20',
               'USDT_ERC20': 'ERC20',
               'USDT_TRC20': 'TRC20',
               'BTC': 'Bitcoin',
               'ETH': 'Ethereum',
               'BNB': 'BEP20'
           };
           return `${currency.split('_')[0] || currency} ${networks[currency]}`;
         }
         
         function showMessage(text, type) {
           const container = document.getElementById('messageContainer');
           
           // Remove existing message
           const existingMsg = container.querySelector('.message');
           if (existingMsg) existingMsg.remove();
           
           // Create new message
           const message = document.createElement('div');
           message.className = `message ${type}`;
           message.textContent = `${text}`;
           message.style.display = 'block';
           
           container.appendChild(message);
           
           // Auto remove after 5 seconds
           setTimeout(() => {
               message.style.opacity = '0';
               message.style.transform = 'translateY(-10px)';
               setTimeout(() => message.remove(), 400);
           }, 5000);
         }
         
         // Initialize everything when page loads
         window.addEventListener('DOMContentLoaded', () => {
            if (typeof createParticles === 'function') createParticles();
            if (typeof createCryptoIcons === 'function') createCryptoIcons();
            if (typeof initCharts === 'function') initCharts();
            
            // Show welcome message for table
            setTimeout(() => {
               showMessage('Deposit history loaded successfully', 'info');
            }, 1500);
         });
         
         // Close sidebar when clicking outside on mobile
         document.addEventListener('click', function(event) {
           const sidebar = document.getElementById('sidebar');
           const menuToggle = document.getElementById('menuToggle');
           
           if (window.innerWidth <= 992 && 
               sidebar && sidebar.classList.contains('active') && 
               !sidebar.contains(event.target) && 
               menuToggle && !menuToggle.contains(event.target)) {
               sidebar.classList.remove('active');
           }
         });
      </script>
   </body>
</html>