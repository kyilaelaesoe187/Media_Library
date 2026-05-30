<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <style>
        :root {
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --accent-color: #e53e3e;
            /* Soft red */
            --btn-primary: #3182ce;
            --btn-primary-hover: #2b6cb0;
            --btn-secondary: #edf2f7;
            --btn-secondary-hover: #e2e8f0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .error-container {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: fadeIn 0.4s ease-out;
        }

        /* Smart Visual Icon */
        .error-icon {
            width: 80px;
            height: 80px;
            background: #fff5f5;
            color: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            font-weight: bold;
            margin: 0 auto 24px;
        }

        h1 {
            font-size: 28px;
            margin: 0 0 12px 0;
            color: var(--text-primary);
            font-weight: 700;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: var(--text-secondary);
            margin: 0 0 28px 0;
        }

        /* Smart Button Group */
        .button-group {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: var(--btn-primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--btn-primary-hover);
        }

        .btn-secondary {
            background: var(--btn-secondary);
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background: var(--btn-secondary-hover);
        }

        /* Optional Smart Feature: Help Search */
        .search-box {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #edf2f7;
        }

        .search-box input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="error-container">
        <div class="error-icon">!</div>

        <h1>Something's Not Right</h1>

        <p><?= htmlspecialchars($message ?? 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.') ?></p>

        <div class="button-group">
            <a class="btn btn-secondary" href="javascript:history.back()">Go Back</a>
            <a class="btn btn-primary" href="?page=home">Home Base</a>
        </div>


    </div>

</body>

</html>